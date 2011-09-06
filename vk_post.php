<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
@ini_set('memory_limit', '16M');
@set_time_limit(0);
@ini_set('max_execution_time',0);
@ini_set('set_time_limit',0);
@ob_end_flush();


define('SCR_DIR', dirname(__FILE__));

include_once(SCR_DIR . '/config.php');
include_once(SCR_DIR . '/functions.php');
include_once(SCR_DIR . '/classes/minicurl.class.php');
include_once(SCR_DIR . '/classes/vk_auth.class.php');
include_once(SCR_DIR . '/classes/htmlparser.class.php');


if(!function_exists('curl_init'))
{
	exit('Not installed curl!');
}


if(!check_last_post())
{
	exit('Already posted!');
}


$minicurl_vk = new minicurl(TRUE, SCR_DIR . '/data/cookies.txt', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1');
$vk = new vk_auth($VKEMAIL, $VKPWD, $VKPPID, $SLEEPTIME, $minicurl_vk);

if(!$vk->check_auth())
{
	exit('Error! See logfile.');
}

include_once(SCR_DIR . '/weather_get.php');


if (!$vk->post_to_wall($message)) {
	exit('Error! Not Posted!');
}
else
{
	write_last_post();

	echo 'Posted!';
}


?>