<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Quarantine extends Controller
{
    function action_index()
    {
        $this->response->body('closed');
    }
}