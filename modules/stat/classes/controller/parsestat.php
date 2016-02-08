<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_ParseStat extends Controller
{
    protected $_params = 'pk';
    public function action_index()
    {
        $this->response->headers('Pragma', 'no-cache');


        $resources = new ResourceFactory();
        foreach (ORM::factory('visit')->where('processed', '=', 0)->order_by('uri', 'ASC')->order_by('date', 'ASC')->limit(300)->find_all() as $v)
        {
            $resources->factory($v);
        }

    }
}