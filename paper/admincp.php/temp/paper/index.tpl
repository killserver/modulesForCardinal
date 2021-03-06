
			<div class="row">
				
                [if {is_new}==new]<a href="{C_default_http_host}admincp.php/?pages=Updaters" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-warning text-center">
                                        <i class="linecons-params"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        {new_version}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="ti-reload"></i> {L_new_version}
                            </div>
                        </div>
                    </div>
                </a>[/if {is_new}==new]

				[if {showLoads}==1]{include templates="MainServerLoad"}[/if {showLoads}==1]


                <span id="cache" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12" style="[if {clearCacheAll}==0]display:none;[/if {clearCacheAll}==0][if {clearCacheData}==1]display:block;[/if {clearCacheData}==1][if {clearCacheData}==0]display:none;[/if {clearCacheData}==0]">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-warning text-center">
                                        <i class="linecons-inbox"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        {Cache}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="linecons-inbox"></i> {L_"Cache Data"}
                            </div>
                        </div>
                    </div>
				</span>
				
                <span id="cachephp" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12" style="[if {clearCacheAll}==0]display:none;[/if {clearCacheAll}==0][if {clearCacheTmp}==1]display:block;[/if {clearCacheTmp}==1][if {clearCacheTmp}==0]display:none;[/if {clearCacheTmp}==0]">
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-warning text-center">
                                        <i class="linecons-inbox"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers">
                                        {CachePHP}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="linecons-inbox"></i> {L_"Cache Templates"}
                            </div>
                        </div>
                    </div>
				</span>
				
                <span id="debug" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12"[if {debugpanelshow}==0] style="display:none;"[/if {debugpanelshow}==0]>
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-warning text-center">
                                        <i class="fa fa-cogs"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers num">
                                        {L_"Активировать"}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="fa fa-cogs"></i> {L_"Debug Panel"}
                            </div>
                        </div>
                    </div>
				</span>
			
                <div class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12"[if {uptime_visible}==false] style="display:none;"[/if {uptime_visible}==false]>
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-warning text-center">
                                        <i class="linecons-cloud"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers num">
                                        {uptime_value}%
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="linecons-cloud"></i> {L_"Users Total"}
                            </div>
                        </div>
                    </div>
				</div>
				
				<a href="{C_default_http_host}admincp.php/?pages=Users" class="col-md-[if {C_FullMenu}==1]4[/if {C_FullMenu}==1][if {C_FullMenu}!=1]3[/if {C_FullMenu}!=1] col-sm-12"[if {isUsers}==1] style="display:none;"[/if {isUsers}==1]>
                    <div class="card">
                        <div class="card-content">
                            <div class="row">
                                <div class="col-xs-5">
                                    <div class="icon-big icon-warning text-center">
                                        <i class="linecons-user"></i>
                                    </div>
                                </div>
                                <div class="col-xs-7">
                                    <div class="numbers num">
                                        {users}
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <hr>
                            <div class="stats">
                                <i class="linecons-user"></i> {L_"Users Total"}
                            </div>
                        </div>
                    </div>
				</a>

				{contentForAdmin}
				
				[if {is_messagesAdmin}==1]
				<div class="col-sm-12">
					<div class="panel panel-info white">
						<div class="panel-heading"><div class="panel-title">{L_"Рекомендации от"}&nbsp;<b>Cardinal Engine</b></div></div>
						<div class="panel-body">
							<div class="scrollable" data-height="200">
								{messagesAdmin}
							</div>
						</div>
					</div>
				</div>
				[/if {is_messagesAdmin}==1]
				
				[if {is_new}==new]
				<div class="col-sm-12">
					<div class="panel panel-warning white">
						<div class="panel-heading"><div class="panel-title">{L_list_changelog}</div></div>
						<div class="panel-body">
							<div class="scrollable" data-height="200">
								{changelog}
							</div>
						</div>
					</div>
				</div>
				[/if {is_new}==new]
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
    var data = localStorage.getItem("mainAdminCollapsed");
    if(data!==null) {
        data = JSON.parse(data);
    } else {
        data = {};
    }
    Object.keys(data).forEach(function(elem) {
        var e = jQuery("[data-module='"+elem+"']");
        if(data[elem]===true) {
            e.removeClass('collapsed');
        } else if(data[elem]===false) {
            e.addClass('collapsed');
        }
    });
});
jQuery(".content_admin [data-toggle]").click(function() {
    var elem = jQuery(this).parent().parent().parent();
    var module = elem.attr("data-module");
    var hidded = elem.hasClass("collapsed");
    var data = localStorage.getItem("mainAdminCollapsed");
    if(data!==null) {
        data = JSON.parse(data);
    } else {
        data = {};
    }
    data[module] = hidded;
    data = JSON.stringify(data);
    localStorage.setItem("mainAdminCollapsed", data);
});
</script>