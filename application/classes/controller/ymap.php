<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Ymap extends Controller_Frontend
{
    public function action_index()
    {
        $this->view = View::factory('frontend/map');
        $this->add_js('assets/js/new_map.js');
        $this->add_js('http://api-maps.yandex.ru/1.1/index.xml?key='.$this->settings['YMaps_key'].'&onerror=map_alert');

        $this->template->content = $this->view;
    }
    public function action_all()
    {
        $this->view = View::factory('frontend/map_all');
        $this->add_js('assets/js/new_map.js');
        $this->add_js('http://api-maps.yandex.ru/1.1/index.xml?key='.$this->settings['YMaps_key'].'&onerror=map_alert');
        $this->template->content = $this->view;
    }
    public function action_send()
    {

        $geo_params = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HEADER, false);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        $service = ORM::factory('service');

        foreach ($service->find_all() as $s)
        {
            $s->disable_validation = TRUE;
            $addr = $s->city->name.', '.$s->address;
            curl_setopt($ch, CURLOPT_URL, 'http://geocode-maps.yandex.ru/1.x/?format=json&geocode='.urlencode($addr).'&key=AHsACE4BAAAAWheEBgIAl_ljYAxgj3KoQ3P4LkcpLKp3pskAAAAAAAAAAAAvJvgAZ0PRsCqOjDxZlQf3S6p8IQ==');
            $data = curl_exec($ch);
            $data = json_decode($data);
            echo $s->name.'<br />';
            $coords = explode(' ', $data->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos);
            $s->ymap_lat = $coords[1];
            $s->ymap_lng = $coords[0];
            $s->update();
            //echo Debug::vars($data).'<hr />';


        }
        curl_close($ch);

         //GET запрос указывается в строке URL



        //echo Debug::vars($geo_params);
        //echo $data->response->GeoObjectCollection->featureMember[0]->GeoObject->Point->pos;


    }
}