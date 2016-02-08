<?php
defined('SYSPATH') or die('No direct script access.');
class StatResource
{
    protected $_resource_model;
    protected $_model_name;
    protected $_model_primary_key_param;
    protected $_has_visits_count = 0;
    protected $_visits_count = 0;
    protected $_visitors = array();

    protected $_visitor_adding_error;

    public static function factory($visit)
    {
        $class_name = StatResource::get_called_class();
        $res = new $class_name;
        if (!$res->_init($visit))
        {
            echo "Ошибка создания модели ресурса ".$visit->uri.", визит удален \n";
            $visit->delete();
            return NULL;
        }

        return $res->add_visit($visit);
    }
    protected function _init($visit)
    {
        $params = json_decode($visit->params);
        $pk = $this->_model_primary_key_param;

        if (!isset($params->$pk))
            return FALSE;

        $this->_resource_model = ORM::factory($this->_model_name, $params->$pk);
        if (!$this->_resource_model->loaded())
            return FALSE;

        // Кол-во отслеженных заходов для данного ресурса
        $this->_has_visits_count = count($this->_resource_model->visits->find_all());

        return TRUE;
    }




    /**
     * Добавление визита
     * @param $visit
     * @return StatResource
     */
    public function add_visit($visit)
    {
        echo "Обработка визита id: ".$visit->id." client_ip: ".$visit->client_ip." uri: ".$visit->uri." date: ".$visit->date.", ";

        // Если еще не имеется последней даты входа с данного IP
        if (!array_key_exists($visit->client_ip, $this->_visitors))
        {
            // Получаем последнию дату входа среди отслеживаемых визитов с данного IP
            $last_relate_visit = $this->_resource_model->visits->where('client_ip', '=', $visit->client_ip)->order_by('date', 'DESC')->find();
            $this->_visitors[$visit->client_ip] = ($last_relate_visit->loaded()) ? strtotime($last_relate_visit->date) : NULL;
        }


        // Удаляем визит
        if (!$this->_processing_visit($visit))
        {
            echo "Визит удален.";
            echo ($this->_visitor_adding_error) ? " Причина: ".$this->_visitor_adding_error."\n" : "\n";
            $visit->delete();
        }
        else
        {
            $visit->processed = 1;
            $visit->update();
            echo "Завершение обработки\n";
        }
        return $this;
    }

    /**
     * Обработка визита
     * @param $visit
     * @return boolean
     */
    protected function _processing_visit($visit)
    {
        if (strpos($visit->referrer, 'http://www.as-avtoservice.ru') !== FALSE AND strpos($visit->referrer, '&usg') !== FALSE )
        {
            $this->_visitor_adding_error = 'Зафиксирован спам';
            return FALSE;
        }
        /**
         * Если последняя дата старше
         * Или время МЕЖДУ визитами меньше cooldawn-а
         */
        if ($this->_visitors[$visit->client_ip] > strtotime($visit->date) OR ($this->_visitors[$visit->client_ip] !== NULL AND abs(strtotime($visit->date) - $this->_visitors[$visit->client_ip]) < Kohana::$config->load('statistics.visit_delay_time')))
        {
            $this->_visitor_adding_error = 'Время еще не прошло';
            return FALSE;
        }

        $this->_visits_count ++;
        $this->_visitors[$visit->client_ip] = strtotime($visit->date);

        /**
         * Если кол-во имеющихся отслеживаемых визитов больше допустимого то выпиливаем первый
         * И увеличиваем счетчик
         */
        if ($this->_has_visits_count >= Kohana::$config->load('statistics.max_last_visits'))
        {
            echo 'Удаление первого отслеживаемого ';

            // Увеличиваем статическое значение "Кол-во просмотров" у ресурса
            DB::update($this->_resource_model->get_table_name())
              ->set(array('hints' => DB::expr('hints + 1')))
              ->where('id', '=', $this->_resource_model->id)
              ->execute();

            $first_relate_visit = $this->_resource_model->visits->find();
            $this->_resource_model->remove('visits', $first_relate_visit);
            $first_relate_visit->delete();
            $this->_has_visits_count --;
        }

        $this->_resource_model->add('visits', $visit->id);
        $this->_has_visits_count ++;
        return TRUE;
    }


    public static  function get_called_class()
    {
        $obj = false;
        $backtrace = debug_backtrace();
        foreach($backtrace as $row)
        {
            if($row['function'] == 'call_user_func')
            {
                $obj = explode('::', $backtrace[2]['args'][0]);
                $obj = $obj[0];
                break;
            }
        }
        if(!$obj)
        {
            $backtrace = $backtrace[1];
            $file = file_get_contents($backtrace["file"]);
            $file = explode("\n", $file);
            for($line = $backtrace["line"] - 1; $line > 0; $line--)
            {
                preg_match("/(?<class>\w+)::(.*)/", trim($file[$line]), $matches);
                if (isset($matches["class"]))
                {
                    return $matches["class"];
                }
            }
            throw new Exception("Could not find");
        }
        return $obj;
    }

    /**
     * Кол-во заходов на ресурс
     * @return int
     */
    public function get_visits_count()
    {
        return $this->_visits_count;
    }
    /**
     * Кол-во отслеженных заходов ресурса
     * @return int
     */
    public function get_has_visits_count()
    {
        return $this->_has_visits_count;
    }
}