<?php
/**
 * Created by IntelliJ IDEA.
 * User: lyo
 * Date: 2016/12/6
 * Time: 11:40
 */

namespace Home\Controller;
use Think\Controller;

require_once './ThinkPHP/Library/Vendor/phpCAS/source/CAS.php';
class UserController extends BaseController {
    public function __construct() {

        parent::__construct();
    }

    public function info() {
        $userEmail = I('GET.user_email', '', 'string');
        if($userEmail == ''){
            echo $this->json(1, '', D("Petition")->buildUserInfo()['response']);
        }
    }

    public function login() {
        session(C('session.user')['email'], I('post.user_email'));
        echo $this->json(1, "登录成功", D("Petition")->buildUserInfo()['response']);
        die;
        // Enable debugging
        \phpCAS::setDebug();
        // Enable verbose error messages. Disable in production!
        \phpCAS::setVerbose(true);

        // Initialize phpCAS
        \phpCAS::client(CAS_VERSION_2_0, C('cas.host'), C('cas.port'), C('cas.context'));
        \phpCAS::setNoCasServerValidation();

        // force CAS authentication
        \phpCAS::forceAuthentication();
        if(\phpCAS::getUser() == '') {
            echo $this->json(-1, '登录失败');
            return;
        }
        session(C('session.user')['email'], \phpCAS::getUser());
        echo $this->json(0, "登录成功", D("Petition")->buildUserInfo()['response']);
    }

    public function logout() {
        // logout if desired
        if (isset($_REQUEST['logout'])) {
            \phpCAS::logout();
        }
        // Enable debugging
        \phpCAS::setDebug();
        // Enable verbose error messages. Disable in production!
        \phpCAS::setVerbose(true);

        // Initialize phpCAS
        \phpCAS::client(CAS_VERSION_2_0, C('cas.host'), C('cas.port'), C('cas.context'));
        \phpCAS::setNoCasServerValidation();

        \phpCAS::logout();


    }

    public function petition() {
        $this->needLogin();
        $offset = I('get.offset', 0, 'int');
        $pageSize = C("page.page_size", null, 15);
        $res = D('Petition')->myOwner($offset, $pageSize);
        echo $this->jsonFromModel($res);
    }
}