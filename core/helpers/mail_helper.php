<?php

/*
* @author Balaji
* @name Rainbow PHP Framework
* @copyright 2019 ProThemes.Biz
*
*/

//Default PHP Mail
function default_mail ($from,$yourName,$replyTo,$replyName,$sentTo,$subject,$body,$sentToName='',$debug=false) {

    $mail = new PHPMailer(); 
    
    $mail->CharSet = 'UTF-8'; 
    
    $mail->SetFrom($from, $yourName);
    
    $mail->AddReplyTo($replyTo,$replyName);

    $mail->AddAddress($sentTo,$sentToName);
    
    $mail->Subject = $subject;
    
    $mail->IsHTML(true);
    
    $mail->MsgHTML($body);
    
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
    
    if(!$mail->Send()) {
        //Mail Failed
        if($debug) {
            var_dump($mail->ErrorInfo);
            die();
        }
        return false;
    } else {
        //Message has been sent
      return true;
    }
}

//SMTP Mail
function smtp_mail ($smtp_host,$smtp_port=587,$smtp_auth,$smtp_user,$smtp_pass,$smtp_sec='tls',$from,$yourName,$replyTo,$replyName,$sentTo,$subject,$body,$sentToName='',$debug=false) {
    $mail = new PHPMailer;
    $mail->IsSMTP();                
    $mail->Host = $smtp_host;           
    $mail->Port = $smtp_port;                           
    $mail->SMTPAuth = $smtp_auth;                              
    $mail->Username = $smtp_user;              
    $mail->Password = $smtp_pass;                 
    $mail->SMTPSecure = $smtp_sec;     
    $mail->CharSet = 'UTF-8'; 
    $mail->SetFrom($from, $yourName);
    $mail->AddReplyTo($replyTo,$replyName);
    $mail->AddAddress($sentTo,$sentToName);
    
    $mail->IsHTML(true);                
    
    $mail->Subject = $subject;
    $mail->Body    = $body;
    $mail->AltBody = 'To view the message, please use an HTML compatible email viewer!';
    
    if(!$mail->Send()) {
        //Mail Failed
        if($debug) {
            var_dump($mail->ErrorInfo);
            die();
        }
        return false;
    } else{
      //Message has been sent
      return true;
    }
}

//Send Mail
function sendMail($con, $sub, $content, $from, $fromName, $to, $toName, $replyTo=null, $replyToName=null, $debug=false){

    //Return Code -> Explanation
    //0 -> Failed to send your message
    //1 -> Your message has been sent successfully
    //2 -> Some fields are missing or empty

    $smtp_host = $smtp_port = $smtp_auth = $smtp_username = $smtp_password = $smtp_socket = $protocol = null;

    if ($sub != null && $content != null && $from != null && $fromName != null && $to != null && $toName != null){

        $htmlMessage = '<html><body>'.$content.'</body></html>';

        //Load Mail Settings
        extract(loadMailSettings($con));

        if($replyTo === null) {
            $replyTo = $from;
            $replyToName = $fromName;
        }

        if($protocol == '1'){

            //PHP Mail

            if(default_mail($from, $fromName, $replyTo, $replyToName, $to, $sub, $htmlMessage, $toName, $debug))
                return 1;
            else
                return 0;
        }else{

            //SMTP Mail

            if(smtp_mail($smtp_host, $smtp_port, isSelected($smtp_auth), $smtp_username, $smtp_password, $smtp_socket,
                         $from, $fromName, $replyTo, $replyToName, $to, $sub, $htmlMessage, $toName, $debug))
                return 1;
            else
                return 0;
        }
    }
    return 2;
}