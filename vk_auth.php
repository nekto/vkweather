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

	public function post_to_wall($msg)
	{
		if(!$this->post_to_wall_query($msg))
		{
			put_error_in_logfile('Message not posted!');
			return FALSE;
		}
		return TRUE;
	}

	private function auth()
	{
		global $VKCOOKIES;
		clear_cookie();

		$location = $this->get_auth_location();
		if($location === FALSE){
			put_error_in_logfile('vK not return Location!');
			return FALSE;
		}

		$sid = $this->get_auth_cookies($location);
		if(!$sid){
			put_error_in_logfile('vK not authorised!');
			return FALSE;
		}
		// TODO: сделать публичную функцию браузера
		$VKCOOKIES = 'remixsid=' . $sid . '; path=/; domain=.vkontakte.ru';

		return TRUE;
	}

	private function get_hash()
	{
		$result = cURL_get_file('http://vkontakte.ru/public' . $this->ppid);
		preg_match('#"post_hash":"(\w+)"#isU', $result, $match);

		if (strpos($result, 'action="https://login.vk.com/?act=login'))
		{
			unset($match[1]);
		}

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
		return ((strpos($result, "setCookieEx('sid', ") === FALSE) ? FALSE :
				substr($result, strpos($result, "setCookieEx('sid', '") + 20, 60));
	}


	private function post_to_wall_query($msg)
	{
		$post = array(
			'act' => 'post',
			'al' => '1',
			'facebook_export' => '',
			'friends_only' => '',
			'hash' => $this->hash,
			'message' => $msg,
			'note_title' => '',
			'official' => '',
			'status_export' => '',
			'to_id' => '-' . $this->ppid,
			'type' => 'all',
		);

		$result = cURL_get_file('http://vkontakte.ru/al_wall.php', http_build_query($post));

		$this->sleep();
		return strpos($result, '<!>0<!><input');
	}

	private function sleep()
	{
		sleep($this->sleeptime + rand(1, 4));
	}
}

?>