<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Tags extends Controller
{
    public function action_index()
    {
        $car_brand = ORM::factory('car_brand');
        foreach ($car_brand->find_all() as $car)
        {
            if (trim($car->name))
                echo 'Автосервисы '.$car->name.'<br/>';
            echo 'Автосервисы '.$car->name_ru.'<br/>';
        }
    }
    public function action_work()
    {
        $work = ORM::factory('work');
        foreach ($work->find_all() as $w)
        {
            echo 'Автосервисы '.$w->name.'<br/>';
        }
    }
    public function action_district()
    {
        $district = ORM::factory('district')->where('city_id', '=', 1);
        foreach ($district->find_all() as $d)
        {
            echo 'Автосервисы '.$d->abbreviation.'<br />';
        }
    }
    public function action_metro()
    {
        $metro = ORM::factory('metro')->where('city_id', '=', 1);
        foreach ($metro->find_all() as $m)
        {
            echo 'Автосервисы '.$m->name.'<br />';
        }
    }
}