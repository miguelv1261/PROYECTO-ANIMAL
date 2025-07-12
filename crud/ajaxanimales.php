<?php
require_once "../includes/System.class.php";
$db = Database::getInstance();

if (isset($_GET["action"])) {
    //LISTAR ANIMALES
    if ($_GET["action"] == "listaanimales") {
        $i = 1;
        $data = array();

        $sql = "SELECT * FROM animales WHERE activo = 1";
        $result = $db->dameQuery($sql);

        while ($row = $result->fetch_assoc()) {
            $item = array();
            $item["id"] = $i++;
            $item["nombre"] = $row["nombre"];
            $item["edad"] = $row["edad"];
            $item["sexo"] = $row["sexo"];
            $item["color"] = $row["color"];
            $item["estado_salud"] = $row["estado_salud"];
            $item["estado_adopcion"] = $row["estado_adopcion"];
            $item["tool"] = $row["id_animal"];
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
    //VER ANIMAL
    if ($_POST["action"] == "veranimal") {
        $id = $db->sanitize($_POST["id"]);
        $datos = $db->dameQuery("SELECT * FROM animales WHERE id_animal = '$id' LIMIT 1");
        $historial = $db->dameQuery("SELECT * FROM trazabilidad_animal WHERE id_animal = '$id' ORDER BY fecha_evento DESC");

        $datos = mysqli_fetch_assoc($datos);
        ?>
        <div class="modal fade" id="Modal-in" tabindex="-1" aria-labelledby="ModalInLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title" id="ModalInLabel">
                            <i class="fas fa-paw me-2"></i> Información del Perro
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>

                    <div class="modal-body">
                        <input type="hidden" id="id">

                        <div class="row g-4 align-items-center">
                            <div class="col-md-4 text-center">
                                <img src="<?php echo $datos["foto_url"]; ?>" alt="Foto" class="img-fluid rounded shadow-sm"
                                    style="max-height: 250px; object-fit: cover;">
                            </div>

                            <div class="col-md-8">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-dog me-1 text-primary"></i> Nombre</label>
                                        <input class="form-control" id="nombre" type="text"
                                            value="<?php echo $datos["nombre"]; ?>" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-hourglass-half me-1 text-primary"></i>
                                            Edad</label>
                                        <input class="form-control" id="edad" type="text" value="<?php echo $datos["edad"]; ?>"
                                            readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-palette me-1 text-primary"></i> Color</label>
                                        <input class="form-control" id="color" type="text"
                                            value="<?php echo $datos["color"]; ?>" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-venus-mars me-1 text-primary"></i>
                                            Sexo</label>
                                        <input class="form-control" id="sexo" type="text" value="<?php echo $datos["sexo"]; ?>"
                                            readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-heartbeat me-1 text-primary"></i> Estado de
                                            Salud</label>
                                        <input class="form-control" id="salud" type="text"
                                            value="<?php echo $datos["estado_salud"]; ?>" readonly>
                                    </div>

                                    <div class="col-md-6">
                                        <label class="form-label"><i class="fas fa-home me-1 text-primary"></i> Estado de
                                            Adopción</label>
                                        <input class="form-control" id="adopcion" type="text"
                                            value="<?php echo $datos["estado_adopcion"]; ?>" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($datos["descripcion"])): ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <label class="form-label"><i class="fas fa-align-left me-1 text-primary"></i>
                                        Descripción</label>
                                    <textarea class="form-control" rows="3" readonly><?php echo $datos["descripcion"]; ?></textarea>
                                </div>
                            </div>
                        <?php endif; ?>
                        <?php if (!empty($historial)): ?>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <label class="form-label">
                                        <i class="fas fa-history me-1 text-success"></i> Historial del Animal
                                    </label>
                                    <div class="border rounded p-3 bg-light" style="max-height: 300px; overflow-y: auto;">
                                        <?php foreach ($historial as $evento): ?>
                                            <div class="mb-2">
                                                <strong><?php echo date("d/m/Y", strtotime($evento["fecha_evento"])); ?>:</strong>
                                                <span><?php echo htmlspecialchars($evento["tipo_evento"]); ?></span><br>
                                                <small
                                                    class="text-muted"><?php echo nl2br(htmlspecialchars($evento["descripcion_evento"])); ?></small>
                                            </div>
                                            <hr>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                </div>
            </div>
        </div>

        <?php
        exit;
    }
    //NUEVO ANIMAL
    if ($_POST["action"] == "nuevoanimal") {

    }
    //CRUD EDITAR
    if ($_POST["action"] == "edit_animales") {
        $id = $db->sanitize($_POST["id_animal"]);
        $nombre = $db->sanitize($_POST["nombre"]);
        $edad = $db->sanitize($_POST["edad"]);
        $color = $db->sanitize($_POST["color"]);
        $salud = $db->sanitize($_POST["estado_salud"]);
        $adopcion = $db->sanitize($_POST["estado_adopcion"]);

        $sql = "UPDATE animales SET 
        nombre = '$nombre',
        edad = '$edad',
        color = '$color',
        estado_salud = '$salud',
        estado_adopcion = '$adopcion'
        WHERE id_animal = '$id'";

        $db->dameQuery($sql);
        exit;
    }
    //EDITAR ANIMAL
    if ($_POST["action"] == "editaranimal") {
        $id = $db->sanitize($_POST["id"]);
        $conn = $db->dameQuery("SELECT * FROM animales WHERE id_animal = '$id' LIMIT 1");
        $datos = mysqli_fetch_assoc($conn);
        ?>
        <div class="modal fade" id="Modal-edit" tabindex="-1" aria-labelledby="ModalEditLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <form id="form-edit-animal">
                    <div class="modal-content shadow-lg border-0">
                        <div class="modal-header bg-gradient-primary text-white">
                            <h5 class="modal-title" id="ModalInLabel">
                                <i class="fas fa-paw me-2"></i> Editar Registro
                            </h5>
                            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                                aria-label="Cerrar"></button>
                        </div>
                        <div class="modal-body">
                            <input type="hidden" name="id_animal" value="<?php echo $datos['id_animal']; ?>">

                            <div class="row g-4 align-items-center">
                                <div class="col-md-4 text-center">
                                    <img src="<?php echo $datos["foto_url"]; ?>" alt="Foto" class="img-fluid rounded shadow-sm"
                                        style="max-height: 250px; object-fit: cover;">
                                </div>

                                <div class="col-md-8">
                                    <div class="row g-3">
                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-dog me-1 text-primary"></i>
                                                Nombre</label>
                                            <input class="form-control" name="nombre" id="nombre" type="text"
                                                value="<?php echo $datos["nombre"]; ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-hourglass-half me-1 text-primary"></i>
                                                Edad</label>
                                            <input class="form-control" name="edad" id="edad" type="text"
                                                value="<?php echo $datos["edad"]; ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-palette me-1 text-primary"></i>
                                                Color</label>
                                            <input class="form-control" name="color" id="color" type="text"
                                                value="<?php echo $datos["color"]; ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-venus-mars me-1 text-primary"></i>
                                                Sexo</label>
                                            <input class="form-control" name="sexo" id="sexo" type="text"
                                                value="<?php echo $datos["sexo"]; ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-heartbeat me-1 text-primary"></i> Estado
                                                de
                                                Salud</label>
                                            <input class="form-control" name="estado_salud" id="estado_salud" type="text"
                                                value="<?php echo $datos["estado_salud"]; ?>">
                                        </div>

                                        <div class="col-md-6">
                                            <label class="form-label"><i class="fas fa-home me-1 text-primary"></i> Estado de
                                                Adopción</label>
                                            <input class="form-control" name="estado_adopcion" id="estado_adopcion" type="text"
                                                value="<?php echo $datos["estado_adopcion"]; ?>">
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <?php if (!empty($datos["descripcion"])): ?>
                                <div class="row mt-4">
                                    <div class="col-12">
                                        <label class="form-label"><i class="fas fa-align-left me-1 text-primary"></i>
                                            Descripción</label>
                                        <textarea class="form-control" rows="3"><?php echo $datos["descripcion"]; ?></textarea>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                        <div class="modal-footer" style="display: flex; justify-content: center;">
                            <button type="submit" class="btn btn-primary">Guardar</button>
                        </div>
                    </div>
            </div>
            </form>
        </div>
        </div>
        <script>
            $(document).on("submit", "#form-edit-animal", function (e) {
                e.preventDefault();

                let formData = new FormData(this);
                formData.append("action", "edit_animales");
                $.ajax({
                    url: "../crud/ajaxanimales.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function (result) {
                        $('#Modal-edit').modal('hide');
                        if (result == "") {
                            Swal.fire("¡Éxito!", "Animal actualizado correctamente.", "success");
                            updatepre();
                        } else {
                            Swal.fire("Error", result, "warning");
                        }
                    },
                    error: function () {
                        Swal.fire("Error", "No se pudo procesar el formulario.", "error");
                    }
                });
            });
        </script>
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
