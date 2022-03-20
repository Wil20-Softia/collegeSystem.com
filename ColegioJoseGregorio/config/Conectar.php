<?php

  class Conectar{
    private $driver;
    private $host;
    private $user;
    private $pass;
    private $database;
    private $charset;

    public function __construct(){
      $db_cfg = require_once "database.php";
      $this->driver = $db_cfg["driver"];
      $this->host = $db_cfg["host"];
      $this->user = $db_cfg["user"];
      $this->pass = $db_cfg["pass"];
      $this->database = $db_cfg["database"];
      $this->charset = $db_cfg["charset"];
    }

    public function conexion(){
      if($this->driver == "mysql" || $this->driver == null){
        $con = new mysqli($this->host, $this->user, $this->pass, $this->database);
        $con->query("SET NAMES '".$this->charset."'");
      }
      return $con;
    }

    # Metodo que funciona principalmente para subir un archivo a las carpetas del
    # servidor y retorna la ubicacion de la foto en el servidor, sino false.
    public function subir_archivo($archivo=array()){
      if($archivo['error'] > 0){
        return false;
      }else{
        $dir_file = $_SERVER["SCRIPT_FILENAME"];
        $dir_file = explode("/", $dir_file);
        $folder_proyect = $dir_file[0] ."/".$dir_file[1]."/".$dir_file[2]."/".$dir_file[3];

        $name_file = $archivo['name'];

        $destiny_folder = $folder_proyect . "/backups/";

        $file = $destiny_folder . $name_file;

        if(!file_exists($destiny_folder)){
          mkdir($destiny_folder);
        }

        if(!file_exists($file)){
          $result = @move_uploaded_file($archivo['tmp_name'], $file);

          if($result){
            # La ruta al archvio copiado o subido al servidor se retorna.
            return $file;
          }else{
            return false;
          }
        }else{
            return false;
        }
      }
    }

    public function respaldo(){
      $path_root = explode("/",$_SERVER["DOCUMENT_ROOT"]);

      $path_mysql = $path_root[0].$_SERVER['MYSQL_HOME'];

      $dir_file = explode("/", $_SERVER["SCRIPT_FILENAME"]);
      $folder_proyect = $dir_file[0] ."/".$dir_file[1]."/".$dir_file[2]."/".$dir_file[3];
      $ruta_archivo = str_replace('/', '\\', $folder_proyect);

      $filename = $this->database.'-respaldo-'.date("YmdHis").'.sql.gz';

      $archivo_completo = $ruta_archivo.'\\'.$filename;
      $ejecutable_zip = str_replace('/','\\',$folder_proyect.'/GnuWin32/bin/gzip');

      $opciones = '--events=TRUE --disable-keys=FALSE --add-locks=FALSE --lock-tables=FALSE --triggers -R -e -f -a --add-drop-database --skip-comments --create-options';

      $sesion = '-h '.$this->host.' -u '.$this->user.' --password='.$this->pass.' '.$this->database;

      $command = $path_mysql.'\mysqldump '. $opciones.' '.$sesion.' | '.$ejecutable_zip.' -9 > '.$archivo_completo;
      system($command." 2>&1", $output);
      
      if (file_exists($archivo_completo)) {  
        header('Content-Description: File Transfer'); 
        header('Content-Type: application/octet-stream'); 
        header('Content-Disposition: attachment; filename="'.basename($filename).'";'); 
        header('Expires: 0'); 
        header('Cache-Control: must-revalidate'); 
        header('Pragma: public'); 
        readfile($archivo_completo);

        sleep(1);
        system('del '.$archivo_completo." 2>&1", $output); 
        exit; 
      }
    }

    public function restauracion($archivo = array()){
      $path_root = explode("/",$_SERVER["DOCUMENT_ROOT"]);
      
      $path_mysql = $path_root[0].$_SERVER['MYSQL_HOME'];

      $dir_file = explode("/", $_SERVER["SCRIPT_FILENAME"]);
      $folder_proyect = $dir_file[0] ."/".$dir_file[1]."/".$dir_file[2]."/".$dir_file[3];

      $path_absolute = $this->subir_archivo($archivo);
      $ejecutable_zip = str_replace('/', '\\', $folder_proyect."/GnuWin32/bin/gzip");

      if($path_absolute != false){
        $path_file = str_replace('/', '\\', $path_absolute);

        $sesion = '-u '.$this->user.' --password='.$this->pass;
        
        $comando_vaciar = $path_mysql.'\mysql '.$sesion.' -e "DROP DATABASE IF EXISTS '.$this->database.'; CREATE DATABASE '.$this->database.' CHARACTER SET \'UTF8\' COLLATE \'utf8_unicode_ci\';"';

        $comando_crear = $ejecutable_zip.' -d < "'.$path_file.'" | '.$path_mysql.'\mysql '.$sesion.' '.$this->database;

        if(system($comando_vaciar." 2>&1", $output) == 0){
          if(system($comando_crear." 2>&1", $output) == 0){
            return true;
          }else{
            return false;
          }
        }else{
          return false;
        }
      }else{
        return false;
      }
    }

    //OTROS METODOS PARA CARGAR QUERY BUILDERS, ORM, ETC.

  }

?>
