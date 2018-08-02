<?php

//Include the internationalized domain name converter (requires PHP 5)
if ( version_compare( phpversion(), '5.0.0', '>=' ) && ! class_exists( 'idna_convert' ) ) {
	include dirname(__FILE__) .DS. 'idn' .DS. 'idna_convert.class.php';
	if ( ! function_exists( 'encode_utf8' ) ) {
		include dirname(__FILE__) .DS. 'idn' .DS. 'transcode_wrapper.php';
	}
}

class brokenLink extends Core {

	private $config = array(
		'timeout' => 30,
	);
	private $last_headers = '';
	private $check_count = 0;
	private $first_failure = 0;

	function idn_to_ascii( $url, $charset = '' ) {
		$idn = $this->get_idna_converter();
		if ( $idn != null ) {
			if ( empty( $charset ) ) {
				$charset = config::Select( 'charset' );
			}

			// Encode only the host.
			if ( preg_match( '@(\w+:/*)?([^/:]+)(.*$)?@s', $url, $matches ) ) {
				$host = $matches[2];
				if ( ( strtoupper( $charset ) != 'UTF-8') && ( strtoupper( $charset ) != 'UTF8') ) {
					$host = encode_utf8( $host, $charset, true );
				}
				$host = $idn->encode( $host );
				$url = $matches[1] . $host . $matches[3];
			}
		}

		return $url;
	}

	function get_idna_converter() {
		static $idn = null;
		if ( ( null === $idn ) && class_exists( 'idna_convert' ) ) {
			$idn = new idna_convert();
		}
		return $idn;
	}

	function extract_tags( $html, $tag, $selfclosing = null, $return_the_entire_tag = false, $charset = 'ISO-8859-1' ) {

		if ( is_array( $tag ) ) {
			$tag = implode( '|', $tag );
		}

		//If the user didn't specify if $tag is a self-closing tag we try to auto-detect it
		//by checking against a list of known self-closing tags.
		$selfclosing_tags = array( 'area', 'base', 'basefont', 'br', 'hr', 'input', 'img', 'link', 'meta', 'col', 'param' );
		if ( is_null( $selfclosing ) ) {
			$selfclosing = in_array( $tag, $selfclosing_tags );
		}

		//The regexp is different for normal and self-closing tags because I can't figure out
		//how to make a sufficiently robust unified one.
		if ( $selfclosing ) {
			$tag_pattern =
				'@<(?P<tag>' . $tag . ')			# <tag
				(?P<attributes>\s[^>]+)?			# attributes, if any
				\s*/?>								# /> or just >, being lenient here
				@xsi';
		} else {
			$tag_pattern =
				'@<(?P<tag>' . $tag . ')			# <tag
				(?P<attributes>\s[^>]+)?	 		# attributes, if any
				\s*>						 		# >
				(?P<contents>.*?)			 		# tag contents
				</(?P=tag)>					 		# the closing </tag>
				@xsi';
		}

		$attribute_pattern =
			'@
			(?P<name>\w+)											# attribute name
			\s*=\s*
			(
				(?P<quote>[\"\'])(?P<value_quoted>.*?)(?P=quote)	# a quoted value
				|							# or
				(?P<value_unquoted>[^\s"\']+?)(?:\s+|$)				# an unquoted value (terminated by whitespace or EOF)
			)
			@xsi';

		//Find all tags
		if ( ! preg_match_all( $tag_pattern, $html, $matches, PREG_SET_ORDER | PREG_OFFSET_CAPTURE ) ) {
			//Return an empty array if we didn't find anything
			return array();
		}

		$tags = array();
		foreach ( $matches as $match ) {

			// Parse tag attributes, if any.
			$attributes = array();
			if ( ! empty( $match['attributes'][0] ) ) {

				if ( preg_match_all( $attribute_pattern, $match['attributes'][0], $attribute_data, PREG_SET_ORDER ) ) {
					//Turn the attribute data into a name->value array
					foreach ( $attribute_data as $attr ) {
						if( ! empty( $attr['value_quoted'] ) ) {
							$value = $attr['value_quoted'];
						} else if( ! empty( $attr['value_unquoted'] ) ) {
							$value = $attr['value_unquoted'];
						} else {
							$value = '';
						}

						// Passing the value through html_entity_decode is handy when you want
						// to extract link URLs or something like that. You might want to remove
						// or modify this call if it doesn't fit your situation.
						$value = html_entity_decode( $value, ENT_QUOTES, $charset );

						$attributes[ $attr['name'] ] = $value;
					}
				}

			}

			$tag = array(
				'tag_name' => $match['tag'][0],
				'offset' => $match[0][1],
				'contents' => ! empty( $match['contents'] ) ? $match['contents'][0] : '', // Empty for self-closing tags.
				'attributes' => $attributes,
			);
			if ( $return_the_entire_tag ) {
				$tag['full_tag'] = $match[0][0];
			}

			$tags[] = $tag;
		}

		return $tags;
	}

	function clean_url($url) {
		$url = html_entity_decode($url);

		$ltrm = preg_quote(json_decode('"\u200E"'), '/');
		$url = preg_replace(
	        array(
				'/([\?&]PHPSESSID=\w+)$/i',	//remove session ID
	            '/(#[^\/]*)$/',				//and anchors/fragments
	            '/&amp;/',					//convert improper HTML entities
	            '/([\?&]sid=\w+)$/i',		//remove another flavour of session ID
				'/' . $ltrm . '/',			//remove Left-to-Right marks that can show up when copying from Word.
	        ),
	        array('', '', '&', '', ''),
	        $url
		);
	    $url = trim($url);
	    
	    return $url;
	}

	function is_error_code($http_code) {
		/*"Good" response codes are anything in the 2XX range (e.g "200 OK") and redirects  - the 3XX range.
          HTTP 401 Unauthorized is a special case that is considered OK as well. Other errors - the 4XX range -
          are treated as such. */
		$good_code = ( ($http_code >= 200) && ($http_code < 400) ) || ( $http_code == 401 );
		return !$good_code;
	}

	function urlencodefix($url) {
		//TODO: Remove/fix this. Probably not a good idea to "fix" invalid URLs like that.  
		return preg_replace_callback(
			'|[^a-z0-9\+\-\/\\#:.,;=?!&%@()$\|*~_]|i', 
			create_function('$str','return rawurlencode($str[0]);'), 
			$url
		 );
	}

	function is_safe_mode() {
		// Check php.ini safe_mode only if PHP version is lower than 5.3.0, else set to false.
		if ( version_compare( phpversion(), '5.3.0', '<' ) ) {
			$safe_mode = ini_get( 'safe_mode' );
		} else {
			$safe_mode = false;
		}

		// Null, 0, '', '0' and so on count as false.
		if ( ! $safe_mode ) {
			return false;
		}
		// Test for some textual true/false variations.
		switch ( strtolower( $safe_mode ) ) {
			case 'on':
			case 'true':
			case 'yes':
				return true;

			case 'off':
			case 'false':
			case 'no':
				return false;

			default: // Let PHP handle anything else.
				return (bool) (int) $safe_mode;
		}
	}

	function is_open_basedir() {
		$open_basedir = ini_get( 'open_basedir' );
		return $open_basedir && ( strtolower( $open_basedir ) != 'none' );
	}

	public function is_internal_to_domain() {
		$host = @parse_url($this->url, PHP_URL_HOST);
		if ( empty($host) ) {
			return false;
		}

		$site_host = @parse_url(config::Select("default_http_host"), PHP_URL_HOST);
		if ( empty($site_host) ) {
			return false;
		}

		//Some users are inconsistent with using/not using the www prefix, so get rid of it.
		$site_host = preg_replace('@^www\.@', '', $site_host, 1);

		//Check if $host ends with $site_host. This means blah.example.com will match example.com.
		return (substr($host, -strlen($site_host)) === $site_host);
	}
	
	function read_header(/** @noinspection PhpUnusedParameterInspection */ $ch, $header) {
		$this->last_headers .= $header;
		return strlen($header);
	}

	private function decide_warning_state($check_results) {
		if ( !$check_results['broken'] && !$check_results['warning'] ) {
			//Nothing to do, this is a working link.
			return $check_results;
		}

		$warning_reason = null;
		$failure_count = $this->check_count;
		$failure_duration = ($this->first_failure != 0) ? (time() - $this->first_failure) : 0;
		//These could be configurable, but lets put that off until someone actually asks for it.
		$duration_threshold = 24 * 3600;
		$count_threshold = 3;

		//We can't just use ($check_results['status_code'] == 'warning' because some "warning" problems are not
		//temporary. For example, region-restricted YouTube videos use the "warning" status code.
		$maybe_temporary_error = false;

		//Some basic heuristics to determine if this failure might be temporary.
		//----------------------------------------------------------------------
		if ( $check_results['timeout'] ) {
			$maybe_temporary_error = true;
			$warning_reason = 'Timeouts are sometimes caused by high server load or other temporary issues.';
		}

		$error_code = isset($check_results['error_code']) ? $check_results['error_code'] : '';
		if ( $error_code === 'connection_failed' ) {
			$maybe_temporary_error = true;
			$warning_reason = 'Connection failures are sometimes caused by high server load or other temporary issues.';
		}

		$http_code = intval($check_results['http_code']);
		$temporary_http_errors = array(
			408, //Request timeout. Probably a plugin bug, but could just be an overloaded client server.
			420, //Custom Twitter code returned when the client gets rate-limited.
			429, //Client has sent too many requests in a given amount of time.
			502, //Bad Gateway. Often a sign of a temporarily overloaded or misconfigured server.
			503, //Service Unavailable.
			504, //Gateway Timeout.
			509, //Bandwidth Limit Exceeded.
			520, //CloudFlare-specific "Origin Error" code.
			522, //CloudFlare-specific "Connection timed out" code.
			524, //Another CloudFlare-specific timeout code.
		);
		if ( in_array($http_code, $temporary_http_errors) ) {
			$maybe_temporary_error = true;

			if ( in_array($http_code, array(502, 503, 504, 509)) ) {
				$warning_reason = sprintf(
					'HTTP error %d usually means that the site is down due to high server load or a configuration problem. '
					. 'This error is often temporary and will go away after while.',
					$http_code
				);
			} else {
				$warning_reason = 'This HTTP error is often temporary.';
			}
		}

		//----------------------------------------------------------------------

		//Attempt to detect false positives.
		$suspected_false_positive = false;

		//A "403 Forbidden" error on an internal link usually means something on the site is blocking automated
		//requests. Possible culprits include hotlink protection rules in .htaccess, badly configured IDS, and so on.
		$is_internal_link = $this->is_internal_to_domain();
		if ( $is_internal_link && ($http_code == 403) ) {
			$suspected_false_positive = true;
			$warning_reason = 'This might be a false positive. Make sure the link is not password-protected, '
				. 'and that your server is not set up to block automated requests.';
		}

		//Some hosting providers turn off loopback connections. This causes all internal links to be reported as broken.
		if ( $is_internal_link && in_array($error_code, array('connection_failed', 'couldnt_resolve_host')) ) {
			$suspected_false_positive = true;
			$warning_reason = 'This is probably a false positive. ';
			if ( $error_code === 'connection_failed' ) {
				$warning_reason .= 'The plugin could not connect to your site. That usually means that your '
					. 'hosting provider has disabled loopback connections.';
			} elseif ( $error_code === 'couldnt_resolve_host' ) {
				$warning_reason .= 'The plugin could not connect to your site because DNS resolution failed. '
					. 'This could mean DNS is configured incorrectly on your server.';
			}
		}

		//----------------------------------------------------------------------

		//Temporary problems and suspected false positives start out as warnings. False positives stay that way
		//indefinitely because they are usually caused by bugs and server configuration issues, not temporary downtime.
		if ( ($maybe_temporary_error && ($failure_count < $count_threshold)) || $suspected_false_positive ) {
			$check_results['warning'] = true;
			$check_results['broken'] = false;
		}

		//Upgrade temporary warnings to "broken" after X consecutive failures or Y hours, whichever comes first.
		$threshold_reached = ($failure_count >= $count_threshold) || ($failure_duration >= $duration_threshold);
		if ( $check_results['warning'] ) {
			if ( ($maybe_temporary_error && $threshold_reached) && !$suspected_false_positive ) {
				$check_results['warning'] = false;
				$check_results['broken'] = true;
			}
		}

		if ( !empty($warning_reason) && $check_results['warning'] ) {
			$formatted_reason = "\n==========\n"
				. 'Severity: Warning' . "\n"
				. 'Reason: ' . trim($warning_reason)
				. "\n==========\n";

			$check_results['log'] .= $formatted_reason;
		}
		return $check_results;
	}

	function parse($url, $use_get = false) {
		$url = $this->idn_to_ascii($url);
		$url = $this->clean_url($url);
		$result = array(
			'broken' => false,
			'timeout' => false,
			'warning' => false,
		);
		$log = '';
		//Init curl.
	 	$ch = curl_init();
		$request_headers = array();
        curl_setopt($ch, CURLOPT_URL, $this->urlencodefix($url));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        
        //Masquerade as Internet Explorer
		$ua = 'Mozilla/5.0 (compatible; MSIE 9.0; Windows NT 6.1; WOW64; Trident/5.0)';
		//$ua = 'Mozilla/5.0 (Windows NT 6.1; WOW64; Trident/7.0; rv:11.0) like Gecko';
        curl_setopt($ch, CURLOPT_USERAGENT, $ua);

		//Close the connection after the request (disables keep-alive). The plugin rate-limits requests,
		//so it's likely we'd overrun the keep-alive timeout anyway.
		curl_setopt($ch, CURLOPT_FORBID_REUSE, true);
		$request_headers[] = 'Connection: close';

        //Add a semi-plausible referer header to avoid tripping up some bot traps 
        curl_setopt($ch, CURLOPT_REFERER, config::Select("default_http_host"));
        
        //Redirects don't work when safe mode or open_basedir is enabled.
        if ( !$this->is_safe_mode() && !$this->is_open_basedir() ) {
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        }
        //Set maximum redirects
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
        
        //Set the timeout
        curl_setopt($ch, CURLOPT_TIMEOUT, $this->config['timeout']);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $this->config['timeout']);

		//Make CURL return a valid result even if it gets a 404 or other error.
        curl_setopt($ch, CURLOPT_FAILONERROR, false);

		
        $nobody = !$use_get; //Whether to send a HEAD request (the default) or a GET request
        
        $parts = @parse_url($url);
        if( isset($parts['scheme']) && $parts['scheme'] == 'https' ){
        	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false); //Required to make HTTPS URLs work.
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false); 
            //$nobody = false; //Can't use HEAD with HTTPS.
        }

		//Set request headers.
		if ( !empty($request_headers) ) {
			curl_setopt($ch, CURLOPT_HTTPHEADER, $request_headers);
		}

        //Register a callback function which will process the HTTP header(s).
		//It can be called multiple times if the remote server performs a redirect. 
		curl_setopt($ch, CURLOPT_HEADERFUNCTION, array($this,'read_header'));

		//Record request headers.
		if ( defined('CURLINFO_HEADER_OUT') ) {
			curl_setopt($ch, CURLINFO_HEADER_OUT, true);
		}

		//Execute the request
		$start_time = microtime_float();
        $content = curl_exec($ch);
        $measured_request_duration = microtime_float() - $start_time;



		$info = curl_getinfo($ch);

		$result['content'] = $content;
		
		//Store the results
        $result['http_code'] = intval($info['http_code']);
        $result['final_url'] = $info['url'];
        $result['request_duration'] = $info['total_time'];
        $result['redirect_count'] = $info['redirect_count'];

        //CURL doesn't return a request duration when a timeout happens, so we measure it ourselves.
        //It is useful to see how long the plugin waited for the server to respond before assuming it timed out.        
        if( empty($result['request_duration']) ){
        	$result['request_duration'] = $measured_request_duration;
        }
        
        //Determine if the link counts as "broken"
        if ( $result['http_code'] == 0 ){
        	$result['broken'] = true;
        	
        	$error_code = curl_errno($ch);
        	$log .= sprintf( "%s [Error #%d]\n", curl_error($ch), $error_code );
        	
        	//We only handle a couple of CURL error codes; most are highly esoteric.
        	//libcurl "CURLE_" constants can't be used here because some of them have 
        	//different names or values in PHP.
        	switch( $error_code ) {
        		case 6: //CURLE_COULDNT_RESOLVE_HOST
		        	$result['status_code'] = BLC_LINK_STATUS_WARNING;
		        	$result['status_text'] = ('Server Not Found');
					$result['error_code'] = 'couldnt_resolve_host';
		        	break;
		        	
		        case 28: //CURLE_OPERATION_TIMEDOUT
		        	$result['timeout'] = true;
		        	break;
		        	
	        	case 7: //CURLE_COULDNT_CONNECT
	        		//More often than not, this error code indicates that the connection attempt 
					//timed out. This heuristic tries to distinguish between connections that fail 
					//due to timeouts and those that fail due to other causes.
	        		if ( $result['request_duration'] >= 0.9*$this->config['timeout'] ){
	        			$result['timeout'] = true;
	        		} else {
	        			$result['status_code'] = BLC_LINK_STATUS_WARNING;
	        			$result['status_text'] = ('Connection Failed');
						$result['error_code'] = 'connection_failed';
	        		}
	        		break;
	        		
        		default:
	        		$result['status_code'] = BLC_LINK_STATUS_WARNING;
	        		$result['status_text'] = ('Unknown Error');
        	}
	        
        } else {
        	$result['broken'] = $this->is_error_code($result['http_code']);
        }

        curl_close($ch);

		$result['info'] = sprintf(
			'HTTP response: %d, duration: %.2f seconds, status text: "%s"',
			$result['http_code'],
			$result['request_duration'],
			isset($result['status_text']) ? $result['status_text'] : 'N/A'
		);
        
        if ( $nobody && $result['broken'] ){
			//The site in question might be expecting GET instead of HEAD, so lets retry the request 
			//using the GET verb.
			return $this->parse($url, true);
			 
			//Note : normally a server that doesn't allow HEAD requests on a specific resource *should*
			//return "405 Method Not Allowed". Unfortunately, there are sites that return 404 or
			//another, even more general, error code instead. So just checking for 405 wouldn't be enough. 
		}
        
        //When safe_mode or open_basedir is enabled CURL will be forbidden from following redirects,
        //so redirect_count will be 0 for all URLs. As a workaround, set it to 1 when the HTTP
		//response codes indicates a redirect but redirect_count is zero.
		//Note to self : Extracting the Location header might also be helpful.
		if ( ($result['redirect_count'] == 0) && ( in_array( $result['http_code'], array(301, 302, 303, 307) ) ) ){
			$result['redirect_count'] = 1;
		} 
		
        //Build the log from HTTP code and headers.
        $log .= '=== ';
        if ( $result['http_code'] ){
			$log .= sprintf('HTTP code : %d', $result['http_code']);
		} else {
			$log .= '(No response)';
		}
		$log .= " ===\n\n";

		$log .= "Response headers\n" . str_repeat('=', 16) . "\n";
		$log .= htmlentities($this->last_headers);
		$status = explode("\n", $this->last_headers);
		$status = array_map("trim", $status);
		if(isset($status[0])) {
			$result['http_resp'] = $status[0];
		}

		if ( isset($info['request_header']) ) {
			$log .= "Request headers\n" . str_repeat('=', 16) . "\n";
			$log .= htmlentities($info['request_header']);
		}

		if ( !$nobody && ($content !== false) && $result['broken'] ) {
			$log .= "Response HTML\n" . str_repeat('=', 16) . "\n";
			$log .= htmlentities(substr($content, 0, 2048));
		}

        if ( !empty($result['broken']) && !empty($result['timeout']) ) {
			$log .= "\n(" . "Most likely the connection timed out or the domain doesn't exist." . ')';
		}

        $result['log'] = $log;
        
        //The hash should contain info about all pieces of data that pertain to determining if the 
		//link is working.  
        $result['result_hash'] = implode('|', array(
			$result['http_code'],
			!empty($result['broken'])?'broken':'0',
			!empty($result['timeout'])?'timeout':'0',
			$this->remove_query_string($result['final_url']),
		));
		return $result;
	}

	function remove_query_string($url) {
		return preg_replace('@\?[^#]*?(#|$)@', '$1', $url);
	}

	function getInfoLink($url) {
		$ltrm = json_decode('"\u200E"');
		$url = str_replace($ltrm, '', $url);
        $defaults = array(
        	'broken' => false,
        	'warning' => false,
        	'http_code' => 0,
        	'http_resp' => '',
        	'redirect_count' => 0,
        	'final_url' => $url,
        	'request_duration' => 0,
        	'timeout' => false,
        	'may_recheck' => true,
        	'log' => '',
        	'result_hash' => '',
        	'status_text' => '',
        	'status_code' => '',
        	'content' => '',
		);
		$rez = $this->parse($this->idn_to_ascii($url));
		$results = array_merge($defaults, $rez);

		//Some HTTP errors can be treated as warnings.
		$results = $this->decide_warning_state($results);

		//Filter the returned array to leave only the restricted set of keys that we're interested in.
		$results = array_intersect_key($results, $defaults);
		return $results;
	}

	function relative2absolute($url, $base_url = '') {
		if ( empty($base_url) ){
			$base_url = config::Select("default_http_host");
		}
		
		$p = @parse_url($url);
	    if(!$p) {
	        //URL is a malformed
	        return false;
	    }
	    if( isset($p["scheme"]) ) return $url;
	    
	    //If the relative URL is just a query string or anchor, simply attach it to the absolute URL and return
	    $first_char = substr($url, 0, 1); 
	    if ( ($first_char == '?') || ($first_char == '#') ){
			return $base_url . $url;
		}
	
	    $parts=(parse_url($base_url));

        //Protocol-relative URLs start with "//". We just need to prepend the right protocol.
        if ( substr($url, 0, 2) === '//' ) {
            $scheme = isset($parts['scheme']) ? $parts['scheme'] : 'http';
            return $scheme . ':'. $url;
        }
	    
	    if(substr($url,0,1)=='/') {
	    	//Relative URL starts with a slash => ignore the base path and jump straight to the root. 
	        $path_segments = explode("/", $url);
	        array_shift($path_segments);
	    } else {
	        if(isset($parts['path'])){
	            $aparts=explode('/',$parts['path']);
	            array_pop($aparts);
	            $aparts=array_filter($aparts);
	        } else {
	            $aparts=array();
	        }
	        
	        //Merge together the base path & the relative path
	        $aparts = array_merge($aparts, explode("/", $url));
	        
	        //Filter the merged path 
	        $path_segments = array();
	        foreach($aparts as $part){
	        	if ( $part == '.' ){
					continue; //. = "this directory". It's basically a no-op, so we skip it.
				} elseif ( $part == '..' )  {
					array_pop($path_segments);	//.. = one directory up. Remove the last seen path segment.
				} else {
					array_push($path_segments, $part); //Normal directory -> add it to the path.
				}
			}
	    }
	    $path = implode("/", $path_segments);
	
		//Build the absolute URL.
	    $url = '';
	    if($parts['scheme']) {
	        $url = $parts['scheme']."://";
	    }
	    if(isset($parts['user'])) {
	        $url .= $parts['user'];
	        if(isset($parts['pass'])) {
	            $url .= ":".$parts['pass'];
	        }
	        $url .= "@";
	    }
	    if(isset($parts['host'])) {
	        $url .= $parts['host'];
	        if(isset($parts['port'])) {
		        $url .= ':' . $parts['port'];
		    }
		    $url .= '/';
	    }
	    $url .= $path;
	
	    return $url;
	}

	function loadLinks($linkMain) {
		$res = $this->getInfoLink($linkMain);
		$links = $this->extract_tags($res['content'], 'a', false, true);
		$results = array();
		
		//Iterate over the links and apply $callback to each
		foreach($links as $link) {
			
			//Massage the found link into a form required for the callback function
			if(!isset($link['attributes']) || !isset($link['attributes']['href'])) {
				continue;
			} else {
				$l = @parse_url($link['attributes']['href']);
			    if(!$l) {
			    	continue;
				}
				if(!isset($l['scheme'])) {
					$link['attributes']['href'] = $this->relative2absolute($link['attributes']['href'], $linkMain); //$base_url comes from $params
					$l = @parse_url($link['attributes']['href']);
				}
				if(!$link['attributes']['href'] || $link['attributes']['href']==$linkMain || (strlen($link['attributes']['href'])<6) || strpos($l['scheme'], "http")===false) {
					continue;
				}
			}
			$param = $link['attributes'];
			$param = array_merge(
				$param,
				array(
					'#raw' => $link['full_tag'],
					'#offset' => $link['offset'],
					'#link_text' => $link['contents'],
					'href' => isset($link['attributes']['href']) ? $link['attributes']['href'] : '',
				)
			);
			if(!empty($param['href'])) {
				$results[$param['href']] = (object) $param;
			}
		}
		$results = array_merge($res, array("links" => array_values($results)));
		unset($results['content']);
		return $results;
	}

	function __construct() {
		$models = modules::loadModel("BrokenLink");
		if(Arr::get($_GET, "clear", false)!==false) {
			db::query("TRUNCATE TABLE {{checker}}");
			callAjax();
			return false;
		}
		if(Arr::get($_GET, "rescan", false)!==false) {
			$id = 0;
			$mainLink = config::Select("default_http_host");
			$linkNow = $models->getInstance(true);
			$linkNow->Where("lastCheck", "0");
			$linkNow->OrderBy("cId", "ASC");
			$linkNow->SetLimit(1);
			$dataNow = $linkNow->Select();
			if($linkNow->Exists()) {
				$id = $dataNow->cId;
				$mainLink = $dataNow->linkNow;
			}
			$data = $this->loadLinks($mainLink);
			$countLinks = 0;
			if(isset($data['links'])) {
				$countLinks = sizeof($data['links']);
				foreach($data['links'] as $link) {
					if(strpos($link->href, $mainLink)===false) {
						continue;
					}
					$model = $models->getInstance(true);
					$modelSelects = $model;
					$modelSelects->Where("linkNow", $link->href);
					$modelSelect = $modelSelects->Select();
					if(!$modelSelects->Exists()) {
						$modelInsert = $model;
						$modelInsert->linkNow = $link->href;
						$modelInsert->htmlOriginal = $link->{"#raw"};
						$modelInsert->linkOriginal = $data['final_url'];
						$modelInsert->lastCheck = "0";
						$modelInsert->Insert();
					} else {
						$model->lastCheck = $models->Time();
						$model->Where("linkNow", $link->href);
						$model->Update();
					}
				}
			}
			$linkNow->statusCode = $data['http_code'];
			$linkNow->broken = ($data['broken']===true ? "yes" : "no");
			$linkNow->warning = ($data['warning']===true ? "yes" : "no");
			$linkNow->status = $data['http_resp'];
			$linkNow->timeResp = $data['request_duration'];
			$linkNow->lastCheck = $models->Time();
			if($id>0) {
				$linkNow->Where($linkNow->cId);
				$linkNow->Update();
			} else {
				$linkNow->linkNow = $mainLink;
				$linkNow->Insert();
				$countLinks++;
			}
			$counter = $models->getInstance();
			$counter->Where("lastCheck", "0");
			$counter->SetLimit(-1);
			$all = $models->getInstance();
			$resp = array(
				"code" => $data['http_code'],
				"http" => $data['http_resp'],
				"broken" => $data['broken'],
				"warning" => $data['warning'],
				"timeResp" => $data['request_duration'],
				"link" => $mainLink,
				"countLinks" => $countLinks,
				"all" => $all->getMax(),
				"stillScan" => $counter->getMax(),
			);
			callAjax();
			HTTP::echos(json_encode($resp));
			return false;
		}
		$all = $models->getInstance();
		$linkNow = $models->getInstance(true);
		$linkNow->Where("lastCheck", "!=", "0");
		$errors = $models->getInstance(true);
		$errors->Where("statusCode", "!=", "200");
		templates::assign_var("maxAll", $all->getMax());
		templates::assign_var("maxScanned", $linkNow->getMax());
		templates::assign_var("maxError", $errors->getMax());
		$datas = $models->getInstance();
		$datas->Where("lastCheck", "!=", "0");
		$datas->OrderBy("cId", "DESC");
		$datas->OrderBy("linkNow", "ASC");
		$datas->SetLimit(-1);
		$datas->multiple(true);
		$dtd = $datas->Select();
		if($datas->Exists()) {
			templates::loadObject($dtd);
		}
		$lastCheck = $models->getInstance();
		$lastCheck->Where("lastCheck", "!=", "0");
		$lastCheck->OrderBy("cId", "DESC");
		$lastCheck = $lastCheck->Select();
		templates::assign_var("lastCheck", (empty($lastCheck->lastCheck) ? "false" : $lastCheck->lastCheck));
		$this->Prints("brokenLink");
	}

}
	
define('BLC_LINK_STATUS_UNKNOWN', 'unknown');
define('BLC_LINK_STATUS_OK', 'ok');
define('BLC_LINK_STATUS_INFO', 'info');
define('BLC_LINK_STATUS_WARNING', 'warning');
define('BLC_LINK_STATUS_ERROR', 'error');