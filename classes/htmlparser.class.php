<?
/*
 Отличный парсер HTML от Дмитрия Родина.
 http://blog.madie.ru/2009/05/03/www-parsing-with-tidy-xmldom-simplexml/

 Отредактировал и немного исправил SkS - http://blog.lalf.ru/
*/


class HTMLParser
{

	private $xml = '';
	private $minicurl;


    function __construct($url)
	{
		$this->minicurl = new minicurl(FALSE, SCR_DIR . '/data/cookies.txt', 'Mozilla/5.0 (Windows NT 6.1; rv:2.0.1) Gecko/20100101 Firefox/4.0.1');;

        // Получение данных
		$input = $this->minicurl->get_file($url);

        // Подготовка полученных данных к обработке
        $this->xml = self::rawToSimpleXML($input);
    }

	public function getConvDataFromXPath($xpath)
	{
		return iconv('UTF-8', 'ISO-8859-1', self::getFromXPath($this->xml->xpath($xpath)));
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
			'input-encoding' => 'utf-8',
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
		unset($tidy);

		/*
		* Инициализация XML DOM
		*/
		$dom = new DOMDocument();
		$dom->strictErrorChecking = FALSE;
		@$dom->loadHTML($tidy_out);

		/*
		* Инициализация SimpleXML
		*/
		$simplexml = simplexml_import_dom($dom);
		unset($dom);

		return $simplexml;
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