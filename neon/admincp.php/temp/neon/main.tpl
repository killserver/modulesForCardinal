<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Neon Admin Panel" />
	<meta name="author" content="" />

	<link rel="icon" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/images/favicon.ico">

	<title>Neon | Dashboard</title>

	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/font-icons/linecons/css/linecons.css?1">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/font-icons/font-awesome/css/font-awesome.min.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/bootstrap.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/neon-core.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/neon-theme.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/neon-forms.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/custom.css?{S_time}">

	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/jquery-1.11.3.min.js"></script>
	<script>
		var defaultTime = {S_time};
		var default_link = "{C_default_http_host}";
		var default_admin_link = "{C_default_http_host}{D_ADMINCP_DIRECTORY}/";
		var default_localadmin_link = "{C_default_http_local}{D_ADMINCP_DIRECTORY}/";
		var selectLang = "{langPanel}";
	</script>

	<!--[if lt IE 9]><script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/ie8-responsive-file-warning.js"></script><![endif]-->
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>
<body class="page-body  page-fade" data-url="http://neon.dev">

<div class="page-container[if {C_FullMenu}!=1&&{M_[mobile]}==false] sidebar-collapsed[/if {C_FullMenu}!=1&&{M_[mobile]}==false]"><!-- add class "sidebar-collapsed" to close sidebar by default, "chat-visible" to make chat appear always -->
	
	<div class="sidebar-menu fixed">

		<div class="sidebar-menu-inner">
			
			<header class="logo-env">

				<!-- logo -->
				<div class="logo">
					<a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=main">
						<img src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/{C_logoAdminMain}" width="120" alt="" />
					</a>
				</div>

				<!-- logo collapse icon -->
				<div class="sidebar-collapse">
					<a href="#" class="sidebar-collapse-icon with-animation"><!-- add class "with-animation" if you want sidebar to have animation during expanding/collapsing transition -->
						<i class="entypo-menu"></i>
					</a>
				</div>

								
				<!-- open/close menu icon (do not remove if you want to enable menu on mobile devices) -->
				<div class="sidebar-mobile-menu visible-xs">
					<a href="#" class="with-animation"><!-- add class "with-animation" to support animation -->
						<i class="entypo-menu"></i>
					</a>
				</div>

			</header>
			
									
			<ul id="main-menu" class="main-menu">
				<!-- add class "multiple-expanded" to allow multiple submenus to open -->
				<!-- class "auto-inherit-active-class" will automatically add "active" class for parent elements who are marked already with class "active" -->
					<li>
						<a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=main">
							<i class="linecons-cog"></i>
							<span class="title">{L_"Main admin"}</span>
						</a>
					</li>
					[foreach block=menu]
					[foreachif {menu.type_st}=="start"]<li>
						<a href="{menu.link}">
							<i class="{menu.icon}"></i>
							<span class="title">{menu.value}</span>
						</a>
						<ul>[/foreachif {menu.type_st}=="start"]
							<li[foreachif {menu.is_now}==1] class="active"[/foreachif][foreachif {menu.type_st}=="start"] style="display:none;"[/foreachif {menu.type_st}=="start"]>
								<a href="{menu.link}">
									[foreachif {menu.type}=="item"]<i class="{menu.icon}"></i>[/foreachif {menu.type}=="item"]
									<span class="title">{menu.value}</span>
								</a>
							</li>
					[foreachif {menu.type_end}=="end"]	</ul>
					</li>[/foreachif {menu.type_end}=="end"]
					[/foreach]
			</ul>
			
		</div>

	</div>

	<div class="main-content">
				
		<div class="row">
		
			<!-- Profile Info and Notifications -->
			<div class="col-md-6 col-sm-8 clearfix">
			
				<ul class="user-info pull-left pull-none-xsm">
					<li class="notifications dropdown">
						<a href="#" data-toggle="dropdown">
							<span>
								{U_username}
								<i class="fa-angle-down"></i>
							</span>
						</a>
						
						<ul class="dropdown-menu user-profile-menu list-unstyled">
							[if {UL_settings}==true]<li>
								<a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Settings">
									<i class="fa-wrench"></i>
									{L_"Settings"}
								</a>
							</li>[/if {UL_settings}==true]
						</ul>
					</li>
				</ul>
				
				<!-- Left links for user info navbar -->
				<ul class="user-info pull-left pull-right-xs pull-none-xsm">
					
					<li class="notifications dropdown">
						<a href="{C_default_http_host}" class="dropdown-toggle" aria-expanded="true" title="{L_"Перейти на сайт"}" alt="{L_"Перейти на сайт"}">
							<i class="fa-paper-plane"></i>
						</a>
					</li>
					
					[if {count_Yui}==true]<li class="notifications dropdown">
						<a href="#" onclick="jQuery('#modal-yui').modal('show', {backdrop: 'static'});" title="{L_"Панель запуска Yui"}" alt="{L_"Панель запуска Yui"}">
							<i class="fa-info"></i>
						</a>
					</li>[/if {count_Yui}==true]
					
					[if {count_unmoder}>=1]<li class="notifications dropdown">
						<a href="#" data-toggle="dropdown">
							<i class="fa-bell-o"></i>
							<span class="badge badge-purple">{count_unmoder}</span>
						</a>
							
						<ul class="dropdown-menu notifications">
							<li>
								<ul class="dropdown-menu-list list-unstyled ps-scrollbar">
									[foreach block=unmoders]<li class="active
									[foreachif {unmoders.errors}==0]notification-success[/foreachif]
									[foreachif {unmoders.errors}>=1]notification-danger[/foreachif]">
										[foreachif {unmoders.errors}==0]<a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}?pages=Videos&mod=edit&edit={unmoders.name_id}">[/foreachif]
										[foreachif {unmoders.errors}>=1]<a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}?pages=Videos&mod=errors">[/foreachif]
											[foreachif {unmoders.errors}>=1]<i class="fa-trash"></i>[/foreachif]
											[foreachif {unmoders.errors}==0]<i class="fa-play-circle-o"></i>[/foreachif]
											<span class="line"><strong>{unmoders.name}</strong></span>
											<span class="line small time">{unmoders.ago}</span>
										</a>
									</li>[/foreach]
								</ul>
							</li>
							
							<li class="external">
								<a href="#">
									<span>View all notifications</span>
									<i class="fa-link-ext"></i>
								</a>
							</li>
						</ul>
					</li>[/if {count_unmoder}>=1]

					<li class="notifications dropdown"> <center style="display:inline-block;margin:0px 10px;text-align:center;font-size:25px;">{L_"Version"}: {D_VERSION}</center> </li>
					
					
				</ul>
		
			</div>
		
		
			<!-- Raw Links -->
			<div class="col-md-6 col-sm-4 clearfix hidden-xs">
		
				<ul class="list-inline links-list pull-right">
		
					[if {count[langListSupport]}>=2]<li class="dropdown hover-line language-switcher" style="min-height: 76px;">
						<a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><img src="{nowLangImg}">{nowLangText}</a>
						<ul class="dropdown-menu languages">
							[foreach block=langListSupport]<li><a href="./?setLanguage={langListSupport.langMenu}"><img src="{langListSupport.img}">{langListSupport.lang}</a></li>[/foreach]
						</ul>
					</li>[/if {count[langListSupport]}>=2]
		
					<li class="sep"></li>
		
					<li>
						<a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Login&out">
							Log Out <i class="entypo-logout right"></i>
						</a>
					</li>
				</ul>
		
			</div>
		
		</div>
		
		<hr />
		
		
		<div class="page-title">
			<div class="title-env">
				<h1 class="title">{title_admin}</h1>
			</div>
		</div>
		<span class="content_admin">{main_admin}</span>
		
		<!-- Footer -->
		<footer class="main">
			
			&copy; 2015 - {S_data="Y"} <strong>Neon</strong> theme by <a href="http://laborator.co" target="_blank">Laborator</a> for Cardinal Engine
			<div class="pull-right col-sm-1 text-muted">rev. {D_INTVERSION}</div>
		
		</footer>
	</div>

</div>

	<div class="modal fade" id="modal-4" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header">
					<h4 class="modal-title">Error</h4>
				</div>
				<div class="modal-body" id="error-body" style="height:500px;overflow:auto;">You can close this modal when you click on button only!</div>
				<div class="modal-footer">
					<button type="button" class="btn btn-info" data-dismiss="modal">Continue</button>
				</div>
			</div>
		</div>
	</div>

	<div class="modal fade" id="modal-yui" data-backdrop="static">
		<div class="modal-dialog">
			<div class="modal-content">
				<div class="modal-header"><h4 class="modal-title">{L_"Панель запуска Yui"}</h4></div>
				<div class="modal-body">
					<button type="button" class="btn btn-info" data-demo="data-demo" data-demo-this="1" data-dismiss="modal">{L_"Запустить обучение для этой страницы"}</button>
					<button type="button" class="btn btn-red" data-demo="data-demo" data-demo-this="0" data-dismiss="modal">{L_"Запустить полный курс обучения"}</button>
				</div>
			</div>
		</div>
	</div>
	
	{css_list}

	<!-- Bottom scripts (common) -->
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/gsap/TweenMax.min.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/bootstrap.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/joinable.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/resizeable.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/neon-api.js"></script>


	<!-- Imported scripts on this page -->
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/toastr.js"></script>


	<!-- JavaScripts initializations and stuff -->
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/neon-custom.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/xenon/js/tinymce/tinymce.min.js?{S_time}"></script>

	{js_list}
	
	<script>
	if(typeof(disableAllEditors)=="undefined") {
		$(document).ready(function(){
			if(typeof(editorTextarea)!="object") {
				var editorTextarea = {
					selector: 'textarea',
					height: 500,
					language : selectLang,
					plugins: [
						"advlist autolink lists link image charmap print preview anchor",
						"searchreplace visualblocks code fullscreen",
						"insertdatetime media table contextmenu paste imagetools responsivefilemanager localautosave"
					],
					toolbar: "insertfile undo redo | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image responsivefilemanager localautosave",
					content_css: [],
					valid_elements : "*[*]",
					forced_root_block : '',
					image_advtab: true, 
					external_filemanager_path: default_admin_link+"assets/xenon/js/tinymce/filemanager/",
					filemanager_title: "{L_"Загрузка файлов"}", 
					external_plugins: { "filemanager" : default_admin_link+"assets/xenon/js/tinymce/filemanager/plugin.min.js"},
					readonly: (typeof(readOnlyEditor)=="undefined" ? 0 : 1),
					las_seconds: 15,
					las_nVersions: 15,
					las_keyName: "LocalAutoSave",
					las_callback: function() {
						var content = this.content; //content saved
						var time = this.time; //time on save action
						console.log(content);
						console.log(time);
					},
					cleanup: false,
					verify_html: false,
					cleanup_on_startup: false,
					validate_children: false,
					remove_redundant_brs: false,
					remove_linebreaks: false,
					force_p_newlines: false,
					force_br_newlines: false,
					valid_children: "+li[p|img|br|strong],+ol[p|img|br|strong],+ul[p|img|br|strong]",
					validate: false,
					fix_table_elements: false,
					fix_list_elements: false,
				}
			}
			tinymce.init(editorTextarea);
		});
	}
	</script>

</body>
</html>