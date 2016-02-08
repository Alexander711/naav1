<?php
defined('SYSPATH') or die('No direct script access.');
$text = str_replace('Login', $username, $text);
$text = str_replace('%email_confirm_link%', $email_confirm_link, $text);
echo $text;
?>
