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
        $this->_connection->set_charset("utf8");
        if (mysqli_connect_error()) {
            if (!file_exists("/var/www/admin/repararmysql.sh")) {
                $comando = "#!/bin/sh\n\necho \"1.- Configurando Mysql a Prueba de fallo\"\n/etc/init.d/mysql stop\nmv /etc/mysql/my.cnf /etc/mysql/my.cnf.bak\ncp /var/www/admin/my.cnf /etc/mysql/my.cnf\nsleep 1\necho \"2.- Iniciando Mysql a Prueba de falla\"\n/etc/init.d/mysql restart\nsleep 1\nrm /var/lib/mysql/" . $mtbd . "/tmp.frm\nrm /var/lib/mysql/" . $mtbd . "/trafico_tmp.frm\necho \"3.- Generando Backup de datos\"\n\nif mysqldump --user=" . $mtuser . " --password=" . $mtcontrasena . " --host=localhost --single-transaction --opt --skip-quick --max_allowed_packet=128M  " . $mtbd . " > mysqlrestore.sql\nthen\n\necho \"4.- Eliminando Base de datos corrupto.\"\nsleep 1\n/etc/init.d/mysql stop\nmv /var/lib/mysql/mysql /root/mysql\nmv /var/lib/mysql/performance_schema /root/performance_schema\nrm -r /var/lib/mysql/*\nmv /root/mysql /var/lib/mysql/mysql\nmv /root/performance_schema /var/lib/mysql/performance_schema\necho \"5.- Iniciando Mysql...\"\nrm /etc/mysql/my.cnf\nmv /etc/mysql/my.cnf.bak /etc/mysql/my.cnf\n/etc/init.d/mysql start\nsleep 2\n/etc/init.d/mysql restart\nsleep 1\necho \"6.- Creando Base de datos y restaurando Backup\"\nmysql --user=" . $mtuser . " --password=" . $mtcontrasena . " -e \"CREATE DATABASE " . $mtbd . " /*\\!40100 DEFAULT CHARACTER SET utf8 */;\"\nmysql --user=" . $mtuser . " --password=" . $mtcontrasena . " --host=localhost " . $mtbd . " < mysqlrestore.sql\nmysql --user=" . $mtuser . " --password=" . $mtcontrasena . " --host=localhost " . $mtbd . " < /var/www/admin/reparar.sql\nsleep 1\necho \"MYSQL REPARADO, Intente abrir nuevamente Mikrotisp.\"\n\nelse\nrm /etc/mysql/my.cnf\nmv /etc/mysql/my.cnf.bak /etc/mysql/my.cnf\necho \"ERROR: No se puede obtener un backup porque los datos estan dañados o Mysql no ha iniciado a prueba de fallos\";\nexit\nfi";
                $fp = fopen("/var/www/admin/repararmysql.sh", "a");
                fwrite($fp, $comando);
                fclose($fp);
            }
            echo "<!doctype html>\n<html>\n<head>\n    <meta charset=\"utf-8\">\n    <meta http-equiv=\"X-UA-Compatible\" content=\"IE=edge\">\n    <title>ERROR MYSQL</title>\n    <!-- Tell the browser to be responsive to screen width -->\n    <meta content=\"width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no\" name=\"viewport\">\n    <link rel=\"stylesheet\" href=\"bootstrap/css/bootstrap.min.css\" media=\"all\">\n    <link rel=\"stylesheet\" href=\"css/font-awesome.min.css\">\n    <link rel=\"stylesheet\" href=\"css/AdminLTE.min.css\">\n    <link rel=\"stylesheet\" href=\"css/skins/skin-blue.min.css\">\n    <link rel=\"stylesheet\" href=\"css/animate.css\">\n    <link rel=\"stylesheet\" href=\"css/mikrotisp.css\" media=\"all\">\n\n    \n  </head>\n\n<body>\n\n<div class=\"container\">\n\t<div class=\"row\">\n\t\n            <div class=\"row\">\n                <div class=\"col-sm-7 col-center\">\n                    <div class=\"box box-sucess\">\n                        <div class=\"box-content\">\n                            <h1 class=\"tag-title text-center\" style=\"color:#E52528\"><i class=\"fa fa-exclamation-triangle\" aria-hidden=\"true\"></i> ERROR MYSQL</h1>\n                            <hr />\n                            <p>No se puede conectar a Mysql porque existe un eror en la base de datos, para solucionar y confirmar el problema ejecute los siguientes comandos vía consola de su servidor Linux.(vía putty):</p>\n \n <code><b>/etc/init.d/mysql restart</b></code>\n<p>Si dicho comando arroja un error es porque mysql tiene tablas o base de datos dañados, para intentar recuperar nuestros datos y reparar mysql ejecute el siguiente script en consola:</p>\n\n <code><b>apt-get -y install dos2unix && dos2unix /var/www/admin/repararmysql.sh && sh /var/www/admin/repararmysql.sh</b></code>\n <br><br>\n \n Si el problema persiste es posible que necesite reinstalar todo el sistema operativo + Mikrotisp, puede descargar el ultimo backup generado correctamente desde la ruta <b style=\"font-size:15px; color:#E60D11\">\"/var/www/admin/backup/\"</b>.\n  <br><br>\n  <h4><b>Recomendaciones:</b></h4>\n  <ul>\n  <li>Si trabaja con equipos Raspberry PI debe utilizar memorias SD clase de 10 y nuevas, tambien puede utilizar una memoria USB para instalar Mikrotisp.</li>\n  \n  <li>Si el error sucede muy seguido es porque el Disco (SD,USB,DISCO RIGIDO,ETC) presenta problemas fisico y debe ser reemplazado.</li>\n\t\n  </ul>\n \n \n                        </div>\n                    </div>\n                </div>\n\n\n            </div>           \n        </div>\n\t</div>\n</div>\n\n<style type=\"text/css\">\n.col-center{\n    float: none;\n    margin: 0 auto;\n}\n\n.box {\n  background:#fff;\n  transition:all 0.2s ease;\n  border:2px dashed #dadada;\n  margin-top: 10px;\n  box-sizing: border-box;\n  border-radius: 5px;\n  background-clip: padding-box;\n  padding:0 20px 20px 20px;\n  min-height:340px;\n}\n\n.box:hover {\n  border:2px solid #525C7A;\n}\n\n.box span.box-title {\n    color: #fff;\n    font-size: 24px;\n    font-weight: 300;\n    text-transform: uppercase;\n}\n\n.box .box-content {\n  padding: 16px;\n  border-radius: 0 0 2px 2px;\n  background-clip: padding-box;\n  box-sizing: border-box;\n}\n.box .box-content p {\n  color:#515c66;\n  text-transform:none;\n}\n\n</style>\n</body>\n</html>";
            exit;
        }
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