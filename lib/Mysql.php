<?php


class Mysql
{
    private $where = '';
    private $data = [];
    private $table = '';
    private $limit = '';
    private $orderBy = '';
    private $fields = '*';
    private $echo = false;
    private $sql = '';
    public function __construct($config)
    {

        $this->conn = mysqli_connect($config['host'],$config['user'],$config['pass'],$config['name'],$config['port']);
        if (!$this->conn)
        {
            die("连接错误: " . mysqli_connect_error());
        }else{
            $this->query("SET NAMES {$config['charset']}");
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
    function sql($echo = true){
        $this->echo = $echo;
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
        return $this;
    }
    function desc($key){
        $this->orderBy = "order by {$key} desc";
        return $this;
    }
    function asc($key){
        $this->orderBy = "order by {$key} asc";
        return $this;
    }
    function limit($pageNo = 1,$pageSize = 10){
        $start = ($pageNo-1)*$pageSize;
        $this->limit = "limit {$start},{$pageSize}";
        return $this;
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
        $this->sql = "insert into {$this->table}({$keys}) values('{$values}')";
        if ($this->echo){
            return $this->sql;
        }
        return mysqli_query($this->conn,$this->sql);
    }
    function delete(){
        $this->sql = "delete from {$this->table} where {$this->where}";
        if ($this->echo){
            return $this->sql;
        }
        return mysqli_query($this->conn,$this->sql);
    }
    function find(){
        $this->sql = "select {$this->fields} from {$this->table} where {$this->where} {$this->orderBy} {$this->limit}";
        if ($this->echo){
            return $this->sql;
        }
        $result = mysqli_query($this->conn,$this->sql);
        if($result->num_rows>0){
            return $result->fetch_array();
        }else{
            return null;
        }
    }
    function all(){
        $this->sql = "select {$this->fields} from {$this->table} where {$this->where} {$this->orderBy} {$this->limit}";
        if ($this->echo){
            return $this->sql;
        }
        $result = mysqli_query($this->conn,$this->sql);
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
        $this->sql = "update {$this->table} set {$set} where {$this->where}";
        if ($this->echo){
            return $this->sql;
        }
        return mysqli_query($this->conn,$this->sql);
    }
    function query($sql){
        return mysqli_query($this->conn,$sql);
    }
}