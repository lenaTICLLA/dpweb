// Validar formulario de inicio de sesión
function validar_login() {
    let username = document.getElementById("username").value.trim();
    let password = document.getElementById("password").value.trim();

    if (username === "" || password === "") {
        Swal.fire({
            icon: "error",
            title: "Campos vacíos",
            text: "Por favor, completa todos los campos antes de continuar.",
            confirmButtonColor: "#d33",
            background: "#fff url('view/img/cat.gif') center top 20% no-repeat",
            footer: "<a>Verifica tus datos e inténtalo de nuevo</a>"
        });
        return;
    }

    iniciar_sesion();
}

// Asociar el evento submit del formulario
if (document.querySelector("#frm_login")) {
    const frm_login = document.querySelector("#frm_login");
    frm_login.onsubmit = function (e) {
        e.preventDefault();
        validar_login();
    };
}

// Función asíncrona para iniciar sesión
async function iniciar_sesion() {
    try {
        const frm_login = document.querySelector("#frm_login");
        const datos = new FormData(frm_login);

        // Enviar al controlador de usuarios que implementa `iniciar_sesion`
        const respuesta = await fetch(base_url + "control/UsuarioController.php?tipo=iniciar_sesion", {
            method: "POST",
            mode: "cors",
            cache: "no-cache",
            body: datos
        });

        const json = await respuesta.json();

        if (json.status) {
            Swal.fire({
                icon: "success",
                title: "Bienvenido",
                text: json.msg,
                confirmButtonColor: "#3085d6",
                timer: 1500,
                showConfirmButton: false
            });

            // Redirigir al home
            setTimeout(() => {
                window.location.href = base_url + "users";
            }, 1500);
        } else {
            Swal.fire({
                icon: "error",
                title: "Error de autenticación",
                text: json.msg,
                confirmButtonColor: "#d33",
                background: "#fff url('view/img/cat.gif') center top 20% no-repeat",
            });
        }

    } catch (error) {
        console.error("Error al iniciar sesión:", error);
        Swal.fire({
            icon: "error",
            title: "Error de conexión",
            text: "No se pudo conectar con el servidor.",
            confirmButtonColor: "#d33"
        });
    }
}
