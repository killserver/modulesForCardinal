<!doctype html>
<html lang="en">
<head>
	<meta charset="utf-8" />
	<link rel="apple-touch-icon" sizes="76x76" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/img/apple-icon.png">
	<link rel="icon" type="image/png" sizes="96x96" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/img/favicon.png">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />

	<title>Admin Panel for {L_sitename}</title>

	<!-- Canonical SEO -->
    <link rel="canonical" href="http://www.creative-tim.com/product/paper-dashboard-pro"/>

	<meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <meta name="viewport" content="width=device-width" />


     <!-- Bootstrap core CSS     -->
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/bootstrap.min.css" rel="stylesheet" />

    <!--  Paper Dashboard core CSS    -->
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/paper-dashboard.css?v=1.2.1" rel="stylesheet"/>


    <!--  CSS for Demo Purpose, don't include it in your project     -->
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/demo.css" rel="stylesheet" />


    <!--  Fonts and icons     -->
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Muli:400,300' rel='stylesheet' type='text/css'>
    <link href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/css/themify-icons.css" rel="stylesheet">
</head>

<body class=" bgFon">
	<span class="imgHere"></span>
    <nav class="navbar navbar-transparent navbar-absolute">
	    <div class="container">
	        <div class="navbar-header">
	            <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navigation-example-2">
	                <span class="sr-only">Toggle navigation</span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	                <span class="icon-bar"></span>
	            </button>
	            <a class="navbar-brand" href="{C_default_http_local}">Admin Panel for {L_sitename}</a>
	        </div>
	        <div class="collapse navbar-collapse">
	            <ul class="nav navbar-nav navbar-right">

	            </ul>
	        </div>
	    </div>
	</nav>

	<div class="wrapper wrapper-full-page">
		<div class="full-page lock-page" data-color="green">
	    <!--   you can change the color of the filter page using: data-color="blue | azure | green | orange | red | purple" -->
	        <div class="content">
				<form method="post" role="form" id="login" class="login-form fade-in-effect" autocomplete="off">
					<input type="hidden" name="page" id="page" value="alogin" />
					<input type="hidden" name="username" id="username" value="{U_username}" />
					<input type="hidden" name="method" id="method" value="login" />
					<input type="hidden" name="ref" id="ref" value="{ref}" />
					<input type="hidden" name="do_login" id="do_login" value="do_login" />
	                <div class="card card-lock">
	                    <p>{L_"Добро пожаловать обратно"}, {U_username}!</p>
	                    <div class="form-group">
	                        <input type="password" name="passwd" class="form-control" autocomplete="off" readonly="readonly" style="cursor:text;" onclick="if(this.getAttribute('readonly') == 'readonly') this.removeAttribute('readonly')">
	                    </div>
	                    <button type="submit" class="btn btn-wd">{L_"Войти"}</button>
	                </div>
	            </form>
	        </div>
	    	<footer class="footer footer-transparent">
	            <div class="container">
	                <div class="copyright">
	                    &copy; <script>document.write(new Date().getFullYear())</script>, made with <i class="fa fa-heart heart"></i> by <a href="http://www.creative-tim.com">Creative Tim</a>
	                </div>
	            </div>
	        </footer>
	    </div>
	</div>
</body>

	<!--   Core JS Files. Extra: TouchPunch for touch library inside jquery-ui.min.js   -->
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/jquery-3.1.1.min.js" type="text/javascript"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/perfect-scrollbar.min.js" type="text/javascript"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/bootstrap.min.js" type="text/javascript"></script>
	<!-- Paper Dashboard PRO Core javascript and methods for Demo purpose -->
    <script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/bootstrap-switch-tags.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/paper-dashboard.js?v=1.2.1"></script>

	<!-- Paper Dashboard PRO DEMO methods, don't include it in your project! -->
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/paper/js/demo.js"></script>
	{js_list}

	<script type="text/javascript">
        $().ready(function(){
            demo.checkFullPageBackgroundImage();

            setTimeout(function(){
                // after 1000 ms we add the class animated to the login/register card
                $('.card').removeClass('card-hidden');
            }, 700)
        });
	</script>

</html>
