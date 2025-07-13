<?php
require_once "../includes/System.class.php";
$db = Database::getInstance();

if (isset($_GET["action"])) {
    //LISTAR ANIMALES
    if ($_GET["action"] == "listaanimales") {
        $i = 1;
        $data = array();

        $sql = "SELECT * FROM reporte_rescates";
        $result = $db->dameQuery($sql);
        while ($row = $result->fetch_assoc()) {
            $item = array();
            $item["id"] = $i++;
            $item["descripcion"] = $row["descripcion"];
            $item["fecha"] = $row["fecha"];
            $item["estado"] = $row["estado"];
            $item["tool"] = $row["id"];
            $data[] = $item;
        }

        if (empty($data)) {
            echo "{\n\"sEcho\": 1,\n\"iTotalRecords\": \"0\",\n\"iTotalDisplayRecords\": \"0\",\n\"aaData\": []\n}";
        } else {
            $results = array("data" => $data);
            unset($data);
            echo json_encode($results);
        }
        exit;
    }
}

if (isset($_POST["action"])) {
    //VER REPORTE
    if ($_POST["action"] == "verreporte") {
        $id = $db->sanitize($_POST["id"]);
        $datos = $db->dameQuery("SELECT * FROM reporte_rescates WHERE id = '$id' LIMIT 1");
        $datos = mysqli_fetch_assoc($datos);
        $latitud = isset($datos['latitud']) ? floatval($datos['latitud']) : 0;
        $longitud = isset($datos['longitud']) ? floatval($datos['longitud']) : 0;
?>
        <div class="modal fade" id="Modal-in" tabindex="-1" aria-labelledby="ModalInLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title" id="ModalInLabel">
                            <i class="fas fa-paw me-2"></i> Reporte Rescate
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="id">

                        <div class="row g-4 align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="<?php echo $datos["foto"]; ?>" alt="Foto" class="img-fluid rounded shadow-sm"
                                    style="max-height: 250px; object-fit: cover;">
                            </div>

                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-file me-1 text-primary"></i>
                                            Descripción</label>
                                        <input class="form-control" id="nombre" type="text"
                                            value="<?php echo $datos["descripcion"]; ?>" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-calendar me-1 text-primary"></i>
                                            Fecha</label>
                                        <input class="form-control" id="edad" type="text" value="<?php echo $datos["fecha"]; ?>"
                                            readonly>
                                    </div>

                                    <div class="col-md-12 mt-4">
                                        <label class="form-label"><i class="fas fa-map-marker-alt me-1 text-primary"></i>
                                            Ubicación en Mapa</label>
                                        <div id="map" style="height: 400px;" data-lat="<?php echo $latitud; ?>"
                                            data-lng="<?php echo $longitud; ?>"></div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

<?php
        exit;
    }
    //CAMBIAR ESTADO
    if ($_POST["action"] == "deleteanimal") {
        $id = $db->sanitize($_POST["id"]);
        $db->dameQuery("UPDATE animales SET activo = 2 WHERE id_animal = $id");
        exit;
    }
}
