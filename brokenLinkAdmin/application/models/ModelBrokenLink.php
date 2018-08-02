<?php
/*
DROP TABLE IF EXISTS `cardinal_checker`;
CREATE TABLE `cardinal_checker` (
`cId` int not null auto_increment,
`linkNow` varchar(255) not null,
`htmlOriginal` longtext not null,
`linkOriginal` varchar(255) not null,
`lastCheck` int(11) not null,
`statusCode` int(11) not null,
`status` varchar(255) not null,
`warning` enum('yes','no') not null default 'no',
`broken` enum('yes','no') not null default 'no',
`timeResp` float(11) not null,
primary key `id`(`cId`)
) ENGINE=MyISAM;
 */
class ModelBrokenLink extends DBObject {

	public $cId;
	public $linkNow;
	public $htmlOriginal;
	public $linkOriginal;
	public $lastCheck;
	public $status;
	public $statusCode;
	public $warning;
	public $broken;
	public $timeResp;

	public function init_model() {
		$this->SetTable((defined("PREFIX_DB") ? PREFIX_DB : "")."brokenLink");
	}

}