<?php

/*
* @author Balaji
* @name Rainbow PHP Framework - PHP Script
* @copyright 2019 ProThemes.Biz
*
*/

function loadIp2(){
    $db = 'GeoLite2-Country.mmdb';
    $libPath = LIB_DIR.'geoip'.D_S;

    require_once $libPath.'geo.php';
    return new GeoIp2\Database\Reader($libPath.$db);
}

function ip2Country($reader, $ip, $unknown = null, $type='country'){
    if($ip !== '' || $ip !== '127.0.0.1') {
        try {
            $record = $reader->$type($ip);
            return $record->country->name;
        } catch (Exception $e) {

        }
    }
    if($unknown === null)
        $unknown = 'Unknown';
    return $unknown;
}

function ip2Code($reader, $ip, $type='country'){
    if($ip !== '' || $ip !== '127.0.0.1') {
        try {
            $record = $reader->$type($ip);
            return $record->country->isoCode;
        } catch (Exception $e) {

        }
    }
    return '';
}

function ip2Details($reader, $ip, $type='country'){
    $ipDetails = array('ip' => $ip, 'country' => '', 'code' => '', 'city' => '', 'postal' => '', 'latitude' => '', 'longitude' => '', 'timeZone' => '');
    if($ip !== '' || $ip !== '127.0.0.1') {
        try {
            $record = $reader->$type($ip);
            $ipDetails['code'] = $record->country->isoCode;
            $ipDetails['country'] = $record->country->name;
            $ipDetails['city'] = $record->city->name;
            $ipDetails['postal'] = $record->postal->code;
            $ipDetails['latitude'] = $record->location->latitude;
            $ipDetails['longitude'] = $record->location->longitude;
            $ipDetails['timeZone'] = $record->location->timeZone;
        } catch (Exception $e) {

        }
    }
    return $ipDetails;
}

function getMyGeoInfo($ip, $json=false){
    $key = $GLOBALS['item_purchase_code'];
    $url = 'http://'.strrev(MY_API_DOMAIN)."/pinky/ip.php?code=$key&ip=$ip&domain=".createLink('',true);
    $jsonData = simpleCurlGET($url);

    if($json)
        return $jsonData;
    else
        return json_decode($jsonData, true);
}