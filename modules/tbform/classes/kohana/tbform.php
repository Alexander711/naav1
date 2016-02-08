<?php
defined('SYSPATH') or die('No direct script access.');
class Kohana_TbForm
{
    // Для автозаполнения при неправильном вводе
    private static $_values = array();
    // Ошибки ввода
    private static $_errors = array();

    private static $_form_id = 0;

    private static function get_errors($key)
    {
        $errors = array();
        if (isset(self::$_errors['_external']) AND Arr::get(self::$_errors['_external'], $key))
            $errors[] = self::$_errors['_external'][$key];

        if (Arr::get(self::$_errors, $key))
            $errors[] = self::$_errors[$key];

        return $errors;
    }
    
    public static function open($action = NULL, Array $attributes = NULL, Array $values = array(), Array $errors = array())
    {
        self::$_values = $values;
        self::$_errors = $errors;
        return FORM::open($action, $attributes);
    }
    public static function close()
    {
        self::$_errors = array();
        self::$_values = array();
        return FORM::close();
    }

    public static function input($name,  Array $options = array())
    {
        self::$_form_id++ ;

        $config = Kohana::$config->load('tbform');

        $attributes['id'] = $config['form_id_prefix'].self::$_form_id;
        $attributes['class'] = (isset($options['class']))
                             ? $options['class'].' span'.Arr::get($options, 'size', $config['input']['size'])
                             : 'span'.Arr::get($options, 'size', $config['input']['size']);

        $form_element = FORM::input($name, Arr::get(self::$_values, $name), $attributes);

        $label = array(
            'text' => Arr::get($options, 'label', $name),
            'for'  => $config['form_id_prefix'].self::$_form_id
        );

        $errors = self::get_errors($name);

        return View::factory('standart_input')
                   ->set('errors', $errors)
                   ->set('form_element', $form_element)
                   ->set('options', $options)
                   ->set('label', $label)
                   ->set('help', Arr::get($options, 'help'))
                   ->set('horizontal', Arr::get($options, 'horizontal'))
                   ->render();
    }
    /**
     * Generate Twitter Bootstrap textarea
     * @static
     * @param $name
     * @param null $label_text - label text
     * @param array $options form option - rows, cols, help
     * @return string
     */
    public static function textarea($name, $options = array())
    {
        self::$_form_id++ ;

        $config = Kohana::$config->load('tbform');

        $attributes = array(
            'rows' => Arr::get($options, 'cols', $config['textarea']['rows']),
            'id'   => $config['form_id_prefix'].self::$_form_id
        );

        $attributes['class'] = (isset($options['class']))
                             ? $attributes['class'] = $options['class'].' span'.Arr::get($options, 'size', $config['textarea']['size'])
                             : $attributes['class'] = 'span'.Arr::get($options, 'size', $config['textarea']['size']);

        $form_element = FORM::textarea($name, Arr::get(self::$_values, $name), $attributes);

        $label = array(
            'text' => Arr::get($options, 'label', $name),
            'for'  => $config['form_id_prefix'].self::$_form_id
        );

        $errors = self::get_errors($name);

        return View::factory('standart_input')
                   ->set('errors', $errors)
                   ->set('form_element', $form_element)
                   ->set('label', $label)
                   ->set('help', Arr::get($options, 'help', NULL))
                   ->set('horizontal', Arr::get($options, 'horizontal', $config['textarea']['horizontal']))
                   ->render();
    }
    public static function select($name, $select_options, $options)
    {

    }
    public static function ckeditor($name, Array $options = array())
    {
        self::$_form_id++ ;
        $errors = self::get_errors($name);
        $form_element = FORM::textarea($name, Arr::get(self::$_values, $name), array('id' => 'text'));
        return View::factory('ckeditor')
                   ->set('name', $name)
                   ->set('values', self::$_values)
                   ->set('errors', $errors)
                   ->set('form_element', $form_element)
                   ->set('options', $options)
                   ->set('form_id', self::$_form_id)
                   ->render();
    }
    public static function checkboxes(Array $check_boxes, $label, Array $options = array())
    {
        $config = Kohana::$config->load('tbform.checkboxes');
        return View::factory('checkboxes')
                   ->set('errors', self::$_errors)
                   ->set('values', self::$_values)
                   ->set('check_boxes', $check_boxes)
                   ->set('config', $config)
                   ->set('label', $label)
                   ->set('horizontal', Arr::get($options, 'horizontal', $config['horizontal']))
                   ->set('inline', Arr::get($options, 'inline', FALSE))
                   ->render();
    }
    public static function actions($actions = array(), $options = array())
    {
        $config = Kohana::$config->load('tbform.actions.presets');
        $view = View::factory('actions');
        if (!is_array($actions) AND array_key_exists($actions, $config))
            $view->set('actions', $config[$actions]);
        else
            $view->set('actions', $actions);

        return $view->set('options', $options)
                    ->render();
    }
    public static function radiobuttons($name, Array $buttons, Array $options = array(), $label_text = NULL, $help_text = NULL)
    {
        $config = Kohana::$config->load('tbform.radiobuttons');
        $errors = self::get_errors($name);
        return View::factory('radiobuttons')
                   ->set('name', $name)
                   ->set('buttons', $buttons)
                   ->set('errors', $errors)
                   ->set('values', self::$_values)
                   ->set('options', $options)
                   ->set('config', $config)
                   ->render();
    }

    
}