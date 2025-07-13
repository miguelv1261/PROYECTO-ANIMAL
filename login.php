<?php
require_once "../includes/start.php";

if (isset($_POST["Submit"])) {
  require_once "../includes/System.class.php";
  $loginSystem = new LoginSystem();

  if ($loginSystem->doLogin($_POST["loginacc"], $_POST["contras"])) {
    header("Location: dashboard.php");
    exit;
  } else {
    // Error de usuario/contraseña
    header("Location: login.php?msg=2");
    exit;
  }
}
?>
<!doctype html>

<head>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <link rel="apple-touch-icon" sizes="76x76" href="../img/logo.jpeg">
  <link rel="icon" type="image/png" href="../img/logo.jpeg">
  <title>
    RescatePet
  </title>
  <!--     Fonts and icons     -->
  <link href="https://fonts.googleapis.com/css?family=Open+Sans:300,400,600,700" rel="stylesheet" />
  <!-- Nucleo Icons -->
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-icons.css" rel="stylesheet" />
  <link href="https://demos.creative-tim.com/argon-dashboard-pro/assets/css/nucleo-svg.css" rel="stylesheet" />
  <!-- CSS Files -->
  <link id="pagestyle" href="../assets/css/argon-dashboard.css?v=2.1.0" rel="stylesheet" />
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
</head>

<body class="">
  <main class="main-content  mt-0">
    <section>
      <div class="page-header min-vh-100">
        <div class="container">
          <div class="row">
            <div class="col-xl-4 col-lg-5 col-md-7 d-flex flex-column mx-lg-0 mx-auto">
              <div class="card card-plain">
                <div class="card-header pb-0 text-start">
                  <h4 class="font-weight-bolder">Iniciar Sesión</h4>
                  <p class="mb-0">Ingresa tu email y contraseña para loguearte</p>
                </div>
                <div class="card-body">
                  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="POST">
                    <div class="mb-3">
                      <input class="form-control" id="loginacc" name="loginacc" type="email" class="form-control form-control-lg" placeholder="Email" aria-label="Email" autofocus required>
                    </div>
                    <div class="mb-3">
                      <input class="form-control" id="contras" name="contras" type="password" class="form-control form-control-lg" placeholder="Password"
                        aria-label="Password" required>
                    </div>
                    <div class="text-center">
                      <button name="Submit" id="Submit" type="submit" class="btn btn-lg btn-primary btn-lg w-100 mt-4 mb-0">Iniciar Sesión</button>
                    </div>
                  </form>
                </div>
                <div class="card-footer text-center pt-0 px-lg-2 px-1">
                  <p class="mb-4 text-sm mx-auto">
                    No tienes cuenta?
                    <a href="javascript:;" class="text-primary text-gradient font-weight-bold">Registrate</a>
                  </p>
                </div>
              </div>
            </div>
            <div
              class="col-6 d-lg-flex d-none h-100 my-auto pe-0 position-absolute top-0 end-0 text-center justify-content-center flex-column">
              <div
                class="position-relative bg-gradient-primary h-100 m-3 px-7 border-radius-lg d-flex flex-column justify-content-center overflow-hidden"
                style="background-image: url('../assets/img/logo.jpeg');
          background-size: cover;">
                <span class="mask bg-gradient-primary opacity-6"></span>

              </div>
            </div>
          </div>
        </div>
      </div>
    </section>
  </main>
  <script src="../assets/js/plugins/perfect-scrollbar.min.js"></script>
  <script src="../assets/js/plugins/smooth-scrollbar.min.js"></script>

  <script>
    var win = navigator.platform.indexOf('Win') > -1;
    if (win && document.querySelector('#sidenav-scrollbar')) {
      var options = {
        damping: '0.5'
      }
      Scrollbar.init(document.querySelector('#sidenav-scrollbar'), options);
    }
  </script>
  <script>
    $(function() {
      <?php if (isset($_GET["msg"])) {
        if ($_GET["msg"] == "2") { ?>
          Swal.fire({
            title: '¡Error!',
            icon: 'error',
            text: 'Usuario y/o Password incorrectos.'
          });
          <?php }
        if ($_GET["msg"] == "3") {
          if (empty($_GET["estado"])) { ?>
            Swal.fire({
              title: '¡Error!',
              icon: 'error',
              text: 'No podemos validar su Licencia, verifique la conexión a internet del servidor.'
            });
          <?php } else { ?>
            Swal.fire({
              title: '¡Error!',
              icon: 'error',
              text: 'ERROR!! Su licencia se encuentra con estado <?php echo $_GET["estado"]; ?>'
            });
          <?php }
        }
        if ($_GET["msg"] == "4") { ?>
          Swal.fire({
            title: '¡Error!',
            icon: 'error',
            text: 'Existe un problema con su licencia, revise que el servidor tenga conexión a internet y los DNS puedan resolver correctamente.'
          });
        <?php }
        if ($_GET["msg"] == "5") { ?>
          Swal.fire({
            title: '¡Error!',
            icon: 'error',
            text: 'Usuario Deshabilitado'
          });
      <?php }
      } ?>
    });
  </script>
</body>

</html>