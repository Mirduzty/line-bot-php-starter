<?php

function remain_time4($expbuffe) {
    if (strtotime($expbuffe) >= strtotime('now')) {
        $remaintime = strtotime($expbuffe) - strtotime('now');
        if ($remaintime >= 31536000) {
            $l_year = $remaintime % 31536000;
            $month = floor($l_year / 2592000);
            if ($month > 0) {
                $tmonth = " " . $month . " เดือน";
            }
            return "" . floor($remaintime / 31536000) . " ปี" . $tmonth;
        } elseif ($remaintime >= 2592000) {
            $l_month = $remaintime % 2592000;
            $wan = floor($l_month / 86400);
            if ($wan > 0) {
                $twan = " " . $wan . " วัน";
            }
            return "" . floor($remaintime / 2592000) . " เดือน" . $twan;
        } elseif ($remaintime >= 604800) {
            $l_subda = $remaintime % 604800;
            $wan = floor($l_subda / 86400);
            if ($wan > 0) {
                $twan = " " . $wan . " วัน";
            }
            return "" . floor($remaintime / 604800) . " สัปดาห์" . $twan;
        } elseif ($remaintime >= 86400) {
            $l_wan = $remaintime % 86400;
            $hour = floor($l_wan / 3600);
            if ($hour > 0) {
                $thour = " " . $hour . " ชั่วโมง";
            }
            return "" . floor($remaintime / 86400) . " วัน" . $thour;
        } elseif ($remaintime >= 3600) {
            $l_hour = $remaintime % 3600;
            $minute = floor($l_hour / 60);
            if ($minute > 0) {
                $tminute = " " . $minute . " นาที";
            }
            return "" . floor($remaintime / 3600) . " ชั่วโมง" . $tminute;
        } elseif ($remaintime >= 60) {
            return "" . floor($remaintime / 60) . " นาที";
        } else {
            return "" . $remaintime . " วินาที";
        }
    } else {
        return "หมดอายุ"; //บุฟเฟ่ต์
    }
}


$get_area1 = $_POST['tarangmet'];
if ($get_area1 >= 1600) {
    $rai = $get_area1 % 1600;
    $ngan = $rai / 400;
    if ($ngan > 0) {
        $t_ngan = " " . floor($ngan) . " งาน ";
        
        $wa = $rai%400;
        $t_wa = floor($wa/4)." ตารางวา";
        
    }
    echo "" . floor($get_area1 / 1600) . " ไร่ " . $t_ngan.$t_wa;
}else if($get_area1 >= 400){
    
    $ngan = $get_area1%400;
    $wa = $ngan/4;
    if($ngan > 0){
        $t_wa = floor($wa)." ตารางวา";
    }
    echo "".floor($get_area1/400)." งาน ".$t_wa;
    
}else if($get_area1 >= 4){
    echo floor($get_area1/4)." ตารางวา";
}

?>