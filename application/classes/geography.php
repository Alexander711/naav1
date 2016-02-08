<?php
defined('SYSPATH') or die('No direct script access.');
class Geography
{

    public static function  update_geography_params()
    {
        $service = ORM::factory('service')->where('type', '=', 1);

        $cities = array(
            'update_date' => Date::formatted_time(),
            'total' => array()
        );
        $districts = array();
        $metro_stations = array();

        foreach ($service->find_all() as $s)
        {
            if ($s->district_id)
            {
                if (!isset($cities['has_district'][$s->city_id]))
                {
                    $cities['has_district'][$s->city_id]['city_id'] = $s->city_id;

                }
                if (!isset($districts[$s->district_id]))
                {
                    $districts[$s->district_id] = array('obj' => $s->district, 'city_id' => $s->city_id);
                    $districts[$s->district_id]['works'] = array();
                    $districts[$s->district_id]['cars'] = array();
                }
            }
            if ($s->metro_id)
            {
                if (!isset($cities['has_metro'][$s->city_id]))
                {
                    $cities['has_metro'][$s->city_id]['city_id'] = $s->city_id;

                }
                if (!isset($metro_stations[$s->metro_id]))
                {
                    $metro_stations[$s->metro_id] = array(
                        'obj' => $s->metro,
                        'city_id' => $s->city_id,
                        'district_id' => $s->district_id
                    );
                    $metro_stations[$s->metro_id]['works'] = array();
                    $metro_stations[$s->metro_id]['cars'] = array();
                }

            }

            if ($s->has('works'))
            {
                if (!isset($cities['total'][$s->city_id]))
                    $cities['total'][$s->city_id] = $s->city;

                $cities['has_works'][$s->city_id]['city_id'] = $s->city_id;
                if (!isset($cities['has_works'][$s->city_id]['works']))
                    $cities['has_works'][$s->city_id]['works'] = array();

                foreach ($s->works->find_all() as $w)
                {
                    if (!in_array($w->id, $cities['has_works'][$s->city_id]['works']))
                        $cities['has_works'][$s->city_id]['works'][] = $w->id;
                    if ($s->district_id)
                    {
                        if (!in_array($w->id, $districts[$s->district_id]['works']))
                            $districts[$s->district_id]['works'][] = $w->id;
                    }

                    if ($s->metro_id)
                    {
                        if (!in_array($w->id, $metro_stations[$s->metro_id]['works']))
                            $metro_stations[$s->metro_id]['works'][] = $w->id;
                    }

                }
            }

            if ($s->has('cars'))
            {
                if (!isset($cities['total'][$s->city_id]))
                    $cities['total'][$s->city_id] = $s->city;
                $cities['has_cars'][$s->city_id]['city_id'] = $s->city_id;
                if (!isset($cities['has_cars'][$s->city_id]['cars']))
                    $cities['has_cars'][$s->city_id]['cars'] = array();

                foreach ($s->cars->find_all() as $c)
                {
                    if (!in_array($c->id, $cities['has_cars'][$s->city_id]['cars']))
                        $cities['has_cars'][$s->city_id]['cars'][] = $c->id;
                    if ($s->district_id)
                        if (!in_array($c->id, $districts[$s->district_id]['cars']))
                            $districts[$s->district_id]['cars'][] = $c->id;
                    if ($s->metro_id)
                        if (!in_array($c->id, $metro_stations[$s->metro_id]['cars']))
                            $metro_stations[$s->metro_id]['cars'][] = $c->id;
                }

            }
        }


        $params = array(
            'cities' => $cities,
            'districts' => $districts,
            'metro_stations' => $metro_stations
        );
        // Запись в кэш на 24 часа
        Cache::instance()->set('geo_params', $params, 86400);
        return $params;
    }
    /**
     * Возвращаем гео параметры автосервисов
     * @static
     * @return mixed|void
     */
    public static function get_geography_params()
    {
        $cache = Cache::instance();
        if (!$cache->get('geo_params'))
        {
            return Geography::update_geography_params();
        }
        else
        {
            return $cache->get('geo_params');
        }
    }
    public static function set_selected_city_id($cities, $city_id)
    {
        $selected_city_ids = array(
            'total'        => $city_id,
            'has_cars'     => $city_id,
            'has_works'    => $city_id,
            'has_metro'    => $city_id,
            'has_district' => $city_id
        );
        foreach ($selected_city_ids as $type => $city_id)
        {
            if (!array_key_exists($selected_city_ids[$type], $cities[$type]))
                list($selected_city_ids[$type]) = each($cities[$type]);
        }
        return $selected_city_ids;

    }
}