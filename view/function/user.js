function validar_form() {
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

if (document.querySelector('#frm_user')) {
    //
    let frm_user = document.querySelector('#frm_user');
    frm_user.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
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
        let json = await respuesta.json();
        // VALIDAMOS QUE JSON.STATUS SEA = TRUE 
        if (json.status) {
            alert(json.msg);
            document.getElementById('frm_user').reset();
        } else {
            alert(json.msg);
        }
    } catch (e) {
        console.log("Error al registrar Usuario:" + e);
    }

}

async function iniciar_sesion() {
    let usuario = document.getElementById("username").value;
    let password = document.getElementById("password").value;
    if (usuario == "" || password == "") {
        Swal.fire({
            title: "Error, campos vacios",
            icon: "error",
            draggable: false
        });
        return;
    }
    /* PARA CAPTURAR ERRORES*/
    try {
        const datos = new FormData(frm_login);
        let respuesta = await fetch(base_url + 'control/usuarioController.php?tipo=iniciar_sesion', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (json.status) {
            location.replace(base_url + 'new-user')
        } else {
            alert(json.msg);
        }
    } catch (error) {
        console.log(error);
    }
}

async function view_users() {
    try {
        const respuesta = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_usuarios', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        const usuarios = await respuesta.json(); // Array de usuarios
        const tbody = document.getElementById('content_users');
        tbody.innerHTML = ''; // Limpia contenido anterior

        const rolesMap = {
            '1': 'Administrador',
            '2': 'Vendedor'
        };

        usuarios.forEach((usuario, index) => {
            const fila = document.createElement('tr');
            fila.classList.add('text-center');

            const celdaNro = document.createElement('td');
            celdaNro.textContent = index + 1;

            const celdaDNI = document.createElement('td');
            celdaDNI.textContent = usuario.nro_identidad;

            const celdaNombre = document.createElement('td');
            celdaNombre.textContent = usuario.razon_social;

            const celdaCorreo = document.createElement('td');
            celdaCorreo.textContent = usuario.correo;

            const celdaRol = document.createElement('td');
            celdaRol.textContent = rolesMap[String(usuario.rol)] || 'Desconocido';

            const celdaEstado = document.createElement('td');
            celdaEstado.textContent = usuario.estado || 'Activo';

            const celdaAcciones = document.createElement('td');
            const btnEditar = document.createElement('a');
            btnEditar.textContent = 'Editar';
            btnEditar.className = 'btn btn-sm btn-primary';
            btnEditar.setAttribute('href', base_url+'edit_user/'+usuario.id);
            btnEditar.setAttribute('data-bs-target', '#modalEditarUsuario');
            btnEditar.addEventListener('click', () => {
                cargarDatosEnModal(usuario); 
            });

            celdaAcciones.appendChild(btnEditar);

            
            fila.appendChild(celdaNro);
            fila.appendChild(celdaDNI);
            fila.appendChild(celdaNombre);
            fila.appendChild(celdaCorreo);
            fila.appendChild(celdaRol);
            fila.appendChild(celdaEstado);
            fila.appendChild(celdaAcciones); 

            tbody.appendChild(fila); 
        });

    } catch (error) {
        console.error("Error al obtener usuarios:", error);
        Swal.fire({  
            icon: "error",
            title: "Error",
            text: "No se pudieron cargar los usuarios."
        });
    }
}

if (document.getElementById('content_users')) {
    view_users();
}
