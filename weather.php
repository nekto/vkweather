<?php
/**
 * User: SkS
 * Date: 05.09.2011
 * Time: 1:09:40
 */

define('SCR_DIR', dirname(__FILE__));
include_once(SCR_DIR . '/functions.php');
include_once(SCR_DIR . '/pwd.php');

$html = cURL_get_file('http://vkontakte.ru/');
preg_match('/<input type="hidden" name="ip_h" value="([a-z0-9]*?)" \/>/isU', $html, $matches);


// auth in vk
$post = array(
	'act' => 'login',
	'al_frame' => '1',
	'captcha_key' => '',
	'captcha_sid' => '',
	'email' => $VKEMAIL,
	'expire' => '',
	'from_host' => 'vkontakte.ru',
	'ip_h' => isset($matches [1])? $matches [1]: '', // получаем из формы логина
	'pass' => $VKPWD,
	'q' => '1',
);

$auth = cURL_get_file('http://login.vk.com/?act=login', http_build_query($post), 'http://vkontakte.ru/');

//$auth = cURL_get_file('http://vk.com/login.php?email=&pass=', FALSE, 'http://vkontakte.ru/');

preg_match('#Location\: ([^\r\n]+)#is', $auth, $math);

htmlbr_var_dump($auth);

$result = cURL_get_file($math[1]);

htmlbr_var_dump($result);

$result = cURL_get_file('http://vkontakte.ru/settings.php');

htmlbr_var_dump($result);


?>