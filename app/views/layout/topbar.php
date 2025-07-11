        <!-- Layout container -->
        <div class="layout-page">
          <!-- Navbar -->
          <nav class="layout-navbar container-xxl navbar-detached navbar navbar-expand-xl align-items-center bg-navbar-theme" id="layout-navbar">
            <div class="layout-menu-toggle navbar-nav align-items-xl-center me-4 me-xl-0   d-xl-none ">
              <a class="nav-item nav-link px-0 me-xl-6" href="javascript:void(0)">
              <i class="icon-base bx bx-menu icon-md"></i>
              </a>
            </div>
            <div class="navbar-nav-right d-flex align-items-center justify-content-end" id="navbar-collapse">
              <!-- Search -->
              <div class="navbar-nav align-items-center me-auto">
                <div class="nav-item d-flex align-items-center">
                  <span class="w-px-22 h-px-22"><i class="icon-base bx bx-search icon-md"></i></span>
                  <input type="text" class="form-control border-0 shadow-none ps-1 ps-sm-2 d-md-block d-none" placeholder="Search..." aria-label="Search..." />
                </div>
              </div>
              <!-- /Search -->
              <ul class="navbar-nav flex-row align-items-center ms-md-auto">                                              
                <!-- Style Switcher -->
                <li class="nav-item dropdown me-2 me-xl-0">
                  <a class="nav-link dropdown-toggle hide-arrow" id="nav-theme" href="javascript:void(0);" data-bs-toggle="dropdown" aria-label="Toggle theme (light)">
                  <i class="bx-sun icon-base bx icon-md theme-icon-active"></i>
                  <span class="d-none ms-2" id="nav-theme-text">Toggle theme</span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="nav-theme-text">
                    <li>
                      <button type="button" class="dropdown-item align-items-center active" data-bs-theme-value="light" aria-pressed="true">
                      <span><i class="icon-base bx bx-sun icon-md me-3" data-icon="sun"></i>Light</span>
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="dark" aria-pressed="false">
                      <span><i class="icon-base bx bx-moon icon-md me-3" data-icon="moon"></i>Dark</span>
                      </button>
                    </li>
                    <li>
                      <button type="button" class="dropdown-item align-items-center" data-bs-theme-value="system" aria-pressed="false">
                      <span><i class="icon-base bx bx-desktop icon-md me-3" data-icon="desktop"></i>System</span>
                      </button>
                    </li>
                  </ul>
                </li>
                <!-- / Style Switcher-->
                <!-- Quick links  -->
                <li class="nav-item dropdown-shortcuts navbar-dropdown dropdown me-2 me-xl-0">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                  <i class="icon-base bx bx-grid-alt icon-md"></i>
                  </a>
                  <div class="dropdown-menu dropdown-menu-end p-0">
                    <div class="dropdown-menu-header border-bottom">
                      <div class="dropdown-header d-flex align-items-center py-3">
                        <h6 class="mb-0 me-auto">Atajos</h6>
                      </div>
                    </div>
                    <div class="dropdown-shortcuts-list scrollable-container ps">
                      
                      <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="icon-base bx bx-receipt icon-26px text-heading"></i>
                          </span>
                          <a href="<?= BASE_URL('reportes/movimientos') ?>" class="stretched-link">Reportes</a>
                          <small>Gestionar Reportes de las operaciones</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="icon-base bx bx-pie-chart-alt-2 icon-26px text-heading"></i>
                          </span>
                          <a href="<?= BASE_URL('auth/home') ?>" class="stretched-link">Dashboard</a>
                          <small>Panel de Usuario</small>
                        </div>
                      </div>
                      
                      <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="icon-base bx bx-user icon-26px text-heading"></i>
                          </span>
                          <a href="<?= BASE_URL('seguridad/usuario') ?>" class="stretched-link">Usuarios</a>
                          <small>Gestionar usuarios</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="icon-base bx bx-check-shield icon-26px text-heading"></i>
                          </span>
                          <a href="<?= BASE_URL('seguridad/rol') ?>" class="stretched-link">Roles</a>
                          <small>Gestionar Roles y Permisos</small>
                        </div>
                      </div>

                      <div class="row row-bordered overflow-visible g-0">
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="icon-base bx bx bx-building icon-26px text-heading"></i>
                          </span>
                          <a href="" class="stretched-link">Datos de empresa</a>
                          <small>Gestionar Información</small>
                        </div>
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="icon-base bx bx-cog icon-26px text-heading"></i>
                          </span>
                          <a href="<?= BASE_URL('seguridad/perfil') ?>" class="stretched-link">Setting</a>
                          <small>Configuración de cuenta</small>
                        </div>
                      </div>

                      <!--<div class="row row-bordered overflow-visible g-0">                        
                        <div class="dropdown-shortcuts-item col">
                          <span class="dropdown-shortcuts-icon rounded-circle mb-3">
                          <i class="icon-base bx bx-window-open icon-26px text-heading"></i>
                          </span>
                          <a href="modal-examples.html" class="stretched-link">Modals</a>
                          <small>Useful Popups</small>
                        </div>
                      </div>-->

                      <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                      </div>
                      <div class="ps__rail-y" style="top: 0px; right: 0px;">
                        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                      </div>
                    </div>
                  </div>
                </li>
                <!-- Quick links -->
                <!-- Notification -->
                <li class="nav-item dropdown-notifications navbar-dropdown dropdown me-3 me-xl-2">
                  <a class="nav-link dropdown-toggle hide-arrow" href="javascript:void(0);" data-bs-toggle="dropdown" data-bs-auto-close="outside" aria-expanded="false">
                  <span class="position-relative">
                  <i class="icon-base bx bx-bell icon-md"></i>
                  <span class="badge rounded-pill bg-danger badge-dot badge-notifications border"></span>
                  </span>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end p-0">
                    <li class="dropdown-menu-header border-bottom">
                      <div class="dropdown-header d-flex align-items-center py-3">
                        <h6 class="mb-0 me-auto">Notification</h6>
                        <div class="d-flex align-items-center h6 mb-0">
                          <span class="badge bg-label-primary me-2">8 New</span>
                          <a href="javascript:void(0)" class="dropdown-notifications-all p-2" data-bs-toggle="tooltip" data-bs-placement="top" aria-label="Mark all as read" data-bs-original-title="Mark all as read"><i class="icon-base bx bx-envelope-open text-heading"></i></a>
                        </div>
                      </div>
                    </li>
                    <li class="dropdown-notifications-list scrollable-container ps">
                      <ul class="list-group list-group-flush">
                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <img src="<?= BASE_URL ?>assets/img/avatars/1.png" alt="" class="rounded-circle">
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">Congratulation Lettie 🎉</h6>
                              <small class="mb-1 d-block text-body">Won the monthly best seller gold badge</small>
                              <small class="text-body-secondary">1h ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-danger">CF</span>
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">Charles Franklin</h6>
                              <small class="mb-1 d-block text-body">Accepted your connection</small>
                              <small class="text-body-secondary">12hr ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <img src="<?= BASE_URL ?>assets/img/avatars/2.png" alt="" class="rounded-circle">
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">New Message ✉️</h6>
                              <small class="mb-1 d-block text-body">You have new message from Natalie</small>
                              <small class="text-body-secondary">1h ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success"><i class="icon-base bx bx-cart"></i></span>
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">Whoo! You have new order 🛒</h6>
                              <small class="mb-1 d-block text-body">ACME Inc. made new order $1,154</small>
                              <small class="text-body-secondary">1 day ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <img src="<?= BASE_URL ?>assets/img/avatars/9.png" alt="" class="rounded-circle">
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">Application has been approved 🚀</h6>
                              <small class="mb-1 d-block text-body">Your ABC project application has been approved.</small>
                              <small class="text-body-secondary">2 days ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-success"><i class="icon-base bx bx-pie-chart-alt"></i></span>
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">Monthly report is generated</h6>
                              <small class="mb-1 d-block text-body">July monthly financial report is generated </small>
                              <small class="text-body-secondary">3 days ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <img src="<?= BASE_URL ?>assets/img/avatars/5.png" alt="" class="rounded-circle">
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">Send connection request</h6>
                              <small class="mb-1 d-block text-body">Peter sent you connection request</small>
                              <small class="text-body-secondary">4 days ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item list-group-item-action dropdown-notifications-item">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <img src="<?= BASE_URL ?>assets/img/avatars/6.png" alt="" class="rounded-circle">
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">New message from Jane</h6>
                              <small class="mb-1 d-block text-body">Your have new message from Jane</small>
                              <small class="text-body-secondary">5 days ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                        <li class="list-group-item list-group-item-action dropdown-notifications-item marked-as-read">
                          <div class="d-flex">
                            <div class="flex-shrink-0 me-3">
                              <div class="avatar">
                                <span class="avatar-initial rounded-circle bg-label-warning"><i class="icon-base bx bx-error"></i></span>
                              </div>
                            </div>
                            <div class="flex-grow-1">
                              <h6 class="small mb-0">CPU is running high</h6>
                              <small class="mb-1 d-block text-body">CPU Utilization Percent is currently at 88.63%,</small>
                              <small class="text-body-secondary">5 days ago</small>
                            </div>
                            <div class="flex-shrink-0 dropdown-notifications-actions">
                              <a href="javascript:void(0)" class="dropdown-notifications-read"><span class="badge badge-dot"></span></a>
                              <a href="javascript:void(0)" class="dropdown-notifications-archive"><span class="icon-base bx bx-x"></span></a>
                            </div>
                          </div>
                        </li>
                      </ul>
                      <div class="ps__rail-x" style="left: 0px; bottom: 0px;">
                        <div class="ps__thumb-x" tabindex="0" style="left: 0px; width: 0px;"></div>
                      </div>
                      <div class="ps__rail-y" style="top: 0px; right: 0px;">
                        <div class="ps__thumb-y" tabindex="0" style="top: 0px; height: 0px;"></div>
                      </div>
                    </li>
                    <li class="border-top">
                      <div class="d-grid p-4">
                        <a class="btn btn-primary btn-sm d-flex" href="javascript:void(0);">
                        <small class="align-middle">View all notifications</small>
                        </a>
                      </div>
                    </li>
                  </ul>
                </li>
                <!--/ Notification -->
                <!-- User -->
                <li class="nav-item navbar-dropdown dropdown-user dropdown">
                  <a class="nav-link dropdown-toggle hide-arrow p-0" href="javascript:void(0);" data-bs-toggle="dropdown">
                    <div class="avatar avatar-online">
                      <img src="<?= BASE_URL ?>assets/img/avatars/1.png" alt="" class="rounded-circle">
                    </div>
                  </a>
                  <ul class="dropdown-menu dropdown-menu-end">
                    <li>
                      <a class="dropdown-item" href="pages-account-settings-account.html">
                        <div class="d-flex">
                          <div class="flex-shrink-0 me-3">
                            <div class="avatar avatar-online">
                              <img src="<?= BASE_URL ?>assets/img/avatars/1.png" alt="" class="w-px-40 h-auto rounded-circle">
                            </div>
                          </div>
                          <div class="flex-grow-1">
                            <h6 class="mb-0"><?php echo $_SESSION['usuario'] ?></h6>
                            <small class="text-body-secondary"><?php echo ucfirst($_SESSION['correo']); ?></small>
                          </div>
                        </div>
                      </a>
                    </li>
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="<?= BASE_URL('seguridad/perfil') ?>"> <i class="icon-base bx bx-user icon-md me-3"></i><span>My Profile</span> </a>
                    </li>                    
                                        
                    <li>
                      <div class="dropdown-divider my-1"></div>
                    </li>
                    <li>
                      <a class="dropdown-item" href="<?= BASE_URL('auth/logout') ?>"> <i class="icon-base bx bx-power-off icon-md me-3"></i><span>Log Out</span> </a>
                    </li>
                  </ul>
                </li>
                <!--/ User -->
              </ul>
            </div>
          </nav>
          <!-- / Navbar -->