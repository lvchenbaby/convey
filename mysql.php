<?php
function iQuery($sql, $index_field = "", $return_all_errors = false, $dbindex = 0) {
	$dbcon = mysqli_connect($dbconf[$dbindex]['host'], $dbconf[$dbindex]['username'], $dbconf[$dbindex]['password'], $dbconf[$dbindex]['dbname']);
	if ($dbcon->connect_error) {
		return false;
	}
	if (!is_array($sql)) {
		$res = $dbcon->query($sql);
		if ($res) {
			$arr = array();
			if ($index_field) {
				while ($item = $res->fetch_array()) {
					$arr[$item[$index_field]] = $item;
				}
			} else {
				while ($item = $res->fetch_array()) {
					array_push($arr, $item);
				}
			}

			$ttr = null;
			if (preg_match("/SQL_CALC_FOUND_ROWS/i", $sql)) {
				$qttr = $dbcon->query("select found_rows()");
				if ($qttr) {
					while ($atom = $qttr->fetch_array()) {
						$ttr = $atom[0];
					}
				}
			}
			return array("insert_id" => $dbcon->insert_id, "affected_rows" => $dbcon->affected_rows, "result" => $arr, "is_empty_result" => empty($arr) ? true : false, "total_rows" => $ttr);
		} else {
			return false;
		}
	} else {
		$rtn_arr = array();
		foreach ($sql as $s) {
			$res = $dbcon->query($s);
			if ($res) {
				$arr = [];
				if ($index_field) {
					while ($item = $res->fetch_array()) {
						$arr[$item[$index_field]] = $item;
					}
				} else {
					while ($item = $res->fetch_array()) {
						array_push($arr, $item);
					}
				}
				$ttr = null;
				if (preg_match("/SQL_CALC_FOUND_ROWS/i", $sql)) {
					$qttr = $dbcon->query("select found_rows()");
					if ($qttr) {
						while ($atom = $qttr->fetch_array()) {
							$ttr = $atom[0];
						}
					}
				}
				array_push($rtn_arr, ["insert_id" => $dbcon->insert_id, "affected_rows" => $dbcon->affected_rows, "is_empty_result" => empty($arr) ? true : false, "result" => $arr, "total_rows" => $ttr]);
			} else {
				if ($return_all_errors) {
					array_push($rtn_arr, false);
				} else {
					return false;
				}
			}
		}
		return $rtn_arr;
	}

}