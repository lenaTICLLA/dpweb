
function validar_form(tipo) {
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;

    if (nombre=="" || detalle=="") {
       
         Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, complete todos los campos requeridos',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    if (tipo == "nuevo") {
        registrarCategoria();
    }
    if (tipo == "actualizar") {
        actualizarCategoria();
    }
}

if(document.querySelector('#frm_categorie')){
    //evita que se envie el formulario
    let frm_categorie = document.querySelector('#frm_categorie');
    frm_categorie.onsubmit = function(e){
        e.preventDefault();
        validar_form("nuevo");
    }
}

async function registrarCategoria() {
    try {
        const frm_categorie = document.querySelector("#frm_categorie");
        const datos = new FormData(frm_categorie);
        let respuesta = await fetch(base_url + 'control/categoriaController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (json.status) {
            Swal.fire({
                icon: "success",
                title: "Éxito",
                text: json.msg
            });
            document.getElementById('frm_categorie').reset();
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: json.msg
            });
        }
    } catch (error) {
        console.log("Error al registrar categoría: " + error);
    }
}

function cancelar() {
    Swal.fire({
        icon: "warning",
        title: "¿Estás seguro?",
        text: "Se cancelará el registro",
        showCancelButton: true,
        confirmButtonText: "Sí, cancelar",
        cancelButtonText: "No"
    }).then((result) => {
        if (result.isConfirmed) {
            window.location.href = base_url + "?view=new-categoria";
        }
    });
}

async function view_categories() {
    let respuesta = await fetch(base_url + 'control/categoriaController.php?tipo=mostrar_categorias', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache'
    });
    let json = await respuesta.json();
    let contenido = '';
    if (json.status && json.data && json.data.length > 0) {
        json.data.forEach((cat, idx) => {
            contenido += `<tr>
                <td>${idx + 1}</td>
                <td>${cat.nombre}</td>
                <td>${cat.detalle}</td>
                <td>
                    <button onclick="editar(${cat.id})">Editar</button>
                    <button onclick="eliminar(${cat.id})">Eliminar</button>
                </td>
            </tr>`;
        });
    } else {
        contenido = `<tr><td colspan="4">No hay categorías disponibles</td></tr>`;
    }
    document.getElementById("content_categorias").innerHTML = contenido;
}

if (document.getElementById('content_categorias')) {
    view_categories();
}

async function edit_categoria() {
    try {
        let id_categoria = document.getElementById('id_categoria').value;
        const datos = new FormData();
        datos.append('id_categoria', id_categoria);

        let respuesta = await fetch(base_url + 'control/categoriaController.php?tipo=ver', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        json = await respuesta.json();
        if (!json.status) {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: json.msg
            });
            return;
        }
        document.getElementById('nombre').value = json.data.nombre;
        document.getElementById('detalle').value = json.data.detalle;

        
    } catch (error) {
        console.log('oops, ocurrio un error' + error);  
    } 
}

if (document.querySelector("#frm_edit_categorie")) {
    let frm_edit_categorie = document.querySelector("#frm_edit_categorie");
    frm_edit_categorie.onsubmit = function (e){
        e.preventDefault();
        validar_form("actualizar");
    }
}

async function actualizarCategoria() {
    const frm_edit_categorie = document.querySelector("#frm_edit_categorie")
    const datos = new FormData(frm_edit_categorie);
    let respuesta = await fetch(base_url + 'control/categoriaController.php?tipo=actualizar', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        body: datos
    });
    json = await respuesta.json();
    if (!json.status) {
        Swal.fire({
            icon: "error",
            title: "Error",
            text: "Ops, ocurrio un error al actualizar, contacte con el administrador",
        });
        console.log(json.msg);
        return;
    } else {
        Swal.fire({
            icon: 'success',
            title: 'Éxito',
            text: json.msg
        });
    }
}

async function eliminar(id) {
    Swal.fire({
        icon: "warning",
        title: "¿Estás seguro?",
        text: "Esta acción no se puede revertir",
        showCancelButton: true,
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "No, cancelar",
        confirmButtonColor: "#d33",
        cancelButtonColor: "#3085d6"
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                const datos = new FormData();
                datos.append('id_categoria', id)
                let respuesta = await fetch(base_url + 'control/categoriaController.php?tipo=eliminar', {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache',
                    body: datos
                });
                json = await respuesta.json();
                if (json.status) {
                    Swal.fire({
                        icon: "success",
                        title: "Eliminado",
                        text: json.msg
                    }).then (() =>{ 
                        view_categoria();
                    });

                } else {
                    Swal.fire({
                        icon: "error",
                        title: "Error",
                        text: json.msg
                    });
                }

            } catch (error) {
                console.log('oops, ocurrio un error' + error);
            }
        }
    });
}