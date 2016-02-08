<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Services_Vacancies extends Controller_Frontend
{
    public function after()
    {
        parent::after();
        if ($this->request->action() == 'view')
            ORM::factory('visit')->save_visit();
    }
    /**
     * Просмотр вакансий автосервиса
     * @throws HTTP_Exception_404
     * @return void
     */
    public function action_index()
    {
        $service = ORM::factory('service', $this->request->param('service_id'));
        if (!$service->loaded())
            throw new HTTP_Exception_404('Такая компания не найдена');

        $this->template->bc['services/'.$service->id] = __('company_type_'.$service->type).' '.$service->name;
        $this->template->bc['#'] = 'Вакансии';

        $this->template->title = 'Вакансии '.__('company_type_'.$service->type.'_genitive').' '.$service->name;
        $this->view = View::factory('frontend/vacancy/all')
                          ->set('vacancy', $service->vacancies->get_vacancies())
                          ->set('show_company_name', FALSE)
                          ->set('h1_title', $this->template->title);
        $this->template->content = $this->view;
    }
    /**
     * Просмотр вакансии
     * @throws HTTP_Exception_404
     * @return void
     */
    public function action_view()
    {
        $service = ORM::factory('service', $this->request->param('service_id'));
        if (!$service->loaded())
            throw new HTTP_Exception_404('Такая компания не найдена');
        $vacancy = $service->vacancies->where('id', '=', $this->request->param('vacancy_id'))->find();
        if (!$vacancy->loaded())
            throw new HTTP_Exception_404('Такая вакансия от компании '.$service->name.' не найдена');
        $this->template->title = 'Вакансия '.$vacancy->title.' &mdash; '.$service->name;
        $this->template->bc = array_merge($this->template->bc,
            array(
                'services/'.$service->id              => $service->get_name(2),
                'services/'.$service->id.'/vacancies' => 'Вакансии',
                '#'                                   => Text::limit_chars($vacancy->title, 80)
            ));
        $this->view = View::factory('frontend/vacancy/view')
                          ->set('vacancy', $vacancy);
        $this->template->content = $this->view;
    }
}