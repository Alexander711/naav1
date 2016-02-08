<?php
if (isset($_POST['order_type']) AND $_POST['order_type'] == 2)
{
    $mobile_phone = (isset($_POST['mobile_phone'])) ? $_POST['mobile_phone'] : NULL;
    $discount = (isset($_POST['sell_type'])) ? $_POST['sell_type'] : NULL;
    $course_name = (isset($_POST['docId'])) ? $_POST['docId'] : NULL;
    $modx->documentObject['cost'] = 4800;
    $total_cost = round($modx->documentObject['cost'] - ($modx->documentObject['cost'] * $discount / 100));
    if (isset($_POST['mobile_phone']) AND strlen($_POST['mobile_phone']) > 0)
    {
        if( $curl = curl_init() )
        {
          curl_setopt($curl, CURLOPT_URL, 'http://w.qiwi.ru/setInetBill.do?from=9492&to='.$mobile_phone.'&summ='.$total_cost.'&com='.$course_name.'&lifetime=96');
          curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
          $out = curl_exec($curl);
            //echo $out;
            echo $total_cost;
          curl_close($curl);
        }
    }
}
if (isset($fields['mobile_phone']))
{
    echo $fields['mobile_phone'];
}

defined('SYSPATH') or die('No direct script access.');
echo FORM::open();
function get_message_to_alert()
{
    $result['str'] = 'Привет Мир!';
    echo json_encode($result);
    return true;
}
?>

<?php
if (isset($_POST['mobile_phone']))
{
    $mobile_phone = $_POST['mobile_phone'];
    $total_cost = 1000;
    echo 'Отправка тестового счета на номер: '.$mobile_phone;
    if( $curl = curl_init() )
    {
        curl_setopt($curl, CURLOPT_URL, 'http://w.qiwi.ru/setInetBill.do?from=9492&to='.$mobile_phone.'&summ='.$total_cost.'&com=тестовый счет&lifetime=96');
        curl_setopt($curl, CURLOPT_RETURNTRANSFER,true);
        $out = curl_exec($curl);
        curl_close($curl);
    }
}
    $cost =  (isset($_POST['cost'])) ? $_POST['cost'] : NULL;
    $total_cost = round($cost - ($cost * ($discount / 100)), -2);
?>
<form action="form.php" method="post">
    <input type="text" name="mobile_phone" placeholder="QIWI кошелек" size="40"/>
    <input type="submit"/>
</form>
<p>Тип оплаты*</p>
<p>
    <label><input type="radio" value="1" name="order_type" checked="checked"> Наличными в офис</label>
    <label><input type="radio" value="2" name="order_type"> qiwi</label>
</p>
<div class="qiwi_order" style="display: none;">
    <p>Сотовый телефон*</p>
    <p><input type="text" name="mobile_phone" size="40" maxlength="60"/></p>
    <p>Форма обучения*</p>
    <p>
        <select name="sell_type">
            <option value="sell_m">Утрений</option>
            <option value="sell_d">Дневной</option>
            <option value="sell_e">Вечений</option>
            <option value="sell_w">Выходной</option>
            <option value="webinar">Вебинар</option>
        </select>
    </p>
</div>
<?php

//$cost =  $modx->documentObject['cost'];
//$sell_m =  $modx->documentObject['sell_m'];
//echo round($cost[1] - ($cost[1]) * ($sell_m[1] / 100), -2);
?>

<div id="order_complete_text" style="display: none;">Вам выставлен счет в QIWI кошельке на сумма <strong id="total_price"></strong> руб. Счет действителен в течение 3 дней. Оплатите созданный счет на оплату: на сайте QIWI Кошелька, терминале QIWI, с помощью приложения для социальных сетей или мобильного телефона.</div>

<h3>Заявка на обучение</h3>
<strong>[*pagetitle*]</strong>
<p>[+validationmessage+]</p>
<form id="order-form-course" method="post" action="/[~[*id*]~]/">
<p>Тип оплаты*</p>
<p>
    <label><input type="radio" value="1" name="order_type" checked="checked"> Наличными в офис</label>
    <label><input type="radio" value="2" name="order_type"> qiwi</label>
</p>
<div class="qiwi_order" style="display: none;">
    <p>Сотовый телефон*</p>
    <p><input type="text" name="mobile_phone" size="40" maxlength="60"/></p>
    <p>Форма обучения*</p>
    <p>
        <select name="sell_type">
            <option value="sell_m">Утрений</option>
            <option value="sell_d">Дневной</option>
            <option value="sell_e">Вечений</option>
            <option value="sell_w">Выходной</option>
            <option value="webinar">Вебинар</option>
        </select>
    </p>
</div>
<input type="hidden" name="formid" value="order-form-course" />
<p><label accesskey="n" for="name">Ваше имя *</label><br/>
<input type="text" id="inp_name" name="name" size="40" maxlength="60" eform="Имя:1:#REGEX /^[а-я]+ [а-я0-9_]+/i"   class="validate" rel="name" /></p><br/>
<p><label accesskey="t" for="tel">Ваш телефон * <span>(без пробелов)</span></label><br/>
<input type="text" id="inp_telefon" name="tel" size="40" maxlength="60" eform="Ваш телефон:string:1:err:#EVAL return (($value)!=='123456')?true:false;"  class="validate" rel="telefon" />
</p><br/>
<p><label accesskey="e" for="email">Электронный ящик *</label><br/>
<input type="text" id="inp_email" name="email" size="40" maxlength="40" eform="Адрес электронной почты:email:1" class="validate" rel="email" /></p><br/>
<input type="hidden" name="docId" value="[*pagetitle*]" eform="Название курса::0::#EVAL return true;" />
<p><label accesskey="c" for="comments">Текст сообщения</label><br/>
<textarea cols="40" rows="10" name="comments" eform="Текст сообщения:html:0"></textarea></p>
<input type="hidden" name="valid" value="444" eform="Valid:float:1:Valid#RANGE 444" />
<p><input type="image" src="/assets/images/submit.png" name="submit" id="submit"  value="Записаться на курс"></p>
</form>
</div>


<!--noindex-->
<div style="display:none;">
<div id="course-form">
    <div id="order_complete_text" style="display: none;">Вам выставлен счет в QIWI кошельке на сумма <strong id="total_price"></strong> руб. Счет действителен в течение 3 дней. Оплатите созданный счет на оплату: на сайте QIWI Кошелька, терминале QIWI, с помощью приложения для социальных сетей или мобильного телефона.</div>
    <script type='text/javascript' src='/assets/js/order-form.js'></script>
    <div class="form-order">
        <h3>Заявка на обучение</h3>
        <strong>[*pagetitle*]</strong>
        <p>[+validationmessage+]</p>
        <form id="order-form-course" method="post" action="/[~[*id*]~]/">
        <p>Тип оплаты*</p>
        <p>
            <label><input type="radio" value="1" name="order_type" checked="checked"> Наличными в офис</label>

            <label><input type="radio" value="2" name="order_type">Qiwi кошелек</label>
        </p>
        <div class="qiwi_order" style="display: none;">
            <p>Номер QIWI кошелька* <br/> (Номер телефона следует вводить без пробелов и без кода страны. Пример 9031234567)</p>
            <p><input type="text" name="mobile_phone" rel="mobile_phone" class="validate" size="40" maxlength="60"/></p>
            <p>Форма обучения*</p>
            <p>
                 <select name="sell_type">
                    <option value="[*sell_m*]">Утрений</option>
                    <option value="[*sell_d*]">Дневной</option>
                    <option value="[*sell_e*]">Вечений</option>
                    <option value="[*sell_w*]">Выходной</option>
                    <option value="30">Вебинар</option>
                </select>
            </p>
        </div>
        <input type="hidden" name="formid" value="order-form-course" />
        <p><label accesskey="n" for="name">Ваше имя *</label><br/>
        <input type="text" id="inp_name" name="name" size="40" maxlength="60" eform="Имя:1:#REGEX /^[а-я]+ [а-я0-9_]+/i"   class="validate" rel="name" /></p><br/>
        <p><label accesskey="t" for="tel">Ваш телефон * <span>(без пробелов)</span></label><br/>
        <input type="text" id="inp_telefon" name="tel" size="40" maxlength="60" eform="Ваш телефон:string:1:err:#EVAL return (($value)!=='123456')?true:false;"  class="validate" rel="telefon" />
        </p><br/>
        <p><label accesskey="e" for="email">Электронный ящик *</label><br/>
        <input type="text" id="inp_email" name="email" size="40" maxlength="40" eform="Адрес электронной почты:email:1" class="validate" rel="email" /></p><br/>
        <input type="hidden" name="docId" value="[*pagetitle*]" eform="Название курса::0::#EVAL return true;" />
        <input type="hidden" name="cost" value="[*cost*]" eform="Стоимость::0::#EVAL return true;" />
        <p><label accesskey="c" for="comments">Текст сообщения</label><br/>
        <textarea cols="40" rows="5" name="comments" eform="Текст сообщения:html:0"></textarea></p>
        <input type="hidden" name="valid" value="444" eform="Valid:float:1:Valid#RANGE 444" />
        <p><input type="image" src="/assets/images/submit.png" name="submit" id="submit"  value="Записаться на курс"></p>
        </form>
    </div>
</div>
</div>
<!--/noindex-->