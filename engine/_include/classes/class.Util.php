<?php
class Util {
	//VOID
	public static function goAlertLocation($location,$message) {
		echo "<script type='text/javascript'>";
		echo "alert('".$message."');";
		echo "location.href='".$location."';";
		echo "</script>";
		exit();
	}

	//VOID
	public static function goLocation($location) {
		echo "<script type='text/javascript'>";
		echo "location.href='".$location."';";
		echo "</script>";
		exit();
	}

	public static function checkRequireField($post, $requiredField){
		foreach ($post as $postKey => $postValue) {
			foreach ($requiredField as $requireValue) {
				if($postKey == $requireValue[0]) {
					if(empty($postValue)) {
						API::result("-2", $requireValue[1], true);
					}
				}
			}
		}
	}

	public static function makePassword($simple=false) {
		if(!$simple) {
			$uppers = str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ');
			$lowers = str_shuffle('abcdefghijklmnopqrstuvwxyz');
			$numbers = str_shuffle('1234567890');
			$specials = str_shuffle('!@#$%^&*()');
			$complex_password = substr($uppers, -2).substr($lowers, -2).substr($numbers, -2).substr($specials, -2).substr($uppers, -2).substr($lowers, -2).substr($numbers, -2).substr($specials, -2);
		} else {
			$lowers = str_shuffle('abcdefghijklmnopqrstuvwxyz');
			$numbers = str_shuffle('1234567890');
			$complex_password = substr($lowers, -2).substr($numbers, -2).substr($lowers, -2).substr($numbers, -2);
		}
		return str_shuffle($complex_password);
	}

	public static function emailValidate($email) {
		return filter_var($email, FILTER_VALIDATE_EMAIL);
	}

	public static function base64ToJpeg($base64String, $path) {
		$base64String = trim($base64String);
		$filename = sha1($base64String);
		$ifp = fopen($path."/".$filename.".jpg", "wb");
		$data = explode(',', $base64String);
		fwrite($ifp, base64_decode($data[1]));
		fclose($ifp);
		return $filename.".jpg";
	}

	public static function calendar($year, $month) {
		sscanf(date('wt', mktime(0, 0, 0, $month, 1, $year)), '%1d%2d', $w, $t);
		$c = $t;
		$l = ceil(($t + $w) / 7); // 주차
		for ($i = 1, $a = array(); $i <= $t; $i++) {
			$a[] = $i < 10 ? '0'.$i : $i; // range(1,$t)
		}
		for ($i = 0; $i < $w; $i++) {
			array_unshift($a, '  ');
			$c++;
		} // 배열 앞 추가
		for ($i = 0, $n = 7 * $l - $c; $i < $n; $i++) {
			array_push($a,'  '); // 배열 뒤 추가
		}
		$a = array_chunk($a, 7); // 7개씩 나누기
		return $a;
	}

	public static function createOrderNumber($length = 6) {
	    $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
	    $charactersLength = strlen($characters);
	    $randomString = '';
	    for ($i = 0; $i < $length; $i++) {
	        $randomString .= $characters[rand(0, $charactersLength - 1)];
	    }
	    return $randomString;
	}
}
?>
