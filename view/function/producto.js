
function validar_form(tipo) {
    let codigo = document.getElementById("codigo").value;
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;
    let precio = document.getElementById("precio").value;
    let stock = document.getElementById("stock").value;
    let id_categoria = document.getElementById("id_categoria").value;
    let fecha_vencimiento = document.getElementById("fecha_vencimiento").value;
    let id_proveedor = document.getElementById("id_proveedor").value;


    if (codigo == "" || nombre == "" || detalle == "" || precio == "" || stock == "" || id_categoria == "" || fecha_vencimiento == "" || id_proveedor == "") {

        Swal.fire({
            icon: 'warning',
            title: 'Campos vacíos',
            text: 'Por favor, complete todos los campos requeridos',
            confirmButtonText: 'Entendido'
        });
        return;
    }
    if (tipo == "nuevo") {
        registrarProducto();
    }
    if (tipo == "actualizar") {
        actualizarProducto();
    }
}

if (document.querySelector('#frm_product')) {
    //evita que se envie el formulario
    let frm_product = document.querySelector('#frm_product');
    frm_product.onsubmit = function (e) {
        e.preventDefault();
        validar_form("nuevo");
    }
}

async function registrarProducto() {
    try {
        const frm_product = document.querySelector("#frm_product");
        const datos = new FormData(frm_product);
        let respuesta = await fetch(base_url + 'control/productosController.php?tipo=registrar', {
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
            document.getElementById('frm_product').reset();
        } else {
            Swal.fire({
                icon: "error",
                title: "Error",
                text: json.msg
            });
        }
    } catch (error) {
        console.log("Error al registrar producto: " + error);
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
            window.location.href = base_url + "?view=new-producto";
        }
    });
}

async function view_producto() {
    try {
    let respuesta = await fetch(base_url + 'control/productosController.php?tipo=mostrar_productos', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache'
    });
    if (!respuesta.ok) {
            throw new Error(`HTTP error! status: ${respuesta.status}`);
        }
        
        let json = await respuesta.json();
        
        if (json.status && json.data && json.data.length > 0) {
        let html = '';
        json.data.forEach((producto, index) => {
        html += `<tr>
            <td>${index + 1}</td>
            <td>${producto.codigo || ''}</td>
            <td>${producto.nombre || ''}</td>
            <td>${producto.detalle || ''}</td>
            <td>${producto.precio || ''}</td>
            <td>${producto.stock || ''}</td>
            <td>${producto.fecha_vencimiento || ''}</td>
            <td>
                ${producto.imagen 
                    ? `<img src="${base_url}uploads/${producto.imagen}" alt="Imagen" width="50">`
                    : ''}
            </td>
            <td>${producto.categoria || ''}</td>
            <td>${producto.proveedor || ''}</td>
            <td>
                <a href="${base_url}productos-edit/${producto.id}" class="btn btn-primary">Editar</a>
                <button onclick="eliminar(${producto.id})" class="btn btn-danger">Eliminar</button>
            </td>
        </tr>`;
});
        document.getElementById('content_productos').innerHTML = html;
    } else {
        document.getElementById('content_productos').innerHTML = '<tr><td colspan="9">No hay productos disponibles</td></tr>';
    }
    }catch (error) {
        console.error("Error al cargar productos:", error);
        document.getElementById('content_productos').innerHTML = '<tr><td colspan="9">Error al cargar los productos</td></tr>';
    }
}

if (document.getElementById('content_productos')) {
    view_producto();
}

async function edit_producto() {
    try {
        let id_producto = document.getElementById('id_producto').value;
        const datos = new FormData();
        datos.append('id_producto', id_producto);

        let respuesta = await fetch(base_url + 'control/productosController.php?tipo=ver', {
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
        document.getElementById('codigo').value = json.data.codigo;
        document.getElementById('nombre').value = json.data.nombre;
        document.getElementById('detalle').value = json.data.detalle;
        document.getElementById('precio').value = json.data.precio;
        document.getElementById('stock').value = json.data.stock;
        document.getElementById('id_categoria').value = json.data.id_categoria;
        document.getElementById('fecha_vencimiento').value = json.data.fecha_vencimiento;
        document.getElementById('id_proveedor').value = json.data.id_proveedor;

    } catch (error) {
        console.log('oops, ocurrio un error' + error);
    }
}

if (document.querySelector("#frm_edit_producto")) {
    let frm_edit_producto = document.querySelector("#frm_edit_producto");
    frm_edit_producto.onsubmit = function (e) {
        e.preventDefault();
        validar_form("actualizar");
    }
}

async function actualizarProducto() {
    const frm_edit_producto = document.querySelector("#frm_edit_producto")
    const datos = new FormData(frm_edit_producto);
    let respuesta = await fetch(base_url + 'control/productosController.php?tipo=actualizar', {
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
                datos.append('id_producto', id)
                let respuesta = await fetch(base_url + 'control/productosController.php?tipo=eliminar', {
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
                    }).then(() => {
                        view_producto();
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

async function cargar_categorias() {
    let respuesta = await fetch(base_url + 'control/categoriaController.php?tipo=mostrar_categorias', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache'
    });
    json = await respuesta.json();
    let contenido = '';
    if (json.status && json.data) {
        contenido += '<option value="">Seleccione una categoria</option>';
        json.data.forEach(categoria => {
            contenido += '<option value="' + categoria.id + '">' + categoria.nombre + '</option>';
        });
    } else {
        contenido = '<option value = ""> No hay categorias disponibles</option>';
    }
    //console.log(contenido);
    document.getElementById("id_categoria").innerHTML = contenido;

}

async function cargar_proveedores() {
    let respuesta = await fetch(base_url + 'control/usuarioController.php?tipo=mostrar_proveedores', {
        method: 'POST',
        mode: 'cors',
        cache: 'no-cache'
    });
    json = await respuesta.json();
    let contenido = '';
    if (json.status && json.data) {
        contenido += '<option value="">Seleccione un proveedor</option>';
        json.data.forEach(proveedor => {
            contenido += '<option value="' + proveedor.id + '">' + proveedor.razon_social + '</option>';
        });
    } else {
        contenido = '<option value = ""> No hay proveedores disponibles</option>';
    }
    //console.log(contenido);
    document.getElementById("id_proveedor").innerHTML = contenido;

}