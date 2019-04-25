<?php

$links["feedback"]["cat"][] = array(
"link" => "#",
"title" => "{L_\"Конструктор форм\"}",
"type" => "cat",
"access" => userlevel::get("feedback_form"),
"icon" => "fa-lemon-o",
);
$links["feedback"]["item"][] = array(
"link" => "{C_default_http_host}{D_ADMINCP_DIRECTORY}/?pages=Feedback_form",
"title" => "{L_\"Конструктор форм\"}",
"type" => "item",
"access" => userlevel::get("feedback_form"),
"icon" => "",
);

?>