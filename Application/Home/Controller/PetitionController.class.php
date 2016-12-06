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
        echo $this->errorJson(101);
    }

    public function close() {
        echo $this->errorJson(102);
    }

    public function start() {
        echo $this->defaultSuccessJson();
    }

    public function detail() {
        $petitionInfo = array(
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
        );
        echo $this->defaultSuccessJson($petitionInfo);
    }

    public function running() {
        $petitionInfoList = array(
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
        echo $this->defaultSuccessJson($petitionInfoList);
    }

    public function history() {
        $petitionInfoList = array(
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
        echo $this->defaultSuccessJson($petitionInfoList);
    }
}