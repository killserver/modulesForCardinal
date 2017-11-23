<!DOCTYPE html>
<html lang="en">
<head>
	<meta http-equiv="X-UA-Compatible" content="IE=edge">

	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1" />
	<meta name="description" content="Cardinal Engine Admin Panel" />
	<meta name="author" content="KilleR" />

	<link rel="icon" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/images/favicon.ico">
	
	<title>Admin Panel for {L_sitename}</title>

	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/jquery-ui/css/no-theme/jquery-ui-1.10.3.custom.min.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/font-icons/entypo/css/entypo.css">
	<link rel="stylesheet" href="//fonts.googleapis.com/css?family=Noto+Sans:400,700,400italic">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/bootstrap.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/neon-core.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/neon-theme.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/neon-forms.css">
	<link rel="stylesheet" href="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/css/custom.css">

	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/jquery-1.11.3.min.js"></script>

	<!--[if lt IE 9]><script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/ie8-responsive-file-warning.js"></script><![endif]-->
	
	<!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	<!--[if lt IE 9]>
		<script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
		<script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	<![endif]-->


</head>
<body class="page-body login-page login-form-fall bgFon" data-url="http://neon.dev">
	<span class="imgHere"></span>


<!-- This is needed when you send requests via Ajax -->
<script type="text/javascript">
var baseurl = '';
</script>

<div class="login-container">
	
	<div class="login-header login-caret">
		
		<div class="login-content">
			
			<p class="description">{L_"Уважаемый пользователь, авторизируйтесь в админ-панели!"}</p>
			
			<!-- progress bar indicator -->
			<div class="login-progressbar-indicator">
				<h3>43%</h3>
				<span>logging in...</span>
			</div>
		</div>
		
	</div>
	
	<div class="login-progressbar">
		<div></div>
	</div>
	
	<div class="login-form">
		
		<div class="login-content">
			
			<div class="form-login-error">
				<h3>Invalid login</h3>
			</div>
			
			<form method="post" role="form" id="form_login">
				<input type="hidden" name="method" id="method" value="login" />
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-user"></i>
						</div>
						
						<input type="text" class="form-control" name="username" id="username" placeholder="{L_"Имя пользователя"}" autocomplete="off" />
					</div>
					
				</div>
				
				<div class="form-group">
					
					<div class="input-group">
						<div class="input-group-addon">
							<i class="entypo-key"></i>
						</div>
						
						<input type="password" class="form-control" name="passwd" id="password" placeholder="{L_"Пароль"}" autocomplete="off" />
					</div>
				
				</div>
				
				<div class="form-group">
					<button type="submit" class="btn btn-primary btn-block btn-login">
						<i class="entypo-login"></i>{L_"Войти"}
					</button>
				</div>
				
			</form>
			
		</div>
		
	</div>
	
</div>


	<!-- Bottom scripts (common) -->
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/gsap/TweenMax.min.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/jquery-ui/js/jquery-ui-1.10.3.minimal.min.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/bootstrap.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/joinable.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/resizeable.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/neon-api.js"></script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/jquery.validate.min.js"></script>
	<script>
var refLink = "{C_default_http_host}{D_ADMINCP_DIRECTORY}/{ref}";
var loginUrl = "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=login";
	</script>
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/neon-login.js"></script>


	<!-- JavaScripts initializations and stuff -->
	<script src="{C_default_http_local}{D_ADMINCP_DIRECTORY}/assets/neon/js/neon-custom.js"></script>

</body>
</html>