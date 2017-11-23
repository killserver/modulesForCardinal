
			<div class="row">
				
				[if {is_new}==1]<a href="{C_default_http_host}admincp.php/?pages=Updaters" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12">
					<div class="tile-stats tile-red">
						<div class="icon"><i class="linecons-params"></i></div>
						<h3>{L_new_version}</h3>
						<p class="num">{new_version}</p>
					</div>
				</a>[/if {is_new}==1]

				[if {showLoads}==1]{include templates="MainServerLoad"}[/if {showLoads}==1]
				
				<span id="cache" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12" style="[if {clearCacheAll}==0]display:none;[/if {clearCacheAll}==0][if {clearCacheData}==1]display:block;[/if {clearCacheData}==1][if {clearCacheData}==0]display:none;[/if {clearCacheData}==0]">
					
					<div class="tile-stats tile-purple" data-count=".num" data-from="0" data-to="{CacheSize}" data-suffix="{CacheSizeS}" data-duration="3" data-easing="false">
						<div class="icon"><i class="linecons-inbox"></i></div>
						<h3>{L_"Cache Data"}</h3>
						<p class="num">{Cache}</p>
					</div>
				
				</span>
				
				<span id="cachephp" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12" style="[if {clearCacheAll}==0]display:none;[/if {clearCacheAll}==0][if {clearCacheTmp}==1]display:block;[/if {clearCacheTmp}==1][if {clearCacheTmp}==0]display:none;[/if {clearCacheTmp}==0]">
					
					<div class="tile-stats tile-purple" data-count=".num" data-from="0" data-to="{CachePHPSize}" data-suffix="{CachePHPSizeS}" data-duration="3" data-easing="false">
						<div class="icon"><i class="linecons-inbox"></i></div>
						<h3>{L_"Cache Templates"}</h3>
						<p class="num">{CachePHP}</p>
					</div>
				
				</span>
				
				<span id="debug" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12"[if {debugpanelshow}==0] style="display:none;"[/if {debugpanelshow}==0]>
					
					<div class="tile-stats tile-orange">
						<div class="icon"><i class="fa-cogs"></i></div>
						<h3>{L_"Debug Panel"}</h3>
						<p class="num">{L_"Активировать"}</p>
					</div>
				
				</span>
			
				<div class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12"[if {uptime_visible}==false] style="display:none;"[/if {uptime_visible}==false]>
					
					<div class="tile-stats tile" data-count=".num" data-from="0" data-to="{uptime_value}" data-suffix="%" data-duration="2">
						<div class="icon"><i class="linecons-cloud"></i></div>
						<h3>{L_"Server uptime"}</h3>
						<p class="num">{uptime_value}%</p>
					</div>
					
				</div>
				
				<a href="{C_default_http_host}admincp.php/?pages=Users" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12"[if {isUsers}==1] style="display:none;"[/if {isUsers}==1]>
					
					<div class="tile-stats tile-blue" data-count=".num" data-from="0" data-to="{users}" data-duration="3" data-easing="false">
						<div class="icon"><i class="linecons-user"></i></div>
						<h3>{L_"Users Total"}</h3>
						<p class="num">{users}</p>
					</div>
				
				</a>
				
				[if {is_messagesAdmin}==1]
				<div class="col-sm-12">
					<div class="panel panel-info">
						<div class="panel-heading"><div class="panel-title">{L_"Рекомендации от"}&nbsp;<b>Cardinal Engine</b></div></div>
						<div class="panel-body">
							<div class="scrollable" data-height="200">
								{messagesAdmin}
							</div>
						</div>
					</div>
				</div>
				[/if]
				
				[if {is_new}==1]
				<div class="col-sm-12">
					<div class="panel panel-warning">
						<div class="panel-heading"><div class="panel-title">{L_list_changelog}</div></div>
						<div class="panel-body">
							<div class="scrollable" data-height="200">
								{changelog}
							</div>
						</div>
					</div>
				</div>
				[/if]
			</div>
<script type="text/javascript">
jQuery(document).ready(function() {
	jQuery("#cache").click(function() {
		jQuery.post("./?pages=Main&clear&cache", function(data) {
			toastr.info(data, "{L_"Clear Cache Data"}");
		});
	});
	jQuery("#cachephp").click(function() {
		jQuery.post("./?pages=Main&clear&tmp", function(data) {
			toastr.info(data, "{L_"Clear Cache Templates"}");
		});
	});
	if({debugPanel}==1) {
		jQuery("#debug .num").html("{L_"Деактивировать"}");
	}
	jQuery("#debug").click(function() {
		jQuery.post("./?pages=Main&debugPanel=true", function(data) {
			var states;
			if(jQuery("#debug .num").html()=="{L_"Активировать"}") {
				jQuery("#debug .num").html("{L_"Деактивировать"}");
				states = "{L_"деактивирована"}";
			} else {
				jQuery("#debug .num").html("{L_"Активировать"}");
				states = "{L_"активирована"}";
			}
			toastr.info(data, "{L_"Debug Panel"} "+states);
		});
	});
});
</script>