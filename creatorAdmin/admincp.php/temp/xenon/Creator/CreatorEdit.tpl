<div class="row">
	<div class="col-md-12">
		<form role="form" class="form-horizontal formCreator" method="post">
			<input type="hidden" class="mode" name="mode" value="add">
			<div class="panel panel-default">
				<div class="panel-heading"><div class="col-sm-11"><input type="text" class="form-control title" name="data[title]" placeholder="Введите название раздела" required="required"></div><div class="col-sm-1"><div class="iconSelect"><input type="hidden" name="data[icon]" class="icons"><div><i class="" data-icon=""></i></div></div></div></div>
				<div class="panel-body">
					<div class="creator"></div>
					<input type="submit" class="btn btn-success" value="{L_submit}" disabled="disabled">
					<a href="#" class="btn btn-info addCreator pull-right">{L_add}</a>
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
</style>
<script type="text/template" id="tmpCreate">
	<div class="col-sm-12" data-field="{id}">
		<div class="col-sm-12 col-md-6"><input type="text" class="form-control" name="data[{id}][name]" placeholder="Введите имя" required="required"><br><label><input type="checkbox" name="data[{id}][hideOnMain]" class="cbr cbr-primary" value="yes">Скрыть с главной</label>&nbsp;&nbsp;&nbsp;&nbsp;<label class="altname"><input type="checkbox" name="data[{id}][translate]" class="cbr cbr-primary" value="yes" data-id="{id}">Создать альтернативное имя</label><br><div class="createAltName" data-altname="{id}"></div></div>
		<div class="col-sm-12 col-md-4">
			<select class="form-control selected" required="required" data-selectId="{id}" name="data[{id}][type]">
				<option value="" selected="selected" disabled="disabled">Выберите тип</option>
				<optgroup label="Числа">
					<option value="int">Целое число</option>
					<option value="float">Число с запятой</option>
					<option value="price">Цена</option>
				</optgroup>
				<optgroup label="Текст">
					<option value="varchar">Однострочный текст</option>
					<option value="longtext">Многострочный редактор текста</option>
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
					<option value="array" disabled="disabled">Массив данных (Скоро)</option>
					<option value="hidden">Скрытое поле</option>
				</optgroup>
			</select>
			<div class="col-sm-12 databased hide" data-hideId="{id}"></div>
		</div>
		<div class="col-sm-12 col-md-2"><a href="#" class="btn btn-red remove" data-id="{id}" tabindex="-1">{L_delete}</a></div>
		<div class="col-sm-12 selectedInput hide" data-selectedInput="{id}"></div>
		<hr class="col-sm-12">
	</div>
</script>
<script type="text/template" class="databaseSelectRadio">
	<br><label><input type="radio" name="data[{id}][selectedData]" class="cbr cbr-primary" value="dataOnTable" onchange="selectedDatabaseChange(this);">Данные из базы данных</label><br>
	<label><input type="radio" name="data[{id}][selectedData]" class="cbr cbr-primary" value="dataOnInput" onchange="selectedDatabaseChange(this);">Данные для ввода</label><br><br>
</script>
<script type="text/template" class="databaseTemplate">
	<select class="form-control dataBaseLoad" name="data[{id}][loadDB]" required="required" data-selectInputedData="{id}"><option></option>{data}</select>
	<div class="col-sm-12 dataBaseSelect" data-selectInputedData="{id}">
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
	<a href="#" class="btn btn-success addInputDB">{L_add}</a>
</script>
<script type="text/template" class="databaseInputedData">
	<div class="row">
		<div class="col-sm-12" data-inputedData="{id}">
			<div class="col-sm-10"><input type="text" class="form-control" name="data[{groupId}][field][{id}]" placeholder="Введите значение" required="required"></div>
			<div class="col-sm-2"><a href="#" class="btn btn-red removeData" data-id="{id}" tabindex="-1">{L_delete}</a></div>
		</div>
	</div>
</script>
<script type="text/template" class="inputTranslate">
	<div class="row">
		<div class="col-sm-12"><input type="text" class="form-control" name="data[{id}][alttitle]" placeholder="Введите имя для альтернативного имени" required="required"></div>
	</div>
</script>
<script type="text/javascript">
	var selectedData = {};
	var arrTranslate = {};
	var struct = '{struct}';
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
					jQuery(".creator").append(tpl);
					jQuery("[data-field='"+i+"']").find("input[type='text']").val(dataField.name);
					jQuery("[data-field='"+i+"']").find("select").val(dataField.type);
					if(typeof(dataField.hideOnMain)!=="undefined" && dataField.hideOnMain=="yes") {
						jQuery("[data-field='"+i+"']").find("input[type='checkbox']").attr("checked", "checked");
					}
					if(typeof(dataField.translate)!=="undefined" && typeof(dataField.alttitle)!=="undefined") {
						var tmp = $(".inputTranslate").html();
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
		var id = jQuery(this).parent().attr("data-selectedInput");
		iInputDB++;
		var tmp = jQuery(".databaseInputedData").html();
		var tpl = tmp;
		tpl = tpl.replace(/\{groupId\}/g, id);
		tpl = tpl.replace(/\{id\}/g, iInputDB);
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
</script>