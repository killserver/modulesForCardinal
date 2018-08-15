<table class="table table-bordered table-striped" id="example-2">
	<thead>
		<tr>
			<td colspan="6" align="right"><form method="post" action="{C_default_http_host}admincp.php/?pages=ErrorJS&delete"><button class="btn btn-red btn-icon btn-icon-standalone"><i class="fa-remove"></i><span>{L_"Удалить всё"}</span></button></form></td>
		</tr>
		<tr>
			<th width="100">Date/Time</th>
			<th width="110">Error Type</th>
			<th>Name</th>
			<th>File</th>
			<th width="150">IP</th>
		</tr>
	</thead>
	
	<tbody class="middle-align">
	[foreach block=logs]
		<tr class="{logs.errorno}">
			<td class="{logs.errorno}">{logs.time}</td>
			<td class="{logs.errorno}">{logs.errorno}</td>
			<td class="{logs.errorno}">{logs.error}</td>
			<td class="{logs.errorno}">{logs.path}</td>
			<td class="{logs.errorno}">{logs.ip}</td>
		</tr>
		<tr style="text-align:center;">
			<td colspan="6" class="subdata"><a href="javascript:;" onclick="getDescr(this);return false;" class="btn btn-block btn-single text-center">{L_descr}</a><div class="spoiler-body" style="display:none;">{logs.descr}</div></td>
		</tr>
		<tr><td colspan="6"></td></tr>
	[/foreach]
	</tbody>
</table>
<script type="text/javascript">
function getDescr(tt) {
	jQuery('#modal-4').modal('show', {backdrop: 'static'});
	var descr = jQuery(tt).parent().children('.spoiler-body').html();
	jQuery('#error-body').html("<pre>"+descr+"</pre>");
}
</script>
<style type="text/css">
	.subdata {
		padding: 0px !important;
	}
	td.error {
		background-color: #d5080f !important;
		color: #ffffff !important;
	}
	tr.error + tr > td,
	tr.error + tr > td a {
		background-color: #d5080f !important;
		color: #ffffff !important;
	}
	td.warn {
		background-color: #ffba00 !important;
		color: #ffffff !important;
	}
	tr.warn + tr > td,
	tr.warn + tr > td a {
		background-color: #ffba00 !important;
		color: #ffffff !important;
	}
	td.log {
		background-color: #40bbea !important;
		color: #ffffff !important;
	}
	tr.log + tr > td,
	tr.log + tr > td a {
		background-color: #40bbea !important;
		color: #ffffff !important;
	}
</style>