<?php
$links['System']["item"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=ErrorJS",
'title' => "{L_'Ошибки скриптов'}",
'type' => "item",
'access' => userlevel::is(LEVEL_CREATOR),
'icon' => '',
);
?>