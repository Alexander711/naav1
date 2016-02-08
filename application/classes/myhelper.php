<?php
defined('SYSPATH') or die('No direct script access.');
class MyHelper
{
    static function get_file_name(Array $file)
    {
        $name = md5(date('YmdHis')).'.'.Myhelper::my_exts($file['type']);
        return $name;
    }
    static function get_image_pict_name($image_name)
    {
        $name_pie = explode('.', $image_name);
        return $name_pie[0].'_pict.'.$name_pie[1];
    }
    static  function my_exts($mime)
    {
        $ext = '';
        switch ($mime)
        {
            case "image/bmp":
                $ext = 'bmp';
            break;

            case "image/x-windows-bmp":
                $ext = 'bmp';
            break;

            case "image/gif":
                $ext = "gif";
            break;

            case "image/jpeg":
                $ext = "jpg";
            break;

            case "image/png":
                $ext = "png";
            break;
        }
        return $ext;
    }
    public static function get_work_by_categories_list($works, $work_id)
    {
        $work = NULL;
        foreach ($works as $category_name => $category)
        {
            if  (Arr::get($category, $work_id, NULL))
                return TRUE;

        }

        return $work;
    }
    public static function compose_url($params)
    {
        $url_pies = array();
        foreach ($params as $url_param => $value)
        {
            if ($value['value'])
               $url_pies[$url_param] = $url_param.'_'.$value['value'];
        }
        return $url_pies;
    }

	public static function 	morph($number, $f1, $f2, $f5)
	{
		$number = abs($number) % 100;
		$n1 = $number % 10;
		if ($number > 10 && $number < 20) return $f5;
		if ($n1 > 1 && $n1 < 5) return $f2;
		if ($n1 == 1) return $f1;
		return $f5;
	}
}