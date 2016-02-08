<?php
defined('SYSPATH') or die('No direct script access.'); ?>
<!DOCTYPE html>
<html>
<head>
    <meta http-equiv="Content-type" content="text/html; charset=UTF-8"/>
    <meta name="SKYPE_TOOLBAR" content="SKYPE_TOOLBAR_PARSER_COMPATIBLE" />
    <link href="<?= URL::base(); ?>assets/img/favicon.ico" rel="shortcut icon" type="image/x-icon" />
    <?php if (trim($meta_description)): ?>
        <meta name="description" content="<?= $meta_description; ?>">
    <?php endif; ?>
    <?php if (trim($meta_keywords)): ?>
        <meta name="Keywords" content="<?= $meta_keywords; ?>">
    <?php endif; ?>
    <?php foreach ($styles as $style): ?>
        <?= HTML::style($style['file'], array('type' => $style['type'], 'media' => $style['media'])); ?>
    <?php endforeach; ?>
    <?php foreach ($scripts['before'] as $script): ?>
   		<?= HTML::script($script['file'], array('type' => $script['type'])); ?>
   	<?php endforeach; ?>
    <!--[if IE 6]>
    <script type="text/javascript">
        DD_belatedPNG.fix('.logo_img, .menu_r_corner_img, .menu_l_corner_img');
    </script>
    <![endif]-->
    <!--[if lt IE 9]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
    <![endif]-->
    <title><?= $title; ?></title>
</head>
<!--noindex-->

<!--[if lt IE 7]>  <div style='border: 1px solid #F7941D; background: #FEEFDA; text-align: center; clear: both; height: 75px; position: relative;'>    <div style='position: absolute; right: 3px; top: 3px; font-family: courier new; font-weight: bold;'><a href='#' onclick='javascript:this.parentNode.parentNode.style.display="none"; return false;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-cornerx.jpg' style='border: none;' alt='Close this notice'/></a></div>    <div style='width: 640px; margin: 0 auto; text-align: left; padding: 0; overflow: hidden; color: black;'>      <div style='width: 75px; float: left;'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-warning.jpg' alt='Warning!'/></div>      <div style='width: 275px; float: left; font-family: Arial, sans-serif;'>        <div style='font-size: 14px; font-weight: bold; margin-top: 12px;'>Ваш браузер устарел</div>        <div style='font-size: 12px; margin-top: 6px; line-height: 12px;'>Чтобы сайт отображался корректно, пожалуйста установите современный браузер.</div>      </div>      <div style='width: 75px; float: left;'><a href='http://www.firefox.com' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-firefox.jpg' style='border: none;' alt='Get Firefox 3.5'/></a></div> <div style='float: left;'><a href='http://www.google.com/chrome' target='_blank'><img src='http://www.ie6nomore.com/files/theme/ie6nomore-chrome.jpg' style='border: none;' alt='Get Google Chrome'/></a></div>    </div>  </div>  <![endif]-->

<!--/noindex-->
<body>


<div id="main_container">



    <div id="header">
        <div id="top_gray">
            <div class="menu">
                <ul>
                    <li><?= HTML::anchor('/', '<img src="/assets/img/homeLogo.png">'); ?></li>
                    <li><?= HTML::anchor('about', 'Об ассоциации') ?></li>
                    <li><?= HTML::anchor('requirements', 'Вступление'); ?></li>
                    <li><?= HTML::anchor('news/association', 'Новости'); ?></li>
                    <li><?= HTML::anchor('contacts', 'Контакты'); ?></li>
                </ul>
            </div>
            <div class="user_panel">
                <?php if (!$user): ?>
                    <?= FORM::open('login'); ?>
                    <ul>
                        <li><?= HTML::anchor('registration', 'ВСТУПИТЬ'); ?></li>
                        <!--li><?= __('f_username_header'); ?></li-->
                        <li><?= FORM::input('username', NULL, array('placeholder'=> 'Логин', 'style' => 'width: 100px;')); ?></li>
                        <li><!--?= __('f_password'); ?--></li>
                        <li><?= FORM::password('password', NULL, array('placeholder'=> 'Пароль', 'style' => 'width: 100px;')); ?></li>
                        <li>
                            <?= FORM::submit(NULL, 'Войти', array('class' => 'login_submit')); ?>
                            <input class="login_forgot" type="button" onclick="location.href='<?= URL::base().'forgot_password'; ?>'" value="">
                        </li>
                                          
                    </ul>
                    <?= FORM::close(); ?>
                <?php else: ?>
                    <ul>
                        <?php if (isset($notice_count) AND $notice_count['new'] > 0): ?>
                            <li><?= HTML::anchor('cabinet/notice', $notice_count['new'], array('class' => 'notice_icon')); ?></li>
                        <?php endif; ?>
                        <li><?= HTML::anchor('cabinet', 'Личный кабинет'); ?></li>
                        <li><?= HTML::anchor('logout', 'Выход'); ?></li>
                    </ul>
                                    
                <?php endif; ?>

            </div>
        </div>
	    <div id="middle_road">
		    <div class="logo">
			    <?= HTML::anchor('/', HTML::image('assets/img/logo.png', array('alt' => 'На главную', 'title' => 'На главную', 'class' => 'logo_img'))); ?>
		    </div>
		    <div class="sub_menu">
			    <div class="sub-menu-form">
				    <ul>
					    <li>
						    <?= FORM::open('search', array('method' => 'get'))?>
						    <?= FORM::input('str', NULL, array('placeholder' => 'поисковый запрос', 'class' => 'search_input')).FORM::hidden('search_type', 'all'); ?>
						    <?= FORM::submit(NULL, 'Найти', array('class' => 'search_submit')); ?>
						    <?= FORM::close(); ?>
					    </li>
				    </ul>
			    </div>
		    </div>
		    <div class="menu">
			    <ul>
                    <li><?= HTML::anchor('/', 'УЧАСТНИКИ', array('class' => 'menu-auto-filter')); ?></li>
                    <li><?= HTML::anchor('programs', 'ПРОГРАММЫ', array('class' => 'menu-auto-filter')); ?></li>
                    <li><?= HTML::anchor('/', 'МЕРОПРИЯТИЯ'); ?></li>
                    <li><?= HTML::anchor('/', 'СЕРВИСЫ'); ?></li>
                    <li><?= HTML::anchor('/', 'СОБЫТИЯ'); ?></li>
                    <li><?= HTML::anchor('/', 'ФОРУМ', array('class' => 'last')); ?></li>
			    </ul>
		    </div>
          <div class="region_expand">
			    <?= HTML::anchor('#', $cities[$selected_city_ids['total']]->name, array('class' => 'choose_region')); ?>
			    <div class="select_region">
				    <?= View::factory('frontend/navigation/header_cities')->set('cities', $cities)->set('current_city', $selected_city_ids['total'])->render(); ?>
			    </div>
		    </div>
		    <div class="assoc-news">
		        <p class="assoc-news-title">Москва, Санкт-Петербург, Владивосток, Волгоград, Воронеж, Екатеринбург, Казань, Краснодар, Красноярск, Нижний Новгород, Новосибирск, Оренбург, Омск, Пермь, Ростов-на-Дону, Самара, Саратов, Тольятти, Уфа, Челябинск</p>
		    </div>
	    </div>
    </div>

    <div id="content_container">
        <div class="breadcrumbs">
            <?php
            $bc_count = count($bc);
            $iteration = 1;
            ?>
            <?php foreach ($bc as $url => $title): ?>
                <?php if ($bc_count == $iteration): ?>
                    <?= HTML::anchor('#', $title, array('class' => 'active')); ?>
                <?php else: ?>
                    <?= HTML::anchor($url, $title); ?>
                <?php endif; ?>

                <?php $iteration++; ?>
            <?php endforeach; ?>
        </div>
		
		<noindex>
			<div class="main_wrapper wrapper-sidebar">
				sidebar
				<div class="banner">
					<a target="_blank" href="#">
						<img src="https://storage.googleapis.com/support-kms-prod/SNP_2922276_en_v1" alt="alt text" title="title banner" />
					</a>
				</div>
			</div>
		</noindex>
	    
        <div class="main_wrapper <?= $css_class; ?>">
            <?= $content; ?>
        </div>
       
    </div>
    <div class="empty"></div>
</div>
<div id="footer">
    <div id="content_bottom_img"></div>
    <!--div class="social">
        <ul>
            <li><?= HTML::anchor('http://vk.com/avtoserviceas', '&nbsp', array('class' => 'vk')) ?></li>
            <li-->
                <!--LiveInternet counter>
                <script type="text/javascript"><!--
                document.write("<a href='http://www.liveinternet.ru/click' "+
                "target=_blank><img src='//counter.yadro.ru/hit?t16.2;r"+
                escape(document.referrer)+((typeof(screen)=="undefined")?"":
                ";s"+screen.width+"*"+screen.height+"*"+(screen.colorDepth?
                screen.colorDepth:screen.pixelDepth))+";u"+escape(document.URL)+
                ";h"+escape(document.title.substring(0,80))+";"+Math.random()+
                "' alt='' title='LiveInternet: показано число просмотров за 24"+
                " часа, посетителей за 24 часа и за сегодня' "+
                "border='0' width='88' height='31'><\/a>")
                //></script>
                <!--/LiveInternet>
            </li>
        </ul>
    </div-->
</div>
<?php foreach ($scripts['after'] as $script): ?>
	<?= HTML::script($script['file'], array('type' => $script['type'])); ?>
<?php endforeach; ?>
<!-- Yandex.Metrika counter -->
<div style="display:none;"><script type="text/javascript">
(function(w, c) {
    (w[c] = w[c] || []).push(function() {
        try {
            w.yaCounter7763482 = new Ya.Metrika({id:7763482, enableAll: true, webvisor:true});
        }
        catch(e) { }
    });
})(window, "yandex_metrika_callbacks");
</script></div>
<script src="//mc.yandex.ru/metrika/watch.js" type="text/javascript" defer="defer"></script>
<noscript><div><img src="//mc.yandex.ru/watch/7763482" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
<!-- /Yandex.Metrika counter -->
<script type="text/javascript">
  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-27332456-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>
</body>
</html>