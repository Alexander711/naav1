<?php
defined('SYSPATH') or die('No direct script access.');
class Controller_Rest extends Controller
{
    protected $success = TRUE;
    protected $message;
    protected $data;
    public function before()
    {
        parent::before();
        $this->response->headers('Content-type', 'application/json');
        $this->response->headers('Pragma', 'no-cache');
        $this->response->headers('Cache-Control', 'no-store, no-cache, must-revalidate');
        $this->response->headers('Content-Disposition', 'inline; filename="files.json"');
        $this->response->headers('X-Content-Type-Options', 'nosniff');
        $this->response->headers('Access-Control-Allow-Origin', '*');
        $this->response->headers('Access-Control-Allow-Methods', 'OPTIONS, HEAD, GET, POST, PUT, DELETE');
        $this->response->headers('Access-Control-Allow-Headers', 'X-File-Name, X-File-Type, X-File-Size');

    }
    public function after()
    {
        parent::after();
        switch ($this->response->headers('Content-type'))
        {
            case 'application/json':

                $this->response->body(json_encode(
                    array(
                        'success' => $this->success,
                        'message' => $this->message,
                        'data'    => $this->data
                    )
                ));
                break;
            default:
                $this->response->body($this->data);
        }
    }
    public function action_index(){}
}