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
        $this->_connection = new mysqli($host, $mtuser, $mtcontrasena, $mtbd);
        if ($this->_connection->connect_error) {
            die("Error de conexión a la base de datos: " . $this->_connection->connect_error);
        }
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
class LoginSystem
{
    public $db_host = NULL;
    public $db_name = NULL;
    public $db_user = NULL;
    public $db_password = NULL;
    public $connection = NULL;
    public $username = NULL;
    public $password = NULL;

    public function __construct()
    {
        include "ini_bd.php";
        $this->db_host = "$host";
        $this->db_name = $mtbd;
        $this->db_user = $mtuser;
        $this->db_password = $mtcontrasena;
        $this->connect();
    }
    public function isLoggedIn()
    {
        if ($_SESSION["Logeado"]) {
            return true;
        }
        return false;
    }
    public function doLogin($username, $password)
    {
        $this->connect();
        $this->username = $username;
        $this->password = $password;
        $stmt = $this->connection->prepare("SELECT * FROM usuarios WHERE correo = ? AND contrasena = ? LIMIT 1");
        $stmt->bind_param("ss", $username, $password);
        $stmt->execute();
        $result = $stmt->get_result();
        if ($result->num_rows !== 1) {
            $this->disconnect();
            return false;
        }
        $row = $result->fetch_assoc();
        if ($row["activo"] != "1") {
            $this->disconnect();
            header("Location: login.php?msg=5");
            exit;
        }
        session_regenerate_id(true);
        $_SESSION["Logeado"] = 'true';
        $_SESSION["estado"] = $row["activo"];
        $_SESSION["idusername"]  = $row["id_usuario"];
        $_SESSION["nombreusername"] = $row["nombre"];
        $_SESSION["rol"] = $row["id_rol"];
        $this->obtenerInformacionUsuario($row["id_usuario"]);
        $this->disconnect();
        return true;
    }
    public function logout($id)
    {
        $_SESSION = array();
        unset($_SESSION["Logeado"]);
        unset($_SESSION["username"]);
        unset($_SESSION["idusername"]);
        unset($_SESSION["nivelusername"]);
        unset($_SESSION["avatarusername"]);
        unset($_SESSION["agenciacod"]);
        unset($_SESSION["nodousername"]);
        unset($_SESSION["nombreusername"]);
        unset($_SESSION["comision"]);
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), "", time() - 42000, $params["path"], $params["domain"], $params["secure"], $params["httponly"]);
        }
        unset($_COOKIE["mktsp5"]);
        session_destroy();
    }
    public function connect()
    {
        $this->connection = mysqli_connect($this->db_host, $this->db_user, $this->db_password, $this->db_name) or exit("Error Mysql, revise datos de conexion" . __DIR__ . "/ini_bd.php");
        if ($this->connection) {
            return true;
        }
        return false;
    }
    public function disconnect()
    {
        mysqli_close($this->connection);
    }
    public function randomPassword($length = 8)
    {
        $pass = "";
        $chars = array("a", "A", "b", "B", "c", "C", "d", "D", "e", "E", "f", "F", "g", "G", "h", "H", "i", "I", "j", "J", "k", "K", "l", "L", "m", "M", "n", "N", "o", "O", "p", "P", "q", "Q", "r", "R", "s", "S", "t", "T", "u", "U", "v", "V", "w", "W", "x", "X", "y", "Y", "z", "Z", "1", "2", "3", "4", "5", "6", "7", "8", "9");
        for ($i = 0; $i < $length; $i++) {
            $pass .= $chars[mt_rand(0, count($chars) - 1)];
        }
        return $pass;
    }
    public function obtenerInformacionUsuario($usuario_id)
    {
        $consulta = "
             SELECT 
            u.id AS usuario_id,
            u.nombre AS usuario_nombre,
            u.email AS usuario_email,
            r.id AS rol_id,
            r.nombre AS rol_nombre
        FROM 
            usuarios u
        JOIN 
            roles r ON u.rol_id = r.id
        WHERE 
            u.id = ?
        ";
        $stmt = $this->connection->prepare($consulta);
        if ($stmt === false) {
            echo "Error en la preparación de la consulta: " . $this->connection->error;
            return null;
        }

        $stmt->bind_param('i', $usuario_id);

        if (!$stmt->execute()) {
            echo "Error al ejecutar la consulta: " . $stmt->error;
            return null;
        }

        $resultado = $stmt->get_result();
        if ($resultado->num_rows == 0) {
            echo "No se encontraron resultados para la consulta";
            return null;
        }

        if ($fila = $resultado->fetch_assoc()) {
            $_SESSION['usuario_info'] = [
                'nombre_agencia' => $fila['nom_age'],
                'agencia_id' => $fila['agencia_id'],
                //'agencia' => $fila['agencia'],
                'mikrotik' => [
                    'ip_mikrotik' => $fila['ip_mikrotik'],
                    'm_mikrotik' => $fila['m_mikrotik'],
                    'id_mikrotik' => $fila['id_mikrotik'],
                    'user_mikrotik' => $fila['user_mikrotik'],
                    'pass_mikrotik' => $fila['pass_mikrotik']
                ]
            ];
        }
        $stmt->close();
    }
}
