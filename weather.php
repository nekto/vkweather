<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
@ini_set('memory_limit', '16M');
@set_time_limit(0);
@ini_set('max_execution_time',0);
@ini_set('set_time_limit',0);
@ob_end_flush();


define('SCR_DIR', dirname(__FILE__));

include_once(SCR_DIR . '/functions.php');
include_once(SCR_DIR . '/config.php');
include_once(SCR_DIR . '/vk_auth.class.php');
include_once(SCR_DIR . '/htmlparser.class.php');

if(!function_exists('curl_init'))
{
	exit('Not installed curl!');
}
/*
$VKCOOKIES = '';

$vk = new vk_auth($VKEMAIL, $VKPWD, $VKPPID, $SLEEPTIME);

if(!$vk->check_auth())
{
	exit('Error! See logfile.');
}

if (!$vk->post_to_wall("")) {
	exit('Error! Not Posted!');
}
*/

/*
$result = cURL_get_file('http://pogoda.yandex.ru/' . $YACITY . '/details/');

$dom = new DOMDocument();
$dom->loadHTML($result);
*/



/*
* Необходимые методы дописываем в класс и используем
*/

$parser = new HTMLParser('http://pogoda.yandex.ru/' . $YACITY . '/details/');
$data = $parser->getDataFromXPath('/html/body/div[2]/table[2]/tbody/tr[2]/td[4]');

var_dump($data);

?>