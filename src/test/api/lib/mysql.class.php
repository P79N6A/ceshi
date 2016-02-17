<?php

/**
 * ---------------------------------------
 * Class Mysql
 * 建议mysql封装类
 * ---------------------------------------
 *
 * echo '<pre>';
 * $mysql = new Mysql();
 * print_r($mysql);
 * echo '<br />';
 *
 * $sql = 'select * from stu';
 * $arr = $mysql->getAll($sql);
 * print_r($arr);
 *
 * 查询16号学员
 * $sql = 'select * from stu where id=16';
 * print_r($mysql->getRow($sql));
 *
 * 查询共有多少个学生
 * $sql = 'select count(*) from stu';
 * print_r($mysql->getOne($sql));
 * ---------------------------------------
 *
 */
class Mysql
{
    private $host;
    private $user;
    private $pwd;
    private $dbName;
    private $charset;

    private $conn = null; // 保存连接的资源


    public function __construct()
    {
        $this->host = 'localhost';
        $this->user = 'root';
        $this->pwd = '';
        $this->dbName = 'hongbeifang';
        // 连接
        $this->connect($this->host, $this->user, $this->pwd);
        // 切换库
        $this->switchDb($this->dbName);
        // 设置字符集
        $this->setChar($this->charset);
    }

    // 负责连接
    private function connect($h, $u, $p)
    {
        $conn = mysql_pconnect($h, $u, $p);
        $this->conn = $conn;
    }

    // 负责切换数据库,网站大的时候,可能用到不止一个库
    public function switchDb($db)
    {
        $sql = 'use ' . $db;
        $this->query($sql);
    }

    // 负责设置字符集
    public function setChar($char)
    {
        $sql = 'set names ' . $char;
        $this->query($sql);
    }

    // 负责发送sql查询
    public function query($sql)
    {
        return mysql_query($sql, $this->conn);
    }

    // 负责获取多行多列的select 结果
    public function getAll($sql)
    {
        $list = array();
        $rs = $this->query($sql);
        if (!$rs) {
            return false;
        }
        while ($row = mysql_fetch_assoc($rs)) {
            $list[] = $row;
        }
        return $list;

    }

    // 获取一行的select 结果
    public function getRow($sql)
    {
        $rs = $this->query($sql);
        if (!$rs) {
            return false;
        }
        return mysql_fetch_assoc($rs);
    }

    // 获取一个单个的值
    public function getOne($sql)
    {
        $rs = $this->query($sql);
        if (!$rs) {
            return false;
        }
        $row = mysql_fetch_row($rs);
        return $row[0];
    }

    public function lastid()
    {
        return mysql_insert_id();
    }

    public function close()
    {
        mysql_close($this->conn);
    }
}
