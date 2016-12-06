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