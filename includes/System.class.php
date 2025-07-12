<?php
class Database
{
    private $_connection = NULL;
    private static $_instance = NULL;

    public static function getInstance()
    {
        if (self::$_instance == NULL) {
            self::$_instance = new Database();
        }
        return self::$_instance;
    }
    private function __construct()
    {
        require __DIR__ . "/ini_bd.php";

        // Crear conexión
        $this->_connection = new mysqli($host, $mtuser, $mtcontrasena, $mtbd);

        // Verificar errores
        if ($this->_connection->connect_error) {
            die("Error de conexión a la base de datos: " . $this->_connection->connect_error);
        }

        // Establecer conjunto de caracteres
        $this->_connection->set_charset("utf8");
    }

    public function dameQuery($query, $insert = "")
    {
        $rs = array();
        if (empty($insert)) {
            $rs = $this->_connection->query($query) or exit("ERROR MYSQL: <br>" . $this->_connection->error);
        } else {
            $this->_connection->query($query) or exit("ERROR MYSQL: <br>" . $this->_connection->error);
            $rs = $this->_connection->insert_id;
        }
        return $rs;
    }
    public function sanitize($string)
    {
        return $this->_connection->real_escape_string(($string));
    }
}
