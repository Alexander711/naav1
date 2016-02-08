<?php
defined('SYSPATH') or die('No direct script access.');
class Text extends Kohana_Text
{
    public static function mb_ucfirst($str, $enc = 'utf-8') {
        return mb_strtoupper(mb_substr($str, 0, 1, $enc), $enc).mb_substr($str, 1, mb_strlen($str, $enc), $enc);
    }
    public static function translit($str)
    {
        $tr = array(
            "А"=>"A","Б"=>"B","В"=>"V","Г"=>"G",
            "Д"=>"D","Е"=>"E","Ж"=>"J","З"=>"Z","И"=>"I",
            "Й"=>"Y","К"=>"K","Л"=>"L","М"=>"M","Н"=>"N",
            "О"=>"O","П"=>"P","Р"=>"R","С"=>"S","Т"=>"T",
            "У"=>"U","Ф"=>"F","Х"=>"H","Ц"=>"TS","Ч"=>"CH",
            "Ш"=>"SH","Щ"=>"SCH","Ъ"=>"","Ы"=>"YI","Ь"=>"",
            "Э"=>"E","Ю"=>"YU","Я"=>"YA","а"=>"a","б"=>"b",
            "в"=>"v","г"=>"g","д"=>"d","е"=>"e","ж"=>"j",
            "з"=>"z","и"=>"i","й"=>"y","к"=>"k","л"=>"l",
            "м"=>"m","н"=>"n","о"=>"o","п"=>"p","р"=>"r",
            "с"=>"s","т"=>"t","у"=>"u","ф"=>"f","х"=>"h",
            "ц"=>"ts","ч"=>"ch","ш"=>"sh","щ"=>"sch","ъ"=>"y",
            "ы"=>"yi","ь"=>"'","э"=>"e","ю"=>"yu","я"=>"ya"
        );
        return strtr($str, $tr);
    }
	
	/**
	 * Helper for create short description news
	 * 
	 * @param string $text
	 * @param integer $limit
	 * @param string $allow_tags
	 * @return string
	 */
	public static function short_story($text, $limit = 200, $allow_tags = NULL)
	{
		if ($allow_tags != NULL)
		{
			$text = strip_tags($text, $allow_tags);
		}
		else 
		{
			$text = strip_tags($text);
		}
		
		$text = str_replace(array('&ndash;', '&nbsp;', '&amp;', '&laquo;', '&raquo;', '\n', '\r'), ' ', $text);
		$text = preg_replace('/(\r\n|\n|\r)\s*(\r\n|\n|\r)/', ' ', $text);
		$text = preg_replace('/(\r\n|\n|\r)\s*(\r\n|\n|\r)/', ' ', $text);
		$text = preg_replace('/[ \t]{1,}/', ' ', $text);
		$text = UTF8::trim($text);
		
		if ($limit > 0)
		{
			$text = self::limit_chars($text, $limit, NULL, TRUE);
			// $text = self::limit_words($text, $limit, NULL, TRUE);
		}
		
		// $text = HTML::chars($text);
		
		return $text;
	}

    /**
     * HTML to Text converter
     *
     * @param string $str
     * @return string
     */
    public static function html_to_text($str)
    {
        $search = array ("'<script[^>]*?>.*?</script>'si",  // Вырезает javaScript
                         "'<[\/\!]*?[^<>]*?>'si",           // Вырезает HTML-теги
                         "'([\r\n])[\s]+'",                 // Вырезает пробельные символы
                         "'&(quot|#34);'i",                 // Заменяет HTML-сущности
                         "'&(amp|#38);'i",
                         "'&(lt|#60);'i",
                         "'&(gt|#62);'i",
                         "'&(nbsp|#160);'i",
                         "'&(iexcl|#161);'i",
                         "'&(cent|#162);'i",
                         "'&(pound|#163);'i",
                         "'&(copy|#169);'i",
                         "'&#(\d+);'e");                    // интерпретировать как php-код

        $replace = array ("",
                          "",
                          "\\1",
                          "\"",
                          "&",
                          "<",
                          ">",
                          " ",
                          chr(161),
                          chr(162),
                          chr(163),
                          chr(169),
                          "chr(\\1)");

        $str = preg_replace($search, $replace, $str);

        return $str;
    }
}