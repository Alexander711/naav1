<?php
defined('SYSPATH') or die('No direct script access.');
/**
 * Модель хранящая очередь на отправку уведомлений пользователям на Email
 */
class Model_Subscribe_Notice extends ORM
{
    protected $_table_name = 'notice_subscribe';
    protected $_table_columns = array(
        'id'          => NULL,
        'notice_id'   => NULL,
        'user_id'     => NULL,
        'email_id'    => NULL
    );
    protected $_belongs_to = array(
        'user' => array(
            'model' => 'user',
            'foreign_key' => 'user_id'
        ),
        'notice' => array(
            'model' => 'notice',
            'foreign_key' => 'notice_id'
        ),
        'email' => array(
            'model' => 'emailsender',
            'foreign_key' => 'email_id'
        )
    );
    public function get_status($notice_id, $user_id)
    {
        return DB::select('status')
                 ->from('notice_subscribe')
                 ->where('notice_id', '=', $notice_id)
                 ->and_where('user_id', '=', $user_id)
                 ->execute()
                 ->get('status');
    }

    public function update_status_to_queue($notice_id ,$user_id = NULL)
    {
        $query = DB::update('notice_subscribe')
                    ->set(array('status' => 'queue'))
                    ->where('notice_id', '=', $notice_id)
                    ->and_where('status', '=', 'send');
        if ($user_id)
            $query->and_where('user_id', '=', $user_id);

        return $query->execute();
    }
    public function new_subscribe($notice_id, $user_id)
    {
        return DB::insert('notice_subscribe', array('notice_id', 'user_id'))
                 ->values(array($notice_id, $user_id))
                 ->execute();
    }
    public function remove_queue($notice_id, Array $user_params = array())
    {
        $query = DB::delete('notice_subscribe')
                   ->where('notice_id', '=', $notice_id)
                   ->and_where('status', '=', 'queue')
                   ->and_where($user_params[0], $user_params[1], $user_params[2])
                   ->execute();
        return $query;
    }
}