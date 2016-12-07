<?php

//注意本文件名后缀是.class.php
namespace Home\Model;

//得先use一下
use Think\Model;

//获取最后执行的sql
//echo $User->getLastSql();
//echo $User->_sql();

//$User = M("User"); // 实例化User对象
//$result = $User->find(1);
//if(false === $result){
//    echo $User->getDbError();
//}


class ArticleModel extends Model {
    protected $tableName = 'news'; 
    protected $fileds = array('id', 'title', 'slug', 'text');
    protected $pk = 'id';
    
    protected $_validate = array(
        /*
        array(验证字段1,验证规则,错误提示,[验证条件,附加规则,验证时间]),
        array('verify','require','验证码必须！'), //默认情况下用正则进行验证
        array('name','','帐号名称已经存在！',0,'unique',1), // 在新增的时候验证name字段是否唯一
        array('value',array(1,2,3),'值的范围不正确！',2,'in'), // 当值不为空的时候判断是否在一个范围内
        array('repassword','password','确认密码不正确',0,'confirm'), // 验证确认密码是否和密码一致
        array('password','checkPwd','密码格式不正确',0,'function'), // 自定义函数验证密码格式
        */
        array('title', array('cao'), '值的范围不对', 2, 'in'),
    );
    protected $_auto = array ( 
        /*
         array('status','1'),  // 新增的时候把status字段设置为1
         array('password','md5',3,'function') , // 对password字段在新增和编辑的时候使md5函数处理
         array('name','getName',3,'callback'), // 对name字段在新增和编辑的时候回调getName方法
         array('update_time','time',2,'function'), // 对update_time字段在更新的时候写入当前时间戳
         */
     );
     
    function __construct() {
        parent::__construct();
    }

    function getOneArticle($id) {
        return $this->where('id=5')->select();
    }

    function orm_add() {
        //直接用表名
        $db = M("news");
        $db->title = "123";
        $db->id = 100;
        $db->add();

        //用类名实例化需要先create
        $db = D("Article");
       
        $db->title = "123";
        $db->id = 100;
        if($db->create()) {
            $db->add();
        }else {
            exit($db->getError());
        }
        
        {
            //create方法默认会对POST进行_validate验证
            //如果想对自己的$data验证，$this->create($data);
            if (!$this->create()){
                // 如果创建失败 表示验证没有通过 输出错误提示信息
                exit($this->getError());
            }else{
                // 验证通过 可以进行其他数据操作
            }
        }
    }

    function orm_find() {
        //可以通过“表达式查询”做高级表达式查询
        //可以通过“统计查询”做统计
        //M("tablename")->query("your sql"); 查询
        //M("tablename")->execute("your sql"); 写更


        //返回一条数据
        $this->find(8);
        //返回多条数据
        $this->select('1,2,3');

        //获取一条数据
        $this->getByTitle("cao");
    }

    function orm_update() {
        $this->find(8);
        //可以对本对象进行更改
        $this->title = "hhhhh";
        $this->save();
    }

    function orm_del() {
        $this->find(8);
        //可以直接删除本对象
        $this->delete();

        //删除主键1，3
        $this->delete('1,3');
    }


}