<?
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
@ini_set('memory_limit', '64M');
@set_time_limit(0);
@ini_set('max_execution_time',0);
@ini_set('set_time_limit',0);
@ob_end_flush();

function cURL_get_file($url, $post = FALSE, $ref = FALSE, $proxy = FALSE, $proxy_port = FALSE, $proxy_type = FALSE) {

/*
$url_a = @explode('#', $url);
$url = $url_a[0];
*/


$users = array('Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1',
'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; .NET CLR 2.0.50727; .NET CLR 1.1.4322)',
'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Zango 10.1.181.0)',
'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; LTS8; .NET CLR 1.1.4322)',
'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; FunWebProducts; SV1; .NET CLR 1.0.3705; .NET CLR 1.1.4322)',
'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 1.1.4322; .NET CLR 2.0.50727; MEGAUPLOAD 2.0)',
'Mozilla/5.0 (Windows; U; Windows NT 5.1; en-US; rv:1.8.1.2) Gecko/20070219 Firefox/2.0.0.2',
'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FDM; .NET CLR 1.1.4322)',
'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; AIRF; Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1) ; .NET CLR 1.0.3705; .NET CLR 1.1.4322; Media Center PC 4.0)',
'Mozilla/4.0 (compatible; BorderManager 3.0)',
'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; FunWebProducts; SIMBAR={A2193EC4-2981-4774-BF42-8BD7120D3637})',
'Mozilla/5.0 (Windows; U; Windows NT 5.1; ru; rv:1.8.1.8) Gecko/20071008 Firefox/2.0.0.8',
'Mozilla/5.0 (X11; U; Linux i686; en-US; rv:1.8.0.3) Gecko/20060523 Ubuntu/dapper Firefox/1.5.0.3',
'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.2; WOW64; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)',
'Mozilla/4.0 (compatible; MSIE 7.0; Windows NT 5.1; iebar; InfoPath.2; .NET CLR 1.1.4322; .NET CLR 2.0.50727; .NET CLR 3.0.04506.30)',
'Mozilla/4.0 (compatible; MSIE 6.0; Windows NT 5.1; SV1; FunWebProducts; .NET CLR 1.1.4322; MEGAUPLOAD 2.0; .NET CLR 2.0.50727)');

//shuffle($users);

$user = $users[0];


$ch = curl_init($url);
	curl_setopt($ch, CURLOPT_HEADER, TRUE);
	curl_setopt($ch, CURLOPT_NOBODY, FALSE);
// ���� ��������� �� ��������� �������, �� ���� ��������� safe_mode � open_basedir
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

/*
������� ��� ������� ����� � ������
*/
function clear_cookie() {
	@chmod(SCR_DIR . '/data/cookies.txt', 0777);
	$fp = fopen(SCR_DIR . '/data/cookies.txt', 'w');
//	fwrite($fp, '');
	fclose($fp);
	@chmod(SCR_DIR . '/data/cookies.txt', 0777);
}


function htmlbr_var_dump($var)
{
	echo '<pre>';
	var_dump($var);
	echo '</pre>';
}

?>