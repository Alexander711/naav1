<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Reviews extends Controller_Frontend {

	public $template = 'templates/frontend_news';
	
    public function before()
    {
        parent::before();

        $this->template->bc['reviews'] = 'Отзывы об автосервисах Москвы и России';
		
		// add class for news pages
        $this->template->css_class = 'wrapper-news';
    }
	
    public function action_index()
    {
        $service = ORM::factory('service', Arr::get($_GET, 'service'));
        if ($service->loaded())
            $this->request->redirect('services/'.$service->id.'/reviews', 301);

        $this->template->title = $this->template->bc['reviews'];
        $view = View::factory('frontend/review/all')
                    ->set('review', ORM::factory('review')->get_reviews())
                    ->set('h1_title', $this->template->title);
        $this->template->content = $view;
    }
	
    public function action_view()
    {
        $review = ORM::factory('review', $this->request->param('id', NULL));
        if (!$review->loaded())
            throw new HTTP_Exception_404('Такой отзыв не найден');
        else
            $this->request->redirect('services/'.$review->service->id.'/reviews/'.$review->id, 301);
    }
	
    public function action_add()
    {
        $cities = ORM::factory('city')->get_cities();
        $services = ORM::factory('service')->get_services_as_array();

        if ($_POST)
        {
            if (isset($_POST['city_id']) AND $_POST['city_id'] != 0)
                $services = ORM::factory('service')->get_services_as_array(array('city_id' => $_POST['city_id']));



            $review = ORM::factory('review');
            try
            {
                $review->values($_POST, array('name', 'email', 'text', 'service_id'));
                if ($this->user)
                    $review->user_id = $this->user->id;

                $review->active = 0;
                $review->date = Date::formatted_time();
                $review->save();
                Message::set(Message::SUCCESS, __('review_adding_complete'));
                $this->request->redirect('reviews');
            }
            catch (ORM_Validation_Exception $e)
            {
                $this->errors = $e->errors('models');
                $this->values = $_POST;
            }

        }
        $this->view = View::factory('frontend/review/add')
                          ->set('values', $this->values)
                          ->set('errors', $this->errors)
                          ->set('cities', $cities)
                          ->set('services', $services);
        $this->template->title= 'Написать отзыв';
;
        $this->template->bc['#'] = $this->template->title;
        $this->template->content = $this->view;
    }
	
    public function check_service($value)
    {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))->from('services')->where('id', '=', $value)->execute()->get('total_count');
    }
}