document.addEventListener("DOMContentLoaded", function() {
    const formLogin = document.getElementById("frm_login");
    if (formLogin) {
        formLogin.addEventListener("submit", async function(e) {
            e.preventDefault();

            let username = document.getElementById("username").value.trim();
            let password = document.getElementById("password").value.trim();

            if (username === "" || password === "") {
                Swal.fire({
                    icon: "warning",
                    title: "Campos vacíos",
                    text: "Por favor, ingresa tu usuario y contraseña."
                });
                return;
            }

            try {
                const datos = new FormData(formLogin);
                let response = await fetch(base_url + "control/UsuarioController.php?tipo=iniciar_sesion", {
                    method: "POST",
                    body: datos
                });
                let json = await response.json();

                if (json.status) {
                    Swal.fire({
                        icon: "success",
                        title: "Bienvenido",
                        text: json.msg,
                        timer: 1500,
                        showConfirmButton: false
                    }).then(() => {
                        window.location.href = base_url + "inicio"; // <-- cambia esta ruta si tu panel tiene otro nombre
                    });
                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: json.msg
                    });
                }
            } catch (error) {
                console.error("Error al iniciar sesión:", error);
            }
        });
    }
});
