<?php

abstract class SmsGate {
	private $data = array();
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
  	public static function balance($client = false) {}
	abstract function send($sender, $to, $mess);
    public function prepPhone($phone) {
        $result = preg_replace('/[^0-9,]/', '', $phone);
        return $result;
    }
}
class Smscru extends SmsGate {
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
class Bytehand extends SmsGate {
	public static function balance($client = false) {
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
	public static function balance($client = false) {
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
	public static function balance($client = false) {
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
	public static function balance($client = false) {
		$return = "0";
		if(($login = config::Select("smsmaster", "smsc", "login"))!==false && ($pass = config::Select("smsmaster", "smsc", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				if(!$client) {
					$client = new SoapClient('https://smsc.ua/sys/soap.php?wsdl');
				}
				$credentials = Array ( 
					'login' => config::Select("smsmaster", "smsc", "login"), 
					'psw' => config::Select("smsmaster", "smsc", "psw") 
				);
				$balance = $client->get_balance($credentials);
				$return = $balance->balanceresult->balance;
			}
		}
		return $return;
	}
	public function send($sender, $to, $mess) {
		$return = false;
		if(($login = config::Select("smsmaster", "smsc", "login"))!==false && ($pass = config::Select("smsmaster", "smsc", "psw"))!==false) {
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
						'login'  => config::Select("smsmaster", "smsc", "login"), 
						'psw' 	 => config::Select("smsmaster", "smsc", "psw"),
						'phones' => $numbers, 
						'mes' 	 => $this->message,
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
	public static function balance($client = false) {
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
	public static function balance($client = false) {
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
    public static function balance($client = false) {
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
		if(defined("IS_ADMINCP")) {
			addEvent("init_core", function() {
				removeEvent("admin_core_prints_info", "smsc");
				removeEventRef("settinguser_main", "smsc");
				add_setting_tab("{include templates=\"SmsMaster.tpl,SettingUser\"}", "Настройка СМС");
			});
			addEventRef("settinguser_main", array($this, "addConfig"));
		} else {
			removeEvent("pay_smsc", "smsc");
			addEvent("pay_smsc", array($this, "sms_notice"));
			addEvent("send_sms", array($this, "sms_notice"));
		}
	}

	public static $version = "1.0";

	function addConfig(&$data) {
	}

	function sms_balance($arr) {
		$echo = "СМС отправка отключена. Доступы для отправки - не прописаны. <a href=\"./?pages=SettingUser\">Настроить</a>";
		$ret = "";
		if(($login = config::Select("smsmaster", "smscru", "login"))!==false && ($pass = config::Select("smsmaster", "smscru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через smsc.ru составляет - ".Smscru::balance()."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "bytehandcom", "login"))!==false && ($pass = config::Select("smsmaster", "bytehandcom", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через bytehand.com составляет - ".Bytehand::balance()."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "infosmskaru", "login"))!==false && ($pass = config::Select("smsmaster", "infosmskaru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через infosmska.ru составляет - ".Infosmska::balance()."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "smscabru", "login"))!==false && ($pass = config::Select("smsmaster", "smscabru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через smscab.ru составляет - ".Smscab::balance()."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "smsc", "login"))!==false && ($pass = config::Select("smsmaster", "smsc", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через smsc.ua составляет - ".Smscua::balance()."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "smsru", "login"))!==false && ($pass = config::Select("smsmaster", "smsru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через sms.ru составляет - ".Smsru::balance()."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "turbosmsinua", "login"))!==false && ($pass = config::Select("smsmaster", "turbosmsinua", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через turbosms.in.ua составляет - ".Turbosms::balance()."<br>";
			}
		}
		if(($login = config::Select("smsmaster", "websmsru", "login"))!==false && ($pass = config::Select("smsmaster", "websmsru", "psw"))!==false) {
			if(!empty($login) && !empty($pass)) {
				$ret .= "Ваш баланс по отправке смс через websms.ru составляет - ".Websms::balance()."<br>";
			}
		}
		if(!empty($ret)) {
			$echo = $ret;
		}
		$arr[$echo] = array("type" => "info", "block" => true, "echo" => $echo, "time" => time()+1);
		return $arr;
	}

	function sms_notice($sender, $to, $mess) {
		$class = config::Select("smsmaster", "sendfrom");
		if(!class_exists($class, false)) {
			return false;
		}
		$class = new $class();
		return call_user_func_array(array($class, "send"), func_get_args());
	}

}


?>