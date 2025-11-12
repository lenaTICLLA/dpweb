//
function validar_form() {
    let formId = document.getElementById("frm_produc") ? "frm_produc" : "frm_edit_produc";
    let form = document.getElementById(formId);
    let codigo = document.getElementById("codigo").value;
    let nombre = document.getElementById("nombre").value;
    let detalle = document.getElementById("detalle").value;
    let precio = document.getElementById("precio").value;
    let stock = document.getElementById("stock").value;
    let fecha_vencimiento = document.getElementById("fecha_vencimiento").value;
    //let imagen = document.getElementById("imagen").value;
    let id_categoria = document.getElementById("id_categoria").value;

    if (codigo == "" || nombre == "" || detalle == "" || precio == "" || stock == "" || fecha_vencimiento == "" || id_categoria == "" ) {
        Swal.fire({
            title: "ERROR",
            text: "¡Ups! Hay campos vacíos.",
            icon: "error"
        });
        return;
    }

    // Validaciones adicionales
    if (parseFloat(precio) < 0) {
        Swal.fire({
            title: "¡Error!",
            text: "El precio no puede ser negativo.",
            icon: "error"
        });
        return;
    }
    
    if (parseInt(stock) < 0) {
        Swal.fire({
            title: "¡Error!",
            text: "El stock no puede ser negativo.",
            icon: "error"
        });
        return;
    }

    if (parseInt(id_categoria) < 1) {
        Swal.fire({
            title: "¡Error!",
            text: "El ID de categoría debe ser un número positivo.",
            icon: "error"
        });
        return;
    }
    
    if (form.dataset.edit === "true") {
        actualizarProducto();
    } else {
        registrarProducto();
    }
}

if (document.querySelector('#frm_produc')) {
    let frm_produc = document.querySelector('#frm_produc');
    frm_produc.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
    };
}

if (document.querySelector('#frm_edit_produc')) {
    let frm_edit_produc = document.querySelector('#frm_edit_produc');
    frm_edit_produc.onsubmit = function (e) {
        e.preventDefault();
        validar_form();
    };
}

async function registrarProducto() {
    try {
        const datos = new FormData(document.getElementById('frm_produc'));
        //enviar datos a controlador
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=registrar', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });
        let json = await respuesta.json();
        if (json.status) {
            Swal.fire({
                title: json.msg,
                icon: "success",
                draggable: true
            });
            document.getElementById('frm_produc').reset();
            // Limpiar la imagen
            const imagenActual = document.getElementById('imagen_actual');
            const imagenPreview = document.getElementById('imagen_preview');
            if (imagenActual) imagenActual.textContent = 'No hay imagen seleccionada';
            if (imagenPreview) {
                imagenPreview.style.display = 'none';
                imagenPreview.src = '';
            }
        } else {
            Swal.fire({
                title: json.msg,
                icon: "error",
                draggable: true
            });
        }
    } catch (e) {
        console.error("Error al registrar producto:", e);
        Swal.fire({
            title: "Error",
            text: "Error al registrar: " + e.message,
            icon: "error"
        });
    }
}

async function actualizarProducto() {
    try {
        const datos = new FormData(document.getElementById('frm_edit_produc'));
        const idProducto = document.getElementById('id_producto').value;

        if (idProducto) {
            datos.append('id_producto', idProducto);
        } else {
            Swal.fire({
                title: "Error",
                text: "No se encontró el ID del producto.",
                icon: "error"
            });
            return;
        }

        for (let pair of datos.entries()) {
            console.log(`${pair[0]}: ${pair[1]}`);
        }

        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=actualizar_producto', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
            body: datos
        });

        let json = await respuesta.json();
        console.log("Respuesta de actualización:", json);
        if (json.status) {
            Swal.fire({
                title: json.msg,
                icon: "success",
                draggable: true
               }).then(() => {
        // Redirigir después de cerrar el alert
        location.href = base_url + 'produc';
        // view_productos(); // Solo si quieres actualizar sin recargar
    });
        } else {
            Swal.fire({
                title: json.msg,
                icon: "error",
                draggable: true
            });
        }
    } catch (e) {
        console.error("Error al actualizar producto:", e);
        Swal.fire({
            title: "Error",
            text: "Error al actualizar: " + e.message,
            icon: "error"
        });
    }
}

async function obtenerProductoPorId(id) {
    try {
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=obtener_producto&id=' + id);
        let producto = await respuesta.json();
        console.log("Producto recibido:", producto); // Verifica en la consola
        if (producto) {
            document.getElementById('id_producto').value = producto.id || '';
            document.getElementById('codigo').value = producto.codigo || '';
            document.getElementById('nombre').value = producto.nombre || '';
            document.getElementById('detalle').value = producto.detalle || '';
            document.getElementById('precio').value = producto.precio || '';
            document.getElementById('stock').value = producto.stock || '';
            document.getElementById('fecha_vencimiento').value = producto.fecha_vencimiento || '';
            document.getElementById('id_categoria').value = producto.id_categoria || ''; // Asegurar asignación
            document.getElementById('id_persona').value = producto.id_proveedor || '';
           // Mostrar la URL de la imagen existente
            const imagenActual = document.getElementById('imagen_actual');
            if (imagenActual && producto.imagen) {
                imagenActual.textContent = producto.imagen;
            } else if (imagenActual) {
                imagenActual.textContent = 'No hay imagen asignada';
            }       
            
            // Mostrar previsualización de la imagen (opcional)
            const imagenPreview = document.getElementById('imagen_preview');
            if (imagenPreview && producto.imagen) {
                imagenPreview.src = base_url + producto.imagen; // Asegúrate de que base_url sea correcto
                imagenPreview.style.display = 'block';
            } else if (imagenPreview) {
                imagenPreview.style.display = 'none';
            }
            document.getElementById("frm_edit_produc").dataset.edit = "true";
        } else {
            console.error("Producto no encontrado:", id);
            Swal.fire({
                title: "Error",
                text: "Producto no encontrado.",
                icon: "error"
            });
        }
    } catch (e) {
        console.error("Error al obtener producto por ID", e);
        Swal.fire({
            title: "Error",
            text: "No se pudo cargar el producto.",
            icon: "error"
        });
    }
}

async function view_productos() {
    try {
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=ver_productos', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
        });

        let json = await respuesta.json();
        console.log("Productos recibidos:", json); // Agrega esto para depurar
        let content_productos = document.getElementById('content_productos');
        if (content_productos) {
            content_productos.innerHTML = '';
            json.forEach((producto, index) => {
                let fila = document.createElement('tr');
                fila.classList.add('text-center');
                fila.innerHTML = `
                    <td>${index + 1}</td>
                    <td>${producto.codigo || ''}</td>
                    <td>${producto.nombre || ''}</td>
                    <td>${producto.detalle || ''}</td>
                    <td>${producto.precio || ''}</td>
                    <td>${producto.stock || ''}</td>
                    <td>${producto.fecha_vencimiento || ''}</td>
                    <td>${producto.categoria || ''}</td>
                    <td>${producto.proveedor || 'Sin proveedor'}</td>
                    <td>
                    <a href="${base_url}edit-producto/${producto.id}" class="btn btn-outline-primary">
                     <i class="bi bi-pencil-square"></i>
                    </a>
                    <a href="javascript:void(0)" onclick="eliminarProducto(${producto.id})" class="btn btn-outline-danger">
                     <i class="bi bi-trash3"></i>
                    </a>
                    </td>
                    `;
                content_productos.appendChild(fila);
            });
        }
    } catch (e) {
        console.error("Error al ver productos:", e);
    }
}

if (document.getElementById('content_productos')) {
    view_productos();
}

if (document.getElementById('btn_guardar')) {
    document.getElementById('btn_guardar').addEventListener('click', function () {
        validar_form();
    });
}

async function eliminarProducto(id) {
    Swal.fire({
        title: "¿Estás seguro?",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Sí, eliminar",
        cancelButtonText: "Cancelar"
    }).then(async (result) => {
        if (result.isConfirmed) {
            try {
                let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=eliminar_producto&id=' + id, {
                    method: 'POST',
                    mode: 'cors',
                    cache: 'no-cache'
                });
                
                let json = await respuesta.json();
                if (json.status) {
                    Swal.fire("Eliminado!", json.msg, "success");
                    view_productos();
                } else {
                    Swal.fire("Error!", json.msg, "error");
                }
            } catch (e) {
                console.error("Error al eliminar producto:", e);
                Swal.fire("Error!", "Ocurrió un error al eliminar el producto.", "error");
            }
        }
    });
}
// cargar categoria 
async function cargarCategorias() {
  let r = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias');
  let j = await r.json();
  let h = '<option value="">Seleccione una categoría</option>';
  j.data.forEach(c => h += `<option value="${c.id}">${c.nombre}</option>`);
  document.getElementById("id_categoria").innerHTML = h;
}

// cargar proveedor
async function cargarProveedores() {
  let r = await fetch(base_url + 'control/UsuarioController.php?tipo=ver_proveedores');
  let j = await r.json();
  let h = '<option value="">Seleccione un proveedor</option>';
  j.data.forEach(p => h += `<option value="${p.id}">${p.razon_social}</option>`);
  document.getElementById("id_persona").innerHTML = h;
}

document.getElementById('imagen').addEventListener('change', function(event) {
    const file = event.target.files[0];
    const imagenActual = document.getElementById('imagen_actual');
    const imagenPreview = document.getElementById('imagen_preview');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(e) {
            imagenPreview.src = e.target.result;
            imagenPreview.style.display = 'block';
        };
        reader.readAsDataURL(file);
        imagenActual.textContent = file.name;
    } else {
        imagenPreview.style.display = 'none';
        imagenActual.textContent = 'No hay imagen seleccionada';
    }
});