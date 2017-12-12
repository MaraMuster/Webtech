<?php
include_once "config.php";


$stadt = utf8_encode($_POST['stadt']);
$pdo = new PDO("mysql:host=$url;dbname=$database", "$username", "$pw");


$statement = $pdo->prepare("SELECT * FROM wetter WHERE Stadt = ?");
$statement->execute(array(utf8_encode($stadt)));
while ($row = $statement->fetch())
{
    $temperatur = $row['Temperatur'];
    $sonnenaufgang = $row['Sonnenaufgang'];
}


if (is_null($temperatur)) {

    $BASE_URL = "http://query.yahooapis.com/v1/public/yql";
    $yql_query = 'select item.condition from weather.forecast where woeid in (select woeid from geo.places(1) where text="' . "$stadt" .'")';
    $yql_query_url = $BASE_URL . "?q=" . urlencode($yql_query) . "&format=json";
    $session = curl_init($yql_query_url);
    curl_setopt($session, CURLOPT_RETURNTRANSFER,true);
    $json = curl_exec($session);
    $encodedjson = json_decode($json, true);
    $temp = (intval($encodedjson['query']['results']['channel']['item']['condition']['temp']) - 32 ) / 1.8;
    $text = $encodedjson['query']['results']['channel']['item']['condition']['text'];
    echo "<center>The weather in " . utf8_decode($stadt) . " is " . round($temp,1) . " degrees celsius " .  "and the status is " . $text;


    $statement = $pdo->prepare("INSERT INTO wetter (Stadt, Temperatur, Sonnenaufgang) VALUES (?, ?, ?)");
    $statement->execute(array(utf8_encode($stadt), round($temp,1), $text));

} else {

    echo "<center>The weather in " . utf8_decode($stadt) . " is " . $temperatur . " degrees celsius " .  "and the status is " . $sonnenaufgang . " proudly presented without yahoo API";

}

