<?php
//注意本文件名后缀是.class.php
//namespace 后面不跟\

namespace Home\Controller;
use Think\Controller;



class IndexController extends Controller {
    public function index(){
        //实例化一个自定义的类
        //如果要实例化一张表（不是从类实例）M('news')
        $art = D('Article');

        print_r($art->getOneArticle(5));
    }

    public function from_m(){
        //直接从表news中查询结果
        $m = M("news");
        $w = array(
            'id' => 5,
        );
        $res = $m->field('id,title, SUM(id)')->where($w)
            ->order('id desc, title')->limit(5,10)//第5行开始，取10条
            ->select();
        if($res == false || $res ==null) {//查询失败

        }
        //$m->page('1,10')->select(); // 查询第一页数据
        //select返回二维数组， find返回一维数组

        print_r($res);
    }

    public function insert() {
        $m = M("news"); //D('Article');
        $m->create();
        //对象方式
        $m->title = "cao";
        $m->add();
        //数据方式
        $data = array(
            "slug" => "hehe",
        );
        //最终只有slug字段，hehe被写入
        //可以->filter()过滤数据
        $m->field('slug')->data($data)->add();

    }

    public function get() {
        //获取满足条件的第一行的title字段
        $title = M("news")->where('id=5')->getField('title');

        //如果需要获取满足条件所有行的title,会是一个一维数组
        $titles = M("news")->where('title != ""')->getField('title', true);
        

        //如果是两个字段
        //返回以id作为key, title作为value的关联数组
        /*
        array(
            '1' => "不错的文章",
            '2' => "真是不错的啊",
        );
        */
        $list = M("news")->where('title != ""')->getField('id,title', 10);//返回10条数据
        
        //如果三个及其以上
        /*
        getField("id, title, text")
        array(
            '1' => array(
                'id' => 1,
                'title' => '不错的文章',
                'text' => "就是嘛",
            ),
            '2' => array(
                ...
            ),
        );
        */
        $list = M("news")->where('title != ""')
            ->getField("id, title, text", 10);
    }

    public function update() {
        $news = M("news");
        //对象方式
        $news->title = "新标题";
        //数据方式
        $newD = array(
            'slug' => '新的啥来着',
        );
        //save返回值
        //false: 更新出错
        //如果正常返回更新成功的条数

        //field指定需要更新的字段
        $news->field('slug')->where('id=6')->save($newD);


        /*还有如下更新方式*/
        //把title更新成新title2
        $news->where('id=5')->setFieed('title', "新title2");
        //支持同时更新多个字段
        $news = where("id=5")->setField(array('title' => "新title", 'slug' => ''));

        //还支持统计字段
        $news->where("id=5")->setInc('id', 10); //id + 10
        //setDec
    }

    public function del() {
        $art = M("news");
        //删除主键6
        $art->delete(6);
        //删除主键1,2,3
        $art->delete('1,2,3');

        $art->where('id=5')->delete();

        //删除最新10个
        M('news')->order('create_time')->limit(10)->delete();
    }

    public function orm() {
        $a = D("Article");
        //$a->orm_find();
        $a->orm_update();
    }

    public function orm_val() {
        $art = D("Article");

        //这里会将$_POST的值填到$art对应的字段（表的字段）
        //并对__validate, _auto做验证和自动完成
        
        //create可以指定数据，默认是$_POST
        if($art->create()) { //如果构造成功就提交到数据库
            $art->add();
        }else {
            exit($art->getError());
        }
    }
}
