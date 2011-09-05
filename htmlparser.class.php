<?
/*
 Отличный парсер HTML от Дмитрия Родина.
 http://blog.madie.ru/2009/05/03/www-parsing-with-tidy-xmldom-simplexml/

 Отредактировал и немного исправил SkS - http://blog.lalf.ru/
*/


class HTMLParser {

	private $xml = '';


    function HTMLParser($url)
	{
        // Получение данных
		if (function_exists('cURL_get_file'))
		{
			$input = cURL_get_file($url);
		}
		else
		{
			$input = file_get_contents($url);
		}

        // Подготовка полученных данных к обработке
        $this->xml = self::rawToSimpleXML($input);
    }

	// Получение данных через XPath
	public function getDataFromXPath($xpath)
	{
		return self::getFromXPath($this->xml->xpath($xpath));
	}

    static private function rawToSimpleXML($data)
	{

        /*
        * Конфиг Tidy
        */
		$tidy_config = array(
			'input-encoding' => 'utf8',
			'output-encoding' => 'utf8',
			'output-xml' => TRUE,
			'add-xml-decl' => TRUE,
			'hide-comments' => TRUE
		);

		/*
		* Загрузка данных и очистка от ошибок
		*/


		$tidy = new tidy();
		$tidy->parseString($data, $tidy_config, $tidy_config['output-encoding']);
		$tidy->cleanRepair();
		$tidy_out = $tidy->html()->value;

		/*
		* Инициализация XML DOM
		*/

		$dom = new DOMDocument();
		$dom->strictErrorChecking = FALSE;
		@$dom->loadHTML($tidy_out);

		unset($tidy);

		/*
		* Инициализация SimpleXML
		*/

		$simpexml = simplexml_import_dom($dom);
		unset($dom);

		return $simpexml;
    }

    /*
    * Вспомогательная функция для очистки XML
    */
    static private function xmlEscape($text)
	{
        return str_replace(array("\r", "\n"), array('', ' '), $text);
    }

    /*
    * Вспомогательная функция для получения данных из SimpleXML
    */
    static private function getFromXPath($xml){
        $text = '';
        if($xml && $xml[0]){
            $text = self::xmlEscape( html_entity_decode (strip_tags($xml[0]->asXML())));
        }
        return $text;
    }
}

?>