<?php


class Mysql
{
    private $where = '';
    private $data = [];
    private $table = '';
    private $fields = '*';
    public function __construct($config)
    {

        $this->conn = mysqli_connect($config['host'],$config['user'],$config['pass'],$config['name'],$config['port']);
        if (!$this->conn)
        {
            die("连接错误: " . mysqli_connect_error());
        }else{
            return $this;
        }
    }
    function table($name){
        $this->table = $name;
        return $this;
    }
    function trans(){
        mysqli_autocommit($this->conn,FALSE);
        return $this;
    }
    function commit(){
        if (!mysqli_errno($this->conn)){
            mysqli_commit($this->conn);
            return true;
        }else{
            mysqli_rollback($this->conn);
            return false;
        }

    }
    function where($where = ''){
        $this->where = $where;
        return $this;
    }
    function fields($fields = '*'){
        $this->fields = $fields;
    }
    function data($data = []){
        $this->data = $data;
        return $this;
    }
    function insert(){
        $keys = implode(',',array_keys($this->data));
        $values = array_values($this->data);
        foreach($values as &$item){
            $item = mysqli_real_escape_string($this->conn,$item);
        }
        $values = implode('\',\'',$values);
        return mysqli_query($this->conn,"insert into {$this->table}({$keys}) values('{$values}')");
    }
    function delete(){
        return mysqli_query($this->conn,"delete from {$this->table} where {$this->where}");
    }
    function find(){
        $result = mysqli_query($this->conn,"select {$this->fields} from {$this->table} where {$this->where}");
        if($result->num_rows>0){
            return $result->fetch_array();
        }else{
            return null;
        }
    }
    function all(){
        $result = mysqli_query($this->conn,"select {$this->fields} from {$this->table} where {$this->where}");
        if($result->num_rows>0){
            $arr = [];
            while ($tmp = $result->fetch_array()){
                array_push($arr,$tmp);
            }
            return $arr;
        }else{
            return null;
        }
    }
    function update(){
        $set = [];
        foreach ($this->data as $k=>$v){
            $value = mysqli_real_escape_string($this->conn,$v);
            array_push($set,"{$k}='{$value}'");
        }
        $set = implode(',',$set);
        return mysqli_query($this->conn,"update {$this->table} set {$set} where {$this->where}");
    }
    function query($sql){
        return mysqli_query($this->conn,$sql);
    }
}