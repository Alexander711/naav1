<?php defined('SYSPATH') or die('No direct script access.');
class Exceptionhandler
{
    public static function handle(Exception $e)
    {
        switch (get_class($e))
        {
            case 'HTTP_Exception_404':
                $response = new Response;
                $response->status(404);
                $request = Request::factory('404error')->method(Request::POST)->post(array('message' => $e->getMessage()))->execute();
                echo $response->body($request)->send_headers()->body();
                return TRUE;
                break;
            default:
                return Kohana_Exception::handler($e);
                break;
        }
    }
}