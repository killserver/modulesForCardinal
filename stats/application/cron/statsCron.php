<?php
class StatsCron {
	
	function __construct() {
        $days = 14;
        if(db::connected()) {
			db::doquery("DELETE FROM {{visitorsHits}} WHERE `vCreatedAt` < (UNIX_TIMESTAMP() - ".($days * 24 * 60 * 60).")");
		}
	}
	
}
?>