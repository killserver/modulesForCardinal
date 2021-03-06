<div class="panel panel-default">
	<div class="panel-heading">{L_"Управление меню"}</div>
	<div class="panel-body">
		<div class="row">
			<form method="post" role="form" class="form-menu form-horizontal">
				<div class="col-sm-12">
					<div class="form-group">
						<label class="col-sm-2 control-label">{L_"Наименование меню"}</label>
						<div class="col-sm-10">
							<input type="text" name="data[{uid}][name]" id="nameMenu" class="form-control" value="{name}">
						</div>
					</div>
					<ul id="nestable-list-1" class="uk-nestable" style="margin-bottom:0px;" data-uk-nestable><!-- ="{maxDepth:1}" -->
						{menuBuilder}
					</ul>
				</div>
				<div class="col-sm-12"><input type="submit" class="btn btn-success"><a href="#" class="btn btn-blue pull-right add">{L_add}</a></div>
			</form>
		</div>
	</div>
</div>
<style>
.uk-nestable-empty { min-height: auto !important; }
.uk-nestable-item { cursor: pointer; }
</style>
<link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.css" rel="stylesheet">
<script type="text/template" id="tmpItems">
	<li data-page="" data-content="" data-class="" data-opened="" data-icon="" data-level="" data-uid="{uid}">
		<div class="uk-nestable-item" data-toggle="collapse" href="#collapseTwo-{uid}">
			<div class="uk-nestable-handle"></div>
			<div data-nestable-action="toggle"></div>
			<div class="list-label"><i class="" style="width:2.5em;text-align:center;font-size:1.35em;"></i><span>{L_"Не заданно"}</span></div>
			<div class="btn btn-red btn-single pull-right remove">x</div>
			<div class="btn btn-warning btn-single pull-right edit"><i class="fa fa-pencil"></i></div>
		</div>
		<div id="collapseTwo-{uid}" class="panel panel-collapse collapse">
			<div class="panel-body">
			</div>
		</div>
	</li>
</script>
<script type="text/template" id="template">
	<div role="form" class="form-horizontal" data-uid="{uid}">
		<input type="hidden" name="data[{uid}][depth]">
		<div class="form-group">
			<label class="col-sm-2 control-label" for="field-1">{L_"Ссылка"}</label>
			<div class="col-sm-10">
				<input type="text" name="data[{uid}][page]" data-id="{uid}" data-name="page" class="form-control" id="field-1" value="{linker}">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="field-2">{L_"Содержимое"}</label>
			<div class="col-sm-10">
				<input type="text" name="data[{uid}][content]" data-id="{uid}" data-name="content" class="form-control" id="field-2" value="{bodyer}">
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="field-3">{L_"Класс"}</label>
			<div class="col-sm-10">
				<div class="input-group"><span class="input-group-addon"><b>.</b></span><input type="text" name="data[{uid}][class]" data-id="{uid}" data-name="class" class="form-control" id="field-3" value="{classer}"></div>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="field-4">{L_"Открывать в"}</label>
			<div class="col-sm-10">
				<select class="form-control" id="field-4" name="data[{uid}][opened]" data-id="{uid}" data-name="opened">
					<option value=""></option>
					<option value="_blank">Новой вкладке</option>
					<option value="_self">Своей вкладке</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="field-4">{L_"Иконка"}</label>
			<div class="col-sm-10">
				<input type="hidden" name="data[{uid}][icon]" data-id="{uid}" data-name="icon"><a href="#" class="btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right addIcon">{iconer}<span>{L_"Выбрать"}</span></a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-sm-2 control-label" for="field-4">{L_"Уровень доступа"}</label>
			<div class="col-sm-10">
				<select class="form-control" name="data[{uid}][level]" id="field-4" data-id="{uid}" data-name="level">
					<option value=""></option>
					[foreach block=levels]<option value="{levels.level}">{L_level[{levels.level}]}</option>[/foreach]
				</select>
			</div>
		</div>
	</div>
</script>
<script type="text/javascript">
	var tmp = $("#tmpItems").html();
	var isz = 0;
	var iszt = {countItems};
	if(typeof(iszt)==="undefined") {
		iszt = 1;
	}
	jQuery(".add").click(function() {
		var tmps = tmp;
		iszt++;
		tmps = tmps.replace(/{uid}/g, iszt);
		jQuery("#nestable-list-1").append(tmps);
		return false;
	});
	jQuery("body").on("click", ".remove", function() {
		jQuery(this).parent().parent().remove();
		return false;
	});
	jQuery(".form-menu").submit(function(event) {
		var elem = this;
		var named = jQuery("#nestable-list-1").data('nestable').serialize();
		named.push({ "name": jQuery("input#nameMenu").val() });
		jQuery.post("./?pages=MenuAdmin{additions}", JSON.stringify(named), function(data) {
			if(data==1) {
				toastr.info("Menu admin", "done save");
				jQuery(elem).find("input").attr("disabled", "disabled");
				jQuery(elem).find("select").attr("disabled", "disabled");
				jQuery(elem).find("a").attr("disabled", "disabled");
				jQuery(elem).find(".remove").attr("disabled", "disabled");
				setTimeout(function() {
					var link = window.location.href.replace(/\&edit=(.+?)/, "");
					link = link.replace(/\&add(.*?)/, "");
					window.location.href = link;
				}, 3000);
			} else {
				toastr.error("Menu admin", "error on save");
			}
		});
		return false;
	});
	var elem, uid;
	jQuery("body").on("click", '.addIcon', function() {
		elem = this;
		uid = jQuery(this).parent().find("input").attr("data-id");
		jQuery.post("./?pages=MenuAdmin&list", function(data) {
			jQuery("#modal-3 .modal-body").html(data);
			jQuery('#modal-3').modal('show', {backdrop: 'fade'});
			jQuery("#modal-3 .modal-body").css("overflow", "auto");
			jQuery("#modal-3 .modal-body").on("click", "a", function() {
				jQuery(elem).parent().children("input").val(jQuery(this).attr("data-icon"));
				jQuery(elem).children("i").children("b").remove();
				jQuery(elem).children("i").attr("class", "").addClass("fa-"+jQuery(this).attr("data-icon"));
				jQuery("li[data-uid='"+uid+"']").attr("data-icon", jQuery(this).attr("data-icon"));
				jQuery("li[data-uid='"+uid+"'] .list-label").children('i').attr("class", "fa-"+jQuery(this).attr("data-icon"));
				jQuery('#modal-3').modal('hide');
				jQuery('#modal-3').off("input").on("input", ".icon-find", function() {
					var val = jQuery(this).val();
					jQuery(".modal-body a").each(function(i, elem) {
						jQuery(elem).removeClass("hide");
						if(!(new RegExp(val, "g").test(jQuery(elem).attr("data-icon")))) {
							jQuery(elem).addClass("hide");
						}
					});
				});
				return false;
			});
		});
		return false;
	});
	jQuery("body").on("click", '[data-toggle="collapse"]', function() {
		if(!jQuery(this).hasClass('selected')) {
			var tmp = $("#template").html();
			tmp = tmp.replace("{linker}", jQuery(this).parent().attr("data-page"));
			tmp = tmp.replace("{bodyer}", jQuery(this).parent().attr("data-content"));
			tmp = tmp.replace("{classer}", jQuery(this).parent().attr("data-class"));
			var vt = jQuery(this).parent().attr("data-icon");
			if(vt==="") {
				vt = '<i><b>&nbsp;</b></i>';
			} else {
				vt = '<i class="fa fa-'+vt+'"></i>';
			}
			tmp = tmp.replace("{iconer}", vt);
			tmp = tmp.replace(/{uid}/g, iszt);
			jQuery(jQuery(this).attr("href")).find(".panel-body").html(tmp);
			jQuery(this).addClass("selected");
			iszt++;
		}
	});
	jQuery("body").on("keyup", "input", function(e) {
		jQuery("div[data-uid='"+jQuery(this).attr("data-id")+"']").parent().parent().parent().data(jQuery(this).attr("data-name"), jQuery(this).val());
		jQuery("div[data-uid='"+jQuery(this).attr("data-id")+"']").parent().parent().parent().attr("data-"+jQuery(this).attr("data-name"), jQuery(this).val());
	});
	jQuery("body").on("change", "input,select", function(e) {
		jQuery("div[data-uid='"+jQuery(this).attr("data-id")+"']").parent().parent().parent().data(jQuery(this).attr("data-name"), jQuery(this).val());
		jQuery("div[data-uid='"+jQuery(this).attr("data-id")+"']").parent().parent().parent().attr("data-"+jQuery(this).attr("data-name"), jQuery(this).val());
	});
	jQuery("body").on("keyup", "#field-2", function(e) {
		jQuery('[data-toggle="collapse"][href="#'+jQuery(this).parent().parent().parent().parent().parent().attr("id")+'"]').find(".list-label").children("span").html(jQuery(this).val());
	});
	var disableAllEditors = true;
</script>