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
        $petitionInfoList = array(
            'petition_info' =>
                array(
                    'petition' => array(
                        'id' => 1,
                        'title' => '请改善网络情况',
                        'desc' => '希望改善网络情况',
                        'owner' => 'luotonglong@domob.cn',
                        'create_time' => '2016-12-06',
                        'follower' => array('xuqian@domob.cn','lufugang@domob.cn'),
                        'vote_num' => 100,
                    ),
                    'is_mine' => false,
                    'is_follower' => true,
                    'expire_time_left' => 1,
                ),
                array(
                    'petition' => array(
                        'id' => 2,
                        'title' => '请改善网络情况2',
                        'desc' => '希望改善网络情况2',
                        'owner' => 'lufugang@domob.cn',
                        'create_time' => '2016-12-06',
                        'follower' => array('xuqian@domob.cn','luotonglong@domob.cn'),
                        'vote_num' => 100,
                    ),
                    'is_mine' => true,
                    'is_follower' => false,
                    'expire_time_left' => 1,
                ),
        );
        echo $this->json(1, "", $petitionInfoList);
    }
}