'use strict';

$(document).ready(function () {
    
    $('.select2').select2({
    placeholder: function () {
        return $(this).data('placeholder');
    },
        width: '100%'
    });

    /* ==== INICIO: VALIDARCIONES CAMPOS ==== */
    const soloNumeros = (e) => {
        const key = e.key;
        if (!/^\d$/.test(key) && key !== 'Backspace' && key !== 'Delete' && key !== 'ArrowLeft' && key !== 'ArrowRight' && key !== 'Tab') {
        e.preventDefault();
        }
    };

    // Bloquear la tecla espacio
    $('#telefono, #user_name, #correo').on('keydown', function (e) {
        if (e.key === ' ') {
        e.preventDefault();
        }
    });
    
    document.getElementById('telefono').addEventListener('keydown', soloNumeros);    
    /* ==== FIN: VALIDARCIONES CAMPOS ==== */


/* ---- INICIO: DATOS PERSONALES ---- */
    let correoEditDisponible = null;
    let usuarioEditDisponible = null;
    let originalEditData1 = {};
    let isSubmitting1 = false;

    // Cargar datos de perfil y preseleccionar valores
    fetch(`${assetsPath}perfilAjax/obtener_perfil`)
        .then(res => res.json())
        .then(data => {
            // Rellenar datos personales
            $('#id_usuario1').val(data.id_usuario);
            $('#user_name').val(data.nombre_usuario);
            $('#telefono').val(data.telefono);
            $('#correo').val(data.correo);        

            originalEditData1 = {
                usuario: data.nombre_usuario,
                telefono: data.telefono,
                correo: data.correo                
            };            
        })
        .catch(err => {
        console.error("Error al obtener usuario:", err);
        alert("Error al obtener datos del usuario.");
        });
    
    function verificarUsuarioExistenteEdit() {
        const usuario = $('#user_name').val().trim();
        const idUsuario = $('#id_usuario1').val();
        if (!usuario) {
            Toastify({
                text: 'Ingrese un usuario',
                duration: 3000,
                style: { background: '#ffc107', color: '#000' }
            }).showToast();
            return Promise.resolve(false);
        };
        return fetch(`${assetsPath}usuarioAjax/validar_usuario?usuario=${encodeURIComponent(usuario)}&id_usuario=${encodeURIComponent(idUsuario)}`)
            .then (res => {
                if (!res.ok) throw new Error('Error de red en validad registros');
                return res.json ();
            })
            .then (dataDB => {      
                usuarioEditDisponible = !dataDB.valid;
                Toastify({
                    text: usuarioEditDisponible ? "Usuario disponible ✅" : "Usuario ya registrado ❌",
                    duration: 3000,
                    style: { background: usuarioEditDisponible ? "#28a745" : "#dc3545" }
                }).showToast();          
            })
            .catch(() => {
                Toastify({
                    text: "Error en la verificación del usuario",
                    duration: 3000,
                    style: { background: "#dc3545" }
                }).showToast();
            });
    }

    function verificarCorreoExistenteEdit() {
        const correo = $('#correo').val().trim();
        const idUsuario = $('#id_usuario1').val();
        if (!correo) {
            Toastify({
                text: 'Ingrese un correo',
                duration: 3000,
                style: { background: '#ffc107', color: '#000' }
            }).showToast();
                return Promise.resolve(false);
        };
        fetch(`${assetsPath}usuarioAjax/validar_correo1?correo=${encodeURIComponent(correo)}&id_usuario=${encodeURIComponent(idUsuario)}`)
            .then(res => {
                if (!res.ok) throw new Error('Error de red en validar registros');
                return res.json();
            })
            .then(dataDB => {
                if (dataDB.valid) {
                    correoEditDisponible = false;
                    Toastify({
                        text: 'Correo ya registrado ❌',
                        duration: 3000,
                        style: { background: '#dc3545' }
                    }).showToast();
                    return false; // Detener aquí   
                }
                return fetch(`${assetsPath}consultaAjax/validar_correo_real1?correo=${encodeURIComponent(correo)}`)
                .then(res => {
                    if (!res.ok) throw new Error('Error de red en validar existencia de correo');
                    return res.json();
                })
                .then(data => {
                    Toastify({
                        text: data.message,
                        duration: 3000,
                        style: { background: data.success && data.valid ? '#28a745' : '#dc3545' }
                    }).showToast();
                    correoEditDisponible = data.success && data.valid;
                    return correoEditDisponible;
                });                
            })
            .catch(err => {
                console.error('Error durante la validación del correo:', err);
                correoEditDisponible = false;
                Toastify({
                    text: "Error validando correo ❌",
                    duration: 3000,
                    style: { background: '#dc3545' }
                }).showToast();
                return false;
            });
    }

    function hasFormChanged() {
        return (
        $('#telefono').val().trim() !== (originalEditData1.telefono || '').trim() ||        
        $('#correo').val().trim() !== (originalEditData1.correo || '').trim() ||
        $('#user_name').val().trim() !== (originalEditData1.usuario || '').trim()        
        );
    }  

    $('#verificarcusuario').prop('disabled', true);
    $('#verificarcorreo').prop('disabled', true);
    
    $('#user_name').on('input', function () {
        const usuarioValido = $(this).val().trim().length >= 4;
        $('#verificarcusuario').prop('disabled', !usuarioValido);
    });

    $('#correo').on('input', function () {
        const correoValido = /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test($(this).val().trim());
        $('#verificarcorreo').prop('disabled', !correoValido);
    });

    $('#verificarcusuario').on('click', function () {
        verificarUsuarioExistenteEdit();
    });

    $('#verificarcorreo').on('click', function () {
        verificarCorreoExistenteEdit();
    });

    //Enviar formulario
  $('#formEditarPerfil1').on('submit', async function (e) {
    e.preventDefault();

    if (!this.checkValidity()) {
      this.reportValidity();
      return;
    }

    if (!hasFormChanged()) {
      Toastify({
        text: "No se realizaron cambios ⚠️",
        duration: 3000,
        style: { background: "#ffc107", color: "#000" }
      }).showToast();
      return; // No enviar si no hay cambios
    }

    if (correoEditDisponible === false) {
      Toastify({ text: 'Correo no válido ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }
    if (usuarioEditDisponible === false) {
      Toastify({ text: 'Usuario no válido ❌', style: { background: '#dc3545' } }).showToast();
      return;
    }
    
    enviarEdicionUsuario();
  });

  //Función de envío
  function enviarEdicionUsuario() {
    isSubmitting1 = true;
    const form1 = document.getElementById('formEditarPerfil1');
    const formData = new FormData(form1);    

    const submitBtn = $(form1).find('button[type="submit"]');
    submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
    
    fetch(`${assetsPath}perfilAjax/save_contacto`, {
      method: 'POST',
      body: formData
    })
      .then(res => res.json())
      .then(data => {
        if (data.success) {          
          Toastify({
            text: "Datos de Contacto actualizados correctamente ✅",
            duration: 3000,
            style: { background: "#28a745" },
            callback: function () {
                location.reload();
            }
          }).showToast();
        } else {
          Toastify({
            text: "Error al actualizar Datos de Contacto ⚠️",
            duration: 3000,
            style: { background: "#dc3545" }
          }).showToast();
        }
      })
      .catch(err => {
        console.error("Error en actualización:", err);
        Toastify({
          text: "Error al actualizar usuario ❌",
          duration: 3000,
          style: { background: "#dc3545" }
        }).showToast();
      })
      .finally(() => {
        submitBtn.prop('disabled', false).html('Guardar cambios');
        isSubmitting1 = false;
      });
  }
/* ---- FIN: DATOS PERSONALES ---- */

    

/* ---- INICIO: DATOS DIRECCION ---- */
    let originalEditData2 = {};
    let isSubmitting2 = false;

    // Asignar eventos dinámicos una sola vez
    $('#departamento').on('change', function () {
        const dep = $(this).val();
        $('#provincia').prop('disabled', !dep).empty().append('<option></option>');
        $('#distrito').prop('disabled', true).empty().append('<option></option>');

        if (!dep) return;

        fetch(`${assetsPath}perfilAjax/listar_provincias?departamento=${encodeURIComponent(dep)}`)
        .then(res => res.json())
        .then(provincias => {
            provincias.forEach(prov => {
            $('#provincia').append(`<option value="${prov.provincia}">${prov.provincia}</option>`);
            });
        });
    });

    $('#provincia').on('change', function () {
        const dep = $('#departamento').val();
        const prov = $(this).val();
        $('#distrito').prop('disabled', !prov).empty().append('<option></option>');

        if (!prov) return;

        fetch(`${assetsPath}perfilAjax/listar_distritos?departamento=${encodeURIComponent(dep)}&provincia=${encodeURIComponent(prov)}`)
        .then(res => res.json())
        .then(distritos => {
            distritos.forEach(dist => {
            $('#distrito').append(`<option value="${dist.distrito}">${dist.distrito}</option>`);
            });
        });
    });

    // Cargar datos de perfil y preseleccionar valores
    fetch(`${assetsPath}perfilAjax/obtener_perfil`)
        .then(res => res.json())
        .then(data => {
        fetch(`${assetsPath}perfilAjax/listar_departamentos`)
            .then(res => res.json())
            .then(departamentos => {
            const selectDep = $('#departamento');
            selectDep.empty().append('<option></option>');
            departamentos.forEach(dep => {
                const selected = dep.departamento == data.departamento ? 'selected' : '';
                selectDep.append(`<option value="${dep.departamento}" ${selected}>${dep.departamento}</option>`);
            });

            if (!data.departamento) return;

            fetch(`${assetsPath}perfilAjax/listar_provincias?departamento=${encodeURIComponent(data.departamento)}`)
                .then(res => res.json())
                .then(provincias => {
                const selectPro = $('#provincia');
                selectPro.empty().append('<option></option>');
                provincias.forEach(prov => {
                    const selected = prov.provincia == data.provincia ? 'selected' : '';
                    selectPro.append(`<option value="${prov.provincia}" ${selected}>${prov.provincia}</option>`);
                });

                if (!data.provincia) return;

                fetch(`${assetsPath}perfilAjax/listar_distritos?departamento=${encodeURIComponent(data.departamento)}&provincia=${encodeURIComponent(data.provincia)}`)
                    .then(res => res.json())
                    .then(distritos => {
                        const selectDis = $('#distrito');
                        selectDis.empty().append('<option></option>');
                        distritos.forEach(dist => {
                            const selected = dist.distrito == data.distrito ? 'selected' : '';
                            selectDis.append(`<option value="${dist.distrito}" ${selected}>${dist.distrito}</option>`);
                        });
                        
                        if (data.provincia) $('#provincia').prop('disabled', false);
                        if (data.distrito) $('#distrito').prop('disabled', false);
                        
                        $('#id_usuario2').val(data.id_usuario);
                        $('#direccion_p').val(data.direccion);

                        originalEditData2 = {                        
                            direccion: data.direccion,
                            departamento: data.departamento,
                            provincia: data.provincia,
                            distrito: data.distrito,                        
                        };
                    });
                });
            });
        })
        .catch(err => {
        console.error("Error al obtener usuario:", err);
        alert("Error al obtener datos del usuario.");
        });

    function hasFormChanged1() {
        return (            
        $('#direccion_p').val().trim() !== (originalEditData2.direccion || '').trim() ||
        $('#departamento').val() !== (originalEditData2.departamento || '') ||
        $('#provincia').val() !== (originalEditData2.provincia || '') ||
        $('#distrito').val() !== (originalEditData2.distrito || '')                        
        );
    }

    //Enviar formulario
    $('#formEditarPerfil2').on('submit', async function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
        this.reportValidity();
        return;
        }

        if (!hasFormChanged1()) {
        Toastify({
            text: "No se realizaron cambios ⚠️",
            duration: 3000,
            style: { background: "#ffc107", color: "#000" }
        }).showToast();
        return; // No enviar si no hay cambios
        }        
        
        enviarEdicionUsuario1();
    });

    //Función de envío
    function enviarEdicionUsuario1() {
        isSubmitting2 = true;
        const form2 = document.getElementById('formEditarPerfil2');
        const formData = new FormData(form2);    

        const submitBtn = $(form2).find('button[type="submit"]');
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');
        
        fetch(`${assetsPath}perfilAjax/save_direccion`, {
        method: 'POST',
        body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {          
            Toastify({
                text: "Datos de Ubicación actualizados correctamente ✅",
                duration: 3000,
                style: { background: "#28a745" },
                callback: function () {
                    location.reload();
                }
            }).showToast();
            } else {
            Toastify({
                text: "Error al actualizar Datos de Ubicación ⚠️",
                duration: 3000,
                style: { background: "#dc3545" }
            }).showToast();
            }
        })
        .catch(err => {
            console.error("Error en actualización:", err);
            Toastify({
            text: "Error al actualizar usuario ❌",
            duration: 3000,
            style: { background: "#dc3545" }
            }).showToast();
        })
        .finally(() => {
            submitBtn.prop('disabled', false).html('Guardar cambios');
            isSubmitting2 = false;
        });
    }
/* ---- FIN: DATOS DIRECCION ---- */



/* ---- INICIO: DATOS CONTRASEÑA ---- */
    let originalEditData3 = {};
    let isSubmitting3 = false;

    // Cargar datos de perfil y preseleccionar valores
    fetch(`${assetsPath}perfilAjax/obtener_perfil`)
        .then(res => res.json())
        .then(data => {
            // Rellenar datos personales
            $('#id_usuario3').val(data.id_usuario);            
            $('#contrasena').val(data.contrasena);

            originalEditData3 = {
                contrasena: data.contrasena
            };            
        })
        .catch(err => {
        console.error("Error al obtener usuario:", err);
        alert("Error al obtener datos del usuario.");
        });
    

    function validarContrasena(password) {
        const regex = /^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[\W_])(?!.*\s).{8,}$/;
        return regex.test(password);
    }

    function hasFormChanged3() {
        return $('#nueva_pws').val().trim().length > 0;
    }

    function mostrarAlertaContrasena(mensaje, tipo = 'danger') {
        Toastify({
            text: mensaje,
            duration: 4000,
            style: { background: tipo === 'success' ? "#28a745" : "#dc3545", color: '#fff' }
        }).showToast();
    }

    // Envío del formulario de contraseña
    $('#formEditarPerfil3').on('submit', function (e) {
        e.preventDefault();

        if (!this.checkValidity()) {
            this.reportValidity();
            return;
        }

        const nueva = $('#nueva_pws').val().trim();
        const repetir = $('#rep_nueva_pws').val().trim();

        if (!hasFormChanged3()) {
            Toastify({
            text: "No se realizaron cambios ⚠️",
            duration: 3000,
            style: { background: "#ffc107", color: "#000" }
            }).showToast();
            return;
        }

        if (!nueva || !repetir) {
            Toastify({
            text: "Ingrese ambas contraseñas",
            duration: 3000,
            style: { background: "#dc3545" }
            }).showToast();
            return;
        }

        if (!validarContrasena(nueva)) {
            Toastify({
            text: "La contraseña debe tener:\n• 8 caracteres\n• 1 mayúscula\n• 1 minúscula\n• 1 número\n• 1 carácter especial\n• Sin espacios",
            duration: 4000,
            style: { background: "#dc3545" }
            }).showToast();
            return;
        }

        if (nueva !== repetir) {
            Toastify({
            text: "Las contraseñas no coinciden",
            duration: 3000,
            style: { background: "#dc3545" }
            }).showToast();
            return;
        }
        enviarEdicionPassword();
    });

    function enviarEdicionPassword() {
        isSubmitting3 = true;
        const form = document.getElementById('formEditarPerfil3');
        const formData = new FormData(form);
        const submitBtn = $(form).find('button[type="submit"]');
        
        submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm"></span> Procesando...');

        fetch(`${assetsPath}perfilAjax/save_password`, {
            method: 'POST',
            body: formData
        })
            .then(async res => {
            const text = await res.text();
            try {
                return JSON.parse(text);
            } catch (err) {
                console.error("Respuesta inesperada:", text);
                throw new Error("Respuesta no válida (no es JSON)");
            }
            })
            .then(data => {
            if (data.success) {
                Toastify({
                    text: "Contraseña actualizada correctamente ✅",
                    duration: 3000,
                    style: { background: "#28a745" },
                    callback: () => location.reload()
                }).showToast();
            } else {
                Toastify({
                    text: data.message || "Error al actualizar contraseña ⚠️",
                    duration: 3000,
                    style: { background: "#dc3545" }
                }).showToast();
            }
            })
            .catch(err => {
            console.error("Error al guardar contraseña:", err);
            Toastify({
                text: "Error en el servidor ❌",
                duration: 3000,
                style: { background: "#dc3545" }
            }).showToast();
            })
            .finally(() => {
            submitBtn.prop('disabled', false).html('Guardar Cambios');
            isSubmitting3 = false;
            });
    }
    document.querySelectorAll('.toggle-pass').forEach(btn => {
    btn.addEventListener('click', () => {
        const inputId = btn.getAttribute('data-target');
        const input = document.getElementById(inputId);
        const icon = btn.querySelector('i');

        if (!input) return;

        if (input.type === 'password') {
            input.type = 'text';
            icon.classList.remove('bx-hide');
            icon.classList.add('bx-show');
        } else {
            input.type = 'password';
            icon.classList.remove('bx-show');
            icon.classList.add('bx-hide');
        }
    });
});
/* ---- INICIO: DATOS CONTRASEÑA ---- */

});
