<?php
    function getStatsBySearch($id, $oauth_token, $minimalCount, $daysAgo, $kn){
        $params = array(
            'ids'        => $id,
            'metrics'    => 'ym:s:visits',
            'dimensions' => 'ym:s:searchPhrase',
            'date1'      => $daysAgo . 'daysAgo',
            'date2'      => 'today'
        );

        $ch = curl_init('https://api-metrika.yandex.net/stat/v1/data?' . urldecode(http_build_query($params)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $oauth_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res, true);
        $sumOfCount = 0;
        foreach ($res['data'] as $row) {
            if($row['metrics'][0] >= $minimalCount && ($row['dimensions'][0]['favicon'] == 'm.yandex.ru' or 'www.yandex.ru')){
                $sumOfCount = $sumOfCount + $row['metrics'][0];
            }
        }
        return $sumOfCount * $kn;
    }

    function getStatsByVisits($id, $oauth_token){
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api-metrika.yandex.ru/stat/v1/data?metrics=ym:s:visits&dimensions=ym:s:searchEngine&date1=6daysAgo&date2=today&limit=10000&offset=1&ids='.$id.'&oauth_token=' . $oauth_token,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_HTTPHEADER => array(
                'Cookie: yandexuid=279606251619885658; JSESSIONID=node01il7wj5v1g1dq5745hja7zn7a29133531.node0'
            ),
        ));

        $response = curl_exec($curl);

        curl_close($curl);

        $res = json_decode($response, true);

        $count = 0;
        foreach ($res['data'] as $row) {
            if($row['dimensions'][0]['favicon'] == 'google.com' || $row['dimensions'][0]['favicon'] == 'm.yandex.ru' || $row['dimensions'][0]['favicon'] == 'www.yandex.ru'){
                $count = $count + $row['metrics'][0];
            }
        }
        return $count/7;
    }

    function getAllCounts($id, $oauth_token, $minimalCount, $daysAgo){
        $params = array(
            'ids'        => $id,
            'metrics'    => 'ym:s:visits',
            'dimensions' => 'ym:s:searchPhrase',
            'date1'      => $daysAgo . 'daysAgo',
            'date2'      => 'today'
        );

        $ch = curl_init('https://api-metrika.yandex.net/stat/v1/data?' . urldecode(http_build_query($params)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $oauth_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res, true);
        $words = array();
        foreach ($res['data'] as $row) {
            if($row['metrics'][0] >= $minimalCount && ($row['dimensions'][0]['favicon'] == 'm.yandex.ru' or 'www.yandex.ru')){
                $words[] = $row['metrics'][0];
            }
        }
        return $words;
    }

    function getAllPhrases($id, $oauth_token, $minimalCount, $daysAgo){
        $params = array(
            'ids'        => $id,
            'metrics'    => 'ym:s:visits',
            'dimensions' => 'ym:s:searchPhrase',
            'date1'      => $daysAgo . 'daysAgo',
            'date2'      => 'today'
        );

        $ch = curl_init('https://api-metrika.yandex.net/stat/v1/data?' . urldecode(http_build_query($params)));
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: OAuth ' . $oauth_token));
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        curl_setopt($ch, CURLOPT_HEADER, false);
        $res = curl_exec($ch);
        curl_close($ch);

        $res = json_decode($res, true);
        $words = array();
        foreach ($res['data'] as $row) {
            if($row['metrics'][0] >= $minimalCount && ($row['dimensions'][0]['favicon'] == 'm.yandex.ru' or 'www.yandex.ru')){
                $words[] = $row['dimensions'][0]['name'];
            }
        }
        return $words;
    }
?>