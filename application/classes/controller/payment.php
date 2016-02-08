<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Payment extends Controller_Frontend
{
	function action_index()
    {
        $this->response->body('closed');
    }
}