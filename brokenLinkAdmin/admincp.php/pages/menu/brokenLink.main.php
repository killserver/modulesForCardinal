<?php
$links['brokenLink']["cat"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=brokenLink",
'title' => "{L_'Поломанные ссылки'}",
'type' => "cat",
'access' => userlevel::is(LEVEL_CREATOR),
'icon' => 'fa-cubes',
);
$links['brokenLink']["item"][] = array(
'link' => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=brokenLink",
'title' => "{L_'Поломанные ссылки'}",
'type' => "item",
'access' => userlevel::is(LEVEL_CREATOR),
'icon' => '',
);
?>