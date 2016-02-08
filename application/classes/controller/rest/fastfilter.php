<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Rest_FastFilter extends Controller_Rest
{
    public function action_index() {}

    public function action_get_cities()
    {
        $default_city_id = 1;
        $cities = array();
        $filter = Arr::get($_POST, 'filter');
        $limit = ($filter == 'metro') ? 2 : null;
        $city = ORM::factory('city')
                    ->distinct('city.id')
                     ->join(array('services', 'services'))
                     ->on('services.city_id', '=', 'city.id');

        switch ($filter) 
        {
            case 'cars':
                $city->join(array('services_cars', 'services_cars'))
                     ->on('services.id', '=', 'services_cars.service_id');
            break;
            case 'works':
                $city->join(array('services_works', 'services_works'))
                      ->on('services.id', '=', 'services_works.service_id');
            break;
            case 'metro':
                $city->join(array('metro', 'metro'))
                     ->on('services.metro_id', '=', 'metro.id');
            break;

            case 'districts':
                $city->join(array('okrug', 'districts'))
                     ->on('services.district_id', '=', 'districts.id');
            break;
            default:
                break;
        }


        foreach ($city->find_all() as $c)
        {
            
            $cities[] = array(
                'id' => $c->id,
                'name' => $c->name,
                'selected' => ($c->name == 'Москва') ? true : false
            );
        }    
        //$cities[5]['selected'] = true;
      
        //sleep(3);
        $this->data = $cities;

    }
    public function action_get_filters()
    {

        $config = Kohana::$config->load('fast_filter');
        $config[0]['selected'] = true;
        $filters = array();
        foreach ($config as $value) 
        {
            $filters[] = array('type' => $value['type'], 'text' => $value['text'], 'selected' => $value['selected']);
        }
        $this->data = $filters;
    
    }
    public function action_tags()
    {
        $filter = Arr::get($_POST, 'filter');
        $city_id = Arr::get($_POST, 'city_id');

        switch ($filter) {
            case 'cars':
            $cars = ORM::factory('car_brand')
                       ->distinct('car_brand.id')
                       ->join(array('services_cars', 's_c'))
                       ->on('s_c.car_id' , '=', 'car_brand.id')
                       ->join(array('services', 'services'))
                       ->on('services.id', '=', 's_c.service_id')
                       ->where('services.city_id', '=', $city_id);
            foreach ($cars->limit(50)->find_all() as $c) 
            {
                $this->data[] = array('url' => '/services/search/car_'.$c->id.'/city_'.$city_id, 'text' => $c->name);
            }


            break;
 
            case 'works':
                $work = ORM::factory('work')
                           ->distinct('work.id')
                           ->join(array('services_works', 's_w'))
                           ->on('s_w.work_id' , '=', 'work.id')
                           ->join(array('services', 'services'))
                           ->on('services.id', '=', 's_w.service_id')
                           ->where('services.city_id', '=', $city_id);
                foreach ($work->limit(21)->find_all() as $w) 
                {
                    $this->data[] = array('url' => '/services/search/work_'.$w->id.'/city_'.$city_id, 'text' => $w->name);
                }
            break;

            case 'metro':
                $metro = ORM::factory('metro')
                            ->distinct('metro.id')
                            ->join(array('services', 'services'))
                            ->on('metro.id', '=', 'services.metro_id')
                            ->join(array('services_works', 's_w'))
                            ->on('s_w.service_id' , '=', 'services.id')
                            ->join(array('services_cars', 's_c'))
                            ->on('s_c.service_id' , '=', 'services.id')
                            ->where('services.city_id', '=', $city_id);
                foreach ($metro->limit(36)->find_all() as $m) 
                {
                    $this->data[] = array('url' => '/services/search/metro_'.$m->id, 'text' => $m->name);
                }
            break;

            case 'districts':
                $district = ORM::factory('district')
                            ->distinct('district.id')
                            ->join(array('services', 'services'))
                            ->on('district.id', '=', 'services.district_id')
                            ->join(array('services_works', 's_w'))
                            ->on('s_w.service_id' , '=', 'services.id')
                            ->join(array('services_cars', 's_c'))
                            ->on('s_c.service_id' , '=', 'services.id')
                            ->where('services.city_id', '=', $city_id);
                foreach ($district->limit(36)->find_all() as $d) 
                {
                    $this->data[] = array('url' => '/services/search/district_'.$d->id, 'text' => $d->name);
                }                
            break;        

            case 'map':
                $service = ORM::factory('service')
                              ->distinct('service.id')
                              ->where('city_id', '=', $city_id);

                foreach ($service->find_all() as $s) 
                {
                    $this->data[] = array(
                        'name' => $s->orgtype->name.' &laquo;'.$s->name.'&raquo;',
                        'info' => $s->city->name.", ".$s->address.' '.HTML::anchor($s->site, $s->site).'<br />'.HTML::anchor('services/'.$s->id, 'Подробнее'),
                        'coords' => array($s->ymap_lng, $s->ymap_lat)
                    );
                }
            break;   
            default:

            break;
        }
    }

    public function action_placemarks()
    {
        $city_id = Arr::get($_POST, 'city_id');

        $service = ORM::factory('service')
                      ->join(array('services_works', 's_w'))
                      ->on('s_w.service_id' , '=', 'service.id')
                      ->join(array('services_cars', 's_c'))
                      ->on('s_c.service_id' , '=', 'service.id')
                      ->where('city_id', '=', $city_id);

        foreach ($service->find_all() as $s) 
        {
            $this->data[] = array(
                'name' => $s->orgtype->name.' &laquo;'.$s->name.'&raquo;',
                'info' => $s->city->name.", ".$s->address.' '.HTML::anchor($s->site, $s->site).'<br />'.HTML::anchor('services/'.$s->id, 'Подробнее')
            );
        }
    }

    public function action_default_filter_type()
    {
        $this->data = array('type' => 'work');
    }
    public function action_get_tags()
    {
        
    }
}
