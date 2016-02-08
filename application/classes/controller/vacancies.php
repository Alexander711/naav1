<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Vacancies extends Controller_Frontend
{
    function before()
    {
        parent::before();
        $this->template->bc['vacancies'] = 'Вакансии автосервисов';
    }
    /**
     * Просмотр всех вакансий
     * @return void
     */
    function action_index()
    {
        $service = ORM::factory('service', Arr::get($_GET, 'service', NULL));
        if ($service->loaded())
            $this->request->redirect('services/'.$service->id.'/vacancies', 301);

        $this->template->title = 'Вакансии автосервисов';

        $this->view = View::factory('frontend/vacancy/all')
                          ->set('vacancy', ORM::factory('vacancy')->get_vacancies())
                          ->set('show_company_name', TRUE)
                          ->set('h1_title', $this->template->title);
        $this->template->content = $this->view;
    }
    /**
     * Обработчик для старых url вакансий вида vacancies/[id вакансии]
     * @throws HTTP_Exception_404
     * @return void
     */
    function action_view()
    {
        $vacancy = ORM::factory('vacancy', $this->request->param('id', NULL));
        if (!$vacancy->loaded() OR $vacancy->active < 1)
            throw new HTTP_Exception_404('Вакансия не найдена');
        else
            $this->request->redirect('services/'.$vacancy->service->id.'/vacancies/'.$vacancy->id, 301);
    }
}