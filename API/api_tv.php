<?php


$ctp = curl_init();
curl_setopt($ctp, CURLOPT_URL, "http://api.themoviedb.org/3/tv/on_the_air?api_key=" . $apikey);
curl_setopt($ctp, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ctp, CURLOPT_HEADER, FALSE);
curl_setopt($ctp, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response12 = curl_exec($ctp);
curl_close($ctp);
$tv_onair = json_decode($response12);

$ctr = curl_init();
curl_setopt($ctr, CURLOPT_URL, "http://api.themoviedb.org/3/tv/top_rated?api_key=" . $apikey);
curl_setopt($ctr, CURLOPT_RETURNTRANSFER, TRUE);
curl_setopt($ctr, CURLOPT_HEADER, FALSE);
curl_setopt($ctr, CURLOPT_HTTPHEADER, array("Accept: application/json"));
$response13 = curl_exec($ctr);
curl_close($ctr);
$tv_top = json_decode($response13);

$cts = curl_init();
curl_setopt_array($cts, [
  CURLOPT_URL => "https://api.themoviedb.org/3/trending/tv/day?language=en-US",
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

$response14 = curl_exec($cts);
$err = curl_error($cts);

curl_close($cts);

if ($err) {
  echo "cURL Error #:" . $err;
} 

$tv_trending = json_decode($response14);
?>