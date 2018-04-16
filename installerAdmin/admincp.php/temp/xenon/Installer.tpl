<div class="row">
	<div class="col-md-12">
		<div class="panel panel-default panel-tabs">
			<div class="panel-heading">
				<h3 class="panel-title">Модули</h3>
				<div class="panel-options">
					<ul class="nav nav-tabs">
						<li class="active">
							<a href="#tab-4" data-toggle="tab">Установленные</a>
						</li>
						<li>
							<a href="#tab-5" data-toggle="tab">Управление серверами</a>
						</li>
						<li>
							<a href="#tab-6" data-toggle="tab">Список модулей</a>
						</li>
					</ul>
				</div>
			</div>
			<div class="panel-body">
				<div class="tab-content">
					<div class="tab-pane active" id="tab-4">
						<table class="table table-hover responsive">
							<thead>
								<tr>
									<th>#</th>
									<th width="200">Изображение</th>
									<th>Название</th>
									<th width="50%">Описание</th>
								</tr>
							</thead>
							<tbody id="accordion">
								[foreach block=installed]<tr>
									<td>{installed.$id}</td>
									<td width="200"><img src="{installed.image}" style="max-width: 100%;"></td>
									<td>
										<b>{installed.name}</b><br>
										<div class="btns" style="display: table-cell; vertical-align: bottom;">
											[foreachif {installed.hasUpdate}==true]<a href="#" class="btn btn-purple btn-icon btn-icon-standalone btn-sm update" data-action="{installed.altName}"><i class="fa fa-refresh"></i><span>Обновить</span></a>[/foreachif {installed.hasUpdate}==true]
											[foreachif {installed.active}=="active"]<a href="#" class="btn btn-blue btn-sm action actived" data-action="{installed.altName}" data-status="{installed.active}" turquoise><span>Отключить</span></a>[/foreachif {installed.active}=="active"]
											[foreachif {installed.active}=="unactive"]<a href="#" class="btn btn-turquoise btn-sm action actived" data-action="{installed.altName}" data-status="{installed.active}"><span>Включить</span></a>[/foreachif {installed.active}=="unactive"]
											<!--a href="#" class="btn btn-red btn-sm"><span>Удалить</span></a--><!-- newer implemented?! -->
										</div>
									</td>
									<td width="50%">{installed.description}[foreachif {installed.noChangelog}==false]<br><a class="btn" data-toggle="collapse" data-parent="#accordion" href="#collapseOne-{installed.$id}">Список изменений</a><div id="collapseOne-{installed.$id}" class="collapse">{installed.changelog}</div>[/foreachif {installed.noChangelog}==false]</td>
								</tr>[/foreach]
							</tbody>
						</table>
					</div>
					<div class="tab-pane" id="tab-5">
						<textarea class="form-control" rows="15">{listServer}</textarea><br><a href="#" class="saveListModules btn btn-success pull-right">Сохранить</a>
					</div>
					<div class="tab-pane moduleList" id="tab-6">
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<script type="text/template" id="templateCategory">
	<div class="col-md-12">
		<ul class="nav nav-tabs nav-tabs-justified">
			<li class="active">
				<a href="#module" data-toggle="tab"><span>{L_"Модули"}</span></a>
			</li>
			<li>
				<a href="#theme" data-toggle="tab"><span>{L_"Шаблоны"}</span></a>
			</li>
			<li>
				<a href="#plugins" data-toggle="tab"><span>{L_"Плагины"}</span></a>
			</li>
		</ul>
		<div class="tab-content">
			<div class="tab-pane active" id="module"><div class="row">{modules}</div></div>
			<div class="tab-pane" id="theme"><div class="row">{themes}</div></div>
			<div class="tab-pane" id="plugins"><div class="row">{plugins}</div></div>
		</div>
	</div>
</script>
<script type="text/template" id="templateItem">
	<div class="col-sm-4">
		<a href="#" data-info="{altName}" style="width: 100%;"{installedHead}>
			<div style="height:15em;display:flex;align-items:center;"><div class="img" style="background-image:url('{image}');"></div></div>
			<br>
			<b style="width: 100%; height: 3em; display: table-cell; vertical-align: middle; white-space: normal;">{name}</b>
		</a>
		{installedFoot}
	</div>
</script>
<script type="text/template" id="templateItemInstalledHead1">
	data-install="install" class="btn install "
</script>
<script type="text/template" id="templateItemInstalledHead2">
	data-install="update" class="btn update"
</script>
<script type="text/template" id="templateItemInstalledHead3">
	data-install="installed" class="btn installed"
</script>
<script type="text/template" id="templateItemInstalledHead4">
	data-install="buy" class="btn buy"
</script>
<script type="text/template" id="templateItemInstalledFoot0">
	<a href="#" class="btn btn-red btn-block disabled">Поддерживается на версии {version}</a>
</script>
<script type="text/template" id="templateItemInstalledFoot1">
	<a href="#" class="btn btn-turquoise btn-block action install" data-action="{altName}">Установить</a>
</script>
<script type="text/template" id="templateItemInstalledFoot2">
	<a href="#" class="btn btn-blue btn-block action update" data-action="{altName}">Обновить</a>
</script>
<script type="text/template" id="templateItemInstalledFoot3">
	<a href="#" class="btn btn-success btn-block action installed" style="cursor: not-allowed;" data-action="{altName}">Установлено</a>
</script>
<script type="text/template" id="templateItemInstalledFoot4">
	<a href="#" class="btn btn-purple btn-block action buy" data-action="{altName}">Купить</a>
</script>
<script type="text/javascript">
	var cardinalVersionNow = "{D_VERSION}";
	var infoAll = '{infoAll}';
	infoAll = JSON.parse(infoAll);
	var test;
	jQuery(document).ready(function($) {
		if(Object.keys(infoAll).length==0) {
			jQuery(".moduleList").html("{L_"Сервера не ответили вовремя либо не доступны"}");
		} else {
			var moduleAll = "";
			var themeAll = "";
			var pluginAll = "";
			Object.keys(infoAll).forEach(function(key) {
				var tmpAll = jQuery("#templateItem").html();
				var installedHead = "";
				var installedFoot = "";
				if(infoAll[key].cardinalVersion > cardinalVersionNow) {
					installedHead = "class=\"btn\"";
					installedFoot = jQuery("#templateItemInstalledFoot0").html();
					installedFoot = installedFoot.replace(/\{version\}/g, infoAll[key].cardinalVersion);
				} else if(infoAll[key].installed==1) {
					installedHead = jQuery("#templateItemInstalledHead1").html();
					installedFoot = jQuery("#templateItemInstalledFoot1").html();
				} else if(infoAll[key].installed==2) {
					installedHead = jQuery("#templateItemInstalledHead2").html();
					installedFoot = jQuery("#templateItemInstalledFoot2").html();
				} else if(infoAll[key].installed==3) {
					installedHead = jQuery("#templateItemInstalledHead3").html();
					installedFoot = jQuery("#templateItemInstalledFoot3").html();
				} else if(infoAll[key].installed==4) {
					installedHead = jQuery("#templateItemInstalledHead4").html();
					installedFoot = jQuery("#templateItemInstalledFoot4").html();
				}
				tmpAll = tmpAll.replace(/\{installedHead\}/g, installedHead);
				tmpAll = tmpAll.replace(/\{installedFoot\}/g, installedFoot);
				tmpAll = tmpAll.replace(/\{altName\}/g, infoAll[key].altName);
				tmpAll = tmpAll.replace(/\{image\}/g, infoAll[key].image);
				tmpAll = tmpAll.replace(/\{name\}/g, infoAll[key].name);
				if(infoAll[key].type=="module") {
					moduleAll += tmpAll;
				} else if(infoAll[key].type=="theme") {
					themeAll += tmpAll;
				} else if(infoAll[key].type=="plugins") {
					pluginAll += tmpAll;
				}
			});
			var allTmp = jQuery("#templateCategory").html();
			allTmp = allTmp.replace(/\{modules\}/g, moduleAll);
			allTmp = allTmp.replace(/\{themes\}/g, themeAll);
			allTmp = allTmp.replace(/\{plugins\}/g, pluginAll);
			jQuery(".moduleList").html(allTmp);
		}
		jQuery(".btns").each(function(i, elem) {
			jQuery(elem).css("height", jQuery(elem).parent().outerHeight()-jQuery(elem).parent().find("b").outerHeight()*3);
		});
		jQuery("body").off("click").on("click", "[data-action]:not(.disabled)", function() {
			test = this;
			var action = this;
			if(jQuery(this).hasClass("actived")) {
				jQuery.post("./?pages=Installer&active="+jQuery(this).attr("data-action"), function(data) {
					jQuery(action).html(jQuery(action).attr("data-status")=="active" ? "Включить" : "Отключить");
					if(jQuery(action).attr("data-status")=="active") {
						jQuery(action).removeClass("btn-blue").addClass('btn-turquoise');
					} else {
						jQuery(action).removeClass("btn-turquoise").addClass('btn-blue');
					}
					jQuery(action).attr("data-status", (jQuery(action).attr("data-status")=="active" ? "unactive" : "active"));
					toastr.info("Переключён режим работы модуля");
				});
			} else if(jQuery(this).hasClass('install')) {
				var th = this;
				toastr.info("Скачивание модуля");
				jQuery.post("./?pages=Installer&download="+jQuery(th).attr("data-action"), function(data) {}).fail(function(data) {
					toastr.error("Модуль не был скачан, попробуйте позже");
				}).done(function(data) {
					toastr.info("Установка нового модуля");
					jQuery.post("./?pages=Installer&install="+jQuery(th).attr("data-action"), function(data) {}).fail(function(data) {
						toastr.error("Модуль не был установлен, попробуйте позже");
					}).done(function(data) {
						toastr.info("Установлен новый модуль");
					});
				});
			} else if(jQuery(this).hasClass('update')) {
				var th = this;
				toastr.info("Обновление модуля");
				jQuery.post("./?pages=Installer&download="+jQuery(th).attr("data-action"), function(data) {}).fail(function(data) {
					toastr.error("Модуль не был скачан, попробуйте позже");
				}).done(function(data) {
					toastr.info("Обновление модуля");
					jQuery.post("./?pages=Installer&install="+jQuery(th).attr("data-action"), function(data) {}).fail(function(data) {
						toastr.error("Модуль не был обновлён, попробуйте позже");
					}).done(function(data) {
						toastr.info("Обновлён модуль");
					});
				});
			} else if(jQuery(this).hasClass('installed')) {
				toastr.info("Модуль успешно запущен и работает из нарицаний");
			} else if(jQuery(this).hasClass('buy')) {
				jQuery("#modal-4 .modal-title").html("Приобретение "+jQuery(this).attr("data-action"));
				var tmp = '<form class="Paymentform" method="POST" action="https://api.privatbank.ua/p24api/ishop"><input type="hidden" name="amt" value="{price}" /><input type="hidden" name="ccy" value="UAH" /><input type="hidden" name="merchant" value="1234567890" /><input type="hidden" name="order" value="'+jQuery(this).attr("data-action")+'" /><input type="hidden" name="details" value="'+jQuery(this).attr("data-action")+'" /><input type="hidden" name="ext_details" value="'+jQuery(this).attr("data-action")+'" /><input type="hidden" name="pay_way" value="privat24" /><input type="hidden" name="return_url" value="" /><input type="hidden" name="server_url" value="" /><button type="submit" class="Privat24">Приват 24</button></form>';
				tmp = tmp.replace(/\{price\}/g, "1000");
				jQuery("#modal-4 .modal-body").html(tmp);
				jQuery("#modal-3 [data-dismiss]").click();
				setTimeout(function() { jQuery("#modal-4").modal('show'); }, 400);
			}
			return false;
		});
		jQuery("body").off("click").on("click", "a[data-info]", function() {
			var data = infoAll[jQuery(this).attr("data-info")];
			var installation = jQuery(this).attr("data-install");
			console.log(installation);
			jQuery("#modal-3 .modal-body").html(jQuery("#templateModule").html());
			jQuery("#modal-3 .modal-body .title").html(data.name);
			jQuery("#modal-3 .modal-body .description span").html(data.description);
			jQuery("#modal-3 .modal-body .version span").html(data.version);
			jQuery("#modal-3 .modal-body .author span").html(data.author);
			jQuery("#modal-3 .modal-body .screens").remove();
			jQuery("#modal-3 .modal-body .img").css("backgroundImage", "url('"+data.image+"')");
			var html = "";
			if(typeof(data.changelog)!=="undefined") {
				Object.keys(data.changelog).forEach(function(v) {
					html += "<b>"+v+"</b>"+data.changelog[v]+"<br>";
				});
			}
			if(html.length>0) {
				jQuery("#modal-3 .modal-body .changelog span").html(html);
			} else {
				jQuery("#modal-3 .modal-body .changelog").remove();
			}
			if(data.cardinalVersion > cardinalVersionNow) {
				jQuery("#modal-3 .modal-body a.btn.action").attr("class", "").addClass("btn btn-red disabled").css("cursor", "").html("Поддерживается на версии "+data.cardinalVersion);
			} else if(installation=="update") {
				jQuery("#modal-3 .modal-body a.btn.action").attr("class", "").addClass("btn action btn-blue update").css("cursor", "").attr("data-action", data.altName).html("Обновить");
			} else if(installation=="installed") {
				jQuery("#modal-3 .modal-body a.btn.action").attr("class", "").addClass("btn action btn-success installed").css("cursor", "not-allowed").attr("data-action", data.altName).html("Установлено");
			} else if(installation=="buy") {
				jQuery("#modal-3 .modal-body a.btn.action").attr("class", "").addClass("btn action btn-purple buy").attr("data-action", data.altName).html("Купить");
			} else if(installation=="buy") {
				jQuery("#modal-3 .modal-body a.btn.action").attr("class", "").addClass("btn action btn-purple buy").attr("data-action", data.altName).html("Купить");
			}
			jQuery("#title_video").html(data.name);
			jQuery(".modal .modal-dialog .modal-content .modal-body").css("overflow", "auto");
			jQuery('#modal-3').modal('show');
			return false;
		});
	});
	var disableAllEditors = true;
</script>
<script type="text/template" id="templateModule">
	<div class="installator">
		<div class="col-sm-12"><div class="img"></div></div>
		<div class="col-sm-12">
			<div class="pull-left title"></div>
			<div class="pull-right"><a href="#" class="btn action">Обновить</a></div>
		</div>
		<div class="col-sm-9">
			<div class="col-sm-12 description">
				<h4>Описание</h4>
				<span></span>
			</div>
			<div class="col-sm-12 screens">
				<h4>Скриншоты</h4>
				<span><i>Не реализовано</i></span>
			</div>
			<div class="col-sm-12 changelog">
				<h4>Список изменений</h4>
				<span></span>
			</div>
		</div>
		<div class="col-sm-3">
			<ul class="list-group list-group-minimal">
				<li class="list-group-item version">
					<span class="badge badge-roundless badge-primary" style="font-size: 12px; letter-spacing: 0.04em; font-weight: normal;"></span>Версия:
				</li>
				<li class="list-group-item author">
					<span class="badge badge-roundless badge-info" style="font-size: 12px; letter-spacing: 0.04em; font-weight: normal;"></span>Автор:
				</li>
			</ul>
		</div>
	</div>
</script>
<style type="text/css">
.modal-body .img {
    width: 100%;
    height: 25em;
    background-size: 90%;
    background-attachment: fixed;
    background-repeat: no-repeat;
    background-position: 50% 0%;
    border: 0.5em solid #ddd;
    margin-bottom: 2em;
}

.modal-body a.btn {
    border-radius: .25rem;
}

.modal .pull-left.title {
    font-size: 1.1em;
    font-weight: 600;
}

.modal .col-sm-12.description {
    margin-bottom: 2em;
}

.modal .col-sm-12.changelog {}
.modal .col-sm-12.changelog b {
    color: #00f;
    margin: 0.5em 0px 0.5em;
    display: table;
}
.modal .col-sm-12.changelog b:before {
    content: '- ';
}
.modal .col-sm-12 h4 {
    color: #333;
    font-style: italic;
    font-weight: bold;
    margin: 1em 0px;
}
.tab-content .tab-content {
    background: whitesmoke !important;
}
.tab-content .nav.nav-tabs > li > a {
    background-color: #fff;
}
.tab-content .nav.nav-tabs > li.active > a {
    background-color: #f4f4f4;
    border: 0px;
}
.tab-content .nav.nav-tabs > li > a:hover {
    background-color: #f4f4f4;
}
.tab-content a .img {
    width: 100%;
    height: 15em;
    margin: auto;
    display: table;
    background-repeat: no-repeat;
    background-size: cover;
    background-position: 50%;
}
</style>