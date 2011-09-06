<?

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
	$msg = '[' . date('Y.m.d H:i:s') . ']: ' . $msg . "\n";
	$fp = fopen(SCR_DIR . '/data/logfile.txt', 'a');
	fwrite($fp, $msg);
	fclose($fp);
}

function format_type_weather($data, $from = 'detail')
{
	$data = trim($data);
	return strtolower(rtrim(substr($data, 0, strpos($data, (($from == 'detail') ? 'Вероятность' : 'Ощущается')))));
}

function weather_wind_bug($data)
{
	return ((empty($data) OR strpos($data, '°')) ? 'не указан' : $data);
}

function check_last_post()
{
	$date = unserialize(file_get_contents(SCR_DIR . '/data/last.txt'));

	if(date('j') != $date['day'])
	{
		return TRUE;
	}
	else
	{
		if ($date['hour']+1 < date('G'))
		{
			return TRUE;
		}
		else
		{
			return FALSE;
		}
	}
}

function write_last_post()
{
	$date = array(
		'day' => date('j'),
		'hour' => date('G'),
	);

	file_put_contents(SCR_DIR . '/data/last.txt', serialize($date));
}

?>