<?php

class Util {
	/**
	 * Takes two version numbers.  Returns 1 if the first is greater than the second.
	 * Returns -1 if the second is greater than the first.  Returns 0 if they are equal.
	 */
	public static function compare_version_numbers($ver1, $ver2) {
		$ver1Array = explode('.', $ver1);
		$ver2Array = explode('.', $ver2);
		
		for ($i = 0; $i < count($ver1Array) && $i < count($ver2Array); $i++) {
			if ($ver1Array[$i] > $ver2Array[$i]) {
				return 1;
			} else if ($ver1Array[$i] < $ver2Array[$i]) {
				return -1;
			}
		}
		
		if (count($ver1Array) > count($ver2Array)) {
			return 1;
		} else if (count($ver1Array) < count($ver2Array)) {
			return -1;
		} else {
			return 0;
		}
	}

	// taken from http://www.php.net/manual/en/function.date-diff.php
	public static function date_difference($sdate, $edate) {
		$time = $edate - $sdate;
		if ($time>=0 && $time<=59) {
			// Seconds
			$timeshift = $time.' seconds';

		} else if ($time>=60 && $time<=3599) {
			// Minutes + Seconds
			$pmin = ($edate - $sdate) / 60;
			$premin = explode('.', $pmin);
			
			$presec = $pmin-$premin[0];
			$sec = $presec*60;
			
			$timeshift = $premin[0].' min '.round($sec,0).' sec';

		} else if ($time>=3600 && $time<=86399) {
			// Hours + Minutes
			$phour = ($edate - $sdate) / 3600;
			$prehour = explode('.',$phour);
			
			$premin = $phour-$prehour[0];
			$min = explode('.',$premin*60);
			
			$presec = '0.'.$min[1];
			$sec = $presec*60;

			$timeshift = $prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec';

		} else if ($time>=86400) {
			// Days + Hours + Minutes
			$pday = ($edate - $sdate) / 86400;
			$preday = explode('.',$pday);

			$phour = $pday-$preday[0];
			$prehour = explode('.',$phour*24); 

			$premin = ($phour*24)-$prehour[0];
			$min = explode('.',$premin*60);
			
			$presec = '0.'.$min[1];
			$sec = $presec*60;
			
			$timeshift = $preday[0].' days '.$prehour[0].' hrs '.$min[0].' min '.round($sec,0).' sec';

		}
		return $timeshift;
	}
	
	public static function verify_filename($filename) {
		//Replace all \ characters with /
		$filename = str_replace("\\", "/", $filename);

		if (!Util::verify_name_convention($filename)) {
			return FALSE;
		}

		//check if the file exists
	    if (file_exists("./core/$filename")) {
	        return "./core/$filename";
    	} else if (file_exists("./modules/$filename")) {
        	return "./modules/$filename";
	    } else {
	     	return FALSE;
	    }
	}

	public static function verify_name_convention($filename) {
		preg_match("/^([0-9a-z_]+)\\/([0-9a-z_]+)\\.php$/i", $filename, $arr);
		if ($arr[2] == strtolower($arr[2])) {
			return TRUE;
		} else {
			Logger::log(__FILE__, "$filename does not match the nameconvention(All php files needs to be in lowercases except loading files)!", WARN);
			return FALSE;
		}
	}
	
	public static function bytes_convert($bytes) {
		$ext = array('B', 'kB', 'MB', 'GB', 'TB', 'PB', 'EB', 'ZB', 'YB');
		$unitCount = 0;
		for(; $bytes > 1024; $unitCount++) {
			$bytes /= 1024;
		}
		return round($bytes, 2) ." ". $ext[$unitCount];
	}
}

?>
