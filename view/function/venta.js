/**
 * SISTEMA DE VENTAS - JavaScript
 * Compatible con dpweb-main
 */

document.addEventListener('DOMContentLoaded', function() {
    listar_productos_venta();
    listar_temporales();
    cargar_categorias_filtro();
    setupEventListeners();
});

function setupEventListeners() {
    // Buscador de productos
    document.getElementById('busqueda_venta')?.addEventListener('keyup', function() {
        listar_productos_venta();
    });

    // Filtro categoría
    document.getElementById('filtro_categoria')?.addEventListener('change', function() {
        listar_productos_venta();
    });

    // Vaciar carrito
    document.getElementById('btn-vaciar-carrito')?.addEventListener('click', vaciar_carrito);

    // Procesar pago
    document.getElementById('btn-procesar-pago')?.addEventListener('click', abrir_modal_pago);

    // Buscar cliente
    document.getElementById('btn-buscar-cliente')?.addEventListener('click', buscar_cliente_venta);

    // Limpiar cliente
    document.getElementById('btn-limpiar-cliente')?.addEventListener('click', limpiar_cliente);

    // Registrar cliente
    document.getElementById('btn-registrar-cliente')?.addEventListener('click', registrar_cliente);

    // Confirmar venta
    document.getElementById('btn-confirmar-venta')?.addEventListener('click', registrar_venta);
}

// ==================== PRODUCTOS ====================

async function listar_productos_venta() {
    try {
        let dato = document.getElementById('busqueda_venta')?.value || '';
        let categoria = document.getElementById('filtro_categoria')?.value || '';

        const datos = new FormData();
        datos.append('dato', dato);

        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=buscar_Producto_venta', {
            method: 'POST',
            body: datos
        });

        let json = await respuesta.json();
        let contenedor = document.getElementById('productos-container');
        let loading = document.getElementById('loadingProductos');
        let noResultados = document.getElementById('noResultados');

        if (loading) loading.style.display = 'none';
        if (!contenedor) return;

        contenedor.innerHTML = '';

        if (json.status && json.data && json.data.length > 0) {
            noResultados.style.display = 'none';

            // Filtrar por categoría
            let productos = json.data;
            if (categoria) {
                productos = productos.filter(p => p.id_categoria == categoria);
            }

            if (productos.length === 0) {
                noResultados.style.display = 'block';
                return;
            }

            productos.forEach(producto => {
                // Corregir ruta de imagen (Uploads con U mayúscula)
                let imgSrc = producto.imagen 
                    ? base_url + producto.imagen.replace('uploads/', 'Uploads/') 
                    : 'https://via.placeholder.com/300x200?text=Sin+Imagen';
                let stockBadge = producto.stock > 0 
                    ? `<span class="badge bg-success">Stock: ${producto.stock}</span>`
                    : `<span class="badge bg-danger">Agotado</span>`;
                let btnDisabled = producto.stock <= 0 ? 'disabled' : '';

                let card = document.createElement('div');
                card.className = 'col-xl-3 col-lg-4 col-md-6 col-sm-6';
                card.innerHTML = `
                    <div class="producto-card h-100">
                        <div class="position-relative">
                            <img src="${imgSrc}" class="producto-imagen" alt="${producto.nombre}"
                                 onerror="this.src='https://via.placeholder.com/300x200?text=Sin+Imagen'">
                            <div class="position-absolute top-0 end-0 m-2">${stockBadge}</div>
                        </div>
                        <div class="producto-info">
                            <div class="producto-codigo text-white-50 small">${producto.codigo || ''}</div>
                            <div class="producto-nombre">${producto.nombre}</div>
                            <div class="producto-categoria">
                                <i class="bi bi-tag me-1"></i>${producto.categoria || 'Sin categoría'}
                            </div>
                            <div class="producto-precio">S/ ${parseFloat(producto.precio).toFixed(2)}</div>
                            <button class="btn btn-pink w-100 mt-2" ${btnDisabled}
                                    onclick="agregar_producto_temporal(${producto.id}, ${producto.precio}, 1)">
                                <i class="bi bi-cart-plus me-1"></i>Agregar
                            </button>
                        </div>
                    </div>
                `;
                contenedor.appendChild(card);
            });
        } else {
            noResultados.style.display = 'block';
        }
    } catch (error) {
        console.error('Error al listar productos:', error);
        document.getElementById('loadingProductos').style.display = 'none';
    }
}

async function cargar_categorias_filtro() {
    try {
        let respuesta = await fetch(base_url + 'control/CategoriaController.php?tipo=ver_categorias', {
            method: 'POST'
        });
        let json = await respuesta.json();

        if (json.status && json.data) {
            let select = document.getElementById('filtro_categoria');
            json.data.forEach(cat => {
                let option = document.createElement('option');
                option.value = cat.id;
                option.textContent = cat.nombre;
                select.appendChild(option);
            });
        }
    } catch (error) {
        console.error('Error categorías:', error);
    }
}

// ==================== CARRITO (usando tipos de dpweb-main) ====================

async function agregar_producto_temporal(id_product, price, cant = 1) {
    const datos = new FormData();
    datos.append('id_producto', id_product);
    datos.append('precio', price);
    datos.append('cantidad', cant);

    try {
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=registrarTemporal', {
            method: 'POST',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: json.msg === 'registrado' ? 'Producto agregado' : 'Cantidad actualizada',
                showConfirmButton: false,
                timer: 1500
            });
            listar_temporales();
        }
    } catch (error) {
        console.error('Error agregar:', error);
    }
}

async function listar_temporales() {
    try {
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=listar_venta_temporal', {
            method: 'POST'
        });

        let json = await respuesta.json();
        let tablaCarrito = document.getElementById('tabla-carrito');
        let carritoVacio = document.getElementById('carrito-vacio');
        let btnPago = document.getElementById('btn-procesar-pago');
        let contador = document.getElementById('contador-carrito');

        if (!tablaCarrito) return;

        tablaCarrito.innerHTML = '';

        if (json.status && json.data && json.data.length > 0) {
            carritoVacio.style.display = 'none';
            btnPago.disabled = false;
            contador.textContent = json.data.length;

            json.data.forEach(item => {
                let subtotal = (item.precio * item.cantidad).toFixed(2);
                tablaCarrito.innerHTML += `
                    <div class="carrito-item d-flex align-items-center gap-2 p-2 mb-2 rounded"
                         style="background: rgba(255,255,255,0.05);">
                        <div class="flex-grow-1">
                            <div class="text-white small fw-semibold">${item.nombre}</div>
                            <div class="text-pink small">S/ ${parseFloat(item.precio).toFixed(2)}</div>
                        </div>
                        <div class="d-flex align-items-center gap-1">
                            <button class="btn btn-sm btn-outline-light px-2" onclick="actualizar_cantidad_menos(${item.id}, ${item.precio})">
                                <i class="bi bi-dash"></i>
                            </button>
                            <input type="number" class="form-control form-control-sm text-center" 
                                   style="width: 50px; background: rgba(255,255,255,0.1); color: white; border: none;"
                                   id="cant_${item.id}" value="${item.cantidad}" min="1"
                                   onchange="actualizar_subtotal(${item.id}, ${item.precio})">
                            <button class="btn btn-sm btn-outline-light px-2" onclick="actualizar_cantidad_mas(${item.id}, ${item.precio})">
                                <i class="bi bi-plus"></i>
                            </button>
                        </div>
                        <div class="text-end" style="min-width: 70px;">
                            <div class="text-white small fw-semibold">S/ ${subtotal}</div>
                        </div>
                        <button class="btn btn-sm btn-outline-danger" onclick="eliminar_temporal(${item.id})">
                            <i class="bi bi-trash"></i>
                        </button>
                    </div>
                `;
            });

            act_subt_general();
        } else {
            carritoVacio.style.display = 'block';
            btnPago.disabled = true;
            contador.textContent = '0';
            actualizar_totales_vista({ subtotal: '0.00', igv: '0.00', total: '0.00' });
        }
    } catch (error) {
        console.error('Error listar temporales:', error);
    }
}

async function actualizar_cantidad_mas(id, precio) {
    let input = document.getElementById('cant_' + id);
    input.value = parseInt(input.value) + 1;
    actualizar_subtotal(id, precio);
}

async function actualizar_cantidad_menos(id, precio) {
    let input = document.getElementById('cant_' + id);
    let cantidad = parseInt(input.value) - 1;
    if (cantidad < 1) {
        eliminar_temporal(id);
        return;
    }
    input.value = cantidad;
    actualizar_subtotal(id, precio);
}

async function actualizar_subtotal(id, precio) {
    let cantidad = document.getElementById('cant_' + id).value;

    if (cantidad < 1) {
        eliminar_temporal(id);
        return;
    }

    try {
        const datos = new FormData();
        datos.append('id', id);
        datos.append('cantidad', cantidad);

        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=actualizar_cantidad', {
            method: 'POST',
            body: datos
        });

        let json = await respuesta.json();
        if (json.status) {
            listar_temporales();
        }
    } catch (error) {
        console.error('Error actualizar:', error);
    }
}

async function eliminar_temporal(id) {
    try {
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=eliminar_temporal&id=' + id);
        let json = await respuesta.json();

        if (json.status) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'info',
                title: 'Producto eliminado',
                showConfirmButton: false,
                timer: 1500
            });
            listar_temporales();
        }
    } catch (error) {
        console.error('Error eliminar:', error);
    }
}

async function act_subt_general() {
    try {
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=listar_venta_temporal', {
            method: 'POST'
        });

        let json = await respuesta.json();
        if (json.status && json.data) {
            let subtotal = 0;
            json.data.forEach(item => {
                subtotal += parseFloat(item.precio * item.cantidad);
            });

            let igv = (subtotal * 0.18).toFixed(2);
            let total = (parseFloat(subtotal) + parseFloat(igv)).toFixed(2);

            actualizar_totales_vista({
                subtotal: subtotal.toFixed(2),
                igv: igv,
                total: total
            });
        }
    } catch (error) {
        console.error('Error totales:', error);
    }
}

function actualizar_totales_vista(totales) {
    document.getElementById('subtotal-carrito').innerHTML = 'S/ ' + totales.subtotal;
    document.getElementById('igv-carrito').innerHTML = 'S/ ' + totales.igv;
    document.getElementById('total-carrito').innerHTML = 'S/ ' + totales.total;

    // Modal
    if (document.getElementById('modal-subtotal')) {
        document.getElementById('modal-subtotal').innerHTML = 'S/ ' + totales.subtotal;
        document.getElementById('modal-igv').innerHTML = 'S/ ' + totales.igv;
        document.getElementById('modal-total').innerHTML = 'S/ ' + totales.total;
    }
}

async function vaciar_carrito() {
    let result = await Swal.fire({
        title: '¿Vaciar carrito?',
        text: 'Se eliminarán todos los productos',
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#d33',
        cancelButtonColor: '#6c757d',
        confirmButtonText: 'Sí, vaciar',
        cancelButtonText: 'Cancelar'
    });

    if (result.isConfirmed) {
        try {
            let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=vaciar_carrito');
            let json = await respuesta.json();
            if (json.status) {
                listar_temporales();
            }
        } catch (error) {
            console.error('Error vaciar:', error);
        }
    }
}

// ==================== MODAL PAGO ====================

function abrir_modal_pago() {
    act_subt_general();
    limpiar_cliente();
    document.getElementById('input-documento').value = '';
    let modal = new bootstrap.Modal(document.getElementById('modalPago'));
    modal.show();
}

async function buscar_cliente_venta() {
    let dni = document.getElementById('input-documento').value.trim();

    if (dni.length < 8) {
        Swal.fire('Error', 'Ingrese un DNI (8) o RUC (11) válido', 'warning');
        return;
    }

    document.getElementById('loading-cliente').style.display = 'block';

    try {
        const datos = new FormData();
        datos.append('dni', dni);

        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=buscar_por_dni', {
            method: 'POST',
            body: datos
        });

        let json = await respuesta.json();
        document.getElementById('loading-cliente').style.display = 'none';

        if (json.status) {
            mostrar_cliente(json.data, 'BD Local');
        } else {
            // Buscar en API
            await buscar_en_api(dni);
        }
    } catch (error) {
        document.getElementById('loading-cliente').style.display = 'none';
        console.error('Error buscar:', error);
    }
}

async function buscar_en_api(dni) {
    try {
        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=consultar_api&documento=' + dni);
        let json = await respuesta.json();

        if (json.status) {
            document.getElementById('nuevo-doc').value = json.data.nro_identidad;
            document.getElementById('nuevo-nombre').value = json.data.razon_social;
            document.getElementById('nuevo-direccion').value = json.data.direccion || '';
            document.getElementById('form-nuevo-cliente').style.display = 'block';

            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: `Encontrado en ${json.origen.toUpperCase()}`,
                showConfirmButton: false,
                timer: 2000
            });
        } else {
            document.getElementById('nuevo-doc').value = dni;
            document.getElementById('form-nuevo-cliente').style.display = 'block';
        }
    } catch (error) {
        document.getElementById('nuevo-doc').value = dni;
        document.getElementById('form-nuevo-cliente').style.display = 'block';
    }
}

function mostrar_cliente(cliente, origen) {
    document.getElementById('nombre-cliente').textContent = cliente.razon_social;
    document.getElementById('doc-cliente').textContent = 'Doc: ' + cliente.nro_identidad;
    document.getElementById('dir-cliente').textContent = cliente.direccion || '';
    document.getElementById('id-cliente-seleccionado').value = cliente.id;
    document.getElementById('badge-origen').textContent = origen;
    document.getElementById('datos-cliente').style.display = 'block';
    document.getElementById('form-nuevo-cliente').style.display = 'none';
}

function limpiar_cliente() {
    document.getElementById('datos-cliente').style.display = 'none';
    document.getElementById('form-nuevo-cliente').style.display = 'none';
    document.getElementById('id-cliente-seleccionado').value = '0';
}

async function registrar_cliente() {
    let nro_identidad = document.getElementById('nuevo-doc').value.trim();
    let razon_social = document.getElementById('nuevo-nombre').value.trim();
    let direccion = document.getElementById('nuevo-direccion').value.trim();

    if (!nro_identidad || !razon_social) {
        Swal.fire('Error', 'Complete DNI y Nombre', 'warning');
        return;
    }

    try {
        const datos = new FormData();
        datos.append('nro_identidad', nro_identidad);
        datos.append('razon_social', razon_social);
        datos.append('direccion', direccion);

        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=registrar_cliente', {
            method: 'POST',
            body: datos
        });

        let json = await respuesta.json();

        if (json.status) {
            Swal.fire({
                toast: true,
                position: 'top-end',
                icon: 'success',
                title: 'Cliente registrado',
                showConfirmButton: false,
                timer: 1500
            });

            mostrar_cliente({
                id: json.id_cliente,
                nro_identidad: nro_identidad,
                razon_social: razon_social,
                direccion: direccion
            }, 'Nuevo');
        }
    } catch (error) {
        console.error('Error registrar:', error);
    }
}

// ==================== REGISTRAR VENTA ====================

async function registrar_venta() {
    let id_cliente = document.getElementById('id-cliente-seleccionado').value;
    let fecha_venta = new Date().toISOString().slice(0, 19).replace('T', ' ');

    let result = await Swal.fire({
        title: '¿Confirmar venta?',
        html: `<p><strong>Total: ${document.getElementById('modal-total').textContent}</strong></p>`,
        icon: 'question',
        showCancelButton: true,
        confirmButtonColor: '#28a745',
        confirmButtonText: 'Confirmar',
        cancelButtonText: 'Cancelar'
    });

    if (!result.isConfirmed) return;

    try {
        const datos = new FormData();
        datos.append('id_cliente', id_cliente);
        datos.append('fecha_venta', fecha_venta);

        let respuesta = await fetch(base_url + 'control/VentaController.php?tipo=registrar_venta', {
            method: 'POST',
            body: datos
        });

        let json = await respuesta.json();

        if (json.status) {
            bootstrap.Modal.getInstance(document.getElementById('modalPago'))?.hide();
            document.getElementById('msg-exito').textContent = 'Código: ' + (json.codigo || '0');
            new bootstrap.Modal(document.getElementById('modalExito')).show();
        } else {
            Swal.fire('Error', json.msg, 'error');
        }
    } catch (error) {
        console.error('Error venta:', error);
        Swal.fire('Error', 'Error al procesar', 'error');
    }
}