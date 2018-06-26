<?php
$links['Creator']["cat"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Creator",
'title' => "{L_'Быстрое создание разделов'}",
'type' => "cat",
'access' => userlevel::is(LEVEL_CREATOR),
'icon' => 'fa-cubes',
);
$links['Creator']["item"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Creator",
'title' => "{L_'Быстрое создание разделов'}",
'type' => "item",
'access' => userlevel::is(LEVEL_CREATOR),
'icon' => '',
);
?>