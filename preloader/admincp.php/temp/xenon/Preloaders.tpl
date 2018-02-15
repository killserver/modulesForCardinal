<div class="preloaders row">
	<div class="col-sm-12">
		<a href="#" class="btn btn-red pull-right" data-id="-1">Убрать предзагрузчик</a>
	</div>
	[for 1 to {maxPreloaders}]<div class="col-sm-12">
		<b>Preloader {id}</b>
		<a href="#" class="btn btn-success pull-right" data-id="{id}">Выбрать</a>
		<br>
		<iframe src="{C_default_http_local}skins/preloader/loader-{id}.html" width="100%" height="200" frameborder="0"></iframe>
	</div>[/for]
</div>
<script type="text/javascript">
jQuery(document).ready(function($) {
	jQuery(".btn[data-id]").bind('click', function(event) {
		var id = jQuery(this).attr("data-id");
		jQuery.post("./?pages=Preloaders&save="+id, function(d) {
			if(id=="-1") {
				toastr.info("Прелоадер успешно деактивирован", "Переключение прелоадера");
			} else {
				toastr.info("Активирован прелоадер №"+id, "Переключение прелоадера");
			}
		});
		return false;
	});
});
</script>