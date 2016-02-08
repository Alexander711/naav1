<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Services_Reviews extends Controller_Frontend
{
    /**
     * Обзор отзывов автосервиса
     * @throws HTTP_Exception_404
     * @return void
     */
    public function action_index()
    {
        $service = ORM::factory('service', $this->request->param('service_id'));
        if (!$service->loaded())
            throw new HTTP_Exception_404('Такая компания не найдена');
        $this->template->title = 'Отзывы к автосервису '.$service->name;
        $this->template->bc['services/'.$service->id] = $service->get_name(2);
        $this->template->bc['#'] = 'Отзывы';

        $this->view = View::factory('frontend/review/all')
                          ->set('review', $service->reviews->get_reviews())
                          ->set('h1_title', $this->template->title);
        $this->template->content = $this->view;
    }
    /**
     * Просмотр отзыва
     * @throws HTTP_Exception_404
     * @return void
     */
    public function action_view()
    {
        $service = ORM::factory('service', $this->request->param('service_id'));
        if (!$service->loaded())
            throw new HTTP_Exception_404('Такая компания не найдена');
        $review = $service->reviews->get_review($this->request->param('review_id'));
        if (!$review->loaded())
            throw new HTTP_Exception_404('Такой отзыв для компании '.$service->name.' не найден');

        $this->template->title = 'Отзыв к автосервису '.$service->name.' от '.MyDate::show($review->date);
        $this->template->bc['services/'.$service->id] = $service->get_name(2);
        $this->template->bc['services/'.$service->id.'/reviews'] = 'Отзывы';
        $this->template->bc['#'] = 'Отзыв от '.MyDate::show($review->date);


        $this->view = View::factory('frontend/review/view')
                          ->set('review', $review);
        $this->template->content = $this->view;
    }
}