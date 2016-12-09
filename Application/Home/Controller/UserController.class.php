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
        session(C('session.user')['username'], '麟');
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
        session_destroy();
        echo $this->json(1);
        die;
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

    public function voted() {
        $this->needLogin();
        $offset = I('get.offset', 0, 'int');
        $pageSize = C("page.page_size", null, 15);
        $res = D('Petition')->myVoted($offset, $pageSize);
        echo $this->jsonFromModel($res);
    }

    public function mail() {
        $mailinfo = array(
            'from' => 'luotonglong@domob.cn',
            'pwd' => '???', //我的密码
            'to' => array('huanglele@domob.cn','1026558548@qq.com','915116900@qq.com'),
            'title' => '测试邮件',
            'content' => '不服么，你打我啊',
        );
        $res = send_mail($mailinfo);
        if ($res === true) {
            echo $this->json(1);
        }else {
            echo $this->json(-1, $res->ErrorInfo);
        }
    }

    public function mm() {
       ;;
    }
}