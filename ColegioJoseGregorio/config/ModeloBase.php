<?php
  require_once "EntidadBase.php";

  class ModeloBase extends EntidadBase{
    private $table;

    public function __construct($table){
      $this->table = (string) $table;
      parent::__construct($table);
    }

    public function ejecutarSql($query){
      $query1 = $this->db()->query($query);

      if($query1){
        if($query1->num_rows >= 1){
          while($row = $query1->fetch_assoc()){
            $resultSet[] = $row;
          }
        }else{
          $resultSet = false;
        }
      }else{
        $resultSet = false;
      }
      return $resultSet;
    }

  }

?>
