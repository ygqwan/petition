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
    public function json($errcode = 1, $message = '', $response = array()) {
        if ($message == '') {
            $message = getErr($errcode);
        }
        $r = array(
            'status' => $errcode,
            'message' => $message,
            'response' => $response,
        );
        if( !isset($r["response"])) {
            unset($r['response']);
        }
        header('Content-type: application/json');
        return json_encode($r);
    }

    public function jsonFromModel($modelRes) {
        return $this->json($modelRes['errcode'],
            $modelRes['message'], $modelRes['response']);
    }

    public function defaultSuccessJson($response = array()) {
        return $this->json(1, "", $response);
    }

    public function errorJson($errcode) {
        return $this->json($errcode, C("errcode.$errcode"));
    }

    public function isAdmin() {
       return in_array_case(
           session(C('session.user')['email']),
           C('admin_email')
       );
    }

    public function needLogin() {
        if(session(C('session.user')['email']) == ""){
            echo $this->json(ERR_NO_LOGIN);
            exit(1);
        }
    }
}