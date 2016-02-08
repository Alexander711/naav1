<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?php
foreach ($models as $model_id => $model_name)
{
    echo '<option value="'.$model_id.'">'.$model_name.'</option>';
}
?>