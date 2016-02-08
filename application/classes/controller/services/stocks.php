<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Services_Stocks extends Controller_Frontend
{
    public function after()
    {
        parent::after();
        if ($this->request->action() == 'view')
            ORM::factory('visit')->save_visit();
    }
    /**
     * Обзор акций компании
     * @throws HTTP_Exception_404
     * @return void
     */
    public function action_index()
    {
        $service = ORM::factory('service', $this->request->param('service_id'));
        if (!$service->loaded())
            throw new HTTP_Exception_404('Такая компания не найдена');

        $this->template->title = 'Акции '.__('company_type_'.$service->type.'_genitive').' '.$service->name;
        $this->template->bc['services/'.$service->id] = $service->get_name(2);
        $this->template->bc['#'] = 'Акции';


        $this->view = View::factory('frontend/stock/all')
                          ->set('company_page', TRUE)
                          ->set('stock', $service->stocks->get_stocks())
                          ->set('h1_title', $this->template->title);
        $this->template->content = $this->view;
    }
    /**
     * Просмотр акции автосервиса
     * @throws HTTP_Exception_404
     * @return void
     */
    public function action_view()
    {
        $service = ORM::factory('service', $this->request->param('service_id'));
        if (!$service->loaded())
            throw new HTTP_Exception_404('Такая компания не найдена');

        $stock = $service->stocks->get_stock($this->request->param('stock_id'));
        if (!$stock->loaded())
            throw new HTTP_Exception_404('Такая акция от компании '.$service->name.' не найдена');

        $this->template->title = 'Акция автосервиса '.$service->name;
        if (trim($stock->title))
            $this->template->title .= ' '.$stock->title;
        $this->template->title .= ' от '.MyDate::show($stock->date);


        $this->template->bc['services/'.$service->id] = $service->get_name(2);
        $this->template->bc['services/'.$service->id.'/stocks'] = 'Акции';
        $this->template->bc['#'] = 'Акция '.$stock->title.' от '.MyDate::show($stock->date);

        $this->view = View::factory('frontend/stock/view')
                          ->set('stock', $stock);
        $this->template->content = $this->view;
    }
}