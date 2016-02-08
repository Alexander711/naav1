<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Cabinet_Notice extends Controller_Cabinet
{
    public function action_index()
    {
        $notices = ORM::factory('notice')->get_notices($this->user);



        $this->view = View::factory('frontend/cabinet/feedback/notice_all')
                          ->set('notice', $notices)
                          ->set('user_id', $this->user->id)
                          ->set('username', $this->user->username);
        $this->template->title = $this->site_name.'Уведомления от администрации';
        $this->template->bc['#'] = 'Уведомления от администрации';
        $this->template->content = $this->view;
    }
    public function after()
    {
        parent::after();
        if ($this->template->notice_count['new'] > 0)
        {
            $services = array();
            foreach ($this->user->services->find_all() as $s)
            {
                $services[] = $s->id;
            }
            if (!empty($services))
            {

                DB::update('notices_services')->set(array('read' => 'y'))->where('service_id', 'in', $services)->execute();
            }

            DB::update('notices_users')->set(array('read' => 'y'))->where('user_id', '=', $this->user->id)->execute();
        }
    }
}