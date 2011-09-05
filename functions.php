<?

function cURL_get_file($url, $post = FALSE, $ref = FALSE, $proxy = FALSE, $proxy_port = FALSE, $proxy_type = FALSE) {

/*
$url_a = @explode('#', $url);
$url = $url_a[0];
*/


$users = array('Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',
'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322)');

//shuffle($users);

$user = $users[0];


$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_NOBODY, FALSE);
//  safe_mode || open_basedir
	curl_setopt($ch, CURLOPT_FOLLOWLOCATION, FALSE);	// FUCKED!
	curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
//	curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 15);
	curl_setopt($ch, CURLOPT_USERAGENT, $user);
//	curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
//	curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE);
//	curl_setopt($ch, CURLOPT_CAPATH, FALSE);

	curl_setopt($ch, CURLOPT_COOKIEJAR, SCR_DIR . '/data/cookies.txt');
	curl_setopt($ch, CURLOPT_COOKIEFILE, SCR_DIR . '/data/cookies.txt');

	if ($post !== FALSE) {
		curl_setopt($ch, CURLOPT_POST, TRUE);
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
	}

	if($proxy !== FALSE) {
		curl_setopt($ch, CURLOPT_PROXY, $proxy);
		curl_setopt($ch, CURLOPT_PROXYPORT, $proxy_port);
		
		if ($proxy_type == 1) {
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS4);
		} elseif ($proxy_type == 2) {
			curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_SOCKS5);
		}
	}
	
	if ($ref !== FALSE) {
		curl_setopt($ch, CURLOPT_REFERER, $ref);
	}

	$result = curl_exec($ch);
	$error = curl_errno($ch);
	
	if ($error != '0') {
		echo '<br>cURL error (' , $error , '): ' , curl_error($ch) , '<br>';
		@ob_flush(); @flush();
		
		curl_close($ch);
		
		return FALSE;
	} else {
		curl_close($ch);
		
		return $result;
	}
}

function clear_cookie() {
	@chmod(SCR_DIR . '/data/cookies.txt', 0777);
	$fp = fopen(SCR_DIR . '/data/cookies.txt', 'w');
//	fwrite($fp, '');
	fclose($fp);
	@chmod(SCR_DIR . '/data/cookies.txt', 0777);
}

function htmlpre_var_dump($var)
{
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

function put_error_in_logfile($msg)
{
	$msg = '[' . date('Y.m.d H:i:s:u') . ']: ' . $msg . "\n";
	$fp = fopen(SCR_DIR . '/data/logfile.txt', 'a');
	fwrite($fp, $msg);
	fclose($fp);
}

?>