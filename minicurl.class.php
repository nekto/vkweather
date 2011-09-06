<?php

class minicurl
{

	private $headers = FALSE;
	private $cookies = '';
	private $cookies_file = '';
	private $proxy = FALSE;
	private $proxy_port = FALSE;
	private $proxy_type = FALSE;
	private $user_agent = FALSE;
	private $referer = FALSE;
	private $postfields = FALSE;


	function __construct($headers, $cookies_file, $user_agent, $proxy = FALSE,
						 $proxy_port = FALSE, $proxy_type = FALSE)
	{
		$this->headers = $headers;
		$this->cookies_file = $cookies_file;
		$this->proxy = $proxy;
		$this->proxy_port = $proxy_port;
		$this->proxy_type = $proxy_type;
		$this->user_agent = $user_agent;
	}

	public function get_file($url, $postfields = FALSE, $referer = FALSE, $cookies = '')
	{
		$this->referer = $referer;
		$this->postfields = (is_array($postfields) ? http_build_query($postfields) : $postfields);
		if (!empty($cookies))
		{
			// TODO: скорее всего не будет работать с несколькими куками сразу...
			if (strpos($this->cookies, $cookies))
			{
				$this->cookies .= $cookies;
			}
		}

		return $this->cURL_get_file($url);
	}

	private function cURL_get_file($url) 
	{
		$url = (strpos($url, '#') ? current(explode('#', $url)) : $url);

		$ch = curl_init($url);
		curl_setopt($ch, CURLOPT_HEADER, $this->headers);
		curl_setopt($ch, CURLOPT_NOBODY, FALSE);
	//  safe_mode || open_basedir
		curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
		curl_setopt($ch, CURLOPT_USERAGENT, $this->user_agent);

		if (empty($this->cookies))
		{
			curl_setopt($ch, CURLOPT_COOKIEJAR, $this->cookies_file);
			curl_setopt($ch, CURLOPT_COOKIEFILE, $this->cookies_file);
		}
		else
		{
			curl_setopt($ch, CURLOPT_COOKIE, $this->cookies);
		}

		if ($this->postfields)
		{
			curl_setopt($ch, CURLOPT_POST, TRUE);
			curl_setopt($ch, CURLOPT_POSTFIELDS, $this->postfields);
		}

		if($this->proxy)
		{
			curl_setopt($ch, CURLOPT_PROXY, $this->proxy);
			curl_setopt($ch, CURLOPT_PROXYPORT, $this->proxy_port);

			if ($this->proxy_type == 1)
			{
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
			}
			elseif ($this->proxy_type == 2)
			{
				curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
			}
		}

		if ($this->referer)
		{
			curl_setopt($ch, CURLOPT_REFERER, $this->referer);
		}

		$result = curl_exec($ch);
		$error = curl_errno($ch);

		if ($error != '0') {
			// TODO: add exceptions
			echo '<br>cURL error (' , $error , '): ' , curl_error($ch) , '<br>';
			@ob_flush(); @flush();

			curl_close($ch);

			return FALSE;
		} else {
			curl_close($ch);

			return $result;
		}
	}
}

?>