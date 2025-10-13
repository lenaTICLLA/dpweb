function validar_form(tipo) {
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;

    if (nombre == "" || detalle == "") {
        Swal.fire({
            icon: "error",
            title: "Oops...",
            background: "#fff url(view/img/cat.gif) center top 20% no-repeat",
            text: "Error: Campos Vacios!",
            footer: '<a> Es necesario rellenar todos los campos </a>'
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

if (document.querySelector('#frm_category')) {
    //Evita que se envíe el formulario
    let frm_category = document.querySelector('#frm_category');
    frm_category.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}

async function registrarCategoria() {
    try {
        // capturar campos de formulario(HTML)
        const datos = new FormData(frm_category);
        //enviar datos al controlador
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (json.status) {
            alert(json.msg);
            document.getElementById('frm_category').reset();
        } else {
            alert(json.msg);
        }

    } catch (error) {
        console.log("Error al registrar usuario:" + error);
    }
}



/* para ver categorias registrados */
async function view_category() {
    try {
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache'
        });

        let json = await respuesta.json();
        let content_categorys = document.getElementById('content_category');
        content_categorys.innerHTML = ''; // limpiamos antes de insertar

        json.forEach((category, index) => {
            let fila = document.createElement('tr');
            fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${category.nombre}</td>
                    <td>${category.detalle}</td>
                    <td>
                        <a href="`+ base_url + `edit-category/` + category.id + `" class="btn btn-success">Editar</a>
                        <br>
                        <button data-id="${category.id}" class="btn btn-eliminar btn-danger">Eliminar</button>
                    </td>
                    
                `;

            content_categorys.appendChild(fila);
        });


        // Agrega el evento click a los botones de eliminar
        document.querySelectorAll('.btn-eliminar').forEach(btn => {
            btn.addEventListener('click', async function () {
                if (confirm('¿Está seguro de eliminar este registro?')) {
                    const datos = new FormData();
                    datos.append('id', this.getAttribute('data-id'));
                    let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=eliminar', {
                        method: 'POST',
                        mode: 'cors',
                        cache: 'no-cache',
                        body: datos
                    });
                    let json = await respuesta.json();
                    alert(json.msg);
                    if (json.status) {
                        view_category(); // Recarga la lista
                    }
                }
            });
        });
    }catch (error) {
        console.log('Error al obtener categoria, No hay nada: ' + error);
    }
}
if (document.getElementById('content_category')) {
    view_category();
}

// para editar categoria

async function edit_category() {
    try {
        let id_categoria = document.getElementById('id_categoria').value;
        const datos = new FormData();
        datos.append('id_categoria', id_categoria);

        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        json = await respuesta.json();
        if (!json.status) {
            alert(json.msg);
            return;
        }
        document.getElementById('nombre').value = json.data.nombre;
        document.getElementById('detalle').value = json.data.detalle;

    } catch (error) {
        console.log('oops, ocurrio un error ' + error);
    }
}

if (document.querySelector('#frm_edit_category')) {
    //evita que se envie el formulario
    let frm_category = document.querySelector('#frm_edit_category');
    frm_category.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}
//actualizar categoria
async function actualizarCategoria() {
    const datos = new FormData(frm_edit_category);
    let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=actualizar', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        body: datos
    });
    json = await respuesta.json();
    if (!json.status) {
        alert("Oops, ocurrio un error al actualizar, intente nuevamente");
        console.log(json.msg);
        return;
    } else {
        alert(json.msg);
    }
}


//para eliminar usuario
async function fn_eliminar(id) {
    if (window.confirm("confirmar eliminar")) {
        eliminar(id);
    }
}

async function eliminar(id) {
    let datos = new FormData();
    datos.append('id_category', id);
    let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=eliminar', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache',
        body: datos
    });
    json = await respuesta.json();
    if (!json.status) {
        alert("");
        console.log(json.msg);
        return;
    } else {
        alert(json.msg)
        location.replace(base_url + 'category');
    }
}
