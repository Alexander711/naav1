<?php
/**
 * Created by yaap
 * Date: 15.03.13
 * Time: 1:10 
 */

defined('SYSPATH') or die('No direct script access.');

?>


<h1>Платеж успешно проведен</h1>
<div style="color:green">Ваш платеж <strong># UPI - <?=$values['LMI_PAYMENT_NO']?></strong>  на сумму <strong><?=$values['LMI_PAYMENT_AMOUNT']?> <?=$values['LMI_CURRENCY']?></strong>  успешно проведен.</div>