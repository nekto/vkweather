<?

class vk_auth {
	
	private $email = '';
	private $pwd = '';
	private $hash = '';
	private $ppid = '';
	private $sleeptime = 1;

	
	function vk_auth($email, $pwd, $ppid, $sleeptime)
	{
		$this->email = $email;
		$this->pwd = $pwd;
		$this->ppid = $ppid;
		$this->sleeptime = $sleeptime;
	}

	public function check_auth()
	{
		$hash = $this->get_hash();
		
		if(empty($hash))
		{
			if($this->auth())
			{
				$hash = $this->get_hash();
				if (!empty($hash))
				{
					put_error_in_logfile('JS-Field "post_hash" not found!');
					return FALSE;
				}
			}
			else
			{
				put_error_in_logfile('Not authorised!');
				return FALSE;
			}
		}

		$this->hash = $hash;
		return TRUE;
	}

	private function auth()
	{
		clear_cookie();

		$location = $this->get_auth_location();
		if($location === FALSE){
			put_error_in_logfile('vK not return Location!');
			return FALSE;
		}

		if(!$this->get_auth_cookies($location)){
			put_error_in_logfile('vK not authorised!');
			return FALSE;
		}

		return TRUE;
	}

	private function get_hash()
	{
		$result = cURL_get_file('http://vkontakte.ru/public' . $this->ppid);
		preg_match('#"post_hash":"(\w+)"#isU', $result, $match);

		$this->sleep();
		return ((isset($match[1])) ? $match[1] : '');
	}

	private function get_auth_location()
	{
		$html = cURL_get_file('http://vkontakte.ru/');
		preg_match('#<input type="hidden" name="ip_h" value="([a-z0-9]*?)" \/>#isU', $html, $matches);

		$post = array(
			'act' => 'login',
			'al_frame' => '1',
			'captcha_key' => '',
			'captcha_sid' => '',
			'email' => $this->email,
			'expire' => '',
			'from_host' => 'vkontakte.ru',
			'ip_h' => (isset($matches[1]) ? $matches[1]: ''),
			'pass' => $this->pwd,
			'q' => '1',
		);

		$auth = cURL_get_file('http://login.vk.com/?act=login', http_build_query($post), 'http://vkontakte.ru/');
		preg_match('#Location\: ([^\r\n]+)#is', $auth, $match);

		$this->sleep();
		return ((isset($match[1])) ? $match[1] : FALSE);
	}

	private function get_auth_cookies($location)
	{
		$result = cURL_get_file($location);

		$this->sleep();
		return ((strpos($result, "setCookieEx('sid', ") === FALSE) ? FALSE : TRUE);
	}

	private function sleep()
	{
		sleep($this->sleeptime + rand(1, 4));
	}
}

?>