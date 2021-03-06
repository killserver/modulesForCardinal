<div class="col-sm-12">
	<div class="panel panel-default" data-module="stats">
		<div class="panel-heading">
			<h3 class="panel-title">Статистика посещений</h3>
			<div class="panel-options">
				<a href="#" data-toggle="panel"><span class="collapse-icon">&ndash;</span><span class="expand-icon">+</span> </a>
			</div>
		</div>
		<div class="panel-body">
			<div id="bar-3" style="height: 400px; width: 100%;"></div>
		</div>
	</div>
	<div class="row">
		<div class="col-md-3">
			<div class="xe-widget xe-counter xe-counter-blue hitsAtt" data-count=".num" data-from="1" data-to="117" data-duration="3" data-easing="true">
				<div class="xe-icon">
					<i class="linecons-user"></i>
				</div>
				<div class="xe-label">
					<strong class="num hits"></strong>
					<span>Хитов<br>Всего</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="xe-widget xe-counter xe-counter-blue unique_hitsAtt" data-count=".num" data-from="1" data-to="117" data-duration="3" data-easing="true">
				<div class="xe-icon">
					<i class="linecons-user"></i>
				</div>
				<div class="xe-label">
					<strong class="num unique_hits"></strong>
					<span>Хитов<br>За 24 часа</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="xe-widget xe-counter xe-counter-blue hits_tfAtt" data-count=".num" data-from="1" data-to="117" data-duration="3" data-easing="true">
				<div class="xe-icon">
					<i class="linecons-user"></i>
				</div>
				<div class="xe-label">
					<strong class="num hits_tf"></strong>
					<span>Уникальных посетителей<br>Всего</span>
				</div>
			</div>
		</div>
		<div class="col-md-3">
			<div class="xe-widget xe-counter xe-counter-blue unique_hits_tfAtt" data-count=".num" data-from="1" data-to="117" data-duration="3" data-easing="true">
				<div class="xe-icon">
					<i class="linecons-user"></i>
				</div>
				<div class="xe-label">
					<strong class="num unique_hits_tf"></strong>
					<span>Уникальных посетителей<br>За 24 часа</span>
				</div>
			</div>
		</div>
	</div>
</div>
<script src="http://themes.laborator.co/xenon/assets/js/devexpress-web-14.1x/js/dx.chartjs.js" id="script-resource-8"></script>
<script type="text/javascript">
	var dt = {hitsTTS};
	jQuery('.hits').html(dt.hits);
	jQuery('.unique_hits').html(dt.unique_hits);
	jQuery('.hits_tf').html(dt.hits_tf);
	jQuery('.unique_hits_tf').html(dt.unique_hits_tf);
	jQuery('.hitsAtt').attr("data-to", dt.hits);
	jQuery('.unique_hitsAtt').attr("data-to", dt.unique_hits);
	jQuery('.hits_tfAtt').attr("data-to", dt.hits_tf);
	jQuery('.unique_hits_tfAtt').attr("data-to", dt.unique_hits_tf);
	jQuery(document).ready(function($) {
		if(!$.isFunction($.fn.dxChart)) {
			return;
		}
		var dataSource = {hitsTT};
		$("#bar-3").dxChart({
			dataSource: dataSource,
			commonSeriesSettings: {
				argumentField: "date"
			},
			series: [
				{ valueField: "visits", name: "Визитов", color: "#40bbea" },
				{ valueField: "visitors", name: "Уникальных посетителей", color: "#cc3f44" },
			],
			argumentAxis: {
				grid: {
					visible: true
				}
			},
			tooltip: {
				enabled: true
			},
			legend: {
				verticalAlignment: "bottom",
				horizontalAlignment: "center"
			},
			commonPaneSettings: {
				border: {
					visible: true,
					right: false
				}
			}
		});
	});
</script>