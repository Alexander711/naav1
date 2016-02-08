<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<?php
foreach ($services as $service_id => $service_name)
{
    echo '<option value="'.$service_id.'">'.$service_name.'</option>';
}
?>