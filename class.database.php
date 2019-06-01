<?php


class Database extends \PDO{

    private $select;

    private $sql;

    private $where;

    private $andWhere;

    private $orWhere;

    private $result;

    private $insert;

    private $update;

    private $delete;

    public $db;

    private $column;

    private $counter=0;



    public function __construct($host="localhost", $dbname="", $user="root", $pass=""){

      try{

        $this->db = new PDO("mysql:host=$host;dbname=$dbname", $user, $pass);

        $this->db->exec("SET NAMES 'utf8'; SET CHARSET 'utf8'");

        return $this->db;

      }

      catch (PDOException $ex) {

        return die($ex->getMessage());

      }

    }



    public function select($table){

      $this->select="SELECT * FROM ".$table;

      return $this;

    }



    public function column($column){

      $this->select=str_replace(" * ", " ".$column." ", $this->select);

      return $this;

    }



    public function where($con, $op, $val){

      $this->where=[

        "con" => $con,

        "op" => $op,

        "val" => $val

      ];

      $this->select = $this->select." WHERE"." ".$this->where["con"]."".$this->where["op"]."?";

      $this->counter++;

      return $this;

    }



    public function andWhere($con, $op, $val){

      $this->andWhere=[

        "con" => $con,

        "op" => $op,

        "val" => $val

      ];

      //$this->select." WHERE"." ".$this->where["con"]."".$this->where["op"]."? and ".$this->andWhere["con"]."".$this->andWhere["op"]."?"

      return $this;

    }



    public function orWhere($con, $op, $val){

      $this->orWhere=[

        "con" => $con,

        "op" => $op,

        "val" => $val

      ];

      //$this->select." WHERE"." ".$this->where["con"]."".$this->where["op"]."? and ".$this->andWhere["con"]."".$this->andWhere["op"]."?"

      return $this;

    }



    public function insert($table="", array $data){

      $columns = "";

      $datas=array();

      foreach ($data as $key => $val) {

        $columns .= $key."=?, ";

        array_push($datas, $val);

      }

      $columns = mb_substr($columns, 0, -2);

      $sql = "INSERT INTO $table SET $columns";



      $query = $this->db->prepare($sql);

      $insert = $query->execute($datas);

      if($insert){

        return 1;

      }

      else{

        return 0;

      }

    }



    public function update($table, $condition, $conditionVal, array $data){



    }



    public function delete($table, $val){

      $sql = "DELETE FROM $table WHERE id=?";

      $query = $this->db->prepare($sql);

      $delete = $query->execute(array($val));

      if($delete){

        return 1;

      }

      else{

        return 0;

      }

    }



    public function get(){

      $this->result=[];

      if($this->counter>0){

        $query = $this->db->prepare($this->select);

        $a=$this->where["val"];

        $query->execute(array(

             $a

        ));

        return $query->fetch(PDO::FETCH_ASSOC);

      }

      else{

        $query=$this->db->query($this->select, PDO::FETCH_ASSOC);

        if($query->rowCount()){

           foreach($query as $row ){

                $this->result[]=$row;

           }

        }

        return $this->result;

      }



    }

}

