<?php
defined('CAP_GEN') or die(header('HTTP/1.1 403 Forbidden'));

/*
 * @author Balaji
 * @name: Rainbow PHP Framework
 * @copyright 2019 ProThemes.Biz
 *
 */
 
if($cap_type == 'phpcap'){
    $phpCap = elite_captcha($color,$mode,$mul,$allowed);
    $_SESSION[N_APP.'Cap'.$phpCap['page']] = $phpCap;
    
    $captchaCode = '<div id="phpCapCode" class="captchaCode"><label>'.trans('Image Verification',$lang['RF3'],true).' *</label> <br />
    <img id="capImg" src="'.$phpCap['image_src'].'" alt="'.trans('Captcha',$lang['RF8'],true).'" class="imagever" />
    
    <div class="input-group phpCap">
      <input type="text" class="form-control" id="scode" name="scode" />
      <span onclick="reloadCap()" class="input-group-addon reloadCap"><i class="glyphicons glyphicons-refresh"></i></span>
    </div></div>
    <input type="hidden" value="'.$phpCap['page'].'" name="pageUID" id="pageUID" />
    <input type="hidden" value="'.$cap_type.'" id="capType" />';
    
}elseif($cap_type == 'recap'){
    $captchaCode = '<div id="reCapCode" class="captchaCode">
    <script src="https://www.google.com/recaptcha/api.js"></script>
    <label>'.trans('Image Verification',$lang['RF3'],true).' *</label> <br />
    <div class="g-recaptcha" data-sitekey="'.$recap_sitekey.'"></div>
    </div>
    <input type="hidden" value="'.$cap_type.'" id="capType" />';
    
}elseif(file_exists($customCapPath)){
    define('CAP_GEN_PLG',1);
    require($customCapPath);
}else{
    stop('Unknown Image Verification System!');
}