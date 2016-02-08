<?php
defined('SYSPATH') or die('No direct script access.');
class Request extends Kohana_Request
{
    public function get_params()
    {
        return $this->_params;
    }
}