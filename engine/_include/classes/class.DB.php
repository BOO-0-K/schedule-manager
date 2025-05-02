<?php
class DB extends Singleton {
	static $link;
	var $lastSQL;
	var $lastInsertIndex;

	function connect() {
		$this->link = new mysqli(DB_HOST, DB_USER, DB_PASS, DB_NAME);
		if ($this->link->connect_errno) {
			printf("Connect failed: %s\n", $this->link->connect_error);
			exit();
		}
		$this->q("set names utf8mb4", false);
		$this->q("set time_zone = '+09:00'", false);
	}

	function insertSQL($table, $values) {
		$columns = implode(", ", array_keys($values));
		$lineValue = "";
		foreach ($values as $key => $obj) {
			if(is_null($obj)) {
				$lineValue .= "null";
			} else {
				if(is_array($obj)) {
					$func = array_keys($obj);
					$funcVal = array_values($obj);
					$lineValue .= $func[0]."('".$funcVal[0]."')";
				} else {
					$lineValue .= "'".$this->clean($obj)."'";
				}
			}
			$lineValue .= ",";
		}

		$lineValue = substr($lineValue, 0, -1);
		$sql = "INSERT INTO `".DB_NAME."`.`".$table."`($columns) VALUES ($lineValue);";
		return $sql;
	}

	function updateSQL($table, $value, $where) {
		$wheres = "1=1 ";
		foreach ($where as $key => $obj) {
			$wheres .= "AND `".$key."`='".$obj."'";
		}

		$values = "";
		foreach ($value as $key => $obj) {
			if($obj == "NULL") {
				$values .= "`".$table."`.`".$key."`=null,";
			} else {

				if(is_array($obj)) {
					$func = array_keys($obj);
					$funcVal = array_values($obj);
					$values .= "`".$table."`.`".$key."`=".$func[0]."('".$funcVal[0]."'),";
				} else {
					$values .= "`".$table."`.`".$key."`='".$obj."',";
				}
			}
		}

		$values = substr($values, 0, -1);

		$sql = "UPDATE `".DB_NAME."`.`".$table."` SET ".$values." WHERE ".$wheres;
		return $sql;
	}

	function rows($sql) {
		if($query = $this->link->query($sql)) {
			return $query->num_rows;
		} else {
			return false;
		}
	}

	function q($sql, $logging = false) {
		if($query = $this->link->query($sql)) {
			$this->lastSQL = $sql;
			$this->lastInsertIndex = $this->link->insert_id;
			if($logging) {
				$value["q"] = $sql;
				$this->link->query($this->insertSQL("M3_query_logs", $value));
			}
			return $query;
		} else {
			$result["error"] = $this->link->errno;
			$result["sql"] = $sql;
			API::result("-1", $result);
		}
	}

	function clean($target) {
		return $this->link->real_escape_string(trim($target));
	}
}
?>
