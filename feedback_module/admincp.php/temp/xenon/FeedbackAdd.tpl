<form method="post" role="form" action="./?pages=Feedback_form&pageType={type}" class="form-horizontal" enctype="multipart/form-data">
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">{show}</h3>
				</div>
				<div class="panel-body">
						[if {count[supportedLang]}>=1]
							</div></div>
							<div class="panel panel-default panel-tabs" data-panel-lang="true">
								<div class="panel-body">
									<ul class="nav nav-tabs nav-tabs-justified" data-support="lang">
										[foreach block=supportedLang]<li>
											<a href="#home-3" data-toggle="tab" data-lang="{supportedLang.lang}">{supportedLang.lang}</a>
										</li>[/foreach]
									</ul>
								</div>
							</div>
							<div class="panel panel-default">
								<div class="panel-body">
						[/if {count[supportedLang]}>=1]
						<div class="form-group">
							<label class="col-sm-2 control-label" for="field-1">Название формы</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="title" id="field-1" placeholder="Введите название формы" value="{title}">
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="field-2">Получатель/ли</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="address" id="field-2" placeholder="Введите получателей" value="{address}">
								<small class="col-sm-12">Если не введен - будет использован из настроек. Несколько получателей разделяются запятыми</small>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="field-2">Форма на сайте</label>
							<div class="col-sm-10">
								<ul class="toolbar1" style="">
									<li>
										<div class="btn-group">
											<a class="btn btn-white" data-btn="text" href="javascript:;" unselectable="on">текст</a>
											<a class="btn btn-white" data-btn="email" href="javascript:;" unselectable="on">email</a>
											<a class="btn btn-white" data-btn="url" href="javascript:;" unselectable="on">URL</a>
											<a class="btn btn-white" data-btn="tel" href="javascript:;" unselectable="on">телефон</a>
											<a class="btn btn-white" data-btn="number" href="javascript:;" unselectable="on">номер</a>
											<a class="btn btn-white" data-btn="date" href="javascript:;" unselectable="on">дата</a>
											<a class="btn btn-white" data-btn="textarea" href="javascript:;" unselectable="on">область текста</a>
											<a class="btn btn-white" data-btn="file" href="javascript:;" unselectable="on">файл</a>
											<a class="btn btn-white" data-btn="submit" href="javascript:;" unselectable="on">отправить</a>
										</div>
									</li>
								</ul>
								<textarea id="example1" name="form" class="onlytext form-control" style="width: 100%; height: 300px">{form}</textarea>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label" for="field-2">Форма, которая будет отправлена на почту</label>
							<div class="col-sm-10">
								<ul class="toolbar2" style="">
									<li>
										<div class="btn-group" id="btn-template"></div>
									</li>
								</ul>
								<textarea id="example2" name="send_mess" class="onlytext form-control" style="width: 100%; height: 300px">{send_mess}</textarea>
							</div>
						</div>
				</div>
			</div>
			<div class="panel panel-default panel-tabs" data-panel-submit="true">
				<div class="panel-body">
					<button class="btn btn-single btn-savePage btn-icon btn-icon-standalone btn-icon-standalone-right btn-sm">
						<i class="fa-save"></i>
						<span>{L_save}</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
<style>
	#inputForFile .array {
		padding-bottom: 2.5em;
		border-bottom: 0.1em solid #e4e4e4;
		margin-bottom: 2em;
		display: inline-block;
		width: 100%;
	}
	#inputForFile .array:last-of-type {
	    border-bottom: 0px
	}
	.panel-body .nav.nav-tabs > li > a {
		border: 1px solid #ddd;
	}
	ul.toolbar1 > li, ul.toolbar2 > li {
	    float: left;
	    display: list-item;
	    list-style: none;
	    margin: 0 5px 10px 0;
	}
</style>
<script type="text/template" id="tmp-btn"><a class="btn btn-white" data-codes='{code}' data-uniqid="{uniqid}" href="javascript:;" unselectable="on">{code}</a></script>
<script>
	function insertAtCursor(myValue, form) {
		if(typeof(form)==="undefined") {
			form = "example1";
		}
	    //IE support
	    if (document.selection) {
	        document.getElementById(form).focus();
	        sel = document.selection.createRange();
	        sel.text = myValue;
	    }
	    //MOZILLA and others
	    else if (document.getElementById(form).selectionStart || document.getElementById(form).selectionStart == '0') {
	        var startPos = document.getElementById(form).selectionStart;
	        var endPos = document.getElementById(form).selectionEnd;
	        var val = document.getElementById(form).value;
	        document.getElementById(form).value = val.substring(0, startPos) + myValue + val.substring(endPos, val.length);
	    } else {
	        document.getElementById(form).value += myValue;
	    }
	}
	function uniqid(prefix, moreEntropy) {
		if(typeof prefix === 'undefined') {
			prefix = ''
		}
		var retId
		var _formatSeed = function (seed, reqWidth) {
			seed = parseInt(seed, 10).toString(16) // to hex str
			if(reqWidth < seed.length) {
				// so long we split
				return seed.slice(seed.length - reqWidth)
			}
			if(reqWidth > seed.length) {
				// so short we pad
				return Array(1 + (reqWidth - seed.length)).join('0') + seed
			}
			return seed
		}
		var $global = (typeof window !== 'undefined' ? window : global)
		$global.$locutus = $global.$locutus || {}
		var $locutus = $global.$locutus
		$locutus.php = $locutus.php || {}

		if(!$locutus.php.uniqidSeed) {
			// init seed with big random int
			$locutus.php.uniqidSeed = Math.floor(Math.random() * 0x75bcd15)
		}
		$locutus.php.uniqidSeed++
		// start with prefix, add current milliseconds hex string
		retId = prefix
		retId += _formatSeed(parseInt(new Date().getTime() / 1000, 10), 8)
		// add seed hex string
		retId += _formatSeed($locutus.php.uniqidSeed, 5)
		if(moreEntropy) {
			// for more entropy we add a float lower to 10
			retId += (Math.random() * 10).toFixed(8).toString()
		}
		return retId
	}
	function htmlspecialchars_decode (string, quoteStyle) { // eslint-disable-line camelcase
	  //       discuss at: http://locutus.io/php/htmlspecialchars_decode/
	  //      original by: Mirek Slugen
	  //      improved by: Kevin van Zonneveld (http://kvz.io)
	  //      bugfixed by: Mateusz "loonquawl" Zalega
	  //      bugfixed by: Onno Marsman (https://twitter.com/onnomarsman)
	  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
	  //      bugfixed by: Brett Zamir (http://brett-zamir.me)
	  //         input by: ReverseSyntax
	  //         input by: Slawomir Kaniecki
	  //         input by: Scott Cariss
	  //         input by: Francois
	  //         input by: Ratheous
	  //         input by: Mailfaker (http://www.weedem.fr/)
	  //       revised by: Kevin van Zonneveld (http://kvz.io)
	  // reimplemented by: Brett Zamir (http://brett-zamir.me)
	  //        example 1: htmlspecialchars_decode("<p>this -&gt; &quot;</p>", 'ENT_NOQUOTES')
	  //        returns 1: '<p>this -> &quot;</p>'
	  //        example 2: htmlspecialchars_decode("&amp;quot;")
	  //        returns 2: '&quot;'

	  var optTemp = 0
	  var i = 0
	  var noquotes = false

	  if (typeof quoteStyle === 'undefined') {
	    quoteStyle = 2
	  }
	  string = string.toString()
	    .replace(/&lt;/g, '<')
	    .replace(/&gt;/g, '>')
	  var OPTS = {
	    'ENT_NOQUOTES': 0,
	    'ENT_HTML_QUOTE_SINGLE': 1,
	    'ENT_HTML_QUOTE_DOUBLE': 2,
	    'ENT_COMPAT': 2,
	    'ENT_QUOTES': 3,
	    'ENT_IGNORE': 4
	  }
	  if (quoteStyle === 0) {
	    noquotes = true
	  }
	  if (typeof quoteStyle !== 'number') {
	    // Allow for a single string or an array of string flags
	    quoteStyle = [].concat(quoteStyle)
	    for (i = 0; i < quoteStyle.length; i++) {
	      // Resolve string input to bitwise e.g. 'PATHINFO_EXTENSION' becomes 4
	      if (OPTS[quoteStyle[i]] === 0) {
	        noquotes = true
	      } else if (OPTS[quoteStyle[i]]) {
	        optTemp = optTemp | OPTS[quoteStyle[i]]
	      }
	    }
	    quoteStyle = optTemp
	  }
	  if (quoteStyle & OPTS.ENT_HTML_QUOTE_SINGLE) {
	    // PHP doesn't currently escape if more than one 0, but it should:
	    string = string.replace(/&#0*39;/g, "'")
	    // This would also be useful here, but not a part of PHP:
	    // string = string.replace(/&apos;|&#x0*27;/g, "'");
	  }
	  if (!noquotes) {
	    string = string.replace(/&quot;/g, '"')
	  }
	  // Put this in last place to avoid escape being double-decoded
	  string = string.replace(/&amp;/g, '&')

	  return string
	}
	var codes = {}, elemList = {};
	document.body.addEventListener("input", function(e) {
		if(e.target.closest("#example1")!=null) {
			var val = e.target.value;
			Object.keys(codes).forEach(function(key) {
				if(val.indexOf(codes[key])==-1) {
					var el = document.querySelector("[data-uniqid='"+elemList[codes[key]]+"']");
					el.parentNode.removeChild(el);
					delete elemList[codes[key]];
					delete codes[key];
				}
			})
		}
	});
	document.body.addEventListener("click", function(e) {
		if(e.target.closest(".toolbar1 a")!=null) {
			e.preventDefault();
			var info = e.target.getAttribute("data-btn")+"_"+uniqid().trim();
			var code = "["+e.target.getAttribute("data-btn")+" name=\""+info+"\"]";
			if(e.target.getAttribute("data-btn").indexOf("submit")>-1) {
				var code = "[submit]";
			}
			var uni = uniqid();
			insertAtCursor(code+" ");
			if(code!="[submit]") {
				var tmp = document.getElementById("tmp-btn").innerHTML;
				tmp = tmp.replace(new RegExp("{code}", "g"), code);
				tmp = tmp.replace(new RegExp("{uniqid}", "g"), uni);
				document.getElementById("btn-template").innerHTML += tmp;
				elemList[code] = uni;
				codes[code] = code;
			}
		}
		if(e.target.closest(".toolbar2 a")!=null) {
			e.preventDefault();
			insertAtCursor(e.target.getAttribute("data-codes"), "example2");
		}
	});
	var t = document.getElementById("example1").innerHTML;
	t = htmlspecialchars_decode(t);
	var list = t.matchAll(/\[.+?(?!name="(.+?)").*?\]/g);
	for(var z of list) {
		var e = z[0].match(/name=['"](.*?)['"]/g);
		if(e!==null) {
			var uni = e[0].replace(/name=['"]/g, "").replace(/['"]/g, "");
			var code = z[0];
			uni = uni.split("_");
			code = z[0].match(/(.+?)name="(.+?)"/g)[0]+"]";
			var tmp = document.getElementById("tmp-btn").innerHTML;
			tmp = tmp.replace(new RegExp("{code}", "g"), code);
			tmp = tmp.replace(new RegExp("{uniqid}", "g"), uni[1]);
			document.getElementById("btn-template").innerHTML += tmp;
			code = code.replace("]", "");
			elemList[code] = uni[1];
			codes[code] = code;
		}
	}
</script>