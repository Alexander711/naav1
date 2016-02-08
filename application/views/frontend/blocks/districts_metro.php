<?php
defined('SYSPATH') or die('No direct script access.');
if (!empty($districts))
{
    $districts_url_pies = $url_pies;
    if (isset($districts_url_pies['district']))
        unset($districts_url_pies['district']);
    if (isset($districts_url_pies['metro']))
        unset($districts_url_pies['metro']);
    $districts_options[0] = array('text' => 'Округ', 'url' => URL::base().$url.'/'.implode('/', $districts_url_pies));
    foreach ($districts as $id => $name)
    {
        $districts_url_pies['district'] = 'district_'.$id;
        $districts_options[$id] = array(
            'text' => $name,
            'url' => URL::base().$url.'/'.implode('/', $districts_url_pies)
        );
    }
    unset($id);
    unset($name);
}

if (!empty($metro))
{
    $metro_url_pies = $url_pies;
    if (isset($metro_url_pies['metro']))
        unset($metro_url_pies['metro']);
    $metro_options[URL::base().$url.'/'.implode('/', $metro_url_pies)] = 'Станция метро';
}
foreach ($metro as $id => $name)
{
    $metro_url_pies['metro'] = 'metro_'.$id;
    $metro_options[URL::base().$url.'/'.implode('/', $metro_url_pies)] = $name;
}

?>
<div class="m_d">
    <?php if (!empty($districts)): ?>
        Округ:
        <select onchange="if(this.options[this.selectedIndex].title!=''){window.location=this.options[this.selectedIndex].title}else{this.options[selectedIndex=0];}">
        <?php foreach ($districts_options as $id => $value): ?>
            <?php
            $attr = array(
                'value' => $id,
                'title' => $value['url']
            );
            if ($district_id == $id)
                $attr['selected'] = 'selected';
            ?>
            <option <?= HTML::attributes($attr); ?>><?= $value['text']; ?></option>
        <?php endforeach; ?>
        </select>

    <?php endif; ?>

    <?php if (!empty($metro)): ?>
        Метро: <?= FORM::select(NULL, $metro_options, URL::base().Request::current()->uri(), array('onChange' => "if(this.options[this.selectedIndex].value!=''){window.location=this.options[this.selectedIndex].value}else{this.options[selectedIndex=0];}")) ?>
    <?php endif; ?>
</div>