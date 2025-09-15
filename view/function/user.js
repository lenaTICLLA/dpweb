function validar_form(tipo) {
    // Captura los valores de los ca)mpos del formulario
    let nro_documento = document.getElementById("nro_identidad").value;
    let razon_social = document.getElementById("razon_social").value;
    let telefono = document.getElementById("telefono").value;
    let correo = document.getElementById("correo").value;
    let departamento = document.getElementById("departamento").value;
    let provincia = document.getElementById("provincia").value;
    let distrito = document.getElementById("distrito").value;
    let cod_postal = document.getElementById("cod_postal").value;
    let direccion = document.getElementById("direccion").value;
    let rol = document.getElementById("rol").value;
    // Verifica si alguno de los campos está vacío
    if (nro_documento == "" || razon_social == "" || telefono == "" || correo == "" || departamento == "" || provincia == "" || distrito == "" || cod_postal == "" || direccion == "" || rol == "") {

        Swal.fire({
            title: "ERROR?",
            text: "¡Ups! Hay campos vacíos.",
            icon: "question"
        });

        return;
    }

    registrarUsuario();
}
// Se verifica si existe en el documento un elemento con el id frm_user.
if (document.querySelector('#frm_user')) {
    // Se guarda una referencia al formulario con id frm_user en una variable llamada frm_user.
    let frm_user = document.querySelector('#frm_user');
    frm_user.onsubmit = function (e) {   //Se asigna una función al evento onsubmit del formulario.
        e.preventDefault();   //Este método evita que el formulario se envíe de forma tradicional (recargando la página).
        validar_form(); //Llama a la función
    }
}

async function registrarUsuario() {
    try {
        //capturar campos de formulario (HTML)
        const datos = new FormData(frm_user);
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        // Convierte la respuesta a formato JSON
        let json = await respuesta.json();
        //validamos que json.status sea = true
        if (json.status) {
            Swal.fire({
                title: json.msg,
                icon: "success",
                draggable: true
            });
            document.getElementById('frm_user').reset(); // Limpia el formulario
        } else {
            Swal.fire({
                title: json.msg,
                icon: "error",
                draggable: false
            });
        }
    } catch (e) {
        console.log("Error al registrar Usuario:" + e);
    }

}
//

async function actualizarUsuario() {
    try {
        const datos = new FormData(frm_user);
        console.log([...datos]);
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=actualizar_usuario', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            alert(json.msg);
            location.href = base_url + 'users'; // Redirige si deseas
        } else {
            alert(json.msg);
        }
    } catch (e) {
        console.log("Error al actualizar usuario:", e);
    }
}



async function iniciar_sesion() {
    // Captura los valores del input usuario y contraseña
    let usuario = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    if (usuario == "" || password == "") {
        //alert("Error, campos vacios!")
        Swal.fire({
            icon: "error",
            title: "Error, campos vacios!"

        });
        return;
    }
    try {
        // Captura los datos del formulario de login
        const datos = new FormData(frm_login);
        // Envía los datos al backend para validar
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=iniciar_sesion', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        // -----------------------------------
        let json = await respuesta.json();
        //validamos que json.status sea = true
        if (json.status) { //true
            location.replace(base_url + 'new-user');
        } else {
            alert(json.msg);
        }
    } catch (error) {
        console.log(error);
    }
}

//
async function obtenerUsuarioPorId(id) {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=obtener_usuario&id=' + id);
        let usuario = await respuesta.json();
        document.getElementById('id_persona').value = usuario.id || '';
        document.getElementById('nro_identidad').value = usuario.nro_identidad || '';
        document.getElementById('razon_social').value = usuario.razon_social || '';
        document.getElementById('telefono').value = usuario.telefono || '';
        document.getElementById('correo').value = usuario.correo || '';
        document.getElementById('departamento').value = usuario.departamento || '';
        document.getElementById('provincia').value = usuario.provincia || '';
        document.getElementById('distrito').value = usuario.distrito || '';
        document.getElementById('cod_postal').value = usuario.cod_postal || '';
        document.getElementById('direccion').value = usuario.direccion || '';
        document.getElementById('rol').value = usuario.rol || '';
    } catch (e) {
        console.error("Error al obtener usuario por ID", e); //  AHORA ESTÁ BIEN
    }
}


async function view_users() {
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_usuarios', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
        });

        let json = await respuesta.json();
        let content_users = document.getElementById('content_users');
        content_users.innerHTML = '';

        // Mapeo de roles numéricos a texto
        const rolesMap = {
            '1': 'Administrador',
            '2': 'Vendedor',

        };
        
        json.forEach((user, index) => {
            let fila = document.createElement('tr');
            fila.classList.add('text-center');
            fila.innerHTML = `
                <td>${index + 1}</td>
                <td>${user.nro_identidad || ''}</td>
                <td>${user.razon_social || ''}</td>
                <td>${user.correo || ''}</td>
                <td>${rolesMap[user.rol] || 'Desconocido'}</td>
                <td>${user.estado || 'Activo'}</td>
                <td>
                    <a href="` + base_url + `edituser/` + user.id + `" class="btn btn-outline-primary"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-pencil-square" viewBox="0 0 16 16">
  <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"/>
  <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5z"/>
</svg></a>
                    <a href="javascript:void(0)" onclick="eliminarUsuario(${user.id})" class="btn btn-outline-danger"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-trash3" viewBox="0 0 16 16">
  <path d="M6.5 1h3a.5.5 0 0 1 .5.5v1H6v-1a.5.5 0 0 1 .5-.5M11 2.5v-1A1.5 1.5 0 0 0 9.5 0h-3A1.5 1.5 0 0 0 5 1.5v1H1.5a.5.5 0 0 0 0 1h.538l.853 10.66A2 2 0 0 0 4.885 16h6.23a2 2 0 0 0 1.994-1.84l.853-10.66h.538a.5.5 0 0 0 0-1zm1.958 1-.846 10.58a1 1 0 0 1-.997.92h-6.23a1 1 0 0 1-.997-.92L3.042 3.5zm-7.487 1a.5.5 0 0 1 .528.47l.5 8.5a.5.5 0 0 1-.998.06L5 5.03a.5.5 0 0 1 .47-.53Zm5.058 0a.5.5 0 0 1 .47.53l-.5 8.5a.5.5 0 1 1-.998-.06l.5-8.5a.5.5 0 0 1 .528-.47M8 4.5a.5.5 0 0 1 .5.5v8.5a.5.5 0 0 1-1 0V5a.5.5 0 0 1 .5-.5"/>
</svg></a>
                </td>

                
               
            `;
            content_users.appendChild(fila);
        });

    } catch (e) {
        console.log("Error al ver Usuario: " + e);
    }
}

if (document.getElementById('content_users')) {
    view_users();
}

//
if (document.getElementById('guardar_cambios')) {
    document.getElementById('guardar_cambios').addEventListener('click', function () {
        actualizarUsuario(); // Llama a la función que hará el update
    });
}

if (document.querySelector('frm_user')) {
    
    let frm_user = document.querySelector('frm_user');
    frm_user.onsubmit = function (e){
             e.preventDefault();
    validar_form_edit();
    }
}

async function eliminarUsuario(id) {
            const result = await Swal.fire({
            title: '¿Estás segura de eliminar al usuario ?',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#6c757d',
            confirmButtonText: 'Sí, eliminar',
            cancelButtonText: 'Cancelar'
        }).then(async (result) => {
            if(result.isConfirmed){
    try {
        let respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=eliminar_usuario&id=' + id, {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        let json = await respuesta.json();
        if (json.status) {
            Swal.fire({
                icon: 'success',
                title: 'Eliminado',
                text: json.msg,
                timer: 2000,
                showConfirmButton: false
            });
            view_users();
        } else {
            Swal.fire({
                icon: 'Erro al actualizar',
                title: 'Error al actualizar',
                text: json.msg
            });
        }  

    } catch (e) {
        console.error("Error al eliminar usuario:", e);
        Swal.fire({
            icon: 'error',
            title: 'Error inesperado',
            text: 'No se pudo eliminar el usuario.'
        });
    }
    }
    });
}
