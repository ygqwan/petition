<?php
/**
 * Created by IntelliJ IDEA.
 * User: lyo
 * Date: 2016/12/6
 * Time: 11:40
 */

namespace Home\Controller;
use Think\Controller;


class UserController extends BaseController {
    public function __construct() {
        parent::__construct();
    }

    public function login() {
        session(C('session.user')['email'], 'luotonglong@domob.cn');
        echo $this->json(0, "登录成功");
    }

    public function myPetition() {
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