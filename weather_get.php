<?php

$parser = new HTMLParser('http://foreca.ru/Russia/' . $CITY);

$now = array(
	$parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div/span'),
	format_type_weather($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div[2]'), 'main'),
//	$parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div/img[2]'),
	'ветер ' . weather_wind_bug($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div/strong')), 
	'данные получены ' . implode(' ', array_slice(explode(' ', trim($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div[3]'))), 1, 2))
);
$now = 'Сейчас: ' . implode(', ', $now) . '.';
unset($parser);

sleep($SLEEPTIME + rand(1,4));




$parser = new HTMLParser('http://foreca.ru/Russia/' . $CITY . '?details=' . date('Y.m.d'));

// 7:00
$morning = array(
	'Данные на 10:00: ' . $parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[6]/div[2]/span/strong'),
	format_type_weather($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[6]/div[4]')),
	'ветер ' . weather_wind_bug($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[6]/div[3]/strong')),
);
$morning = implode(', ', $morning) . '.';

// 12:00
$afternoon = array(
	'Данные на 13:00: ' . $parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[7]/div[2]/span/strong'),
	format_type_weather($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[7]/div[4]')),
	'ветер ' . weather_wind_bug($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[7]/div[3]/strong')),
);
$afternoon = implode(', ', $afternoon) . '.';

$afternoon16 = array(
	'Данные на 16:00: ' . $parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[8]/div[2]/span/strong'),
	format_type_weather($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[8]/div[4]')),
	'ветер ' . weather_wind_bug($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[8]/div[3]/strong')),
);
$afternoon16 = implode(', ', $afternoon16) . '.';

// 18:00
$evening = array(
	'Данные на 19:00: ' . $parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[9]/div[2]/span/strong'),
	format_type_weather($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[9]/div[4]')),
	'ветер ' . weather_wind_bug($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[9]/div[3]/strong')),
);
$evening = implode(', ', $evening) . '.';


$night = array(
	'Данные на 22:00: ' . $parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[10]/div[2]/span/strong'),
	format_type_weather($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[10]/div[4]')),
	'ветер ' . weather_wind_bug($parser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[10]/div[3]/strong')),
);
$night = implode(', ', $night) . '.';

$message = $now . "\n";

switch(date('G'))
{
	case 7:
		$message .= $morning . "\n" . $afternoon;
		break;

	case 12:
		$message .= $afternoon . "\n" . $afternoon16;
		break;

	case 18:
		$message .= $evening . "\n" . $night;
		break;
}

?>