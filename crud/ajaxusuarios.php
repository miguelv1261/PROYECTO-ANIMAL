<?php
require_once "../includes/System.class.php";
$db = Database::getInstance();

if (isset($_GET["action"])) {
    //LISTAR USUARIOS
    if ($_GET["action"] == "listausuarios") {
        $i = 1;
        $data = array();

        $sql = "SELECT * FROM usuarios ";
        $result = $db->dameQuery($sql);

        while ($row = $result->fetch_assoc()) {
            $item = array();
            $item["id"] = $i++;
            $item["nombre"] = $row["nombre"];
            $item["correo"] = $row["correo"];
            $item["telefono"] = $row["telefono"];
            $item["direccion"] = $row["direccion"];
            $item["estado"] = $row["activo"];
            $item["tool"] = $row["id_usuario"];
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
    //VER USUARIO
    if ($_POST["action"] == "verusuario") {
        $id = $db->sanitize($_POST["id"]);
        $conn = $db->dameQuery("SELECT u.*, r.nombre_rol 
        FROM usuarios u
        LEFT JOIN roles r ON u.id_rol = r.id_rol
        WHERE id_usuario = '$id'");
        $datos = mysqli_fetch_assoc($conn);
?>
        <div class="modal fade" id="Modal-in" tabindex="-1" aria-labelledby="ModalInLabel" aria-hidden="true">
            <div class="modal-dialog modal-dialog-centered modal-lg">
                <div class="modal-content shadow-lg border-0">
                    <div class="modal-header bg-gradient-primary text-white">
                        <h5 class="modal-title" id="ModalInLabel">
                            <i class="fas fa-user me-2"></i> Información del Usuario
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"
                            aria-label="Cerrar"></button>
                    </div>
                    <div class="modal-body">
                        <input type="hidden" id="id">
                        <div class="row g-4 align-items-center">
                            <div class="row g-3">
                                <div class="col-md-12">
                                    <label class="form-label"><i class="fas fa-user me-1 text-primary"></i> Nombre</label>
                                    <input class="form-control" id="nombre" type="text" value="<?php echo $datos["nombre"]; ?>"
                                        readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-envelope me-1 text-primary"></i>
                                        Correo</label>
                                    <input class="form-control" id="correo" type="text" value="<?php echo $datos["correo"]; ?>"
                                        readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-key me-1 text-primary"></i> Contraseña</label>
                                    <input class="form-control" id="pass" type="password"
                                        value="<?php echo $datos["contrasena"]; ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-compass me-1 text-primary"></i>
                                        Dirección</label>
                                    <input class="form-control" id="direccion" type="text"
                                        value="<?php echo $datos["direccion"]; ?>" readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-phone me-1 text-primary"></i>Teléfono</label>
                                    <input class="form-control" id="telf" type="text" value="<?php echo $datos["telefono"]; ?>"
                                        readonly>
                                </div>

                                <div class="col-md-6">
                                    <label class="form-label"><i class="fas fa-home me-1 text-primary"></i> Rol</label>
                                    <input class="form-control" id="rol" type="text" value="<?php echo $datos["nombre_rol"]; ?>"
                                        readonly>
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
            $(document).on("submit", "#form-edit-animal", function(e) {
                e.preventDefault();

                let formData = new FormData(this);
                formData.append("action", "edit_animales");
                $.ajax({
                    url: "crud/ajaxanimales.php",
                    type: "POST",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(result) {
                        $('#Modal-edit').modal('hide');
                        if (result == "") {
                            Swal.fire("¡Éxito!", "Animal actualizado correctamente.", "success");
                            updatepre();
                        } else {
                            Swal.fire("Error", result, "warning");
                        }
                    },
                    error: function() {
                        Swal.fire("Error", "No se pudo procesar el formulario.", "error");
                    }
                });
            });
        </script>
<?php
        exit;
    }
    //CAMBIAR ESTADO
    if ($_POST["action"] == "toggleusuario") {
        $id = $db->sanitize($_POST["id"]);
        $resultado = $db->dameQuery("SELECT activo FROM usuarios WHERE id_usuario = $id LIMIT 1");
        if ($resultado && $fila = $resultado->fetch_assoc()) {
            $estado_actual = $fila["activo"];
            $nuevo_estado = ($estado_actual == 1) ? 2 : 1;

            $db->dameQuery("UPDATE usuarios SET activo = $nuevo_estado WHERE id_usuario = $id");

            echo ($nuevo_estado == 1) ? "Usuario habilitado correctamente." : "Usuario deshabilitado correctamente.";
        } else {
            echo "Usuario no encontrado.";
        }
        exit;
    }
}
