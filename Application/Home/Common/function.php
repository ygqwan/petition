<?php
/**
 * Created by IntelliJ IDEA.
 * User: lyo
 * Date: 2016/12/6
 * Time: 21:55
 */

function model_res($errcode = 1, $message = '', $res = array()) {
    if($message == '') {
        $message = getErr($errcode);
    }
    return array(
        'errcode' => $errcode,
        'message' => $message,
        'response' => $res,
    );
}

function getErr($errcode) {
    if ($errcode === ERR_SUCCESS) {
        return '';
    }

    if(isset(C("errcode")[$errcode])) {
        return C('errcode.'.$errcode);
    }else {
        return '未知错误';
    }
}

function isValid($res) {
    return !isset($res['errcode']) || $res['errcode'] == ERR_SUCCESS;
}
    //发邮件
function sendemail($to,$title,$body){
    header("content-type:text/html;charset=utf-8");
    ini_set("magic_quotes_runtime",0);
    try {
        require_once './ThinkPHP/Library/Vendor/PHPMailer/class.phpmailer.php';
        $mail = new \PHPMailer;
        $mail->IsSMTP();
        $mail->CharSet='UTF-8'; //设置邮件的字符编码，这很重要，不然中文乱码
        $mail->SMTPAuth = true; //开启认证
        $mail->Port = C('email.smtp_port');
        $mail->Host = C('email.smtp_host');
        $mail->Username = $info['smtp_user'];
        $mail->Password = $info['smtp_pass'];
        $mail->AddReplyTo($info['smtp_user'],$info['send_name']);//回复地址
        $mail->From = $info['smtp_user'];
        $mail->FromName = $info['send_name'];
        $mail->AddAddress($to);
        $mail->Subject = $title;
        $mail->Body = $body;
        $mail->WordWrap = 80; // 设置每行字符串的长度
        $mail->IsHTML(true);
        $mail->Send();
        return true;
    } catch (phpmailerException $e) {
        echo "邮件发送失败：".$e->errorMessage();
    }
}