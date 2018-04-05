<center><a href="./?pages=Creator&mod=Add" class="btn btn-secondary">{L_add}</a></center>
<form method="post" action="./?pages=Creator&mod=MultiAction">
	<table id="example-1" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				<th><label class="checkbox"><input type="checkbox" class="cbr deleteAll"></label></th>
				<th>Раздел</th>
				<th>{L_options}</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				<th><label class="checkbox"><input type="checkbox" class="cbr deleteAll"></label></th>
				<th>Раздел</th>
				<th>{L_options}</th>
			</tr>
			<tr><td colspan="3"><div class="row"><div class="col-sm-offset-9"><div class="col-sm-7"><select name="action" class="form-control" style="width:100%;"><option value="">{L_"Выберите действие"}</option><option value="delete">{L_delete}</option></select></div><div class="col-sm-5"><input type="submit" class="btn btn-purple" value="{L_"Выполнить"}"></div></div></div></td></tr>
		</tfoot>
		<tbody>
		[foreach block=creator]<tr>
			<td><label class="checkbox"><input type="checkbox" class="cbr" name="delete[]" value="{creator.table}"></label></td>
			<td>{L_"{creator.table}"}</td>
			<td>
				<a href="./?pages=Creator&mod=Edit&name={creator.table}" class="btn btn-turquoise">{L_edit}</a><br>
				<a href="./?pages=Creator&mod=Delete&name={creator.table}" onclick="return confirmDelete();" class="btn btn-red">{L_delete}</a>
			</td>
		</tr>[/foreach]
		</tbody>
	</table>
</form>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#example-1").dataTable({
		language: {
			"processing": "{L_"Подождите"}...",
			"search": "{L_"Поиск"}:",
			"lengthMenu": "{L_"Показать"} _MENU_ {L_"записей"}",
			"info": "{L_"Записи с"} _START_ {L_"до"} _END_ {L_"из"} _TOTAL_ {L_"записей"}",
			"infoEmpty": "{L_"Записи с"} 0 {L_"до"} 0 {L_"из"} 0 {L_"записей"}",
			"infoFiltered": "({L_"отфильтровано"} {L_"из"} _MAX_ {L_"записей"})",
			"infoPostFix": "",
			"loadingRecords": "{L_"Загрузка записей"}...",
			"zeroRecords": "{L_"Записи отсутствуют"}.",
			"emptyTable": "{L_"В таблице отсутствуют данные"}",
			"paginate": {
				"first": "{L_"Первая"}",
				"previous": "{L_"Предыдущая"}",
				"next": "{L_"Следующая"}",
				"last": "{L_"Последняя"}"
			},
			"aria": {
				"sortAscending": ": {L_"активировать для сортировки столбца по возрастанию"}",
				"sortDescending": ": {L_"активировать для сортировки столбца по убыванию"}"
			}
		},
		aLengthMenu: [
			[10, 25, 50, 100, -1], [10, 25, 50, 100, "{L_"Всё"}"]
		],
		"aoColumnDefs": [{
			'bSortable': false,
			'aTargets': [
				0, 2
			]
		}],
		"order": [[ 0, false ]]
	});
	var arrToSave = {};
	var linkForAutoSave = encodeURIComponent(window.location.href.split(default_admin_link)[1])+"&v=1";
	if(localStorage.getItem(linkForAutoSave)===null) {
		$("[aria-controls='example-1'],.yadcf-filter").each(function(i, elem) {
			if(elem.nodeName!=="TH"&&elem.nodeName!=="LI") {
				arrToSave[elem.nodeName.toLowerCase()+"[aria-controls='"+$(elem).attr("aria-controls")+"']"] = elem.value;
			}
		});
		localStorage.setItem(linkForAutoSave, JSON.stringify(arrToSave));
		Object.keys(arrToSave).forEach(function(k) {
			$(k).bind("change input", function() {
				arrToSave[k] = $(this).val();
				localStorage.setItem(linkForAutoSave, JSON.stringify(arrToSave));
			});
		});
	} else {
		var strForAutoSave = localStorage.getItem(linkForAutoSave);
		arrToSave = JSON.parse(strForAutoSave);
		Object.keys(arrToSave).forEach(function(k) {
			$(k).val(arrToSave[k]).change().keyup();
			$(k).bind("change input", function() {
				arrToSave[k] = $(this).val();
				localStorage.setItem(linkForAutoSave, JSON.stringify(arrToSave));
			});
		});
	}
	jQuery(".deleteAll").click(function() {
		jQuery("label.checkbox").click();
	});
	cbr_replace();
});
function confirmDelete() {
	if (confirm("{L_"Вы подтверждаете удаление?(Данную операцию невозможно будет обратить)"}")) {
		return true;
	} else {
		return false;
	}
}
</script>