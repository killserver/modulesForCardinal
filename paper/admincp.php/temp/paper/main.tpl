<!doctype html>
<html lang="{langPanel}">
<head>
    <meta charset="{C_charset}">
	<link rel="apple-touch-icon" sizes="76x76" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <meta name="description" content="Cardinal Engine Admin Panel" />
    <meta name="author" content="KilleR" />
    <!--base href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/" /-->
    
    <title>Admin Panel for {L_sitename}</title>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


    <!-- Bootstrap core CSS     -->
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/bootstrap.min.css" rel="stylesheet" />

    <!-- Animation library for notifications   -->
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/animate.min.css" rel="stylesheet"/>

    <!--  Paper Dashboard core CSS    -->
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/paper-dashboard.css" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/demo.css" rel="stylesheet" />


    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/themify-icons.css" rel="stylesheet">
    <link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/fonts/linecons/css/linecons.css?1">
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/jquery-1.10.2.js" type="text/javascript"></script>
    <script>
        var defaultTime = {S_time};
        var default_link = "{C_default_http_host}";
        var default_admin_link = "{C_default_http_host}{D_ADMINCP_DIRECTORY}/";
        var default_localadmin_link = "{C_default_http_local}{D_ADMINCP_DIRECTORY}/";
        var selectLang = "{langPanel}";
    </script>

</head>
<body>

<div class="wrapper [if {C_FullMenu}!=1&&{M_[mobile]}==false] sidebar-mini[/if {C_FullMenu}!=1&&{M_[mobile]}==false]">
    <div class="sidebar" data-background-color="white" data-active-color="danger">

    <!--
		Tip 1: you can change the color of the sidebar's background using: data-background-color="white | black"
		Tip 2: you can change the color of the active button using the data-active-color="primary | info | success | warning | danger"
	-->
        <div class="logo">
            <a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=main" class="simple-text logo-mini">
                CE
            </a>

            <a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=main" class="simple-text logo-normal">
                Cardinal Engine
            </a>
        </div>

    	<div class="sidebar-wrapper">

            <ul class="nav">
                <li>
                    <a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=main">
                        <i class="ti-panel"></i>
                        <p>{L_"Главная админ-панели"}</p>
                    </a>
                </li>
                [foreach block=menu]
                [foreachif {menu.type_st}=="start"]<li>
                    <a data-toggle="collapse" href="#menu-{menu.$id}">
                        <i class="fa {menu.icon}"></i>
                        <p class="title">{menu.value}<b class="caret"></b></p>
                    </a>
                    <span class="collapse" id="menu-{menu.$id}">
                    <ul class="nav">[/foreachif {menu.type_st}=="start"]
                        <li[foreachif {menu.is_now}==1] class="active"[/foreachif][foreachif {menu.type_st}=="start"] style="display:none;"[/foreachif {menu.type_st}=="start"]>
                            <a href="{menu.link}">
                                [foreachif {menu.type}=="item"]<i class="fa {menu.icon}"></i>[/foreachif {menu.type}=="item"]
                                <p class="title">{menu.value}</p>
                            </a>
                        </li>
                [foreachif {menu.type_end}=="end"]  </ul>
            </span>
                </li>[/foreachif {menu.type_end}=="end"]
                [/foreach]
            </ul>
    	</div>
    </div>

    <div class="main-panel">
        <nav class="navbar navbar-default">
            <div class="container-fluid">
                <div class="navbar-minimize">
                    <button id="minimizeSidebar" class="btn btn-fill btn-icon"><i class="ti-more-alt"></i></button>
                </div>
                <div class="navbar-header">
                    <button type="button" class="navbar-toggle">
                        <span class="sr-only">Toggle navigation</span>
                        <span class="icon-bar bar1"></span>
                        <span class="icon-bar bar2"></span>
                        <span class="icon-bar bar3"></span>
                    </button>
                    <a class="navbar-brand" href="#">{title_admin}</a>
                </div>
                <div class="collapse navbar-collapse">
                    <div class="navbar-form navbar-left">
                        <div class="input-group">
                            <div class="versionCardinal">{L_"Version"}: {D_VERSION}</div>
                        </div>
                    </div>
                    <ul class="nav navbar-nav navbar-right">
                        <li class="dropdown hover-line">
                            <a href="{C_default_http_host}" class="dropdown-toggle" aria-expanded="true" title="{L_"Перейти на сайт"}" alt="{L_"Перейти на сайт"}">
                                <i class="fa fa-paper-plane"></i>
                                <p>{L_"Перейти на сайт"}</p>
                            </a>
                        </li>
                        [if {count_Yui}==true]<li class="dropdown hover-line">
                            <a href="#" onclick="jQuery('#modal-yui').modal('show', {backdrop: 'static'});" title="{L_"Панель запуска Yui"}" alt="{L_"Панель запуска Yui"}">
                                <i class="fa-info"></i>
                            </a>
                        </li>[/if {count_Yui}==true]
                        [if {count[langListSupport]}>=2]<li class="dropdown hover-line language-switcher" style="min-height: 76px;">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false"><img src="{nowLangImg}">{nowLangText}</a>
                            <ul class="dropdown-menu languages">
                                [foreach block=langListSupport]<li><a href="./?setLanguage={langListSupport.langMenu}"><img src="{langListSupport.img}">{langListSupport.lang}</a></li>[/foreach]
                            </ul>
                        </li>[/if {count[langListSupport]}>=2]

                        [if {UL_settings}==true]<li>
                            <a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Settings">
                                <i class="ti-settings"></i>
                                <p>{L_"Settings"}</p>
                            </a>
                        </li>[/if {UL_settings}==true]
                        <li class="dropdown">
                              <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                    <p>{U_username}</p>
                                    <b class="caret"></b>
                              </a>
                              <ul class="dropdown-menu">
                                <li><a href="{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Login&out">{L_"Logout"}</a></li>
                              </ul>
                        </li>
                    </ul>

                </div>
            </div>
        </nav>


        <div class="content">
            <div class="container-fluid">
                <span class="content_admin">{main_admin}</span>
            </div>
        </div>


        <footer class="footer">
            <div class="container-fluid">
                <div class="copyright pull-left">
                    &copy; 2015 - {S_data="Y"}, made <a href="http://www.creative-tim.com" target="_blank">Creative Tim</a> for Cardinal Engine
                </div>
                <div class="copyright pull-right">rev. {D_INTVERSION}</div>
            </div>
        </footer>

    </div>
</div>

    <link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/toastr/toastr.min.css?1">
    {css_list}

    <!--   Core JS Files   -->
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/bootstrap.min.js" type="text/javascript"></script>
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/perfect-scrollbar.min.js"></script>

    <!--  Checkbox, Radio & Switch Plugins -->
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/bootstrap-checkbox-radio.js"></script>
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/bootstrap-switch-tags.js"></script>

    <!--  Charts Plugin -->
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/jquery.easypiechart.min.js"></script>
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/chartist.min.js"></script>

    <!--  Notifications Plugin    -->
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/bootstrap-notify.js"></script>

    <!--  Google Maps Plugin    -->
    <script type="text/javascript" src="https://maps.googleapis.com/maps/api/js"></script>

    <!-- Paper Dashboard Core javascript and methods for Demo purpose -->
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/paper-dashboard.js"></script>

    <!-- Paper Dashboard DEMO methods, don't include it in your project! -->
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/demo.js"></script>
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/tinymce/tinymce.min.js?{S_time}"></script>

    
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/datepicker/bootstrap-datepicker.js"></script>
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/timepicker/bootstrap-timepicker.min.js"></script>
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/colorpicker/bootstrap-colorpicker.min.js"></script>

    {js_list}
    <script>
    var editorTextarea;
    if(typeof(disableAllEditors)==="undefined") {
        $(document).ready(function(){
            if(typeof(editorTextarea)!=="object") {
                editorTextarea = {
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
                    external_filemanager_path: default_admin_link+"assets/tinymce/filemanager/",
                    filemanager_title: "{L_"Загрузка файлов"}", 
                    external_plugins: { "filemanager" : default_admin_link+"assets/tinymce/filemanager/plugin.min.js"},
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
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/toastr/toastr.min.js?1"></script>

</body>

</html>
