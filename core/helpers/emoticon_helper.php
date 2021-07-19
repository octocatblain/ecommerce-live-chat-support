<?php

/*
* @author Balaji
* @name Rainbow PHP Framework
* @copyright 2019 ProThemes.Biz
*
*/


function loadEmoticonPack($con, $addtional = false, $pack=1){
    $emoticon = false;
    $cssData = '';
    $replacementIcons = $emoticonsData = $newArr = array();
    if($addtional)
        $qu = 'SELECT name,status,type,data FROM ' . DB_PREFIX . 'emoticon WHERE id='.$pack;
    else
        $qu = 'SELECT name,status,type FROM ' . DB_PREFIX . 'emoticon WHERE id='.$pack;
    $result = mysqli_query($con, $qu);
    $row = mysqli_fetch_array($result);
    if(isSelected($row['status'])){
        $result = mysqli_query($con, 'SELECT name,code,data,display,status FROM ' . DB_PREFIX . 'emoticon_data WHERE emoticon='.$pack);
        while ($icons = mysqli_fetch_array($result)) {
           if(isSelected($icons['status'])){
               $emoticon = true;
               if($row['type'] === 'image')
                   $icons['data'] = '<img title="'. $icons['name'].'" alt="'. $icons['name'].'" src="'. $icons['data'].'" /><div style="clear:both"></div>';
               else
                   $icons['data'] = htmlspecialchars_decode($icons['data']);
               $replacementIcons[$icons['code']] = $icons['data'];
               $emoticonsData[$icons['code']] = array($icons['display'], $icons['name'], $icons['data'],$icons['code']);
               if($addtional) $newArr[$icons['code']] = array(strlen(htmlspecialchars_decode($icons['code'])),$icons['data']);
            }
        }
    }
    if($addtional){
        $cssData = $row['data'];
        if ($emoticon)
            arsort($newArr);
   }
    return array($emoticon, $replacementIcons, $emoticonsData, $newArr, $cssData);
}

function emoticons($text,$replacementIcons){
    return strtr($text, $replacementIcons);
}

