<?php

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */
 
function successMsg($msg){
    return '
    <div class="alert alert-success alert-dismissable alert-premium">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
        <b>'.$GLOBALS['lang']['RF20'].'</b> '.$msg.'
    </div>';
}

function errorMsg($msg){
    return '
    <div class="alert alert-danger alert-dismissable alert-premium">
        <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
        <b>'.$GLOBALS['lang']['RF20'].'</b> ' . $msg . '
    </div>';
}

function previewBox(){
    $cssCode = $htmlCode = '';
    $cssCode = '
    <style>
    .previewFloatingBox {
        background: #2ecc71;
        border-right: 4px 4px;
        padding: 5px;;
        width: 150px;
        z-index: 10000;
        position: fixed;
        left:0;
        top:200px;
        border: 1px solid rgba(0, 0, 0, 0.2);
        border-radius: 0 4px 4px 0;
    }
    .btn {
        display: inline-block;
        margin-bottom: 0;
        font-weight: 400;
        text-align: center;
        vertical-align: middle;
        cursor: pointer;
        background-image: none;
        white-space: nowrap;
        padding: 6px 12px;
        font-size: 14px;
        line-height: 1.42857143;
        border-radius: 4px;
        -webkit-user-select: none;
        -moz-user-select: none;
        -ms-user-select: none;
        user-select: none;
        color: #fff;
        background-color: #eb4d4b;
        border-color: #eb4d4b;
        text-decoration: none;
    }
    </style>
    ';
    
    $htmlCode = '
    <nav class="previewFloatingBox">
    <h4>'.$GLOBALS['lang']['CH801'].'</h4>
    <a class="btn btn-info" href="'.createLink('theme/unset/',true).'">'.$GLOBALS['lang']['CH418'].'</a>
    </nav>
    ';
    return $cssCode.$htmlCode;
}

function detectAdBlock($con){
    $data1 = $data2 = '';
    $taskData =  mysqli_query($con, "SELECT data FROM ".DB_PREFIX."rainbowphp where task='adblock'");
    $taskRow = mysqli_fetch_assoc($taskData);
    $adblock = dbStrToArr($taskRow['data']);
    if(isset($adblock['enable']) && isSelected($adblock['enable'])){
        if($adblock['options'] == 'link'){
            $data1 = $adblock['link'];
        }else if($adblock['options'] == 'close'){
            $data1 = $adblock['close']['title'];
            $data2 = $adblock['close']['msg'];
        }else if($adblock['options'] == 'force'){
            $data1 = $adblock['force']['title'];
            $data2 = $adblock['force']['msg']; 
        }
        return array(true,$adblock['options'],$data1,$data2);
    }
    return array(false);
}

function detectAdBlockScript($con){
    $master = $data1 = $data2 = '';
    $enable = false;
    $taskData =  mysqli_query($con, "SELECT data FROM ".DB_PREFIX."rainbowphp where task='adblock'");
    $taskRow = mysqli_fetch_assoc($taskData);
    $adblock = dbStrToArr($taskRow['data']);
    if(isset($adblock['enable']) && isSelected($adblock['enable'])){
        if($adblock['options'] == 'link'){
            $data1 = shortCodeFilter($adblock['link']);
        }else if($adblock['options'] == 'close'){
            $data1 = makeJavascriptStr(htmlspecialchars_decode(shortCodeFilter($adblock['close']['title'])));
            $data2 = makeJavascriptStr(htmlspecialchars_decode(shortCodeFilter($adblock['close']['msg'])));
        }else if($adblock['options'] == 'force'){
            $data1 = makeJavascriptStr(htmlspecialchars_decode(shortCodeFilter($adblock['close']['title'])));
            $data2 = makeJavascriptStr(htmlspecialchars_decode(shortCodeFilter($adblock['close']['msg'])));
        }
        $master .= 'var xdEnabled = true;';
        $master .= 'var xdOption = "'.$adblock['options'].'";';
        $master .= 'var xdData1 = \''.$data1.'\';';
        $master .= 'var xdData2 = "'.$data2.'";';
        $enable = true;
    }else{
        $master .= 'var xdEnabled = false;';
    }
    return array('data' => $master, 'enable' => $enable);
}

function makeJavascriptStr($string, $echo=false){
    if($echo)
        echo str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));  
    else
        return str_replace("\n", '\n', str_replace('"', '\"', addcslashes(str_replace("\r", '', (string)$string), "\0..\37'\\")));  
}

function makeJavascriptBol($bol, $echo=false){
    $data = 'false';
    if($bol)
        $data = 'true';

    if($echo)
        echo $data;
    else
        return $data;
}

function makeJavascriptArray($array){
    $str =  array_map('makeJavascriptStr', $array);
    $str =  array_map('trim', $array);
    return '["' . implode('","', $str) . '"]';
}

function defineJs($data, $value=null, $return=false){
    $output = '';
    if (is_array($data)){
        foreach ($data as $key => &$value) {
            if(is_int($value))
                $output .= 'var ' . $key . ' = ' . makeJavascriptStr($value) . ';' . PHP_EOL;
            elseif (is_bool($value))
                $output .= 'var ' . $key . ' = ' . makeJavascriptBol($value) . ';' . PHP_EOL;
            elseif(is_array($value))
                $output .= 'var ' . $key . ' = ' . makeJavascriptArray($value) . ';' . PHP_EOL;
            elseif($value === '')
                $output .= 'var ' . $key . ' = \'' . makeJavascriptStr($value) . '\';' . PHP_EOL;
            elseif($value[0] === '{' && substr($value, -1) === '}')
                $output .= 'var ' . $key . ' = ' . $value . ';' . PHP_EOL;
            elseif($value[0] === '[' && substr($value, -1) === ']')
                $output .= 'var ' . $key . ' = ' . $value . ';' . PHP_EOL;
            else
                $output .= 'var ' . $key . ' = \'' . makeJavascriptStr($value) . '\';' . PHP_EOL;
        }
    }else {
        if(is_int($value))
            $output = 'var ' . $data . ' = ' . makeJavascriptStr($value) . ';' . PHP_EOL;
        elseif (is_bool($value))
            $output .= 'var ' . $data . ' = ' . makeJavascriptBol($value) . ';' . PHP_EOL;
        elseif(is_array($value))
            $output = 'var ' . $data . ' = ' . makeJavascriptArray($value) . ';' . PHP_EOL;
        elseif($value === '')
            $output .= 'var ' . $data . ' = \'' . makeJavascriptStr($value) . '\';' . PHP_EOL;
        elseif($value[0] === '{' && substr($value, -1) === '}')
            $output .= 'var ' . $data . ' = ' . $value . ';' . PHP_EOL;
        else
            $output = 'var ' . $data . ' = \'' . makeJavascriptStr($value) . '\';' . PHP_EOL;
    }
    if(!$return)
        echo $output;
    return $output;
}

function defineCss($data, $compressed = true, $return=false){
    $output = '';

    $nLine = PHP_EOL;
    if($compressed) $nLine = '';

    if (is_array($data)) {
        foreach ($data as $key => $values) {
            $output .= $key . '{' .$nLine;
            if (is_array($values)) {
                foreach($values as $value)
                    $output .= $value .';'.$nLine;
            }else{
                $output .= $values.$nLine;
            }
            $output .=   '}' .$nLine;
        }
    }
    if(!$return)
        echo $output;
    return $output;
}

function cssBackground($gradient = false, $color1='#fff', $color2='#fff', $return = true){
    $output = '';

    if($gradient)
        $output = 'background: linear-gradient(-180deg, '.$color1.', '.$color2.');background: -webkit-linear-gradient(-180deg, '.$color1.', '.$color2.')';
    else
        $output = 'background: '.$color1;

    if(!$return)
       echo $output;
    return $output;
}


function getUserInfo($username,$con){
   
    $row = mysqliPreparedQuery($con, "SELECT * FROM ".DB_PREFIX."users WHERE username=?",'s',array($username));
    if($row !== false){
        return $row;
    }
    return false;
}

function getUserID($username,$con){
   
    $data = mysqliPreparedQuery($con, "SELECT * FROM ".DB_PREFIX."users WHERE username=?",'s',array($username));
    if($data !== false){
        //Username found
        $userID = Trim($data['id']);  
        return $userID;
    }
    return false;
}

function unqFile($path,$filename){
    if (file_exists($path.$filename)) {
        $filename = rand(1, 99999999) . "_" . $filename;
        return unqFile($path,$filename);
    }else{
        return $filename;
    }
}

function adminInputTxt($name,$id=null,$title=null,$placeholder='Enter text',$value=null,$class=null,$mClass=null,$return=false){

    if($id !== NULL)
        $id = 'id="'.$id.'"';

    if($title === NULL)
        $title = '';
    else
        $title = '<label for="'.$name.'">'.shortCodeFilter($title).'</label>';

    $placeholder = shortCodeFilter($placeholder);
    $value = htmlentities($value);

    $data = '<div class="form-group '.$mClass.'">
        '.$title.'
        <input type="text" placeholder="'.$placeholder.'" '.$id.' name="'.$name.'" value="'.$value.'" class="form-control '.$class.'">
    </div>';

    if(!$return)
        echo $data;

    return $data;
}

function adminTextArea($name,$id=null,$title=null,$placeholder='Enter text',$value=null,$otherAttributes='',$class=null,$mClass=null,$return=false){

    if($id !== NULL)
        $id = 'id="'.$id.'"';

    if($title === NULL)
        $title = '';
    else
        $title = '<label for="'.$name.'">'.shortCodeFilter($title).'</label>';

    $placeholder = shortCodeFilter($placeholder);
    $value = htmlentities($value);

    $data = '<div class="form-group '.$mClass.'">
        '.$title.'
        <textarea placeholder="'.$placeholder.'" '.$id.' name="'.$name.'" '.$otherAttributes.' class="form-control '.$class.'">'.$value.'</textarea>
    </div>';

    if(!$return)
        echo $data;

    return $data;
}


function adminInputNum($name,$id=null,$title=null,$placeholder='Enter text',$value=null,$step=null,$class=null,$mClass=null,$return=false){

    if($id !== NULL)
        $id = 'id="'.$id.'"';

    if($step !== NULL)
        $step = 'step="'.$step.'"';

    if($title === NULL)
        $title = '';
    else
        $title = '<label for="'.$name.'">'.shortCodeFilter($title).'</label>';

    $placeholder = shortCodeFilter($placeholder);
    $value = floatval($value);

    $data = '<div class="form-group '.$mClass.'">
        '.$title.'
        <input type="number" placeholder="'.$placeholder.'" '.$id.' '.$step.' name="'.$name.'" value="'.$value.'" class="form-control '.$class.'">
    </div>';

    if(!$return)
        echo $data;

    return $data;
}

function adminInputFile($name,$id,$title=null,$placeholder='Enter text',$value=null,$type=1,$class=null,$mClass=null,$return=false){
    $typeClass = '';
    $sid = 'id="'.$id.'"';

    if($title === NULL)
        $title = '';
    else
        $title = '<label for="'.$name.'">'.shortCodeFilter($title).'</label>';

    $placeholder = shortCodeFilter($placeholder);
    $value = htmlentities($value);

    if($type === 1)
        $typeClass = 'fa fa-picture-o';
    elseif($type === 3)
        $typeClass = 'fa fa-file-audio-o';

    $data = '<div class="form-group '.$mClass.'">
        '.$title.'
       <div class="input-group">
         <input type="text" placeholder="'.$placeholder.'" '.$sid.' name="'.$name.'" value="'.$value.'" class="form-control '.$class.'">
         <span class="input-group-addon">
            <a class="'.$typeClass.' iframe-btn" href="'. $GLOBALS['baseURL'] .'core/library/filemanager/dialog.php?type='.$type.'&field_id='.$id.'"></a>
         </span>
        </div>
    </div>';

    if(!$return)
        echo $data;

    return $data;
}

function adminCheckbox($name,$id=null,$title=null,$afterCheckBoxTitle=null,$value=null,$noOpposite=true,$class=null,$mClass=null,$return=false){

    if($id !== NULL)
        $id = 'id="'.$id.'"';

    if($title === NULL)
        $title = '';
    else
        $title = '<label for="'.$name.'">'.shortCodeFilter($title).'</label>';

    $data = '<div class="form-group '.$mClass.'">
        '.$title.'
        <input '. isSelected($value, $noOpposite, 2, null, true) .' type="checkbox" '.$id.' name="'.$name.'" value="'.$value.'" class="'.$class.'"> '.$afterCheckBoxTitle.'
    </div>';

    if(!$return)
        echo $data;

    return $data;
}

function adminSelect($name,$id=null,$title=null,$data=array(), $selectedValue=null, $values=true,$class=null,$mClass=null,$return=false){

    if($id !== NULL)
        $id = 'id="'.$id.'"';

    if($title === NULL)
        $title = '';
    else
        $title = '<label for="'.$name.'">'.shortCodeFilter($title).'</label>';

    $options = '';
    foreach($data as $val => $key){

        if(!$values)
            $val = $key;

        $selected = '';
        if($val == $selectedValue)
            $selected = ' selected="" ';

        $options .= '<option '.$selected.' value="'.$val.'">'.$key.'</option>';
    }

    $data = '<div class="form-group '.$mClass.'">
        '.$title.'
        <select '.$id.' name="'.$name.'" class="form-control '.$class.'">
            '.$options.'
        </select>
    </div>';

    if(!$return)
        echo $data;

    return $data;
}

function adminColorPicker($name,$id=null,$title=null,$placeholder='Enter text',$value=null,$class=null,$mClass=null,$return=false){

    if($id !== NULL)
        $id = 'id="'.$id.'"';

    if($title === NULL)
        $title = '';
    else
        $title = '<label for="'.$name.'">'.shortCodeFilter($title).'</label>';

    $placeholder = shortCodeFilter($placeholder);
    $value = htmlentities($value);

    $data = $title.'
    <div class="input-group colorpicker-component '.$mClass.'">
        <input type="text" placeholder="'.$placeholder.'" '.$id.' name="'.$name.'" value="'.$value.'" class="form-control colorpicker '.$class.'">
        <span class="input-group-addon"><i></i></span>
    </div>';

    if(!$return)
        echo $data;

    return $data;
}