<?php

$curltrending = curl_init();

curl_setopt_array($curltrending, [
  CURLOPT_URL => "https://api.themoviedb.org/3/trending/all/day?language=en-US",
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => "",
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 30,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => "GET",
  CURLOPT_HTTPHEADER => [
    "Authorization: Bearer eyJhbGciOiJIUzI1NiJ9.eyJhdWQiOiI0ZDM4ZTUwOGYwNWE4MWZmZmQyNTgyNmQ4ZWQ0MGYyYSIsIm5iZiI6MTc0OTI0OTY4NS4yNDgsInN1YiI6IjY4NDM2ZTk1MWVhNjUxYmExY2RlNjUyYiIsInNjb3BlcyI6WyJhcGlfcmVhZCJdLCJ2ZXJzaW9uIjoxfQ.XeJlFVpeha96DDdnKQ2nnSIR4-8UJNgDBSbjbsHq_aU",
    "accept: application/json"
  ],
]);

$responsetrending = curl_exec($curltrending);
$err = curl_error($curltrending);

curl_close($curltrending);

if ($err) {
  echo "cURL Error #:" . $err;
} 

$trending = json_decode($responsetrending);