<?php
require_once "../includes/start.php";
require_once "../includes/System.class.php";
$loginSys = new LoginSystem();
if (!$loginSys->isLoggedIn()) {
  echo "<script type=\"text/javascript\">window.location=\"login.php\"; </script>";
  exit;
}
?>
<?php include '../includes/header.php'; ?>
<div class="min-height-300 bg-dark position-absolute w-100"></div>
<?php include "../includes/menu.php"; ?>
<main class="main-content position-relative border-radius-lg ">
  <?php include "../includes/navbar.php"; ?>
  <div class="container-fluid py-4">
    <div class="row">
      <div class="col-12">
        <div class="card mb-4">
          <div class="card-header pb-0">
            <h6>USUARIOS</h6>
          </div>
          <div class="card-body">
            <table id="list-pre" class="table table-striped table-bordered dt-responsive nowrap w-100">
              <thead>
                <tr>
                  <th>ID</th>
                  <th>Nombre</th>
                  <th>Correo</th>
                  <th>Telefono</th>
                  <th>Dirección</th>
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
        ajax: "../crud/ajaxusuarios.php?action=listausuarios",
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
            data: 'nombre',
            responsivePriority: 1
          },
          {
            data: 'correo',
            responsivePriority: 4
          },
          {
            data: 'telefono',
            responsivePriority: 5
          },
          {
            data: 'direccion',
            responsivePriority: 7
          },
          {
            data: 'estado',
            responsivePriority: 7,
            render: function(data, type, row) {
              if (data == 1) {
                return '<span class="badge bg-success">Habilitado</span>';
              } else {
                return '<span class="badge bg-danger">Deshabilitado</span>';
              }
            }
          },
          {
            data: 'tool',
            responsivePriority: 0,
            render: function(data, type, row, meta) {
              return `
                    <i class="fa fa-eye text-info me-2" style="cursor:pointer"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Ver"
                      onclick="view_usuario(${data})"></i>

                    <i class="fa fa-pen text-warning me-2" style="cursor:pointer"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Editar"
                      onclick="edit_usuario(${data})"></i>

                    <i class="fa fa-trash text-danger" style="cursor:pointer"
                      data-bs-toggle="tooltip" data-bs-placement="top" title="Habilitar/Deshabilitar"
                      onclick="delete_usuario(${data})"></i>
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
            '<a class="btn btn-default buttons-collection" onClick="updatepre()"><i class="fa fa-refresh" ></i></a>'
          );
          $('#list-pre_wrapper .dt-buttons').append(
            '<a class="btn btn-default buttons-collection" onClick="new_animal()"><i class="fa fa-plus"></i> AGREGAR </a>'
          );


        }
      });
    });

    function updatepre() {
      pre.ajax.reload(null, false);
    }
    //VER USUARIO
    function view_usuario(id_animal) {
      $.post("../crud/ajaxusuarios.php", {
        action: "verusuario",
        id: id_animal
      }).done(function(data) {
        $('#tmp').html(data);
        setTimeout(function() {
          const modal = new bootstrap.Modal(document.getElementById('Modal-in'));
          modal.show();
        }, 100);
      });
    }
    //NUEVO USUARIO
    function new_usuario(id_animal) {
      $.post("../crud/ajaxusuarios.php", {
        action: "nuevousuario",
        id: id_animal
      }).done(function(data) {
        $('#tmp').html(data);
        setTimeout(function() {
          const modal = new bootstrap.Modal(document.getElementById('Modal-new'));
          modal.show();
        }, 100);
      });
    }
    //EDITAR USUARIO
    function edit_usuario(id_animal) {
      $.post("../crud/ajaxusuarios.php", {
        action: "editarusuario",
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
    function delete_usuario(id) {
      Swal.fire({
        title: "¿Está seguro que desea cambiar el estado del registro?",
        text: "Podrá volver a modificarlo cuando lo desee.",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        cancelButtonText: "Cancelar",
        confirmButtonText: "Sí, continuar",
      }).then((result) => {
        if (result.isConfirmed) {
          $.post("../crud/ajaxusuarios.php", {
            action: "toggleusuario",
            id: id
          }, function(response) {
            updatepre();
            Swal.fire({
              icon: "success",
              title: "Operación exitosa",
              text: response,
              timer: 2000,
              showConfirmButton: false
            });
          });
        }
      });
    }
  </script>