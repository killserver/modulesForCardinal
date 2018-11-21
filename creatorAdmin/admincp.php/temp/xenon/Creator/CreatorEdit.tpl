<div class="row">
	<div class="col-md-12">
		<form role="form" class="form-horizontal formCreator" method="post">
			<input type="hidden" class="mode" name="mode" value="add">
			<div class="panel panel-default">
				<div class="panel-heading"><div class="col-sm-11"><input type="text" class="form-control title" name="data[title]" placeholder="Введите название раздела" required="required"></div><div class="col-sm-1"><div class="iconSelect"><input type="hidden" name="data[icon]" class="icons"><div><i class="" data-icon=""></i></div></div></div></div>
				<div class="panel-body">
					<ul class="creator uk-nestable row" data-uk-nestable="{maxDepth:1}"></ul>
					<input type="submit" class="btn btn-success" value="{L_submit}" disabled="disabled">
					<a href="#" class="btn btn-info addCreator pull-right">{L_"Добавить"}</a>
				</div>
			</div>
		</form>
	</div>
</div>
<style type="text/css">
.iconSelect > div {
	border: 0.01em solid #aaa;
	width: 1.8em;
	height: 1.8em;
	cursor: pointer;
}
.iconSelect > div > i {
	font-size: 1em;
	margin: 0.4em auto;
	display: table;
}
.uk-nestable-placeholder { float: left; float: left; width: 97%; margin-left: 15px; margin-right: 15px; }
.collapse { margin-top: 2rem; margin-bottom: 2rem; }
.togglePanel { cursor: pointer; background: #eeeeee; padding: 0px; margin-left: 15px; margin-right: 15px; width: 96.5%; }
.errorInput { border: 1px solid red; animation: errorInput 300ms ease-in-out 1500ms infinite; }
@keyframes errorInput {
    0% {
        border-color: rgba(255,0,0,0);
    }
    50% {
        border-color: rgba(255,0,0,1);
    }
    100% {
        border-color: rgba(255,0,0,0);
    }
}
</style>
<script type="text/template" id="tmpCreate">
	<li class="col-xs-12" data-field="{id}">
		<div class="uk-nestable-item">
			<input type="hidden" name="order[]" value="{id}">
			<input type="hidden" name="data[{id}][depth]" value="{depth}">
			<div class="row">
				<div class="col-xs-12 togglePanel" data-toggle="{id}">
					<div class="uk-nestable-handle"></div>
					<div data-nestable-action="toggle"></div>
					<div class="list-label hereTitle" data-hereTitle-id="{id}">Undefined</div>
					<div class="pull-right">
						<a href="#" class="btn btn-icon btn-red btn-single btn-sm" data-id="{id}" tabindex="-1"><i class="fa-remove"></i></a>
					</div>
				</div>
				<div class="col-xs-12 collapse form-horizontal" data-panel="{id}">
					<div class="col-xs-12">
						<div class="form-group">
							<label class="col-xs-12 col-md-3 control-label">Название поля</label>
							<div class="col-xs-12 col-md-9">
								<input type="text" class="form-control title" name="data[{id}][name]" placeholder="Введите название" required="required" data-input-id="{id}">
							</div>
						</div>
						<br>
						<div class="form-group">
							<label class="col-xs-12 col-md-3 control-label">Тип поля</label>
							<div class="col-xs-12 col-md-9">
								<select class="form-control selected" required="required" data-selectId="{id}" name="data[{id}][type]">
									<option value="" selected="selected" disabled="disabled">Выберите тип</option>
									<optgroup label="Числа">
										<option value="int">Целое число</option>
										<option value="float">Число с запятой</option>
										<option value="price">Цена</option>
									</optgroup>
									<optgroup label="Текст">
										<option value="varchar">Однострочный текст</option>
										<option value="email">E-mail</option>
										<option value="link">Ссылка</option>
										<option value="password">Пароль</option>
										<option value="onlytextareatext">Многострочный редактор текста</option>
										<option value="longtext">Визуальный редактор текста</option>
									</optgroup>
									<optgroup label="Картинки">
										<option value="image">Загрузка картинки</option>
										<option value="imageArray">Загрузка нескольких картинок</option>
									</optgroup>
									<optgroup label="Файлы">
										<option value="file">Загрузка файла</option>
										<option value="fileArray">Загрузка нескольких файлов</option>
									</optgroup>
									<optgroup label="Дата/время">
										<option value="date">Поле для ввода даты</option>
										<option value="time">Поле для ввода времени</option>
										<option value="datetime">Поле для ввода даты/времени</option>
										<option value="systime">Автоматическое установление времени</option>
									</optgroup>
									<optgroup label="Разное">
										<option value="array">Массив данных</option>
										<option value="hidden">Скрытое поле</option>
										<option value="radio">Радио-группа</option>
										<option value="linkToAdmin">Ссылка на другой раздел</option>
									</optgroup>
								</select>
							</div>
						</div>
						<div class="createAltName" data-altname="{id}"></div>
						<div class="col-xs-12 selectedInput hide" data-selectedInput="{id}"></div>
						<div class="form-group">
							<label class="col-xs-12 col-md-3 control-label">Подсказка</label>
							<div class="col-xs-12 col-md-9">
								<input type="text" class="form-control placeholder" name="data[{id}][placeholder]" placeholder="Введите подсказку">
							</div>
						</div>
						<br>
						<div class="form-group">
							<label class="col-xs-12 col-md-3 control-label">Дополнительные возможности</label>
							<div class="col-xs-12 col-md-9">
								<div class="checkbox">
									<label class="hides">
										<input type="checkbox" name="data[{id}][hideOnMain]" class="cbr cbr-primary" value="yes">Скрыть с главной
									</label>
								</div>
								<div class="checkbox">
									<label class="altname">
										<input type="checkbox" name="data[{id}][translate]" class="cbr cbr-primary" value="yes" data-id="{id}">Создать альтернативное имя
									</label>
								</div>
								<div class="checkbox">
									<label class="supportLang">
										<input type="checkbox" name="data[{id}][supportLang]" class="cbr cbr-primary" value="yes" data-id="{id}">Поддержка мультиязычности
									</label>
								</div>
								<div class="checkbox">
									<label class="repeater">
										<input type="checkbox" name="data[{id}][repeater]" class="cbr cbr-primary" value="yes" data-id="{id}" disabled="disabled">Возможность повторять (Скоро)
									</label>
								</div>
							</div>
						</div>
						<div class="col-xs-12 databased hide" data-hideId="{id}"></div>
						<div class="col-xs-12 col-md-2">
							<a href="#" class="btn btn-red remove" data-id="{id}" tabindex="-1">{L_"Удалить"}</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</li>
</script>
<script type="text/template" class="databaseSelectRadio">
	<br><label class="col-xs-12 col-md-6 text-center"><input type="radio" name="data[{id}][selectedData]" class="cbr cbr-primary" value="dataOnTable" onchange="selectedDatabaseChange(this);">Данные из базы данных</label>
	<label class="col-xs-12 col-md-6 text-center"><input type="radio" name="data[{id}][selectedData]" class="cbr cbr-primary" value="dataOnInput" onchange="selectedDatabaseChange(this);">Данные для ввода</label><br><br>
</script>
<script type="text/template" class="databaseTemplate">
	<br><select class="form-control dataBaseLoad" name="data[{id}][loadDB][name]" required="required" data-selectInputedData="{id}"><option></option>{data}</select>
	<br><div class="col-sm-12 dataBaseSelect" data-selectInputedData="{id}">
	</div>
</script>
<script type="text/template" class="databaseTemplateSelect">
	<div class="col-sm-6">
		<div class="col-sm-12">
			<b>Ключём будет:</b>
		</div>
		<div class="col-sm-12">
			<select class="form-control dataBaseLoadKey" name="data[{id}][loadDB][key]" required="required"><option></option>{data1}</select>
		</div>
	</div>
	<div class="col-sm-6">
		<div class="col-sm-12">
			<b>Значением будет:</b>
		</div>
		<div class="col-sm-12">
			<select class="form-control dataBaseLoadValue" name="data[{id}][loadDB][value]" required="required"><option></option>{data2}</select>
		</div>
	</div>
</script>
<script type="text/template" class="databaseInput">
	<div class="row inputedData" data-selectInputedData="{id}"></div>
	<a href="#" class="btn btn-success addInputDB pull-right" data-addInputDB="{id}">{L_"Добавить"}</a>
</script>
<script type="text/template" class="databaseInputedData">
	<div class="row">
		<div class="col-sm-12" data-inputedData="{id}">
			<div class="col-sm-10"><input type="text" class="form-control" name="data[{groupId}][field][{id}]" placeholder="Введите значение" required="required" value="{val}"></div>
			<div class="col-sm-2"><a href="#" class="btn btn-red btn-block removeData" data-id="{id}" tabindex="-1">{L_"Удалить"}</a></div>
		</div>
	</div>
</script>
<script type="text/template" class="inputTranslate">
	<div class="form-group">
		<label class="col-xs-12 col-md-3 control-label">Альтернативное имя</label>
		<div class="col-xs-12 col-md-9">
			<div class="col-sm-12"><input type="text" class="form-control" name="data[{id}][alttitle]" placeholder="" required="required"></div>
		</div>
	</div>
</script>
<script type="text/template" class="linkToInput">
	<div class="form-group">
		<label class="col-xs-12 col-md-3 control-label">Ссылка на другой раздел</label>
		<div class="col-xs-12 col-md-9">
			<input type="text" class="form-control" name="data[{id}][field][link]" placeholder="" required="required" value="{valLink}">
		</div>
	</div>
	<div class="form-group">
		<label class="col-xs-12 col-md-3 control-label">Название ссылки на другого раздела</label>
		<div class="col-xs-12 col-md-9">
			<input type="text" class="form-control" name="data[{id}][field][title]" placeholder="" required="required" value="{valTitle}">
		</div>
	</div>
</script>
<script type="application/json" id="json">{struct}</script>
<script type="text/javascript">
	var selectedData = {};
	var arrTranslate = {};
	var struct = document.getElementById("json").innerHTML;
	var iInputDB = 0;
	var i = 0;
	jQuery(document).ready(function($) {
		if(struct.length>0) {
			struct = JSON.parse(struct);
			if(typeof(struct.data)!=="undefined") {
				console.log("!!! BUILD !!!");
				jQuery(".formCreator input[type='hidden'].mode").val("edit");
				var titles = struct.data.title;
				jQuery(".formCreator .title").val(titles);
				delete struct.data.title;
				var icons = struct.data.icon;
				jQuery(".formCreator .iconSelect > input").val(icons);
				jQuery(".formCreator .iconSelect > div > i").addClass(icons);
				jQuery(".formCreator .iconSelect > div > i").attr("data-icon", icons);
				delete struct.data.icon;
				Object.keys(struct.data).forEach(function(k) {
					var dataField = struct.data[k];
					console.log(dataField);
					i++;
					var tmp = jQuery("#tmpCreate").html();
					var tpl = tmp;
					tpl = tpl.replace(/\{id\}/g, i);
					tpl = tpl.replace(/\{depth\}/g, (typeof(dataField.depth)==="undefined" ? "0" : dataField.depth));
					jQuery(".creator").append(tpl);
					jQuery("[data-field='"+i+"'] .hereTitle").html(dataField.name);
					jQuery("[data-field='"+i+"']").find(".title").val(dataField.name);
					jQuery("[data-field='"+i+"']").find(".placeholder").val(dataField.placeholder);
					jQuery("[data-field='"+i+"']").find("select").val(dataField.type);
					if(typeof(dataField.hideOnMain)!=="undefined" && dataField.hideOnMain=="yes") {
						jQuery("[data-field='"+i+"']").find("label.hides input[type='checkbox']").attr("checked", "checked");
					}
					if(typeof(dataField.supportLang)!=="undefined" && dataField.supportLang=="yes") {
						jQuery("[data-field='"+i+"']").find("label.supportLang input[type='checkbox']").attr("checked", "checked");
					}
					if(typeof(dataField.selectedData)!=="undefined" && dataField.selectedData=="dataOnInput") {
						var tmp = jQuery(".databaseSelectRadio").html();
						tmp = tmp.replace(/\{id\}/g, i);
						jQuery("[data-hideId='"+i+"']").removeClass('hide').html(tmp);
						jQuery("[data-hideId='"+i+"'] input[value='dataOnInput']").attr("checked", "checked");

						tmp = jQuery(".databaseInput").html();
						tmp = tmp.replace(/\{id\}/g, i);
						jQuery("[data-field='"+i+"'] .selectedInput[data-selectedInput='"+i+"']").removeClass('hide').html(tmp);
						Object.keys(dataField.field).forEach(function(key) {
							iInputDB++;
							var tmp = jQuery(".databaseInputedData").html();
							var tpl = tmp;
							tpl = tpl.replace(/\{groupId\}/g, i);
							tpl = tpl.replace(/\{id\}/g, iInputDB);
							tpl = tpl.replace(/\{val\}/g, dataField.field[key]);
							jQuery(".inputedData[data-selectInputedData='"+i+"']").append(tpl);
						});
					}
					if(typeof(dataField.selectedData)!=="undefined" && dataField.selectedData=="dataOnTable") {
						var tmp = jQuery(".databaseSelectRadio").html();
						tmp = tmp.replace(/\{id\}/g, i);
						jQuery("[data-hideId='"+i+"']").removeClass('hide').html(tmp);
						jQuery("[data-hideId='"+i+"'] input[value='dataOnTable']").attr("checked", "checked");

						var idE = i;
						
						jQuery.post("./?pages=Creator&loadTables=1", function(d) {
							tmp = jQuery(".databaseTemplate").html();
							tmp = tmp.replace(/\{id\}/g, idE);
							var datas = "";
							selectedData = JSON.parse(d);
							Object.keys(selectedData).forEach(function(k) {
								datas += "<option value='"+selectedData[k].name+"'>"+selectedData[k].name+"</option>";
							});
							tmp = tmp.replace(/\{data\}/g, datas);
							jQuery(".selectedInput[data-selectedInput='"+idE+"']").removeClass('hide').html(tmp);
							jQuery(".dataBaseLoad[data-selectInputedData='"+idE+"']").val(dataField.loadDB.name);

							console.log(".selectedInput[data-selectedInput='"+idE+"']");

							var tmp = jQuery(".databaseTemplateSelect").html();
							var datas = "";
							var data = selectedData[dataField.loadDB.name].fields;
							for(var i=0;i<data.length;i++) {
								datas += "<option value='"+data[i]+"'>"+data[i]+"</option>";
							}
							tmp = tmp.replace(/\{data1\}/g, datas);
							tmp = tmp.replace(/\{data2\}/g, datas);
							tmp = tmp.replace(/\{id\}/g, idE);
							jQuery(".dataBaseSelect[data-selectInputedData='"+idE+"']").html(tmp);
							jQuery(".dataBaseSelect[data-selectInputedData='"+idE+"'] .dataBaseLoadKey").val(dataField.loadDB.key);
							jQuery(".dataBaseSelect[data-selectInputedData='"+idE+"'] .dataBaseLoadValue").val(dataField.loadDB.value);
						});
					}
					if(dataField.type=="radio") {
						tmp = jQuery(".databaseInput").html();
						tmp = tmp.replace(/\{id\}/g, i);
						jQuery("[data-field='"+i+"'] .selectedInput[data-selectedInput='"+i+"']").removeClass('hide').html(tmp);
						Object.keys(dataField.field).forEach(function(key) {
							iInputDB++;
							var tmp = jQuery(".databaseInputedData").html();
							var tpl = tmp;
							tpl = tpl.replace(/\{groupId\}/g, i);
							tpl = tpl.replace(/\{id\}/g, iInputDB);
							tpl = tpl.replace(/\{val\}/g, dataField.field[key]);
							jQuery(".inputedData[data-selectInputedData='"+i+"']").append(tpl);
						});
					}
					if(dataField.type=="linkToAdmin") {
						tmp = jQuery(".linkToInput").html();
						tmp = tmp.replace(/\{id\}/g, i);
						tmp = tmp.replace(/\{valLink\}/g, dataField.field.link);
						tmp = tmp.replace(/\{valTitle\}/g, dataField.field.title);
						jQuery("[data-field='"+i+"'] .selectedInput[data-selectedInput='"+i+"']").removeClass('hide').html(tmp);
					}
					if(typeof(dataField.translate)!=="undefined" && typeof(dataField.alttitle)!=="undefined") {
						var tmp = jQuery(".inputTranslate").html();
						var tpl = tmp;
						tpl = tpl.replace(/\{id\}/g, i);
						jQuery(".createAltName[data-altname='"+i+"']").html(tpl);
						jQuery("[data-field='"+i+"']").find("label.altname input").attr("checked", "checked");
						jQuery(".createAltName[data-altname='"+i+"']").find("input").val(dataField.alttitle);
						arrTranslate[i] = true;
					}
					cbr_replace();
					jQuery("input[type='submit']").removeAttr("disabled");
				});
			}
		}
	});
	function selectedDatabaseChange(th) {
		var id = jQuery(th).parent().parent().parent().parent().attr("data-hideId");
		var tmp = "";
		if(th.value=="dataOnTable") {
			jQuery.post("./?pages=Creator&loadTables=1", function(d) {}).done(function(d) {
				tmp = jQuery(".databaseTemplate").html();
				tmp = tmp.replace(/\{id\}/g, id);
				var datas = "";
				selectedData = JSON.parse(d);
				Object.keys(selectedData).forEach(function(k) {
					datas += "<option value='"+selectedData[k].name+"'>"+selectedData[k].name+"</option>";
				});
				tmp = tmp.replace(/\{data\}/g, datas);
				jQuery(".selectedInput[data-selectedInput='"+id+"']").removeClass('hide').html(tmp);
			}).error(function(d) {

			});
		} else if(th.value=="dataOnInput") {
			tmp = jQuery(".databaseInput").html();
			tmp = tmp.replace(/\{id\}/g, id);
			jQuery(".selectedInput[data-selectedInput='"+id+"']").removeClass('hide').html(tmp);
		}
	}
	jQuery("body").on("click", ".addInputDB", function() {
		var id = jQuery(this).attr("data-addInputDB");
		iInputDB++;
		var tmp = jQuery(".databaseInputedData").html();
		var tpl = tmp;
		tpl = tpl.replace(/\{groupId\}/g, id);
		tpl = tpl.replace(/\{id\}/g, iInputDB);
		tpl = tpl.replace(/\{val\}/g, "");
		jQuery(".inputedData[data-selectInputedData='"+id+"']").append(tpl);
		return false;
	});
	jQuery("body").on("click", ".removeData", function() {
		var id = jQuery(this).attr("data-id");
		jQuery("[data-inputedData='"+id+"']").remove();
		return false;
	});
	jQuery("body").on("change", ".dataBaseLoad", function() {
		var id = jQuery(this).attr("data-selectInputedData");
		if(this.value=="") {
			jQuery(".dataBaseSelect[data-selectInputedData='"+id+"']").html();
		} else {
			//var data = 
			var tmp = jQuery(".databaseTemplateSelect").html();
			var datas = "";
			var data = selectedData[this.value].fields;
			for(var i=0;i<data.length;i++) {
				datas += "<option value='"+data[i]+"'>"+data[i]+"</option>";
			}
			tmp = tmp.replace(/\{data1\}/g, datas);
			tmp = tmp.replace(/\{data2\}/g, datas);
			tmp = tmp.replace(/\{id\}/g, id);
			jQuery(".dataBaseSelect[data-selectInputedData='"+id+"']").html(tmp);
		}
	});
	jQuery(".creator").on("change", ".selected", function() {
		var id = jQuery(this).attr("data-selectId");
		if(this.value=="array") {
			var tmp = jQuery(".databaseSelectRadio").html();
			tmp = tmp.replace(/\{id\}/g, id);
			jQuery("[data-hideId='"+id+"']").removeClass('hide').html(tmp);
			cbr_replace();
		} else if(this.value=="radio") {
			tmp = jQuery(".databaseInput").html();
			tmp = tmp.replace(/\{id\}/g, id);
			jQuery(".selectedInput[data-selectedInput='"+id+"']").removeClass('hide').html(tmp);
		} else if(this.value=="linkToAdmin") {
			tmp = jQuery(".linkToInput").html();
			tmp = tmp.replace(/\{id\}/g, id);
			tmp = tmp.replace(/\{valLink\}/g, "");
			tmp = tmp.replace(/\{valTitle\}/g, "");
			jQuery(".selectedInput[data-selectedInput='"+id+"']").removeClass('hide').html(tmp);
		} else {
			jQuery(".selectedInput[data-selectedInput='"+id+"']").addClass('hide').html("");
			jQuery("[data-hideId='"+id+"']").addClass('hide').html("");
		}
	});
	jQuery(".creator").on("click", ".remove", function() {
		var id = jQuery(this).attr("data-id");
		jQuery("[data-field='"+id+"']").remove();
		if(jQuery(".creator").html().trim().length==0) {
			jQuery("input[type='submit']").attr("disabled", "disabled");
		}
		return false;
	});
	jQuery(".addCreator").click(function() {
		i++;
		var tmp = $("#tmpCreate").html();
		var tpl = tmp;
		tpl = tpl.replace(/\{id\}/g, i);
		jQuery(".creator").append(tpl);
		cbr_replace();
		jQuery("input[type='submit']").removeAttr("disabled");
		return false;
	});
	jQuery("body").on("click", '.iconSelect', function() {
		var elem = this;
		jQuery.post("./?pages=Creator&list", function(data) {
			jQuery("#modal-3 .modal-body").html(data);
			jQuery('#modal-3').modal('show', {backdrop: 'fade'});
			jQuery('#modal-3').off("input").on("input", ".icon-find", function() {
				var val = jQuery(this).val();
				jQuery(".modal-body a").each(function(i, elem) {
					jQuery(elem).removeClass("hide");
					if(!(new RegExp(val, "g").test(jQuery(elem).attr("data-icon")))) {
						jQuery(elem).addClass("hide");
					}
				});
			});
			jQuery("#modal-3 .modal-body").css("overflow", "auto");
			jQuery("#modal-3 .modal-body").on("click", "a", function() {
				var icon = jQuery(this).attr("data-icon");
				if(icon.length>0) {
					icon = "fa-"+icon;
				}
				jQuery(elem).find("i").attr("class", "").addClass(icon);
				jQuery(elem).find("i").attr("data-icon", icon);
				jQuery(elem).find("input").val(icon);
				jQuery('#modal-3').modal('hide');
			});
		});
		return false;
	});
	jQuery("body").on("click", ".altname input", function() {
		var id = jQuery(this).attr("data-id");
		if(typeof(arrTranslate[id])!=="undefined") {
			jQuery(".createAltName[data-altname='"+id+"']").html("");
			delete arrTranslate[id];
		} else {
			var tmp = $(".inputTranslate").html();
			var tpl = tmp;
			tpl = tpl.replace(/\{id\}/g, id);
			jQuery(".createAltName[data-altname='"+id+"']").html(tpl);
			arrTranslate[id] = true;
		}
	});
	jQuery(".creator").off('nestable-stop').on('nestable-stop', function(ev) {
		var list = jQuery(this).data('nestable').list();
		var elems = jQuery(this).find('[data-field]');
		console.log(elems);
		for(var i=0;i<elems.length;i++) {
			jQuery(elems[i]).find("input[name*='order']").val((i+1));
			jQuery(elems[i]).find("input[name*='depth']").val(list[i].depth);
			console.log(list[i]);
		}
	});
	jQuery("body").on("click", ".togglePanel", function() {
		var id = jQuery(this).attr("data-toggle");
		jQuery("[data-panel]").each(function(i, elem) {
			if(jQuery(elem).attr("data-panel")!=id) {
				jQuery(elem).removeClass('in');
			}
		});
		jQuery("[data-panel='"+id+"']").toggleClass('in');
		return false;
	});
	jQuery("body").on("input change insert", "[data-input-id]", function() {
		var id = jQuery(this).attr("data-input-id");
		jQuery("[data-hereTitle-id='"+id+"']").html(this.value);
	});
	jQuery("input[required],textarea[required],select[required]").each(function(i, elem) {
		if(jQuery(elem).val()==null) {
			jQuery(elem).parent().parent().parent().parent().addClass("in");
			jQuery(elem).addClass("errorInput");
		}
	});
	jQuery("body").on("click", "input.errorInput,textarea.errorInput,select.errorInput", function() {
		jQuery(this).removeClass("errorInput");
	});
</script>