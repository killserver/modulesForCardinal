<div class="">
	<div class="row">
		<div class="col-md-12 tabs-vertical-env tabs-vertical-bordered">
			<ul class="nav tabs-vertical">
				<li class="active"><a href="#main-sms" data-toggle="tab">Главная</a></li>
				<li><a href="#bytehand-com" data-toggle="tab">bytehand.com</a></li>
				<li><a href="#alphasms-ua" data-toggle="tab">alphasms.ua</a></li>
				<li><a href="#smsc-ru" data-toggle="tab">smsc.ru</a></li>
				<li><a href="#infosmska-ru" data-toggle="tab">infosmska.ru</a></li>
				<li><a href="#smscab-ru" data-toggle="tab">smscab.ru</a></li>
				<li><a href="#smsc-ua" data-toggle="tab">smsc.ua</a></li>
				<li><a href="#sms-ru" data-toggle="tab">sms.ru</a></li>
				<li><a href="#turbosms-in-ua" data-toggle="tab">turbosms.in.ua</a></li>
				<li><a href="#websms-ru" data-toggle="tab">websms.ru</a></li>
			</ul>
			<div class="tab-content">
				<div class="tab-pane active" id="main-sms">
					<div class="form-group">
						<label class="col-sm-4 control-label" for="mdg11 text-center">{L_"Выберите шлюз для отправки смс"}</label>
						<div class="col-sm-8">
							<select id="mdg11" name="smsmaster[sendfrom]" class="form-control">
								<option value="" [if {C_smsmaster[sendfrom]}==""] selected="selected"[/if {C_smsmaster[sendfrom]}==""]>Отключено</option>
								<option value="sort" [if {C_smsmaster[sendfrom]}=="sort"] selected="selected"[/if {C_smsmaster[sendfrom]}=="sort"]>Сортировкой</option>
								<option value="Bytehand" [if {C_smsmaster[sendfrom]}=="Bytehand"] selected="selected"[/if {C_smsmaster[sendfrom]}=="Bytehand"]>bytehand.com</option>
								<option value="AlphaSms" [if {C_smsmaster[sendfrom]}=="AlphaSms"] selected="selected"[/if {C_smsmaster[sendfrom]}=="AlphaSms"]>alphasms.ua</option>
								<option value="Smscru" [if {C_smsmaster[sendfrom]}=="Smscru"] selected="selected"[/if {C_smsmaster[sendfrom]}=="Smscru"]>smsc.ru</option>
								<option value="Smscua" [if {C_smsmaster[sendfrom]}=="Smscua"] selected="selected"[/if {C_smsmaster[sendfrom]}=="Smscua"]>smsc.ua</option>
								<option value="Infosmska" [if {C_smsmaster[sendfrom]}=="Infosmska"] selected="selected"[/if {C_smsmaster[sendfrom]}=="Infosmska"]>infosmska.ru</option>
								<option value="Smscab" [if {C_smsmaster[sendfrom]}=="Smscab"] selected="selected"[/if {C_smsmaster[sendfrom]}=="Smscab"]>smscab.ru</option>
								<option value="Smsru" [if {C_smsmaster[sendfrom]}=="Smsru"] selected="selected"[/if {C_smsmaster[sendfrom]}=="Smsru"]>sms.ru</option>
								<option value="Turbosms" [if {C_smsmaster[sendfrom]}=="Turbosms"] selected="selected"[/if {C_smsmaster[sendfrom]}=="Turbosms"]>turbosms.in.ua</option>
								<option value="Websms" [if {C_smsmaster[sendfrom]}=="Websms"] selected="selected"[/if {C_smsmaster[sendfrom]}=="Websms"]>websms.ru</option>
							</select>
						</div>
					</div>
					<div class="form-group-separator [if {C_smsmaster[sendfrom]}!=sort]hide[/if {C_smsmaster[sendfrom]}!=sort] ifSorted"></div>
					<div class="form-group [if {C_smsmaster[sendfrom]}!=sort]hide[/if {C_smsmaster[sendfrom]}!=sort] ifSorted">
						<label class="col-sm-4 control-label" for="text-center">{L_"Включенные сервисы отправки смс"}</label>
						<div class="col-sm-8">
							<ul class="uk-nestable grouped-nestables" id="nestable-list-available" data-uk-nestable="{group:'smsmaster',maxDepth:1}">
								{E_[settinguser_sort_smsmaster][type=available]}
							</ul>
							<textarea name="smsmaster[sort_available]" id="changed_sort-available" class="onlytext" style="position:fixed;top:-99999vh;left:-99999vw;opacity:0;z-index:-9999">{C_smsmaster[sort_available]}</textarea>
						</div>
					</div>
					<div class="form-group-separator [if {C_smsmaster[sendfrom]}!=sort]hide[/if {C_smsmaster[sendfrom]}!=sort] ifSorted"></div>
					<div class="form-group [if {C_smsmaster[sendfrom]}!=sort]hide[/if {C_smsmaster[sendfrom]}!=sort] ifSorted">
						<label class="col-sm-4 control-label" for="text-center">{L_"Отключённые сервисы отправки смс"}</label>
						<div class="col-sm-8">
							<ul class="uk-nestable grouped-nestables" id="nestable-list-disabled" data-uk-nestable="{group:'smsmaster',maxDepth:1}">
								{E_[settinguser_sort_smsmaster][type=disabled]}
							</ul>
							<textarea name="smsmaster[sort_disabled]" id="changed_sort-disabled" class="onlytext" style="position:fixed;top:-99999vh;left:-99999vw;opacity:0;z-index:-9999">{C_smsmaster[sort_disabled]}</textarea>
						</div>
					</div>
				</div>
				<div class="tab-pane" id="bytehand-com">
					<div class="form-group"><div class="col-md-11 text-center">bytehand.com</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_bytehandcom">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_bytehandcom" name="smsmaster[bytehandcom][login]" value="{C_smsmaster[bytehandcom][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_bytehandcom">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_bytehandcom" name="smsmaster[bytehandcom][psw]" value="{C_smsmaster[bytehandcom][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="alphasms-ua">
					<div class="form-group"><div class="col-md-11 text-center">alphasms.ua</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_alphasms">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_alphasms" name="smsmaster[alphasms][login]" value="{C_smsmaster[alphasms][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_alphasms">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_alphasms" name="smsmaster[alphasms][psw]" value="{C_smsmaster[alphasms][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="smsc-ru">
					<div class="form-group"><div class="col-md-11 text-center">smsc.ru</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_smsc-ru">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_smsc-ru" name="smsmaster[smscru][login]" value="{C_smsmaster[smscru][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_smsc-ru">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_smsc-ru" name="smsmaster[smscru][psw]" value="{C_smsmaster[smscru][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="infosmska-ru">
					<div class="form-group"><div class="col-md-11 text-center">infosmska.ru</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_infosmska-ru">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_infosmska-ru" name="smsmaster[infosmskaru][login]" value="{C_smsmaster[infosmskaru][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_infosmska-ru">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_infosmska-ru" name="smsmaster[infosmskaru][psw]" value="{C_smsmaster[infosmskaru][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="smscab-ru">
					<div class="form-group"><div class="col-md-11 text-center">smscab.ru</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_smscab-ru">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_smscab-ru" name="smsmaster[smscabru][login]" value="{C_smsmaster[smscabru][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_smscab-ru">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_smscab-ru" name="smsmaster[smscabru][psw]" value="{C_smsmaster[smscabru][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="smsc-ua">
					<div class="form-group"><div class="col-md-11 text-center">smsc.ua</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_smsc-ua">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_smsc-ua" name="smsmaster[smscua][login]" value="{C_smsmaster[smscua][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_smsc-ua">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_smsc-ua" name="smsmaster[smscua][psw]" value="{C_smsmaster[smscua][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="sms-ru">
					<div class="form-group"><div class="col-md-11 text-center">sms.ru</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_sms-ru">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_sms-ru" name="smsmaster[smsru][login]" value="{C_smsmaster[smsru][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_sms-ru">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_sms-ru" name="smsmaster[smsru][psw]" value="{C_smsmaster[smsru][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="turbosms-in-ua">
					<div class="form-group"><div class="col-md-11 text-center">turbosms.in.ua</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_turbosms-in-ua">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_turbosms-in-ua" name="smsmaster[turbosmsinua][login]" value="{C_smsmaster[turbosmsinua][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_turbosms-in-ua">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_turbosms-in-ua" name="smsmaster[turbosmsinua][psw]" value="{C_smsmaster[turbosmsinua][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
				<div class="tab-pane" id="websms-ru">
					<div class="form-group"><div class="col-md-11 text-center">websms.ru</div></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="login_websms-ru">{L_"Логин для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="text" class="form-control" id="login_websms-ru" name="smsmaster[websmsru][login]" value="{C_smsmaster[websmsru][login]}" placeholder="{L_"Введите логин"}">
						</div>
					</div>
					<div class="form-group-separator"></div>
					<div class="form-group">
						<label class="col-sm-4 control-label" for="psw_websms-ru">{L_"Пароль для отправки СМС"}</label>
						<div class="col-sm-8">
							<input type="password" class="form-control" id="psw_websms-ru" name="smsmaster[websmsru][psw]" value="{C_smsmaster[websmsru][psw]}" placeholder="{L_"Введите пароль"}">
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<style>
	.tab-bordered .nav-tabs > li.active > a {
	    border: 1px solid #ddd !important;
	    border-bottom: 0 !important;
	}
	.tab-bordered .nav.nav-tabs + .tab-content {
		border: 1px solid #ddd;
	    margin-top: -1px;
	    padding-top: 31px;
	}
</style>
<script type="text/javascript">
	jQuery(document).ready(function($) {
		$("#nestable-list-available").on('nestable-stop nestable-change', function(ev) {
			$("#changed_sort-available").val(JSON.stringify($(this).data('nestable').serialize()))
		});
		$("#nestable-list-disabled").on('nestable-stop nestable-change', function(ev) {
			$("#changed_sort-disabled").val(JSON.stringify($(this).data('nestable').serialize()))
		});
		$("#mdg11").on("change", function() {
			var val = $(this).val();
			if(val=="sort") {
				$(".ifSorted").removeClass("hide");
			} else {
				$(".ifSorted").addClass("hide");
			}
		});
	});
</script>