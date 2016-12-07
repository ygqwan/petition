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
        if(I('post.user_name') !== session(C('session.user')['username'])) {
            echo $this->errorJson(-1, '非本人签字');
            return;
        }
        $res = D('Vote')->vote(I('post.id'));
        if(isValid($res)) {
            echo $this->defaultSuccessJson();
        }else {
            echo $this->json($res['errcode'], $res['message'], $res['response']);
        }
    }

    public function close() {
        $this->needLogin();
        $res = D("Petition")->close(I('post.id'));
        echo $this->jsonFromModel($res);
    }

    public function launch() {
        //$this->needLogin();
        $res = D("Petition")->launch();
        echo $this->jsonFromModel($res);
    }

    public function detail() {
        $res = D("Petition")->petition(I("get.id"));
        echo $this->jsonFromModel($res);
    }

    public function running() {
        $offset = I('get.offset', 0, 'int');
        $pageSize = C("page.page_size", null, 15);
        $res = D("Petition")->running($offset, $pageSize);
        echo $this->jsonFromModel($res);
    }

    public function history() {
        $offset = I('get.offset', 0, 'int');
        $pageSize = C("page.page_size", null, 15);
        $res = D("Petition")->history($offset, $pageSize);
        echo $this->jsonFromModel($res);
    }
}