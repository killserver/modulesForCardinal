<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/temp/xenon/excel/excel.css?{S_time}" />
<label class="excel-load" ><div class="fa-cloud-upload" ></div></label>
<span class="excel-head" >
	<figure class="progress" ><i></i></figure>
	<a href="./?pages=ExcelModelAdmin&get&model={model_data}" target="_blank" class="btn btn-single btn-red">Скачать</a>
	<button onclick="excel.complete()" class="executeExcel hide">Выполнить</button>
	<label class="selectAllExcel hide"><input type="checkbox" onchange="excel.selected(this.checked)" /><span>Отметить всё</span></label>
</span>
<div class="excel-table" ></div>
<script>
	var model_data = '{model_data}';
	var loadExcelModel = './?pages=ExcelModelAdmin&save&model={model_data}'
	var notifiedSuccess = "/admincp.php/?pages=ExcelModelAdmin&update&model={model_data}"
</script>
<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/temp/xenon/excel/excel.js"></script>
<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/temp/xenon/excel/xlsx.full.min.js"></script>