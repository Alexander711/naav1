<?php

defined('SYSPATH') or die('No direct script access.');

class Model_Group extends ORM {

    protected $_reload_on_wakeup = FALSE;
    protected $_table_name = 'groups';
    protected $_table_columns = array(
        'id' => NULL,
        'name' => NULL,
        'parrent_id' => NULL
    );
    protected $_has_many = array(
        'service' => array(
            'model' => 'service',
            'through' => 'services_groups',
            'foreign_key' => 'group_id',
            'far_key' => 'service_id',
        )
    );
    public $disable_validation = FALSE;

    /**
     * Убираем тире, скобки и пробелы для полей базы
     * @param $field
     * @return Database_Expression
     */
    private function _db_replace($field) {
        return DB::expr("REPLACE(REPLACE(REPLACE(REPLACE(" . (string) $field . ", '-', ''), ' ', ''), ')', ''), '(', '')");
    }

    /**
     * Получаем все группы
     * @return void
     */
    public function get_groups_as_array() {
        $goups = array();
        foreach ($this->where('parrent_id', '=', '0')->find_all() as $group) {
            $goups[$group->id] = $group->name;
        }

        return $goups;
    }

    public function get_sub_groups_for_group($group_id) {
        $sub_groups = array();

        foreach ($this->where('parrent_id', '=', $group_id)->find_all() as $data) {
            $sub_groups[$data->id] = $data->name;
        }

        return $sub_groups;
    }

    public function get_type_group($group_id) {
        $group = $this->where('id', '=', $group_id)->find();

        return $group->type;
    }

    public function get_groups_this_type($type) {
        $groups = array();

        foreach ($this->where('type', '=', $type)->find_all() as $data) {
            $groups[$data->id] = $data->name;
        }

        return $groups;
    }

    /**
     * Проверка организации
     * @static
     * @param $group_id
     * @return bool
     */
    public static function available($group_id) {
        return (bool) DB::select(array('COUNT("*")', 'total_count'))
                        ->from('groups')
                        ->where('id', '=', $group_id)
                        ->execute()
                        ->get('total_count');
    }

    static function check_valid_group($group_id) {
        $group_id = DB::select(array('id', 'group_id'))->from('groups')->where('id', '=', $group_id)->execute()->get('group_id');

        return !empty($group_id);
    }

    /**
     * Правила валидации
     * @return array
     */
    public function rules() {
        if ($this->disable_validation)
            return array();

        return array(
            'name' => array(array('not_empty')),
            'group_id' => array(
                array('not_empty')
            )
        );
    }

}
