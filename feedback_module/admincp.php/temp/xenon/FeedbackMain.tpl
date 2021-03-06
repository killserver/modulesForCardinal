[if {C_disableAdd}!=1]<center><a href="./?pages=Feedback_form&pageType=Add" class="btn btn-secondary">{L_add}</a></center>[/if {C_disableAdd}!=1]
<form method="post" action="./?pages=Feedback_form&pageType=MultiAction">
	<table id="example-1" class="table table-striped table-bordered" cellspacing="0" width="100%">
		<thead>
			<tr>
				[if {C_disableMassAction}!=1]<th><label class="checkbox"><input type="checkbox" class="cbr deleteAll"></label></th>[/if {C_disableMassAction}!=1]
				<th>{L_"ID"}</th>
				<th>{L_"Название"}</th>
				<th>{L_"Кому отправлять"}</th>
				<th>{L_"Опции"}</th>
			</tr>
		</thead>
		<tfoot>
			<tr>
				[if {C_disableMassAction}!=1]<th><label class="checkbox"><input type="checkbox" class="cbr deleteAll"></label></th>[/if {C_disableMassAction}!=1]
				<th>{L_"ID"}</th>
				<th>{L_"Название"}</th>
				<th>{L_"Кому отправлять"}</th>
				<th>{L_"Опции"}</th>
			</tr>
			[if {C_disableMassAction}!=1]<tr><td colspan="5"><div class="row"><div class="col-sm-offset-9"><div class="col-sm-7"><select name="action" class="form-control" style="width:100%;"><option value="">{L_"Выберите действие"}</option><option value="delete">{L_"Удалить"}</option></select></div><div class="col-sm-5"><input type="submit" class="btn btn-purple" value="{L_"Выполнить"}"></div></div></div></td></tr>[/if {C_disableMassAction}!=1]
		</tfoot>
		<tbody>
		[foreach block=feedback]<tr>
			[if {C_disableMassAction}!=1]<td><label class="checkbox"><input type="checkbox" class="cbr" name="delete[]" value="{feedback.filename}"></label></td>[/if {C_disableMassAction}!=1]
			<td>{feedback.$id}</td>
			<td>{feedback.name}</td>
			<td>{feedback.address}</td>
			<td>
				<a href="javascript:;" onclick="showCode('{feedback.filename}');return false;" class="btn btn-success btn-block">{L_"Код"}</a>
				<a href="./?pages=Feedback_form&pageType=Copy&viewId={feedback.filename}" class="btn btn-turquoise btn-block">{L_"Клонировать"}</a>
				<a href="./?pages=Feedback_form&pageType=Edit&viewId={feedback.filename}" class="btn btn-edit btn-block">{L_"Редактировать"}</a>
				<a href="./?pages=Feedback_form&pageType=Delete&viewId={feedback.filename}" onclick="return confirmDelete();" class="btn btn-red btn-block">{L_"Удалить"}</a>
			</td>
		</tr>[/foreach]
		</tbody>
	</table>
</form>
<script type="text/javascript">
function showCode(file) {
	jQuery('#modal-4 .modal-title').html('{L_"Код для вывода формы на сайте"}');
	jQuery('#modal-4 .modal-body').html('<small style="width:100%;display:block;text-align:center;">{L_"Расположите в удобном для Вас месте сайта следующий код"}</small><br><input type="text" onclick="this.select();" readonly value=\'&#123;include module="feedback&file='+file+'"}\' style="border:0;width:100%;outline:0;text-align:center;">').css("height", "auto");
	jQuery('#modal-4 .modal-footer button').html('{L_"Закрыть"}');
	jQuery('#modal-4').modal('show', {backdrop: 'fade'});
	setTimeout(function() {
		jQuery('#modal-4 .modal-body input').click()
	}, 200);
}
jQuery(document).ready(function() {
	var dTable = jQuery("#example-1").dataTable({
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
				0, 4
			]
		}],
		"order": [[ 0, false ], [ 1, "asc" ]]
	});
	var sorted = [];
	if(sorted.length>0) {
		for(var i=0;i<sorted.length;i++) {
			var th = $("table#example-1").find('th');
			var getId = -1;
			var count = 0;
			th.each(function(is, k) {
				if($(k).attr("data-AltName") == sorted[i] && getId===-1) {
					getId = count;
					return;
				}
				count++;
			});
			dTable.yadcf([{column_number: getId}]);
		}
	}
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
	if(typeof($.fn.editableform)!=="undefined") {
		console.log("Test");
		$.fn.editableform.buttons = '<button type="submit" class="btn btn-primary btn-sm editable-submit"><i class="fa fa-check"></i></button><button type="button" class="btn btn-default btn-sm editable-cancel"><i class="fa fa-close"></i></button>';
		$('.quickEdit span').editable({
			url: '{C_default_http_local}{D_ADMINCP_DIRECTORY}/?pages=Feedback_form&pageType=QuickEdit&Save=true',
			validate: function(value) {
				if($.trim(value) == '') {
					return '{L_"Данное поле не может быть пустым"}';
				}
			}
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