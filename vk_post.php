<?php
error_reporting(E_ALL);
ini_set('display_errors', TRUE);
@ini_set('memory_limit', '16M');
@set_time_limit(0);
@ini_set('max_execution_time',0);
@ini_set('set_time_limit',0);
header('Content-Type: text/html; charset=utf-8'); 
@ob_end_flush();


define('SCR_DIR', dirname(__FILE__));

include_once(SCR_DIR . '/config.php');
include_once(SCR_DIR . '/functions.php');
include_once(SCR_DIR . '/classes/minicurl.class.php');
include_once(SCR_DIR . '/classes/vk_auth.class.php');
include_once(SCR_DIR . '/classes/htmlparser.class.php');
if($PARSINGFROM == 'foreca')
{
	include_once(SCR_DIR . '/classes/parsing_foreca.class.php');
}
elseif ($PARSINGFROM == 'gismeteo')
{
	include_once(SCR_DIR . '/classes/parsing_gismeteo.class.php');
}


if(!function_exists('curl_init'))
{
	exit('Not installed curl!');
}

if(!check_last_post())
{
	exit('Already posted!');
}


$vk = new vk_auth($VKEMAIL, $VKPWD, $VKPPID, $SLEEPTIME);

if(!$vk->check_auth())
{
	exit('Error! See logfile.');
}

$weather = new WeatherParser($CITY);
$message = $weather->get_data();

if (!$vk->post_to_wall($message)) {
	exit('Error! Not Posted!');
}
else
{
	write_last_post();

	echo 'Posted!';
}


?>