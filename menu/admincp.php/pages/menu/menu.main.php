<?php
$links['menuAdmin']["cat"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=MenuAdmin",
'title' => "{L_\"Редактор меню\"}",
'type' => "cat",
'access' => db::connected() && db::getTable("menu"),
'icon' => 'fa-folder-o',
);
$links['menuAdmin']["item"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=MenuAdmin",
'title' => "{L_\"Редактор меню\"}",
'type' => "item",
'access' => db::connected() && db::getTable("menu"),
'icon' => '',
);
?>