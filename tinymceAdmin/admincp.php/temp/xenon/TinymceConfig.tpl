<form name="form" role="form" class="form-horizontal" method="POST" enctype="multipart/form-data" >
	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<!--div class="form-group" >
						<label class="col-sm-2 control-label" for="field-1">Найменования настроек</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="name">
						</div>
					</div-->
					<div class="form-group" >
						<label class="col-sm-2 control-label" for="field-1">Язык</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="language">
						</div>
					</div>
					<div class="form-group" >
						<label class="col-sm-2 control-label" for="field-1">Селектор</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="selector">
						</div>
					</div>
					<div class="form-group" >
						<label class="col-sm-2 control-label" for="field-1">Высота</label>
						<div class="col-sm-10">
							<input type="text" class="form-control" name="height">
						</div>
					</div>
					<div class="form-group" >
						<label class="col-sm-2 control-label" for="field-1">Файловый менеджер</label>
						<div class="col-xs-1" >
							<input type="hidden" name="manager" value="off">
							<input type="checkbox" class="iswitch iswitch-info" value="on" name="manager">
						</div>
						<label class="col-sm-2 control-label" for="field-1">Меню-бар</label>
						<div class="col-xs-1" >
							<input type="hidden" name="menubar" value="off">
							<input type="checkbox" class="iswitch iswitch-info" value="on" name="menubar">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Плагины</h3>
				</div>
				<div class="panel-body">
					<div class="form-group" >
						<div class="col-xs-3" >
							[foreach block=block1]<div class="form-group" >
								<label class="col-sm-10 control-label" for="field-1">{block1.name}</label>
								<div class="col-sm-2">
									<input type="hidden" name="plugins[{block1.title}]" value="off">
									<input type="checkbox" class="iswitch iswitch-info" value="on" name="plugins[{block1.title}]"[foreachif {block1.val}] checked="checked"[/foreachif {block1.val}]>
								</div>
							</div>[/foreach]
						</div>
						<div class="col-xs-3" >
							[foreach block=block2]<div class="form-group" >
								<label class="col-sm-10 control-label" for="field-1">{block2.name}</label>
								<div class="col-sm-2">
									<input type="hidden" name="plugins[{block2.title}]" value="off">
									<input type="checkbox" class="iswitch iswitch-info" value="on" name="plugins[{block2.title}]"[foreachif {block2.val}] checked="checked"[/foreachif {block2.val}]>
								</div>
							</div>[/foreach]
						</div>
						<div class="col-xs-3" >
							[foreach block=block3]<div class="form-group" >
								<label class="col-sm-10 control-label" for="field-1">{block3.name}</label>
								<div class="col-sm-2">
									<input type="hidden" name="plugins[{block3.title}]" value="off">
									<input type="checkbox" class="iswitch iswitch-info" value="on" name="plugins[{block3.title}]"[foreachif {block3.val}] checked="checked"[/foreachif {block3.val}]>
								</div>
							</div>[/foreach]
						</div>
						<div class="col-xs-3" >
							[foreach block=block4]<div class="form-group" >
								<label class="col-sm-10 control-label" for="field-1">{block4.name}</label>
								<div class="col-sm-2">
									<input type="hidden" name="plugins[{block4.title}]" value="off">
									<input type="checkbox" class="iswitch iswitch-info" value="on" name="plugins[{block4.title}]"[foreachif {block4.val}] checked="checked"[/foreachif {block4.val}]>
								</div>
							</div>[/foreach]
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-heading">
					<h3 class="panel-title">Дополнительно</h3>
				</div>
				<div class="panel-body">
					<!--div class="col-xs-12" >
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="field-1">Использовать стили</label>
							<div class="col-6" >
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="content_css[]" />
							</div>
							<div class="col-2" ><label class="label-control"><button type="button" class="button" id="add-css" ><i class="fa fa-plus" ></i></button></label></div>
						</div>
						<div id="css-additionally" ></div>
					</div--> 
			
					<div class="col-xs-12" >
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="field-1">Валидация елементов</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="valid_elements" />
							</div>
						</div>
					</div>
					<div class="col-xs-12" >
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="field-1">Принудительное создание контейнера</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="forced_root_block" />
							</div>
						</div>
					</div>
					<div class="col-xs-12" >
						<div class="form-group" >
							<label class="col-sm-2 control-label" for="field-1">Валидация дочерних елементов</label>
							<div class="col-sm-10">
								<input type="text" class="form-control" name="valid_children" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Вкладка изображенния</label>
							<div class="col-sm-2">
								<input type="hidden" name="image_advtab" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="image_advtab" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Очистка</label>
							<div class="col-sm-2">
								<input type="hidden" name="cleanup" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="cleanup" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Валидация html</label>
							<div class="col-sm-2">
								<input type="hidden" name="verify_html" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="verify_html" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Очистка при запуске</label>
							<div class="col-sm-2">
								<input type="hidden" name="cleanup_on_startup" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="cleanup_on_startup" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Валидация дочерних елементов</label>
							<div class="col-sm-2">
								<input type="hidden" name="validate_children" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="validate_children" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Удалить лишнии переносы</label>
							<div class="col-sm-2">
								<input type="hidden" name="remove_redundant_brs" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="remove_redundant_brs" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Удалить разрывы строк</label>
							<div class="col-sm-2">
								<input type="hidden" name="remove_linebreaks" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="remove_linebreaks" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Создавать параграф на новой линии</label>
							<div class="col-sm-2">
								<input type="hidden" name="force_p_newlines" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="force_p_newlines" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Создавать перенос на новой линии</label>
							<div class="col-sm-2">
								<input type="hidden" name="force_br_newlines" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="force_br_newlines" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Валидация</label>
							<div class="col-sm-2">
								<input type="hidden" name="validate" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="validate" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Фиксировать элементы таблицы</label>
							<div class="col-sm-2">
								<input type="hidden" name="fix_table_elements" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="fix_table_elements" />
							</div>
						</div>
					</div>
					<div class="col-xs-3" >
						<div class="form-group" >
							<label class="col-sm-10 control-label" for="field-1">Фиксировать элементы списка</label>
							<div class="col-sm-2">
								<input type="hidden" name="fix_list_elements" value="off">
								<input type="checkbox" class="iswitch iswitch-info" value="on" name="fix_list_elements" />
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="row">
		<div class="col-sm-12">
			<div class="panel panel-default">
				<div class="panel-body">
					<button class="btn btn-blue btn-icon btn-icon-standalone btn-icon-standalone-right btn-sm">
						<i class="fa-save"></i>
						<span>{L_save}</span>
					</button>
				</div>
			</div>
		</div>
	</div>
</form>
<script type="text/javascript">
var config = '{tinymceConfigPage}';
config = JSON.parse(config);
Object.keys(config).forEach(function(key) {
	$("input[name*='"+key+"']").each(function(i, elem) {
		if($(elem).attr("type")=="checkbox") {
			if(config[key]===true) {
				$(elem).attr("checked", "checked");
			}
		} else {
			$(elem).val(config[key]);
		}
	});
});
$(document).ready(function() {
	$("#add-css").click(function(){
		$("#css-additionally").append(tpl);
		$(".css-del").click(function(){
			$(this).parent().parent().parent().remove();
		});
	});
});

tpl = '<div class="form-group" ><div class="col-10" ><input type="text" class="form-control" name="content_css[]" /></div><div class="col-2" ><label class="label-control"><button  type="button" class="button css-del" ><i class="fa fa-trash" ></i></button></label></div></div>';

$(document).ready(function() {
	if(typeof(JSON_CSS) != "undefined"){
		for(var i = 0; i < JSON_CSS.length; i++){
			tpl_ = '<div class="form-group" ><div class="col-10" ><input type="text" class="form-control" value="'+JSON_CSS[i]+'" name="additionally[css-content][]" /></div><div class="col-2" ><label class="label-control"><button  type="button" class="button css-del" ><i class="fa fa-trash" ></i></button></label></div></div>';
			$("#css-additionally").append(tpl_);
		}
		$(".css-del").click(function(){
			$(this).parent().parent().parent().remove();
		});
	}
}); 
</script>