<?php
$links['Installer']["cat"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Installer",
'title' => "{L_'Установщик модулей'}",
'type' => "cat",
'access' => userlevel::is(LEVEL_CREATOR),
'icon' => 'fa-list-alt',
);
$links['Installer']["item"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Installer",
'title' => "{L_'Установщик модулей'}",
'type' => "item",
'access' => userlevel::is(LEVEL_CREATOR),
'icon' => '',
);
?>