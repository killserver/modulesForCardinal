<?php
$links['Settings']["item"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Preloaders",
'title' => "{L_\"Прелоадеры\"}",
'type' => "item",
'access' => userlevel::get("settings"),
'icon' => '',
);
?>