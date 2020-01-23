<?php
$links["LangExcel"]["cat"][] = array(
"link" => "#",
"title" => "{L_\"Языки в Excel\"}",
"type" => "cat",
"access" => userlevel::get("langexcel"),
"icon" => "",
);
$links["LangExcel"]["item"][] = array(
"link" => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=LangExcel",
"title" => "{L_\"Языки в Excel\"}",
"type" => "item",
"access" => userlevel::get("langexcel"),
"icon" => "",
);
?>