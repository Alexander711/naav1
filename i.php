<?php
/**
 * Created by yaap
 * Date: 07.03.13
 * Time: 10:35
 */

//open_basedir	/home/artstra/data:.
//upload_tmp_dir	/home/artstra/data/mod-tmp


echo ini_get("open_basedir")."<br>";
echo ini_get("upload_tmp_dir")."<br>";
echo sys_get_temp_dir()."<br>";
die;
phpinfo();