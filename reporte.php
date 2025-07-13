<?php
require_once "includes/start.php";
require_once "includes/System.class.php";
$loginSys = new LoginSystem();
if (!$loginSys->isLoggedIn()) {
  echo "<script type=\"text/javascript\">window.location=\"login.php\"; </script>";
  exit;
}
?>
<?php include 'includes/header.php'; ?>
<div class="min-height-300 bg-dark position-absolute w-100"></div>
<?php include "includes/menu.php"; ?>
<main class="main-content position-relative border-radius-lg ">
    <?php include "includes/navbar.php"; ?>
    <div class="container-fluid py-4">
        <div class="row">
            <div class="col-12">
                <div class="card mb-4">
                    <div class="card-header pb-0">
                        <h6>PERROS POR RESCATAR</h6>
                    </div>
                    <div class="card-body">
                        <table id="list-pre" class="table table-striped table-bordered dt-responsive nowrap w-100">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Nombre</th>
                                    <th>Edad</th>
                                    <th>Estado</th>
                                    <th>Acción</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        <div id="tmp"></div>
    </div>
    <script>
        var pre;
        $(function() {
            pre = $('#list-pre').DataTable({
                ajax: "crud/ajaxreporte.php?action=listaanimales",
                responsive: true,
                deferRender: true,
                stateSave: true,
                autoWidth: false,
                order: [0, 'asc'],
                columns: [{
                        data: 'id',
                        responsivePriority: 6
                    },
                    {
                        data: 'descripcion',
                        responsivePriority: 1
                    },
                    {
                        data: 'fecha',
                        responsivePriority: 4
                    },
                    {
                        data: 'estado',
                        responsivePriority: 4
                    },
                    {
                        data: 'tool',
                        responsivePriority: 0,
                        render: function(data, type, row, meta) {
                            return `
                    <i class="fa fa-eye text-info me-2" style="cursor:pointer"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Ver"
                      onclick="view_reporte(${data})"></i>

                    <i class="fa fa-pen text-warning me-2" style="cursor:pointer"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"
                      onclick="edit_reporte(${data})"></i>

                    <i class="fa fa-trash text-danger" style="cursor:pointer"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Eliminar"
                      onclick="delete_reporte(${data})"></i>
                  `;
                        }
                    }
                ],
                dom: 'Bfrtip',
                buttons: [
                    'pageLength',
                    'colvis',
                    {
                        extend: 'collection',
                        text: '<i class="fa fa-floppy-o"></i> Exportar',
                        buttons: [{
                                extend: 'print',
                                title: 'Lista de animales',
                                text: '<i class="fa fa-print"></i> Imprimir'
                            },
                            {
                                extend: 'csvHtml5',
                                title: 'Lista de animales',
                                text: '<i class="fa fa-file-csv"></i> CSV'
                            },
                            {
                                extend: 'pdfHtml5',
                                title: 'Lista de animales',
                                orientation: 'landscape',
                                text: '<i class="fa fa-file-pdf"></i> PDF'
                            }
                        ]
                    }
                ],
                "initComplete": function() {
                    $('#list-pre_wrapper .dt-buttons').after('<div class="btn-group dt-btns"></div>');
                    $('#list-pre_wrapper .dt-buttons').append(
                        '<a class="btn btn-sm btn-default buttons-collection" onClick="updatepre()"><i class="fa fa-refresh" ></i></a>'
                    );
                }
            });
        });

        function updatepre() {
            pre.ajax.reload(null, false);
        }
        //VER REPORTE
        function view_reporte(id) {
            $.post("crud/ajaxreporte.php", {
                action: "verreporte",
                id: id
            }).done(function(data) {
                $('#tmp').html(data);

                setTimeout(function() {
                    const modalEl = document.getElementById('Modal-in');
                    const modal = new bootstrap.Modal(modalEl);
                    modal.show();
                    modalEl.addEventListener('shown.bs.modal', function() {
                        const mapContainer = document.getElementById('map');
                        const lat = parseFloat(mapContainer.getAttribute('data-lat'));
                        const lng = parseFloat(mapContainer.getAttribute('data-lng'));

                        if (!isNaN(lat) && !isNaN(lng)) {
                            const map = L.map('map').setView([lat, lng], 14);

                            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                                attribution: '&copy; OpenStreetMap contributors'
                            }).addTo(map);

                            const marker = L.marker([lat, lng]).addTo(map).openPopup();

                            marker.on('click', function() {
                                const googleMapsUrl = `https://www.google.com/maps?q=${lat},${lng}`;
                                window.open(googleMapsUrl, '_blank');
                            });

                            setTimeout(() => {
                                map.invalidateSize();
                            }, 300);
                        }
                    }, {
                        once: true
                    });
                }, 100);
            });
        }

        //EDITAR PERRO
        function edit_animal(id_animal) {
            $.post("crud/ajaxanimales.php", {
                action: "editaranimal",
                id: id_animal
            }).done(function(data) {
                $('#tmp').html(data);
                setTimeout(function() {
                    const modal = new bootstrap.Modal(document.getElementById('Modal-edit'));
                    modal.show();
                }, 100);
            });
        }
        //CAMBIAR ESTADO
        function delete_animal(id) {
            Swal.fire({
                title: "¿Está seguro que desea desactivar este registro?",
                text: "Podrá activarlo nuevamente si lo desea.",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#DD6B55",
                cancelButtonText: "Cancelar",
                confirmButtonText: "Sí, desactivar",
            }).then((result) => {
                if (result.isConfirmed) {
                    $.post("crud/ajaxanimales.php", {
                        action: "deleteanimal",
                        id: id
                    })
                    updatepre();
                    Swal.fire({
                        icon: "success",
                        title: "Operación exitosa",
                        text: "Registro desactivado correctamente",
                        timer: 2000,
                        showConfirmButton: false
                    });
                }
            });
        }
    </script>