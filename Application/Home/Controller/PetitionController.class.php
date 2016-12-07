<?php
/**
 * Created by IntelliJ IDEA.
 * User: lyo
 * Date: 2016/12/6
 * Time: 12:37
 */

namespace Home\Controller;

class PetitionController extends BaseController {
    public function __construct(){
        parent::__construct();
    }

    public function vote() {
        $this->needLogin();
        $res = D('Vote')->vote(I('post.id'));
        if(isValid($res)) {
            echo $this->defaultSuccessJson();
        }else {
            echo $this->json($res['errcode'], $res['message'], $res['response']);
        }
    }

    public function close() {
        echo $this->errorJson(102);
    }

    public function start() {
        echo $this->defaultSuccessJson();
    }

    public function detail() {
        $res = D("Petition")->petition(I("get.id"));
        echo $this->jsonFromModel($res);
    }

    public function running() {
        $res = D("Petition")->running();
        echo $this->jsonFromModel($res);
    }

    public function history() {
        $res = D("Petition")->history();
        echo $this->jsonFromModel($res);
    }
}