<?php

class faq extends modules {
	
	function __construct() {
		Route::Set("faq_page", "(<lang>/)faq")->defaults(array(
			"class" => __CLASS__,
			"method" => "faqPage",
		));
	}

	public static function installation() {
		self::create_table("faq", " `fId` int(11) NOT NULL AUTO_INCREMENT,".
										" `fQuestion` varchar(32) NOT NULL DEFAULT '',".
										" `fAnswer` longtext NOT NULL DEFAULT '',".
										" PRIMARY KEY `id` (`fId`)");
	}

	public static $version = "1.3.1";

	public static function updater() {}
	
	function faqPage() {
		$tmp = $this->init_templates();
		$db = $this->init_db();

		$db->doquery("SELECT * FROM {{faq}} ORDER BY `fId` DESC", true);
		while($row = $db->fetch_assoc()) {
			$tmp->assign_vars($row, "faq", $row['fId']);
		}

		$tpl = $tmp->completed_assign_vars("faq", null);
		$tmp->completed($tpl, "F.A.Q.");
		$tmp->display();
	}
	
}