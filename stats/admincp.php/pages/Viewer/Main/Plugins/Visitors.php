<?php

class Main_Visitors extends Main {

    private function addZero( $number ) {
        $string = (string) $number;
        if( strlen( $string ) == 2 ) {
            return $string;
        }
        return '0' . $string;
    }

	function __construct() {
            $days = 14;
            $today = date('j');
            $month = date('n');
            $year = date('Y');
            $chart = array();
            $dataAll = array();
            if($today > $days) {
                for($i = $today - $days + 1; $i <= $today; $i ++) {
                    $chart[$this->addZero($i).'.'.$this->addZero($month).'.'.$year] = array(
                    	'date' => $this->addZero($i).'.'.$this->addZero($month).'.'.$year,
                        'visits' => 0,
                        'visitors' => 0,
                    );
                }
            } else {
                $days1 = $today;
                $days2 = $days - $today + 1;
                $last_month = $month - 1;
                $last_year = $year;
                $countDays = date('t', strtotime('01.'.$last_month.'.'.$last_year));
                if( !$last_month ) {
                    $last_month = 12;
                    $last_year = $year - 1;
                }
                for($i = $countDays - $days2 + 1; $i <= $countDays; $i ++) {
                    $chart[$this->addZero($i).'.'.$this->addZero($last_month).'.'.$last_year] = array(
                    	'date' => $this->addZero($i).'.'.$this->addZero($last_month).'.'.$year,
                        'visits' => 0,
                        'visitors' => 0,
                    );
                    $dataAll[$this->addZero($i).'.'.$this->addZero($last_month).'.'.$year] = array(
                        'hits' => 0,
                        'unique_hits' => 0,
                    );
                }
                for($i = $today - $days1 + 1; $i <= $today; $i ++) {
                    $chart[$this->addZero($i).'.'.$this->addZero($month).'.'.$year] = array(
                    	'date' => $this->addZero($i).'.'.$this->addZero($month).'.'.$year,
                        'visits' => 0,
                        'visitors' => 0,
                    );
                    $dataAll[$this->addZero($i).'.'.$this->addZero($month).'.'.$year] = array(
                        'hits' => 0,
                        'unique_hits' => 0,
                        'hits_tf' => 0,
                        'unique_hits_tf' => 0,
                    );
                }
            }
            db::doquery("SELECT COUNT(`vIp`) AS `visits`, `vIp`, FROM_UNIXTIME(`vCreatedAt`, \"%d.%m.%Y\") as `vDate` FROM {{visitorsHits}} WHERE `vCreatedAt` >= (UNIX_TIMESTAMP() - ".($days * 24 * 60 * 60).") GROUP BY `vDate`", true);
            while($row = db::fetch_assoc()) {
                $chart[$row['vDate']]['date'] = $row['vDate'];
                $chart[$row['vDate']]['visits'] += $row['visits'];
            }
            db::doquery("SELECT COUNT(DISTINCT `vIp`) AS `visitors`, `vIp`, FROM_UNIXTIME(`vCreatedAt`, \"%d.%m.%Y\") as `vDate` FROM {{visitorsHits}} WHERE `vCreatedAt` >= (UNIX_TIMESTAMP() - ".($days * 24 * 60 * 60).") GROUP BY `vDate`", true);
            while($row = db::fetch_assoc()) {
                $chart[$row['vDate']]['date'] = $row['vDate'];
                $chart[$row['vDate']]['visitors'] += $row['visitors'];
            }
            $chart = array_values($chart);
            db::doquery("SELECT COUNT(`vIp`) AS `hits`, COUNT(DISTINCT `vIp`) AS `unique` FROM {{visitorsHits}} WHERE `vCreatedAt` >= (UNIX_TIMESTAMP() - ".($days * 24 * 60 * 60).")", true);
            while($row = db::fetch_assoc()) {
                $dataAll['hits'] += $row['hits'];
                $dataAll['unique_hits'] += $row['unique'];
            }
            db::doquery("SELECT COUNT(`vIp`) AS `hits`, COUNT(DISTINCT `vIp`) AS `unique` FROM {{visitorsHits}} WHERE `vCreatedAt` >= (UNIX_TIMESTAMP() - ".(24 * 60 * 60).")", true);
            while($row = db::fetch_assoc()) {
                $dataAll['hits_tf'] += $row['hits'];
                $dataAll['unique_hits_tf'] += $row['unique'];
            }
            $dataAll['hits'] = number_format($dataAll['hits'], 0, '.', ',');
            $dataAll['unique_hits'] = number_format($dataAll['unique_hits'], 0, '.', ',');
            $dataAll['hits_tf'] = number_format($dataAll['hits_tf'], 0, '.', ',');
            $dataAll['unique_hits_tf'] = number_format($dataAll['unique_hits_tf'], 0, '.', ',');
            templates::assign_var("hitsTT", json_encode($chart));
            templates::assign_var("hitsTTS", json_encode($dataAll));
	}

}
