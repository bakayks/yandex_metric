<?php

      function getAll($access_token){
          $curl = curl_init();

          curl_setopt_array($curl, array(
              CURLOPT_URL => 'api-metrika.yandex.net/management/v1/counters',
              CURLOPT_RETURNTRANSFER => true,
              CURLOPT_ENCODING => '',
              CURLOPT_MAXREDIRS => 10,
              CURLOPT_TIMEOUT => 0,
              CURLOPT_FOLLOWLOCATION => true,
              CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
              CURLOPT_CUSTOMREQUEST => 'GET',
              CURLOPT_HTTPHEADER => array(
                  'Authorization: OAuth ' . $access_token,
                  'Content-Type: application/x-yametrika+json',
                  'Content-Length: 127',
                  'Cookie: JSESSIONID=node0sqg68rsk8ho81bkonvls4qnmz16014342.node0'
              ),
          ));
          $response = curl_exec($curl);
          curl_close($curl);
          $res = json_decode($response, true);

          $yandexSites = array();
          foreach($res['counters'] as $counter){
              $yandexSites[] = $counter['site'];
              $yandexSites[] = $counter['id'];

          }
          return $yandexSites;
      }

//    function getById($access_token, $id){
//    $curl = curl_init();
//
//    curl_setopt_array($curl, array(
//        CURLOPT_URL => 'https://api-metrika.yandex.net/management/v1/counter/' . $id,
//        CURLOPT_RETURNTRANSFER => true,
//        CURLOPT_ENCODING => '',
//        CURLOPT_MAXREDIRS => 10,
//        CURLOPT_TIMEOUT => 0,
//        CURLOPT_FOLLOWLOCATION => true,
//        CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//        CURLOPT_CUSTOMREQUEST => 'GET',
//        CURLOPT_HTTPHEADER => array(
//            'Authorization: OAuth ' . $access_token,
//            'Content-Type: application/x-yametrika+json',
//            'Content-Length: 127',
//            'Cookie: JSESSIONID=node01sm82jipjiyh213q1nzrmabtf516015564.node0'
//        ),
//    ));
//
//    $response = curl_exec($curl);
//
//    curl_close($curl);
//    echo $response;
//}

?>
