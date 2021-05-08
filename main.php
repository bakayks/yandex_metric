<?php
    include 'DB.php';
    include 'counterService.php';
    include 'statistic.php';

    $accessToken = 'AQAAAAABXEk2AAT_HGoTWzSW5E3mkFJLvG46dII';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if($_POST['stat'] != null){
            main($accessToken);
        }
    }

    function main($accessToken){
       $activeSites = getActiveSites();
       $yandexSites = getAll($accessToken);
       $i = 0;
       $finalArray = array();
       foreach($yandexSites as $yandexSite) {
           foreach ($activeSites as $activeSite) {
               if ($activeSite['site_name'] == $yandexSite) {
                   $yandexId = $yandexSites[$i + 1];
                   $siteName = $activeSite['site_name'];
                   $interval_in_days = $activeSite['interval_in_days'];
                   $nakrutka_coefficient = $activeSite['nakrutka_coefficient'];
                   $minimal_frequency = $activeSite['minimal_frequency'];

                   $zp = getStatsByVisits($yandexId, $accessToken);
                   $zk = getStatsBySearch($yandexId, $accessToken, $minimal_frequency, $interval_in_days, $nakrutka_coefficient);
                   $arrayOfKeys = getAllCounts($yandexId, $accessToken, $minimal_frequency, $interval_in_days);

                   $result = getResultArray($zp, $zk, $arrayOfKeys);
                   $kf = getClosest($result, $zp);
                   $arrayt = array();
                   $line = 'Site name: ' . $siteName . ', Array: ' . $result[0] . ' ' . $result[1] . ' ' . $result[2] . ' '
                       . $result[3] . ' ' . $result[4] . ' ' . $result[5] . ' ' . $result[6] . ' ' . $result[7] . ' ' . $result[8] . ' '
                       . $result[9] . ' ' . $result[10] . ', Value: ' . $kf;
                   $arrayt[] = $line;
                   $finalArray[] = $arrayt;
               }
           }
           $i++;
       }
       getFile($finalArray);
    }

    function cleanData(&$str){
        $str = preg_replace("/\t/", "\\t", $str);
        $str = preg_replace("/\r?\n/", "\\n", $str);
        if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
    }

    function getFile($data){
        // file name for download
        $filename = "website_data_" . date('Ymd') . ".csv";

        header("Content-Disposition: attachment; filename=\"$filename\"");
        header("Content-Type: application/vnd.csv");

        $flag = false;
        foreach($data as $row) {
            if(!$flag) {
                // display field/column names as first row
                echo implode("\t", array_keys($row)) . "\n";
                $flag = true;
            }
            array_walk($row, 'cleanData');
            echo implode("\t", array_values($row)) . "\n";
        }

        exit;
    }

//    function array_to_csv_download($array, $filename = "export.csv", $delimiter=";") {
//        // open raw memory as file so no temp files needed, you might run out of memory though
//        $f = fopen('php://memory', 'w');
//        // loop over the input array
//        foreach ($array as $line) {
//            // generate csv lines from the inner arrays
//            fputcsv($f, $line, $delimiter);
//        }
//        // reset the file pointer to the start of the file
//        fseek($f, 0);
//        // tell the browser it's going to be a csv file
//        header('Content-Type: application/csv');
//        // tell the browser we want to save it instead of displaying it
//        header('Content-Disposition: attachment; filename="'.$filename.'";');
//        // make php send the generated csv lines to the browser
//        fpassthru($f);
//        fclose($f);
//    }

    function getResultArray($zp, $zk, $arrayOfKeys) {
        if($zp > $zk) {
            $base = intval($zp / $zk); // отброс десятичной части
            $finalArray = array($base, $base + 0.1, $base + 0.2, $base + 0.3, $base + 0.4, $base + 0.5, $base + 0.6, $base + 0.7, $base + 0.8, $base + 0.9, $base + 1);
            for($i = 0; $i < 11; $i++) {
                $sumOfFreqs = 0;
                for($j = 0; $j < count($arrayOfKeys); $j++) {
                    $sumOfFreqs += $arrayOfKeys[$j] * $finalArray[$i];
                }
                $finalArray[$i] = $sumOfFreqs;
            }
            return $finalArray;
        } else {
            $base = round($zp / $zk, 1); // округление до 1 знака после запятой
            $finalArray = array($base, $base + 0.01, $base + 0.02, $base + 0.03, $base + 0.04, $base + 0.05, $base + 0.06, $base + 0.07, $base + 0.08, $base + 0.09, $base + 0.1);
            for($i = 0; $i < 11; $i++) {
                $sumOfFreqs = 0;
                for($j = 0; $j < count($arrayOfKeys); $j++) {
                    $sumOfFreqs += $arrayOfKeys[$j] * $finalArray[$i];
                }
                $finalArray[$i] = $sumOfFreqs;
            }
            return $finalArray;
        }
    }


    function getClosest($ress, $zp) {
        $closest = null;
        $index = null;
        for($k = 0; $k < count($ress); $k++) {
            if ($closest === null || abs($zp - $closest) > abs($ress[$k] - $zp)) {
                $closest = $ress[$k];
            }
        }
        return $closest;
    }
?>