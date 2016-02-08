<?php
defined('SYSPATH') or die('No direct script access.');
class StatResourceNews extends StatResource
{
    protected $_model_name = 'NewsService';
    protected $_model_primary_key_param = 'news_id';
}