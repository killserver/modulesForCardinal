<div class="col-sm-12">
	<div class="panel panel-default white" data-module="stats">
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
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-warning text-center">
                                <i class="linecons-user"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers num hits"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="linecons-user"></i> Хитов<br>Всего
                    </div>
                </div>
            </div>
		</div>
		<div class="col-md-3">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-warning text-center">
                                <i class="linecons-user"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers num unique_hits"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="linecons-user"></i> Хитов<br>За 24 часа
                    </div>
                </div>
            </div>
		</div>
		<div class="col-md-3">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-warning text-center">
                                <i class="linecons-user"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers num hits_tf"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="linecons-user"></i> Уникальных посетителей<br>Всего
                    </div>
                </div>
            </div>
		</div>
		<div class="col-md-3">
            <div class="card">
                <div class="card-content">
                    <div class="row">
                        <div class="col-xs-5">
                            <div class="icon-big icon-warning text-center">
                                <i class="linecons-user"></i>
                            </div>
                        </div>
                        <div class="col-xs-7">
                            <div class="numbers num unique_hits_tf"></div>
                        </div>
                    </div>
                </div>
                <div class="card-footer">
                    <hr>
                    <div class="stats">
                        <i class="linecons-user"></i> Уникальных посетителей<br>За 24 часа
                    </div>
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