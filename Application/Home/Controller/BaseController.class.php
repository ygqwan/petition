<?php
/**
 * Created by IntelliJ IDEA.
 * User: lyo
 * Date: 2016/12/6
 * Time: 11:42
 */

namespace Home\Controller;
use Think\Controller;


class BaseController extends Controller {
    public function __construct(){
        parent::__construct();
    }
    /*
     * 返回json
     */
    public function json($status, $message, $response = array()) {
        $r = array(
            'status' => $status,
            'message' => $message,
            'response' => $response,
        );
        if( !isset($r["response"])) {
            unset($r['response']);
        }
        return json_encode($r);
    }

    public function defaultSuccessJson($response) {
        return $this->json(1, "", $response);
    }

    public function errorJson($status) {
        return $this->json($status, C("errcode.$status"));
    }
}