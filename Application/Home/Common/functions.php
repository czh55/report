<?
	 function weeks($cu_time)
	{
		$week = date("w",$cu_time);
		if ($week != 6) {
			echo "can't submit";
			exit();
		}else{
			$year = date("Y",$cu_time);
			$mouth = date("m",$cu_time);
			$day = date("d",$cu_time);
	
			$d=mktime(0, 0, 0, $mouth, 1, $year);//这个月的第一天，是全年的第几周
			$week1 = date("w",$d);
			$start = NULL;
			if ($week1 == 6) {
				$start = 1;
			}elseif ($week1 == 0) {
				$start = 7;
			}else{
				$start = 1 + (6 - $week1);
			}
			$differ = (($day - $start) / 7) + 1;
			//echo $differ."week";
			return $differ;
		}
	}
?>