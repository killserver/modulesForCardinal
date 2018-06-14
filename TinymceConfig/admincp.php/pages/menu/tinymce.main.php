<?php
$links['Settings']["item"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=TinymceConfig",
'title' => "{L_\"Настройки Tinymce\"}",
'type' => "item",
'access' => userlevel::get("settingsSystem"),
'icon' => '',
);
?>