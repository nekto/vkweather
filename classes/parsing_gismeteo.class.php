<?

class WeatherParser
{

	private $HTMLParser;
	private $city;
	private $d = '';


	function __construct($city)
	{
		$this->city = $city;
		$this->d = date('Y-m-d'); // shortcut
	}

	public function get_data()
	{
		$this->new_parser('http://www.gismeteo.ru/city/hourly/' . $this->city);
		$data = $this->now_data() . "\n";

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
		$this->HTMLParser = new HTMLParser($url);
	}


	private function weather_bug($data, $returned = '')
	{
		// simply hack for weather time
		if($returned)
		{
			return (empty($data) ? '' : $returned . $data);
		}
		else
		{
			return (empty($data) ? 'не указано' : $data);
		}
	}

	private function wind_type($data)
	{
		switch($data)
		{
			case 'Ш':
				return 'штиль';
			break;

			default:
				return $data;
			break;
		}
	}

	private function now_data()
	{
		$now = array(
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='weather']/div[1]/div/div[2]")),
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='weather']/div[1]/div/dl/dd")),
			'ветер ' .
				$this->wind_type($this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='weather']/div[1]/div/div[4]/dl/dt"))) . ' ' .
				$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='weather']/div[1]/div/div[4]/dl/dd")),
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='weather']/div[1]/div/div[6]/span"), 'данные получены ')
		);
		return 'Сейчас: ' . implode(', ', array_filter($now)) . '.';
	}

	private function morning_data()
	{
		// 7:00
		$morning = array(
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-10']/td[3]")),
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-10']/td[2]")),
			'ветер ' .
				$this->wind_type($this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-10']/td[5]/dl/dt"))) . ' ' .
				$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-10']/td[5]/dl/dd")) . ' м/с'
		);
		return 'Данные на 10:00: ' . implode(', ', array_filter($morning)) . '.';
	}

	private function afternoon_data()
	{
		// 12:00
		$afternoon = array(
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-13']/td[3]")),
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-13']/td[2]")),
			'ветер ' .
				$this->wind_type($this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-13']/td[5]/dl/dt"))) . ' ' .
				$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-13']/td[5]/dl/dd")) . ' м/с'
		);
		return 'Данные на 13:00: ' . implode(', ', array_filter($afternoon)) . '.';
	}

	private function afternoon16_data()
	{
		// 12:00
		$afternoon16 = array(
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-16']/td[3]")),
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-16']/td[2]")),
			'ветер ' .
				$this->wind_type($this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-16']/td[5]/dl/dt"))) . ' ' .
				$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-16']/td[5]/dl/dd")) . ' м/с'
		);
		return 'Данные на 16:00: ' . implode(', ', array_filter($afternoon16)) . '.';
	}

	private function evening_data()
	{
		// 18:00
		$evening = array(
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-19']/td[3]")),
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-19']/td[2]")),
			'ветер ' .
				$this->wind_type($this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-19']/td[5]/dl/dt"))) . ' ' .
				$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-19']/td[5]/dl/dd")) . ' м/с'
		);
		return 'Данные на 19:00: ' . implode(', ', array_filter($evening)) . '.';
	}

	private function night_data()
	{
		// 18:00
		$night = array(
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-22']/td[3]")),
			$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-22']/td[2]")),
			'ветер ' .
				$this->wind_type($this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-22']/td[5]/dl/dt"))) . ' ' .
				$this->weather_bug($this->HTMLParser->getConvDataFromXPath(".//*[@id='wrow-" . $this->d . "-22']/td[5]/dl/dd")) . ' м/с'
		);
		return 'Данные на 22:00: ' . implode(', ', array_filter($night)) . '.';
	}

}


?>