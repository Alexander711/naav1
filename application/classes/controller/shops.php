<?php

defined('SYSPATH') or die('No direct script access.');

class Controller_Shops extends Controller_Frontend {

    public $template = 'templates/frontend_news';

    public function before() {
        parent::before();

        $this->template->bc['shops'] = 'Магазины автозапчастей';

        // add class for news pages
        $this->template->css_class = 'wrapper-news';
    }

    /**
     * Обзор магазинов
     * @return void
     */
    public function action_index() {
        $result = array();

        $groups = ORM::factory('group')->find_all();

        foreach ($groups as $group) {
            if ($group->parrent_id == 0) {
                $result[$group->id]['name_group'] = $group->name;
            }

            if ($group->parrent_id != 0) {
                $result[$group->parrent_id]['sub_group'][$group->id]['name'] = $group->name;
            }
        }

        $this->view = View::factory('frontend/services/shops_all')
                ->set('result', $result);
        $this->template->title = 'Только лучшие магазины авто запчастей';
        $this->template->content = $this->view;
    }

    public function action_view() {
        
    }

}
