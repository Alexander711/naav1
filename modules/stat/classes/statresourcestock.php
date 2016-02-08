<?php
defined('SYSPATH') or die('No direct script access.');
class StatResourceStock extends StatResource
{
    protected $_model_name = 'Stock';
    protected $_model_primary_key_param = 'stock_id';
}