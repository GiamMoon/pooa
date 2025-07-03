
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
          
            <div class="row g-6">
                <h5 class="mb-4">Configuración de Cuenta</h5>

                <!-- Options -->
                <div class="col-12 col-lg-12 pt-6 pt-lg-0">
                    <div class="tab-content p-0">
                        <!-- Store Details Tab -->
                        <div class="tab-pane fade show active" id="store_details" role="tabpanel">
                        
                            <form id="formEditarPerfil1">                                
                            <div class="card mb-6">
                                <div class="card-header">
                                    <h5 class="card-title m-0">Datos personales</h5>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" id="id_usuario1" name="id_usuario1" />
                                    <div class="row mb-4 g-4">
                                        <div class="col-12 col-md-4">
                                            <label class="form-label mb-1" for="user_name">Nombre de usuario</label>
                                            <div class="input-group">
                                                <input type="text" maxlength="255" class="form-control text-lowercase" id="user_name" name="usuario" aria-label="Nombre" placeholder="usuario"/>
                                                <button id="verificarcusuario" type="button" class="btn btn-primary"><i class="bx bx-user-check"></i></button>                            
                                            </div>
                                        </div>

                                        <div class="col-12 col-md-4"><label class="form-label mb-1" for="telefono">Teléfono</label>
                                            <div class="input-group"><span class="input-group-text">+51</span><input type="text" maxlength="9" inputmode="numeric" pattern="[0-9]*" class="form-control phone-mask" id="telefono" name="telefono" placeholder="999 999 999" aria-label="phone" /></div>
                                        </div>

                                        <div class="col-12 col-md-4"><label class="form-label mb-1" for="correo">Correo electrónico</label>                                            
                                            <div class="input-group">
                                                <input type="email" maxlength="255" class="form-control" id="correo" name="correo" aria-label="email" placeholder="correo@dominio"/>
                                                <button id="verificarcorreo" type="button" class="btn btn-primary"><i class="bx bx-message-check"></i></button>                            
                                            </div>
                                        </div>                
                                    </div>
                                </div>
                                <div class="d-flex justify-content-end px-4 pb-4">                                
                                    <button type="submit" class="btn btn-primary" id="btn-1">Guardar Cambios</button>                                
                                </div>
                            </div>                            
                            </form>

                            <form id="formEditarPerfil2">
                            <div class="card mb-6">
                                <div class="card-header">
                                <h5 class="card-title m-0">Datos de Dirección</h5>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" id="id_usuario2" name="id_usuario2" />
                                <div class="row g-6">
                                    <div class="col-12 col-md-6">
                                    <label class="form-label mb-1" for="direccion_p">Dirección</label>
                                    <input type="text" id="direccion_p" class="form-control" placeholder="Address" name="direccion"/>
                                    </div>
                                    <div class="col-12 col-md-2">
                                    <label class="form-label mb-1" for="departamento">Departamento</label>
                                    <select id="departamento" name="departamento" class="form-select select2" data-placeholder="Seleccione departamento"></select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                    <label class="form-label mb-1" for="provincia">Provincia</label>
                                    <select id="provincia" name="provincia" class="form-select select2" disabled data-placeholder="Seleccione provincia"></select>
                                    </div>
                                    <div class="col-12 col-md-2">
                                    <label class="form-label mb-1" for="distrito">Distrito</label>
                                    <select id="distrito" name="distrito" class="form-select select2" disabled data-placeholder="Seleccione distrito"></select>
                                    </div>
                                </div>
                                </div>
                                <div class="d-flex justify-content-end px-4 pb-4">                                
                                    <button type="submit" class="btn btn-primary" id="btn-2">Guardar Cambios</button>                                
                                </div>
                            </div>                            
                            </form>

                            <form id="formEditarPerfil3">                                
                            <div class="card mb-6">
                                <div class="card-header">
                                <div class="card-title mb-0">
                                    <h5 class="m-0">Cambio de Contraseña</h5>
                                    <p class="my-0 card-subtitle">Ingrese su Nueva contraseña</p>
                                </div>
                                </div>
                                <div class="card-body">
                                    <input type="hidden" id="id_usuario3" name="id_usuario3" />
                                    <input type="hidden" id="contrasena" name="contrasena" />
                                <div class="row g-6">
                                    <div class="col-12 col-md-6">
                                    <label class="form-label mb-1" for="nueva_pws">Contraseña Nueva</label>                                    
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="nueva_pws" name="nueva_pws" aria-label="Prefix" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;" />
                                            <span class="input-group-text cursor-pointer toggle-pass" data-target="nueva_pws">
                                                <i class="icon-base bx bx-hide"></i>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="col-12 col-md-6"><label class="form-label mb-1" for="rep_nueva_pws">Repita su Contraseña Nueva</label>                                        
                                        <div class="input-group">
                                            <input type="password" class="form-control" id="rep_nueva_pws" name="rep_nueva_pws" aria-label="Suffix" placeholder="&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;&#xb7;"/>
                                            <span class="input-group-text cursor-pointer toggle-pass" data-target="rep_nueva_pws">
                                                <i class="icon-base bx bx-hide"></i>
                                            </span>
                                        </div>                                        
                                    </div>
                                </div>
                                </div>
                                <div class="d-flex justify-content-end px-4 pb-4">                                
                                    <button type="submit" class="btn btn-primary" id="btn-3">Guardar Cambios</button>                                
                                </div>
                            </div>                            
                            </form>
                        </div>
                    </div>
                </div>
                <!-- /Options-->
            </div>            
        </div>
        <!-- / Content -->