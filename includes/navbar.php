   <nav class="navbar navbar-main navbar-expand-lg px-0 mx-4 shadow-none border-radius-xl " id="navbarBlur"
       data-scroll="false">
       <div class="container-fluid py-1 px-3">
           <div class="collapse navbar-collapse mt-sm-0 mt-2 me-md-0 me-sm-4" id="navbar">
               <div class="ms-md-auto pe-md-3 d-flex align-items-center">
               </div>
               <ul class="navbar-nav justify-content-end">
                   <?php if (isset($_SESSION["nombreusername"])): ?>
                       <li class="nav-item dropdown">
                           <a class="nav-link dropdown-toggle text-white font-weight-bold px-0"
                               href="#" id="userDropdown"
                               role="button"
                               data-bs-toggle="dropdown"
                               aria-expanded="false"
                               onclick="event.preventDefault();">
                               <i class="fa fa-user me-sm-1"></i>
                               <span class="d-sm-inline d-none"><?php echo $_SESSION["nombreusername"]; ?></span>
                           </a>
                           <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userDropdown">
                               <li>
                                   <a class="dropdown-item" href="#perfil/">
                                       <i class="fas fa-user fa-sm fa-fw me-2 text-gray-400"></i> Perfil
                                   </a>
                               </li>
                               <li>
                                   <hr class="dropdown-divider">
                               </li>
                               <li>
                                   <a class="dropdown-item" href="#" data-bs-toggle="modal" data-bs-target="#logoutModal">
                                       <i class="fas fa-sign-out-alt fa-sm fa-fw me-2 text-gray-400"></i> Cerrar sesión
                                   </a>
                               </li>
                           </ul>
                       </li>
                   <?php endif; ?>
               </ul>


           </div>
       </div>
   </nav>
   <!-- Logout Modal-->
   <div class="modal fade in" id="logoutModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
       <div class="modal-dialog" role="document">
           <div class="modal-content">
               <div class="modal-header">
                   <h5 class="modal-title" id="exampleModalLabel">Cerrar sesión</h5>
               </div>
               <div class="modal-body">Seleccione "Cerrar sesión" a continuación si está listo para finalizar su sesión actual.</div>
               <div class="modal-footer">
                   <button class="btn btn-secondary" type="button" data-bs-dismiss="modal">Cancelar</button>
                   <a class="btn btn-primary" href="logout.php">Cerrar Sesion</a>
               </div>
           </div>
       </div>
   </div>
   <script>
       const userDropdownToggle = document.getElementById('userDropdown');
       if (userDropdownToggle) {
           userDropdownToggle.addEventListener('click', function(e) {
               e.preventDefault();

               const dropdownMenu = document.querySelector('[aria-labelledby="userDropdown"]');
               if (dropdownMenu) {
                   dropdownMenu.classList.toggle('show');
               }
           });
       }
   </script>