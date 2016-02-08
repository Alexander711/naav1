<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * @use ORM
 */
class Controller_Rest_CompanyImage extends Controller_Rest
{
    /**
     * jQuery Iframe Transport Plugin Redirect
     */
    public function action_iframe_transport()
    {
        $this->response->headers('Content-type', 'text/html');
        $this->data = View::factory('system/blueimpuploader_iframe_transport');
    }
    /**
     * Редактирование заголовка и описания изображения
     */
    public function action_edit()
    {
        $user = Auth::instance()->get_user();
        $company_id = $this->request->post('company_id');
        $company_image = ORM::factory('CompanyImage', $this->request->param('id'));
        /**
         * Если имага есть
         * И принадлежит нужной компании
         * И юзер залогинен
         * И юзер админ ИЛИ хозяин компании
         */
        if ($company_image->loaded() AND $company_image->company->id == $company_id AND $user AND ($user->has('roles', 2) OR $company_image->company->user->id == $user->id))
        {
            $company_image->values($_POST, array('title'));
            $company_image->company->date_edited = Date::formatted_time();
            $company_image->company->update();
            $company_image->date_edited = Date::formatted_time();
            $company_image->update();
            $this->data = array(
                'image_id' => $company_image->id,
                'title' => (trim($company_image->title)) ? HTML::anchor($company_image->img_path, $company_image->title) : HTML::anchor($company_image->img_path, 'не указано'),
            );
        }
    }
    /**
     * Контроллер для загрзуки и удаления имаг
     */
    public function action_index()
    {
        $user = Auth::instance()->get_user();
        if (Request::current()->method() == Request::POST)
        {
            $this->_upload($this->request->post('company_id'), $user);
        }
        else if (Request::current()->method() == Request::DELETE)
        {
            $this->_delete($this->request->param('id'), $this->request->query('company_id'), $user);
        }
    }
    /**
     * Загрузка имаги
     * @param $company_id
     * @param $user_id
     */
    private function _upload($company_id, $user)
    {
        $validation = Validation::factory($_FILES)
                                ->rule('files', 'not_empty');
        if ($validation->check())
        {
            $file = (object) array(
                'type' => $validation['files']['type'][0],
                'tmp_name' => $validation['files']['tmp_name'][0],
                'size' => $validation['files']['size'][0],
                'name' => $validation['files']['name'][0],
                'error' => $validation['files']['error']
            );

            $company = ORM::factory('service', $company_id);
            $settings = (object) Kohana::$config->load('gallery');

            /**
             * Если файл есть
             * И папка доступна для записи
             * И такая компания есть
             * И ты админ или хозяин компании
             */
            if ($file->size > 0 AND is_writable($settings->img_path) AND $company->loaded() AND ($user->has('roles', 2) OR $company->user_id == $user->id))
            {
                // Манипуляция с временным изображением
                $image = Image::factory($file->tmp_name);

                // Название изображения
                $file_name = $company->id.'_'.md5(Date::formatted_time().$file->name).'.'.File::ext_by_mime($file->type);

                if ($image->width > $settings['img_max_width'])
                    $image->resize($settings['img_max_width'], NULL);

                $image->save($settings['img_path'].$file_name);


                if ($image->height > $settings['thumb_img_max_width'])
                    $image->resize(NULL, $settings['thumb_img_max_width']);

                $image->save($settings['img_path'].$settings['thumb_file_name_prefix'].$file_name);
                unlink($file->tmp_name);
                $company_image = ORM::factory('CompanyImage');
                $company_image->name = $file->name;
                $company_image->date_created = Date::formatted_time();
                $company_image->img_path = $settings['img_path'].$file_name;
                $company_image->thumb_img_path = $settings['img_path'].$settings['thumb_file_name_prefix'].$file_name;
                $company_image->company_id = $company->id;
                $company_image->title = (trim(Arr::path($_POST, 'title'))) ? Arr::path($_POST, 'title') : $company_image->name;
                $company_image->save();
                $company->date_edited = Date::formatted_time();
                $company->update();

                $this->data[] = (object) array(
                    'title' => $company_image->title,
                    'url' => '/'.$company_image->img_path,
                    'thumbnail_url' => '/'.$company_image->thumb_img_path,
                    'delete_url' => '/rest/companyimage/index/'.$company_image->id.'?company_id='.$company->id,
                    'delete_type' => 'DELETE',
                    'image_id' => $company_image->id,
                    'company_id' => $company_image->company->id
                );

                $redirect = ($this->request->post('redirect'))
                          ? stripslashes($this->request->post('redirect'))
                          : null;
                // Редирект для IE
                if ($redirect)
                    $this->request->redirect(sprintf($redirect, rawurlencode(json_encode($this->data))));


            }
        }
    }
    /**
     * Удаление имаги
     * @param $image_id
     * @param $company_id
     * @param $user_id
     */
    private function _delete($image_id, $company_id, $user)
    {

        $company_image = ORM::factory('CompanyImage', $image_id);
        if ($company_image->loaded() AND $company_image->company->id == $company_id AND ($user->has('roles', 2) OR $company_image->company->user->id == $user->id))
        {
            if (is_writable($company_image->img_path))
                unlink($company_image->img_path);
            if (is_writable($company_image->thumb_img_path))
                unlink($company_image->thumb_img_path);

            $company_image->company->date_edited = Date::formatted_time();
            $company_image->company->update();
            $company_image->delete();
        }
    }
}