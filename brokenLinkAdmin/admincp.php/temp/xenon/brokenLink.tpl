<div class="row">
	<div class="col-sm-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h3 class="panel-title">Проверка страниц</h3>
				<div class="panel-options">
					<a href="#" data-toggle="panel"><span class="collapse-icon">&ndash;</span><span class="expand-icon">+</span></a>
				</div>
			</div>
			<div class="panel-body">
				<div class="row">
					[if {lastCheck}!=false]<div class="col-sm-12">Время последней проверки: <b>{S_langdata="{lastCheck}","d F Y H:i:s",true}</b></div>
					<br>
					<br>
					<div class="col-sm-12"><b>С последней проверки:</b></div>[/if {lastCheck}!=false]
					<br>
					<div class="col-sm-12 col-md-6"><div class="col-sm-12 bg-info"><span class="label label-info pull-right countLinks-scan">{maxAll}</span>Найдено ссылок</div></div>
					<div class="col-sm-12 col-md-6"><div class="col-sm-12 bg-success"><span class="label pull-right success-scan">{maxScanned}</span>Успешно просканировано</div></div>
					<div class="col-sm-12 col-md-6"><div class="col-sm-12 bg-danger"><span class="label pull-right error-scan">{maxError}</span>Просканировано с ошибкой</div></div>
					<div class="col-sm-12"></div>
					<div class="col-sm-6 col-md-6"><a href="#" class="btn btn-purple btn-block btn-icon btn-icon-standalone rescan"><i><b class="fa-cog"></b></i><span>Сканировать</span></a></div>
					<div class="col-sm-6 col-md-6"><a href="#" class="btn btn-gray btn-block btn-icon btn-icon-standalone clear"><i><b class="fa-remove"></b></i><span>Очистить</span></a></div>
					<br>
					<br>
					<div class="col-sm-12 scrollable" data-max-height="400">
						<table class="table responsive">
							<thead>
								<tr>
									<th>#</th>
									<th>Ссылка</th>
									<th>Статус</th>
									<th>Скорость ответа</th>
								</tr>
							</thead>
							<tbody class="data-scan">
								[foreach block=ModelChecker]
								<tr>
									<td>{ModelChecker.cId}</td>
									<td>{ModelChecker.linkNow}</td>
									<td class="color-code" data-code="{ModelChecker.statusCode}">{ModelChecker.status}</td>
									<td>{ModelChecker.timeResp}ms</td>
								</tr>
								[/foreach]
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!--div class="col-sm-12">
		<div class="panel panel-default collapsed">
			<div class="panel-heading">
				<h3 class="panel-title">Line Charts</h3>
				<div class="panel-options">
					<a href="#" data-toggle="panel" class="showLive"><span class="collapse-icon">&ndash;</span><span class="expand-icon">+</span></a>
				</div>
			</div>
			<div class="panel-body">
				<div id="bar-3" style="height: 400px; width: 100%;"></div>
			</div>
		</div>
	</div-->
</div>
<template class="resp">
	<tr>
		<td>{id}</td>
		<td>{link}</td>
		<td>{http}</td>
		<td>{time}ms</td>
	</tr>
</template>
<style type="text/css">
	.ps-container .ps-scrollbar-x-rail {
		display: none;
	}
	table .bg-muted, table .bg-gray, table .bg-primary, table .bg-success, table .bg-info, table .bg-warning, table .bg-danger {
		padding: 3px 5px;
		font-size: 1.1rem;
	}
	.bg-muted, .bg-gray, .bg-primary, .bg-success, .bg-info, .bg-warning, .bg-danger {
		margin-bottom: 0.5rem;
	}
	.label.pull-right { margin-top: 2px; }
</style>
<script type="text/javascript" src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/xenon/js/devexpress-web-14.1x/js/globalize.min.js"></script>
<script type="text/javascript" src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/xenon/js/devexpress-web-14.1x/js/dx.chartjs.js"></script>
<script type="text/javascript">
var jsonData = {};
var scanId = 1;
var lastScanId = -1;
var btn = null;
var stop = false;
jQuery(document).ready(function($) {
	jQuery("body").on("click", ".rescan", function(event) {
		scanId = 1;
		jQuery(this).find("b").addClass('fa-spin');
		jQuery(this).find("span").html("Сканируется...");
		jQuery(this).removeClass('rescan').addClass("stopper");
		jQuery(".data-scan").html("");
		jQuery(".countLinks-scan").html("0");
		jQuery(".success-scan").html("0");
		jQuery(".error-scan").html("0");
		jQuery(".clear").attr("disabled", "disabled");
		btn = this;
		scanner();
		return false;
	});
	jQuery("body").on("click", ".stopper", function() {
		stop = true;
	});
	jQuery(".clear").click(function() {
		jQuery.post("./?pages=brokenLink&clear=1", function(d) {
			jQuery(".data-scan").html("");
			jQuery(".countLinks-scan").html("0");
			jQuery(".success-scan").html("0");
			jQuery(".error-scan").html("0");
			toastr.info("Успешно очистили");
		});
	});
	jQuery(".showLive").click(function() {
		if(!$.isFunction($.fn.dxChart)) {
			return;
		}
		var dataSource = [
			{ year: "13.05.2018", links: 546, americas: 332, africa: 227 }
		];
		setTimeout(function() {
			jQuery("#bar-3").dxChart({
				dataSource: dataSource,
				commonSeriesSettings: {
					argumentField: "year"
				},
				series: [
					{ valueField: "links", name: "Europe", color: "#40bbea" },
					{ valueField: "americas", name: "Americas", color: "#cc3f44" },
					{ valueField: "africa", name: "Africa", color: "#8dc63f" }
				],
				argumentAxis:{
					grid:{
						visible: true
					}
				},
				tooltip:{
					enabled: true
				},
				title: "Historic, Current and Future Population Trends",
				legend: {
					verticalAlignment: "bottom",
					horizontalAlignment: "center"
				},
				commonPaneSettings: {
					border:{
						visible: true,
						right: false
					}  
				}
			});
		}, 1000);
	});
	var elems = jQuery(".color-code");
	for(var elem=0;elem<elems.length;elem++) {
		var dd = jQuery(elems[elem]);
		var code = dd.attr("data-code");
		if(code>=200 && code<=300) {
			dd.html('<p class="bg-success">'+dd.html()+'</p>');
		} else if(code>=300 && code<=399) {
			dd.html('<p class="bg-warning">'+dd.html()+'</p>');
		} else if(code>400) {
			dd.html('<p class="bg-danger">'+dd.html()+'</p>');
		}
	}
});
function scanner() {
	if(stop) {
		jQuery(btn).find("b").removeClass('fa-spin');
		jQuery(btn).find("span").html("Сканировать");
		jQuery(btn).addClass('rescan').removeClass("stopper");
		jQuery(".clear").removeAttr("disabled");
		stop = false;
		return;
	}
	jQuery.post("./?pages=brokenLink&rescan=1", function(data) {
		jsonData = JSON.parse(data);
		if(jsonData.broken!==false) {
			var id = jQuery(".error-scan").html();
			id = parseInt(id);
			id++;
			jQuery(".error-scan").html(""+id);
		}
		if(jsonData.warning!==false) {
			var id = jQuery(".error-scan").html();
			id = parseInt(id);
			id++;
			jQuery(".error-scan").html(""+id);
		}
		if(jsonData.broken===false && jsonData.warning===false) {
			var id = jQuery(".success-scan").html();
			id = parseInt(id);
			id++;
			jQuery(".success-scan").html(""+id);
		}
		var id = jsonData.all;
		jQuery(".countLinks-scan").html(""+id);
		var tmp = jQuery(".resp").html();
		tmp = tmp.replace(/\{id\}/g, scanId);
		tmp = tmp.replace(/\{link\}/g, jsonData.link);
		var http = jsonData.http;
		if(jsonData.code>=200 && jsonData.code<=300) {
			http = '<p class="bg-success">'+http+'</p>';
		} else if(jsonData.code>=300 && jsonData.code<=399) {
			http = '<p class="bg-warning">'+http+'</p>';
		} else if(jsonData.code>400) {
			http = '<p class="bg-danger">'+http+'</p>';
		}
		tmp = tmp.replace(/\{http\}/g, http);
		tmp = tmp.replace(/\{time\}/g, jsonData.timeResp);
		jQuery(".data-scan").prepend(tmp);
		scanId++;
		if(jsonData.stillScan>0) {
			scanner();
		} else {
			jQuery(btn).find("b").removeClass('fa-spin');
			jQuery(btn).find("span").html("Сканировать");
			jQuery(btn).addClass('rescan').removeClass("stopper");
			jQuery(".clear").removeAttr("disabled");
		}
	});
}
</script>