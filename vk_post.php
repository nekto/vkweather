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
*/
include_once(SCR_DIR . '/weather_get.php');

/*
if (!$vk->post_to_wall("")) {
	exit('Error! Not Posted!');
}
*/

?>