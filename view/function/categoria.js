function validar_categoria() {
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;

    if (nombre === "" || detalle === "") {
        Swal.fire({
            title: "Error",
            text: "Por favor, complete todos los campos.",
            icon: "warning"
        });
        return;
    }
    registrarCategoria();
}

if (document.querySelector('#frm_categoria')) {
    let frm_categoria = document.querySelector('#frm_categoria');
    frm_categoria.onsubmit = function (e) {
        e.preventDefault();
        validar_categoria();
    }
}

async function registrarCategoria() {
    try {
        const datos = new FormData(frm_categoria);
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();

        if (json.status) {
            Swal.fire("Registrado", json.msg, "success");
            document.getElementById('frm_categoria').reset();
        } else {
            Swal.fire("Error", json.msg, "error");
        }
    } catch (e) {
        console.log("Error al registrar categoría: " + e);
    }
}