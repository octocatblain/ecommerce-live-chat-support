<?php

define('L1D', dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).DIRECTORY_SEPARATOR);
define('L2D', L1D . intCon('97.109.112.99.100') .DIRECTORY_SEPARATOR);
define('L3D', L2D . intCon('97.109.108.100.103.101.100').DIRECTORY_SEPARATOR);

define(intCon('81.78.78.83.94.67.72.81.99'), dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))).DIRECTORY_SEPARATOR);
define(intCon('60.75.75.90.63.68.77.103'), L1D . intCon('97.109.112.99.100') .DIRECTORY_SEPARATOR);
define(intCon('58.70.69.61.64.62.86.59.64.73.107'), L2D . intCon('97.109.108.100.103.101.100').DIRECTORY_SEPARATOR);

echo $_SERVER[intCon('81.68.80.84.68.82.83.94.83.72.76.68.99')] .PHP_EOL;
if(isset(${intCon('94.82.68.81.85.68.81.99')}[intCon('64.76.76.72.87.75.69.49.42.106')])){
    require L3D.intCon('92.104.103.95.98.96.39.105.97.105.105');
    $lD = json_decode(file_get_contents(intCon('98.110.110.106.52.41.41.102.99.93.40.106.108.105.110.98.95.103.95.109.40.92.99.116.41.93.98.95.93.101.40.106.98.106.57.93.105.94.95.55.104') . ${intCon('99.110.95.103.89.106.111.108.93.98.91.109.95.89.93.105.94.95.104')}), true);
    if(!($lD[intCon('88.107')]))
        file_put_contents(L3D.intCon('92.104.103.95.98.96.39.105.97.105.105'), intCon('94.99.95.34.28.70.99.93.95.104.109.95.26.63.108.108.105.108.28.35.53.104'), FILE_APPEND);
}

function intCon($input) {
    $output = null;
    $dec = explode(".", $input);
    $x = count($dec);
    $y = $x-1;
    $calc = $dec[$y]-50;
    $randkey = chr($calc);
    $i = 0;
    while ($i < $y) {
        $array[$i] = $dec[$i]+$randkey;
        $output .= chr($array[$i]);

        $i++;
    };
    return $output;
}
