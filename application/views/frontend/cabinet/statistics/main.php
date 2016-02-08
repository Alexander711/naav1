<?php
defined('SYSPATH') or die('No direct script access.'); ?>

<style>
.jqplot-data-label
{
	color: #FFF;
}
#content_container ul.nav 
{
	list-style-type: none;
	margin-left: 0;
}
#content_container ul.nav li.active a
{
	text-decoration: none;
}
</style>
<script type="text/javascript">
    var stat = <?= json_encode($data); ?>;
</script>

<ul class="nav nav-tabs">
	<?php foreach ($user->services->find_all() as $s): ?>
		<li class="<?php echo ($service->id == $s->id ? 'active' : '') ?>"><?= HTML::anchor('cabinet/statistics/index/'.$s->id, $s->name); ?></li>
	<?php endforeach; ?>
</ul>

<div style="overflow: hidden; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 2px dotted #C9C9C9">
  <div id="company-page-chart" style="width: 550px; height: 300px; margin-top: 20px; float: left;">

  </div>
  <div style="float: right; padding-top: 38px;">
    <strong>Средняя посещаемость</strong> <br/>
    за  день <strong style="color: #5FC240"><?= $company_average_on_day; ?></strong> <br/>
    за неделю <strong style="color: #5FC240"><?= $company_average_on_week; ?></strong>  <br/>
    за месяц <strong style="color: #5FC240"><?= $company_average_on_month; ?></strong> <br/>
  </div>
  <button style="margin-top: 160px" onclick="companyChart.resetZoom();" type="button" value="reset">Сбросить приближение</button>
</div>


<?php if ($data->visits_counts[1][1] > 0):  ?>
  <div style="overflow: hidden; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 2px dotted #C9C9C9">
    <div id="news-pages-chart" style="width: 550px; float: left;">

    </div>
    <div style="float: right; padding-top: 20px;">
      <strong>Средняя посещаемость</strong> <br/>
      за  день <strong style="color: #5FC240"><?= $news_average_on_day; ?></strong> <br/>
      за неделю <strong style="color: #5FC240"><?= $news_average_on_week; ?></strong>  <br/>
      за месяц <strong style="color: #5FC240"><?= $news_average_on_month; ?></strong> <br/>  
    </div>
    <button style="margin-top: 160px" onclick="newsChart.resetZoom();" type="button" value="reset">Сбросить приближение</button>

  </div>
<?php endif; ?>

<?php if ($data->visits_counts[2][1] > 0):  ?>
  <div style="overflow: hidden; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 2px dotted #C9C9C9">
    <div id="stocks-pages-chart" style="width: 550px; float: left;">

    </div>
    <div style="float: right; padding-top: 20px;">
      <strong>Средняя посещаемость</strong> <br/>
      за  день <strong style="color: #5FC240"><?= $stocks_average_on_day; ?></strong> <br/>
      за неделю <strong style="color: #5FC240"><?= $stocks_average_on_week; ?></strong>  <br/>
      за месяц <strong style="color: #5FC240"><?= $stocks_average_on_month; ?></strong> <br/>  
    </div>
    <button style="margin-top: 160px" onclick="stocksChart.resetZoom();" type="button" value="reset">Сбросить приближение</button>
  </div>

<?php endif; ?>

<?php if ($data->visits_counts[3][1] > 0):  ?>
  <div style="overflow: hidden; padding-bottom: 10px; margin-bottom: 10px; border-bottom: 2px dotted #C9C9C9">
    <div id="vacancies-pages-chart" style="width: 550px; float: left;">

    </div>
    <div style="float: right; padding-top: 20px;">
      <strong>Средняя посещаемость</strong> <br/>
      за  день <strong style="color: #5FC240"><?= $vacancies_average_on_day; ?></strong> <br/>
      за неделю <strong style="color: #5FC240"><?= $vacancies_average_on_week; ?></strong>  <br/>
      за месяц <strong style="color: #5FC240"><?= $vacancies_average_on_month; ?></strong> <br/>  
    </div>
    <button style="margin-top: 160px" onclick="vacanciesChart.resetZoom();" type="button" value="reset">Сбросить приближение</button>
  </div>

<?php endif; ?>


<div id="pie-chart" style="width: 100%; height: 500px">

</div>
