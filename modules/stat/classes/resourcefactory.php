<?php
defined('SYSPATH') or die('No direct script access.');
class ResourceFactory
{
    private $_resources = array();
    /**
     * Фабрика
     * @param $visit
     * @return mixed
     */
    public  function factory($visit)
    {
        if (array_key_exists($visit->uri, $this->_resources))
        {
            if ($this->_resources[$visit->uri] !== NULL)
                $this->_resources[$visit->uri]->add_visit($visit);
            return;
        }

        switch ($this->_get_type($visit))
        {
            case 'service_page':
                $this->_resources[$visit->uri] = StatResourceService::factory($visit);
                break;
            case 'service_news_page':
                $this->_resources[$visit->uri] = StatResourceNews::factory($visit);
                break;
            case 'service_stock_page':
                $this->_resources[$visit->uri] = StatResourceStock::factory($visit);
                break;
            case 'service_vacancy_page':
                $this->_resources[$visit->uri] = StatResourceVacancy::factory($visit);
                break;
            default:
                $visit->delete();
        }

    }
    /**
     * Получение типа страницы
     * @param $visit
     * @return string
     */
    private function _get_type($visit)
    {
        if ($visit->directory == 'services' AND $visit->controller == 'news' AND $visit->action == 'view')
            return 'service_news_page';

        if ($visit->directory == 'services' AND $visit->controller == 'main' AND $visit->action == 'view')
            return 'service_page';

        if ($visit->directory == 'services' AND $visit->controller == 'stocks' AND $visit->action == 'view')
            return 'service_stock_page';

        if ($visit->directory == 'services' AND $visit->controller == 'vacancies' AND $visit->action == 'view')
            return 'service_vacancy_page';

    }
    public function get_resources()
    {
        return $this->_resources;
    }
}