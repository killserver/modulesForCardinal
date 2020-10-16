<?php

abstract class SmsGate {
	private $data = array();
	public static $name = '';
	public function __construct($options = array()) {
		if(is_array($options)) {
			$this->data = $options;
		}
	}
	public function __set($key, $value) {
		$this->data[$key] = $value;
	}
	public function __get($key) {
		return (isset($this->data[$key]) ? $this->data[$key] : NULL);
	}
	public function has($key) {
    	return isset($this->data[$key]);
  	}
  	public static function balance($client = false, $print = false) {}
	abstract function send($sender, $to, $mess);
    public function prepPhone($phone) {
        $result = preg_replace('/[^0-9,]/', '', $phone);
        return $result;
    }
}
class Smscru extends SmsGate {
	public static $name = "Smsc.ru";
	public static function balance($client = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "smscru", "login"))!==false && ($pass = config::Select("smsmaster", "smscru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				if(!$client) {
					$client = new SoapClient('https://smsc.ru/sys/soap.php?wsdl');
				}
				$credentials = Array ( 
					'login' => config::Select("smsmaster", "smscru", "login"), 
					'psw' => config::Select("smsmaster", "smscru", "psw") 
				);
				$balance = $client->get_balance($credentials);
				$return = $balance->balanceresult->balance;
			}
		}
		return $return;
	}
	public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "smscru", "login"))!==false && ($pass = config::Select("smsmaster", "smscru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$client = new SoapClient('https://smsc.ru/sys/soap.php?wsdl');
				$balance = self::balance($client);
				if($to) {
					$numbers = $this->prepPhone($to);
				} else {
					$numbers = false;
				}
				if($balance && $numbers) {
					$sms = Array ( 
						'login'  => config::Select("smsmaster", "smscru", "login"), 
						'psw' 	 => config::Select("smsmaster", "smscru", "psw"),
						'phones' => $numbers, 
						'mes' 	 => $this->message,
						'sender' => $sender,
						'time'	 => 0
					); 
					$result = $client->send_sms($sms);
					$return = $result;
				}
			}
		}
		return $return;
	}
}
class AlphaSms extends SmsGate {
	public static $name = "AlphaSms.ua";
	public static function balance($client = false, $print = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "alphasms", "login"))!==false && ($pass = config::Select("smsmaster", "alphasms", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$xml_data = new SimpleXMLElement('<?xml version="1.0"?><package login="'.$login.'" password="'.$pass.'" />', null, false);
				$xml_data->addChild('balance', '');
				if(!$client) {
					$client = new Parser('https://alphasms.ua/api/xml.php');
				}
				$client->post($xml_data->asXML());
				$data = $client->get();
				$response = new SimpleXMLElement($data, null, false);
				$return = ($print ? round($response->balance->amount, 2)." ".$response->balance->currency : floatval(round($response->balance->amount, 2)));
			}
		}
		return $return;
	}
	public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "alphasms", "login"))!==false && ($pass = config::Select("smsmaster", "alphasms", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$client = new Parser('https://alphasms.ua/api/xml.php');
				$balance = self::balance($client);
				if($to) {
					$numbers = $this->prepPhone($to);
				} else {
					$numbers = false;
				}
				if($balance && $numbers) {
					$xml_data = new SimpleXMLElement('<?xml version="1.0"?><package login="'.$login.'" password="'.$pass.'" />', null, false);
					$message = $xml_data->addChild('message', "");
					$msg = $message->addChild("msg", $mess);
					$msg->addAttribute("recipient", $numbers);
					$msg->addAttribute("sender", $sender);
					$msg->addAttribute("type", 0);
					$client->post($xml_data->asXML());
					$data = $client->get();
					$response = new SimpleXMLElement($data, null, false);
					$status = intval($response->message->msg);
					$return = $status;
				}
			}
		}
		return $return;
	}
}
class Bytehand extends SmsGate {
	public static $name = "Bytehand.com";
	public static function balance($client = false, $print = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "bytehandcom", "login"))!==false && ($pass = config::Select("smsmaster", "bytehandcom", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
	            $credentials = Array(
	                'id'    => config::Select("smsmaster", "bytehandcom", "login"),
	                'key'   => config::Select("smsmaster", "bytehandcom", "psw")
	            );
	            $balance = file_get_contents('http://api.bytehand.com/v1/balance?' . http_build_query($credentials));
	            $balance_result = json_decode($balance);
				$return = $balance_result->description;
			}
        }
		return $return;
	}
    public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "bytehandcom", "login"))!==false && ($pass = config::Select("smsmaster", "bytehandcom", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
	            $balance_result = self::balance();
	            if($balance_result > 0) {
	                if($to) {
	                    $phone = $this->prepPhone($to);
	                } else {
	                    $phone = false;
	                }
	                if($balance_result && $phone) {
	                    $sms = Array(
	                        'id'    => config::Select("smsmaster", "bytehandcom", "login"),
	                        'key'   => config::Select("smsmaster", "bytehandcom", "psw"),
	                        'from'  => $sender,
	                        'to'    => $phone,
	                        'text'  => $mess
	                    );
	                    $result = $this->sendSms($sms);
	                    $return = $result;
	                }
	            }
	        }
        }
		return $return;
    }
    private function sendSms($data) {
        $result = file_get_contents("http://api.bytehand.com/v1/send?" . http_build_query($data));
        return $result;
    }
}
class Infosmska extends SmsGate {
	public static $name = "Infosmska.ru";
	public static function balance($client = false, $print = false) {
		return false;
	}
    public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "infosmskaru", "login"))!==false && ($pass = config::Select("smsmaster", "infosmskaru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				if($to) {
					$numbers = $this->prepPhone($to);
				} else {
					$numbers = false;
				}
				$return = $this->sendSMS(config::Select("smsmaster", "infosmskaru", "login"), config::Select("smsmaster", "infosmskaru", "psw"), $numbers, $mess, $sender);
			}
		}
		return $return;
    }
    //SendSMS
    public function sendSMS($login, $password, $phone, $text, $sender) {
        $host = "api.infosmska.ru";
        $fp = fsockopen($host, 80);
        fwrite($fp, "GET /interfaces/SendMessages.ashx" .
            "?login=" . rawurlencode($login) .
            "&pwd=" . rawurlencode($password) .
            "&phones=" . rawurlencode($phone) .
            "&message=" . rawurlencode($text) .
            "&sender=" . rawurlencode($sender) .
            " HTTP/1.1\r\nHost: ".$host."\r\nConnection: Close\r\n\r\n");
        fwrite($fp, "Host: " . $host . "\r\n");
        fwrite($fp, "\n");
        $response = "";
        while(!feof($fp)) {
            $response .= fread($fp, 1);
        }
        fclose($fp);
        list($other, $responseBody) = explode("\r\n\r\n", $response, 2);
        list($other, $ids_str) = explode(":", $responseBody, 2);
        list($sms_id, $other) = explode(";", $ids_str, 2);
        return $sms_id;
    }
}
class Smscab extends SmsGate {
	public static $name = "Smscab.ru";
	public static function balance($client = false, $print = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "smscabru", "login"))!==false && ($pass = config::Select("smsmaster", "smscabru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				if(!$client) {
					$client = new SoapClient('https://smscab.ru/sys/soap.php?wsdl');
				}
				$credentials = Array ( 
					'login' => config::Select("smsmaster", "smscabru", "login"), 
					'psw' => config::Select("smsmaster", "smscabru", "psw") 
				);
				$balance = $client->get_balance($credentials);
				$return = $balance->balanceresult->balance;
			}
		}
		return $return;
	}
	public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "smscabru", "login"))!==false && ($pass = config::Select("smsmaster", "smscabru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$client = new SoapClient('http://my.smscab.ru/sys/soap.php?wsdl');
				$balance = self::balance($client);
				if($to) {
					$numbers = $this->prepPhone($to);
				} else {
					$numbers = false;
				}
				if($balance && $numbers) {
					$sms = Array ( 
						'login'  => config::Select("smsmaster", "smscabru", "login"), 
						'psw' 	 => config::Select("smsmaster", "smscabru", "psw") ,
						'phones' => $numbers, 
						'mes' 	 => $mess,
						'sender' => $sender,
						'time'	 => 0
					); 
					$result = $client->send_sms($sms);
					$return = $result->sendresult;
				}
			}
		}
		return $return;
	}
    public function prepPhone($phone) {
        $result = preg_replace('/[^0-9,]/', '', $phone);
        return $result;
    }
}
class Smscua extends SmsGate {
	public static $name = "Smsc.ua";
	public static function balance($client = false, $print = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "smscua", "login"))!==false && ($pass = config::Select("smsmaster", "smscua", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				if(!$client) {
					$client = new SoapClient('https://smsc.ua/sys/soap.php?wsdl');
				}
				$credentials = Array ( 
					'login' => $login, 
					'psw' => $pass, 
				);
				$balance = $client->get_balance($credentials);
				$return = ($print ? $balance->balanceresult->balance." UAH" : floatval($balance->balanceresult->balance));
			}
		}
		return $return;
	}
	public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "smscua", "login"))!==false && ($pass = config::Select("smsmaster", "smscua", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$client = new SoapClient('https://smsc.ua/sys/soap.php?wsdl');
				$balance = self::balance($client);
				if($to) {
					$numbers = $this->prepPhone($to);
				} else {
					$numbers = false;
				}
				if($balance && $numbers) {
					$sms = Array ( 
						'login'  => $login, 
						'psw' 	 => $pass,
						'phones' => $numbers, 
						'mes' 	 => $mess,
						'sender' => $sender,
						'time'	 => 0
					); 
					$result = $client->send_sms($sms);
					$return = $result->sendresult;
				}
			}
		}
		return $return;
	}
}
class Smsru extends SmsGate {
	public static $name = "Sms.ru";
	public static function balance($client = false, $print = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "smsru", "login"))!==false && ($pass = config::Select("smsmaster", "smsru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
	            $credentials = Array(
	                'api_id' => config::Select("smsmaster", "smsru", "login"),
	                'json' => '1',
	            );
	            $auth = file_get_contents("https://sms.ru/auth/check?" . http_build_query($credentials));
	            $auth_result = json_decode($auth);
	            if($auth_result->status == 'OK') {
	                $balance = file_get_contents("https://sms.ru/my/balance?" . http_build_query($credentials));
	                $balance_result = json_decode($balance);
					$return = $balance_result->balance;
	            }
	        }
		}
		return $return;
	}
    public function send($sender, $to, $mess) {
		$return = false;
        if(config::Select("smsmaster", "smsru", "login")) {
            $credentials = Array(
                'api_id' => config::Select("smsmaster", "smsru", "login"),
                'json' => '1',
            );
            $auth = file_get_contents("https://sms.ru/auth/check?" . http_build_query($credentials));
            $auth_result = json_decode($auth);
            if($auth_result->status == 'OK') {
                if($to) {
                    $numbers = $this->prepPhone($to);
                } else {
                    $numbers = false;
                }
                $balance_result = self::balance();
                if($balance_result && $numbers) {
                    $sms = Array(
                        'api_id' => config::Select("smsmaster", "smsru", "login"),
                        'to'     => $numbers,
                        'msg'    => $this->message,
                        'from'   => $sender,
                        'time'   => 0,
                        'json' => '1'
                    );
                    $result = file_get_contents("https://sms.ru/sms/send?" . http_build_query($sms));
                    $send_result = json_decode($result);
                    if($send_result->status == 'OK') {
                    	$return = $send_result;
                    }
                }
            }
        }
		return $return;
    }
}
class Turbosms extends SmsGate {
	public static $name = "Turbosms.in.ua";
	public static function balance($client = false, $print = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "turbosmsinua", "login"))!==false && ($pass = config::Select("smsmaster", "turbosmsinua", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				if(!$client) {
					$client = new SoapClient ('http://turbosms.in.ua/api/wsdl.html');
				}
				$credentials = Array ( 
					'login' => config::Select("smsmaster", "turbosmsinua", "login"), 
					'password' => config::Select("smsmaster", "turbosmsinua", "psw") 
				); 
				$auth = $client->Auth($credentials);
				$balance = $client->GetCreditBalance();
				$return = $balance->GetCreditBalanceResult;
			}
		}
		return $return;
	}
	public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "turbosmsinua", "login"))!==false && ($pass = config::Select("smsmaster", "turbosmsinua", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$client = new SoapClient ('http://turbosms.in.ua/api/wsdl.html');
				$balance = self::balance($client);
				if($to) {
					$numbers = $to;
				} else {
					$numbers = false;
				}
				if(!$sender) {
					$sender = 'Msg';
				}
				if($balance && $numbers) {
					$sms = Array ( 
						'sender' => $sender, 
						'destination' => $numbers, 
						'text' => $mess 
					); 
					$result = $client->SendSMS($sms);
					if($result) {
						$return = $result->SendSMSResult->ResultArray[0];
					}
				}
			}
		}
		return $return;
	}
}
class Websms extends SmsGate {
	public static $name = "Websms.ru";
	private $baseurl = 'http://cab.websms.ru/';
    public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "websmsru", "login"))!==false && ($pass = config::Select("smsmaster", "websmsru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				if($to) {
					$numbers = $this->prepPhone($to);
				} else {
					$numbers = false;
				}
	            $balance = $this->getBalance();
	            if($balance) {
		        	$result = $this->sendSMS(config::Select("smsmaster", "websmsru", "login"), config::Select("smsmaster", "websmsru", "psw"), $numbers, $mess, $sender);
		        	if($result['error'] && $result['error'] === 'OK') {
		        		$return = $result;
		        	}
	            }
	        }
        }
		return $return;
    }
    public function sendSMS($login, $password, $phone, $text, $sender) {
    	$post = array(
    		'http_username' => rawurlencode($login),
    		'http_password' => rawurlencode($password),
    		'message' => rawurlencode($text),
    		'phone_list' => rawurlencode($phone),
    		'fromPhone' => rawurlencode($sender)
    	);
    	$post = json_encode($post);
    	$result = @file_get_contents($this->baseurl . 'json_in5.asp?json=' . $post);
    	$result = json_decode($result, TRUE);
        return $result;
    }
    public static function balance($client = false, $print = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "websmsru", "login"))!==false && ($pass = config::Select("smsmaster", "websmsru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$return = @file_get_contents($this->baseurl . 'http_credit.asp?http_username=' . rawurlencode(config::Select("smsmaster", "websmsru", "login")) . '&http_password=' . rawurlencode(config::Select("smsmaster", "websmsru", "psw")));
			}
	    }
        return $return;
    }
}




class SmsMaster extends modules {

	function __construct() {
		addEvent("get_current_sms_balance", array($this, "current_sms_balance"));
		if(defined("IS_ADMINCP")) {
			addEvent("init_core", function() {
				removeEvent("admin_core_prints_info", "smsc");
				addEvent("admin_core_prints_info", array($this, "sms_balance"));
				removeEventRef("settinguser_main", "smsc");
				add_setting_tab("{include templates=\"SmsMaster.tpl,SettingUser\"}", "Настройка СМС");
			});
			addEventRef("settinguser_main", array($this, "addConfig"));
			addEvent("settinguser_sort_smsmaster", array($this, "sortAdmin"));
		} else {
			/*removeEvent("pay_smsc", "smsc");
			addEvent("pay_smsc", array($this, "sms_notice"));*/
			addEvent("send_sms", array($this, "sms_notice"));
		}
	}

	private function sorter($array, $byArray) {
		if(sizeof($byArray)>0) {
			$array = array_merge(array_flip($byArray), $array);
		}
		return $array;
	}

	private function getSort($data) {
		if(empty($data)) {
			$array = array();
		} else {
			$array = array();
			try {
				$data = json_decode($data, true);
				$data = array_map(function($item) {
					return $item['item'];
				}, $data);
				$array = $data;
			} catch(Exception $ex) {}
		}
		return $array;
	}

	function getList() {
		$available = array(
			"Smscru" => Smscru::$name,
			"AlphaSms" => AlphaSms::$name,
			"Bytehand" => Bytehand::$name,
			"Infosmska" => Infosmska::$name,
			"Smscab" => Smscab::$name,
			"Smscua" => Smscua::$name,
			"Smsru" => Smsru::$name,
			"Turbosms" => Turbosms::$name,
			"Websms" => Websms::$name,
		);
		$sort_available = config::Select("smsmaster", "sort_available");
		$sort_available = $this->getSort($sort_available);
		$sort_disabled = config::Select("smsmaster", "sort_disabled");
		$sort_disabled = $this->getSort($sort_disabled);
		$sort_disabled_key = array_flip($sort_disabled);
		$sort_availabled = array();
		$sort_disable = array();
		foreach($available as $class => $name) {
			if(!isset($sort_disabled_key[$class])) {
				$sort_availabled[$class] = $name;
			} else {
				$sort_disable[$class] = $name;
			}
		}
		$sort_availabled = $this->sorter($sort_availabled, $sort_available);
		$sort_disable = $this->sorter($sort_disable, $sort_disabled);
		return array(
			"available" => $sort_availabled,
			"disable" => $sort_disable,
		);
	}

	function sortAdmin($ret, $type) {
		$tpl = '<li data-item="{id}">
									<div class="uk-nestable-item">
										<div class="uk-nestable-handle"></div>
										<div data-nestable-action="toggle"></div>
										<div class="list-label">{name}</div>
									</div>
								</li>';
		if($type=="available") {
			$sorted_available = $this->getList();
			$sorted_available = $sorted_available['available'];
		} else {
			$sorted_available = $this->getList();
			$sorted_available = $sorted_available['disable'];
		}
		// $type
		$tmp = "";
		foreach($sorted_available as $class => $name) {
			$tmp .= str_replace(array("{id}", "{name}"), array($class, $name), $tpl);
		}
		$ret .= $tmp;
		return $ret;
	}

	public static $version = "1.1";

	function addConfig(&$data) {
	}

	function sms_balance($arr) {
		$echo = "СМС отправка отключена. Доступы для отправки - не прописаны. <a href=\"./?pages=SettingUser\">Настроить</a>";
		$ret = "";
		if(($login = config::Select("smsmaster", "smscru", "login"))!==false && ($pass = config::Select("smsmaster", "smscru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через smsc.ru составляет - ".Smscru::balance(false, true)."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "bytehandcom", "login"))!==false && ($pass = config::Select("smsmaster", "bytehandcom", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через bytehand.com составляет - ".Bytehand::balance(false, true)."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "alphasms", "login"))!==false && ($pass = config::Select("smsmaster", "alphasms", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через alphasms.ua составляет - ".AlphaSms::balance(false, true)."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "infosmskaru", "login"))!==false && ($pass = config::Select("smsmaster", "infosmskaru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через infosmska.ru составляет - ".Infosmska::balance(false, true)."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "smscabru", "login"))!==false && ($pass = config::Select("smsmaster", "smscabru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через smscab.ru составляет - ".Smscab::balance(false, true)."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "smscua", "login"))!==false && ($pass = config::Select("smsmaster", "smscua", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через smsc.ua составляет - ".Smscua::balance(false, true)."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "smsru", "login"))!==false && ($pass = config::Select("smsmaster", "smsru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через sms.ru составляет - ".Smsru::balance(false, true)."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "turbosmsinua", "login"))!==false && ($pass = config::Select("smsmaster", "turbosmsinua", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через turbosms.in.ua составляет - ".Turbosms::balance(false, true)."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "websmsru", "login"))!==false && ($pass = config::Select("smsmaster", "websmsru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через websms.ru составляет - ".Websms::balance(false, true)."<br>";
			}
		}
		if(!empty($ret)) {
			$echo = $ret;
		}
		$arr[$echo] = array("type" => "info", "block" => true, "echo" => $echo, "time" => time()+1);
		return $arr;
	}

	function current_sms_balance() {
		$available = array(
			"Smscru" => Smscru::$name,
			"AlphaSms" => AlphaSms::$name,
			"Bytehand" => Bytehand::$name,
			"Infosmska" => Infosmska::$name,
			"Smscab" => Smscab::$name,
			"Smscua" => Smscua::$name,
			"Smsru" => Smsru::$name,
			"Turbosms" => Turbosms::$name,
			"Websms" => Websms::$name,
		);
		$class = config::Select("smsmaster", "sendfrom");
		if($class=="sort") {
			$sorted_available = $this->getList();
			$sorted_available = $sorted_available['available'];
			foreach($sorted_available as $class => $name) {
				$sorted_available[$class] = array("name" => $name, "balance" => $class::balance());
			}
			$sendFor = "";
			foreach($sorted_available as $class => $data) {
				if($data['balance']>2) {
					$sendFor = $class;
					break;
				}
			}
			if(!empty($sendFor)) {
				$class = $sendFor;
			} else {
				return array("service" => "unknown", "balance" => 0);
			}
		}
		if(!class_exists($class, false)) {
			return array("service" => "unknown", "balance" => 0);
		}
		return array("service" => $available[$class], "balance" => $class::balance());
	}

	function sms_notice($sender, $to, $mess) {
		$class = config::Select("smsmaster", "sendfrom");
		if($class=="sort") {
			$sorted_available = $this->getList();
			$sorted_available = $sorted_available['available'];
			foreach($sorted_available as $class => $name) {
				$sorted_available[$class] = array("name" => $name, "balance" => $class::balance());
			}
			$sendFor = "";
			foreach($sorted_available as $class => $data) {
				if($data['balance']>2) {
					$sendFor = $class;
					break;
				}
			}
			if(!empty($sendFor)) {
				$class = $sendFor;
			} else {
				return false;
			}
		}
		if(is_array($sender) && isset($sender[$class])) {
			$sender = $sender[$class];
		}
		if(!class_exists($class, false)) {
			return false;
		}
		$class = new $class();
		return call_user_func_array(array($class, "send"), array($sender, $to, $mess));
	}

}


?>