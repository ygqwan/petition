<?php
/**
 * Created by IntelliJ IDEA.
 * User: lyo
 * Date: 2016/12/6
 * Time: 21:55
 */
require_once "./ThinkPHP/Library/Vendor/PHPMailer/class.phpmailer.php";
require_once "./ThinkPHP/Library/Vendor/PHPMailer/class.smtp.php";
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



/*
 * @desc 发送邮件
 * @param mailinfo array
 *  => from string 发送人邮箱地址
 *  => pwd string 发送人邮箱的密码
 *  => to array string 收件人邮箱地址
 *  => title 邮件主题
 *  => content 邮件内容
 * @return boolean 如果发送成功，返回true
 */
function send_mail($mailinfo) {
    $mail = new \PHPMailer;

    $mail->isSMTP();
    $mail->Host = 'smtp.domob.cn';
    $mail->SMTPAuth = true;
    $mail->Username = $mailinfo['from'];
    $mail->Password = $mailinfo['pwd'];
    $mail->SMTPSecure = 'ssl';
    $mail->Port = 465;

    $mail->setFrom($mailinfo['from']);
    foreach($mailinfo['to'] as $to) {
        $mail->addAddress($to);
    }

    $mail->CharSet = "UTF-8";
    $mail->IsHTML(false);

    $mail->Body = $mailinfo['content'];
    $mail->Subject = $mailinfo['title'];
    if ($mail->send()){
        return true;
    }
    return $mail;
}