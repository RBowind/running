<?php

/**
 * Created by PhpStorm.
 * User: 赵天歌
 * Date: 2016/8/25
 * Time: 16:34
 * 功能：实现评论功能。此功能必登录来保持session。
 */
    require_once'header.php';
    if(!isset($_SESSION['account']))
    {
        exit("请注册或登录");
    }

    class remark
    {
        public $r_id ;
        public $time;
        /**
         * @param mixed $r_id
         */
        public function setRId($r_id)
        {
            date_default_timezone_set('Asia/Shanghai');
            $time = date("Y-m-d H:i:s");
            $r_id = $_SESSION['account'].$time;
            $this->time = $time;
            $this->r_id = $r_id;

        }

        /**
         * @return mixed
         */
        public function getRId()
        {
            return $this->time;
            return $this->r_id;
        }

        public function remark()
        {
            $account = $_SESSION['account'];
            $pdo = new PdoMySQL();
            $data = array(
                'r_id' => self::getRId($this->r_id),
                'a_id' => $_GET['a_id'],
                't_id' => $_GET['t_id'],
                'account'=> $account,
                'content' =>$_POST['content'],
                'time' =>self::getRId($this->time)
            );

            $pdo -> add($data,'remark');
            var_dump($data);
        }

    }

    class user_remark extends remark
    {
        public function user_remark()
        {
            $pdo1 = new PdoMySQL();
            // 分情况查询得到，发布活动或者感想的用户名
                if($_GET['a_id'])
                {
                    $r_account = $pdo1 -> find("user_activity","a_id = '".$_GET['a_id']."'",'account');
                }else{
                    $r_account = $pdo1 -> find("thought","t_id ='".$_GET['t_id']."'",'account');
                }

            $data1 = array
            (
                'account' => $_SESSION['account'],
                'r_account' => $r_account
            );
                $pdo1 ->add($data1,'user_remark');  //向“user_remark”表插入数据
        }
    }

    $act = $_GET['act'];
    if($act === 'remark')
    {
        try
        {
            $user_remark = new user_remark();
            $user_remark->user_remark();
            $remark = new remark();
            $remark ->remark();

        }catch (PDOException $e){
            echo $sql . "<br>" . $e->getMessage();
        }
    }














