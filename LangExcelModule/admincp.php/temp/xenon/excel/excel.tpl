<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/temp/xenon/excel/excel.css?{S_time}" />

<label class="excel-load" ><div class="fa-cloud-upload" ></div></label>
<div class="excel-head" >
	<figure class="progress" ><i></i></figure>
	<button onclick="excel.complete()" >Выполнить</button>
	<label><input type="checkbox" onchange="excel.selected(this.checked)" /><span>Отметить</span></label>
</div>
<div class="excel-head" style="display:flex;flex-wrap:wrap;justify-content: space-between;">
	<span><a href="#" onclick="return showSupportLangs()">Поддерживаемые языки</a></span>
	<span>
		<figure style="margin-left:auto;"></figure>
		[foreach block=langList]<a href="./?pages=LangExcel&get={langList.lang}" target="_blank" >Скачать {langList.langer}</a>&nbsp;[/foreach]
		[foreach block=langList]<a href="./?pages=LangExcel&get={langList.lang}&templateOnly=1" target="_blank" >Скачать {langList.langer} из шаблона</a>[/foreach]
	</span>
</div>
<div class="excel-table" ></div>

<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/temp/xenon/excel/excel.js"></script>
<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/temp/xenon/excel/xlsx.full.min.js"></script>
<script type="application/json" id="supportsLang">{json}</script>
<script>
	var json = document.getElementById("supportsLang").innerHTML;
	json = JSON.parse(json)
	function showSupportLangs() {
		$("#modal-4").modal("show")
		$("#modal-4 .modal-header").remove();
		var tpl = "";
		tpl += '<div><input type="text" class="form-control findLanguage" placeholder="Введите интересующий язык"></div>';
		tpl += '<div style="display: flex;flex-direction: column;margin-top: 1em;" class="supportsLangModal">';
		Object.keys(json).forEach(function(key) {
		    tpl += '<div style="display: flex;"><div style="width: 30px;height: 30px;display: flex;align-items: center;justify-content: center;border: 1px solid rgba(0,0,0,0.5);">'+key+'</div><div style="display: flex;align-items: center;justify-content: center;border: 1px solid rgba(0,0,0,0.5);width: 100%;margin-left: -1px;">'+json[key]+'</div></div>';
		});
		tpl += '</div>';
		$("#modal-4 .modal-body").html(tpl)
		return false;
	}
	$("body").on("input", ".findLanguage", function() {
		var tpl = '';
		var val = this.value;
		Object.keys(json).forEach(function(key) {
		    if(json[key].match(new RegExp(val, "gi")))
		    tpl += '<div style="display: flex;"><div style="width: 30px;height: 30px;display: flex;align-items: center;justify-content: center;border: 1px solid rgba(0,0,0,0.5);">'+key+'</div><div style="display: flex;align-items: center;justify-content: center;border: 1px solid rgba(0,0,0,0.5);width: 100%;margin-left: -1px;">'+json[key]+'</div></div>';
		});
		$("#modal-4 .modal-body .supportsLangModal").html(tpl)
	});
</script>