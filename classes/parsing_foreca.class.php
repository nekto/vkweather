<?

class Foreca
{

	private $HTMLParser;
	private $city;


	function __construct($city)
	{
		$this->city = $city;
	}

	public function get_data()
	{
		$this->new_parser('http://foreca.ru/Russia/' . $this->city);
		$data = $this->now_data() . "\n";

		$this->new_parser('http://foreca.ru/Russia/' . $this->city . '?details=' . date('Y.m.d'));
		switch(date('G'))
		{
			case 7:
				$data .= $this->morning_data() . "\n" . $this->afternoon_data();
				break;

			case 12:
				$data .= $this->afternoon_data() . "\n" . $this->afternoon16_data();
				break;

			case 18:
				$data .= $this->evening_data() . "\n" . $this->night_data();
				break;
		}

		return $data;
	}

	private function new_parser($url)
	{
		$minicurl_wg = new minicurl(FALSE, SCR_DIR . '/data/cookies.txt', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1');
		$HTMLParser = new HTMLParser($url, $minicurl_wg);
		$this->HTMLParser = $HTMLParser;
	}

	private function weather_bug($data)
	{
		return ((empty($data) OR strpos($data, '°')) ? 'не указано' : $data);
	}

	private function format_type_weather($data, $from = 'detail')
	{
		$data = trim($data);
		return strtolower(rtrim(substr($data, 0, strpos($data, (($from == 'detail') ? 'Вероятность' : 'Ощущается')))));
	}

	private function now_data()
	{
		$now = array(
			$this->weather_bug($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div/span')),
			$this->weather_bug($this->format_type_weather($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div[2]'), 'main')),
		//	$this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div/img[2]'),
			'ветер ' .
				$this->weather_bug($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div/strong')),
			'данные получены ' .
				$this->weather_bug(implode(' ', array_slice(explode(' ', trim($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[6]/div/div/div/div[3]'))), 1, 2)))
		);
		return 'Сейчас: ' . implode(', ', $now) . '.';
	}

	private function morning_data()
	{
		// 7:00
		$morning = array(
			$this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[6]/div[2]/span/strong'),
			$this->format_type_weather($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[6]/div[4]')),
			'ветер ' . $this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[6]/div[3]/strong')
		);
		return 'Данные на 10:00: ' . implode(', ', $morning) . '.';
	}

	private function afternoon_data()
	{
		// 12:00
		$afternoon = array(
			$this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[7]/div[2]/span/strong'),
			$this->format_type_weather($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[7]/div[4]')),
			'ветер ' . $this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[7]/div[3]/strong')
		);
		return 'Данные на 13:00: ' . implode(', ', $afternoon) . '.';
	}

	private function afternoon16_data()
	{
		$afternoon16 = array(
			$this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[8]/div[2]/span/strong'),
			$this->format_type_weather($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[8]/div[4]')),
			'ветер ' . $this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[8]/div[3]/strong')
		);
		return 'Данные на 16:00: ' . implode(', ', $afternoon16) . '.';
	}

	private function evening_data()
	{
		// 18:00
		$evening = array(
			$this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[9]/div[2]/span/strong'),
			$this->format_type_weather($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[9]/div[4]')),
			'ветер ' . $this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[9]/div[3]/strong')
		);
		return 'Данные на 19:00: ' . implode(', ', $evening) . '.';
	}

	private function night_data()
	{
		$night = array(
			$this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[10]/div[2]/span/strong'),
			$this->format_type_weather($this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[10]/div[4]')),
			'ветер ' . $this->HTMLParser->getDataFromXPath('/html/body/div/div/div[4]/div/div[2]/div[4]/div/div/div[10]/div[3]/strong')
		);
		return 'Данные на 22:00: ' . implode(', ', $night) . '.';
	}

}


?>