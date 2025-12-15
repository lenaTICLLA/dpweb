<!-- INICIO DE CUERPO DE PAGINA -->
<div class="container-fluid mt-4">
    <div class="card">
        <h5 class="card-header">Actualizar Producto</h5>
        <form id="frm_edit_produc" enctype="multipart/form-data">
            <input type="hidden" id="id_producto" name="id_producto">
            <input type="hidden" id="imagen_anterior" name="imagen_anterior">
            <div class="card-body">
                <div class="mb-3 row">
                    <label for="codigo" class="col-sm-4 col-form-label">Codigo:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="codigo" name="codigo" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="nombre" class="col-sm-4 col-form-label">Nombre:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="nombre" name="nombre" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="detalle" class="col-sm-4 col-form-label">Detalle:</label>
                    <div class="col-sm-8">
                        <input type="text" class="form-control" id="detalle" name="detalle" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="precio" class="col-sm-4 col-form-label">Precio:</label>
                    <div class="col-sm-8">
                        <input type="number" step="0.01" min="0" class="form-control" id="precio" name="precio" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="stock" class="col-sm-4 col-form-label">Stock:</label>
                    <div class="col-sm-8">
                        <input type="number" min="0" class="form-control" id="stock" name="stock" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="fecha_vencimiento" class="col-sm-4 col-form-label">Fecha Vencimiento:</label>
                    <div class="col-sm-8">
                        <input type="date" class="form-control" id="fecha_vencimiento" name="fecha_vencimiento" required>
                    </div>
                </div>
                
                <!-- IMAGEN CON PREVIEW -->
                <div class="mb-3 row">
                    <label for="imagen" class="col-sm-4 col-form-label">Imagen:</label>
                    <div class="col-sm-8">
                        <div class="image-box-rect">
                            <input type="file" class="form-control form-control-sm mb-2" id="imagen" name="imagen" accept="image/*">
                            <img id="imagen_preview" src="" alt="Vista previa" style="display: none;">
                        </div>
                        <small id="imagen_actual" class="text-muted d-block mt-1">Cargando imagen...</small>
                        <small class="text-info">Dejar vacío para mantener la imagen actual</small>
                    </div>
                </div>
                
                <div class="mb-3 row">
                    <label for="id_categoria" class="col-sm-4 col-form-label">Categoría:</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="id_categoria" name="id_categoria" required>
                            <option value="">Seleccione una categoría</option>
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="id_persona" class="col-sm-4 col-form-label">Proveedor:</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="id_persona" name="id_persona">
                            <option value="">Seleccionar Proveedor</option>
                        </select>
                    </div>
                </div>

                <div class="d-flex justify-content-end gap-2">
                    <button type="button" class="btn btn-primary" id="btn_guardar">Actualizar</button>
                    <a href="<?= BASE_URL ?>produc" type="button" class="btn btn-danger">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- FIN DE CUERPO DE PAGINA -->

<script src="<?php echo BASE_URL; ?>view/function/products.js"></script>

<script>
document.addEventListener('DOMContentLoaded', async () => {
    let partes = window.location.pathname.split('/');
    let id = partes[partes.length - 1];

    // Cargar categorías y proveedores primero
    await Promise.all([
        cargarCategorias(),
        cargarProveedores()
    ]);

    // Luego cargar datos del producto
    if (!isNaN(id) && id) {
        await obtenerProductoParaEditar(id);
    }
});

// Función para cargar producto con imagen
async function obtenerProductoParaEditar(id) {
    try {
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=obtener_producto&id=' + id);
        let producto = await respuesta.json();
        
        if (producto) {
            document.getElementById('id_producto').value = producto.id || '';
            document.getElementById('codigo').value = producto.codigo || '';
            document.getElementById('nombre').value = producto.nombre || '';
            document.getElementById('detalle').value = producto.detalle || '';
            document.getElementById('precio').value = producto.precio || '';
            document.getElementById('stock').value = producto.stock || '';
            document.getElementById('fecha_vencimiento').value = producto.fecha_vencimiento || '';
            document.getElementById('id_categoria').value = producto.id_categoria || '';
            document.getElementById('id_persona').value = producto.id_proveedor || '';
            
            // Guardar ruta de imagen anterior para eliminarla si se cambia
            document.getElementById('imagen_anterior').value = producto.imagen || '';
            
            // Mostrar imagen actual
            const preview = document.getElementById('imagen_preview');
            const textoActual = document.getElementById('imagen_actual');
            
            if (producto.imagen) {
                // Corregir ruta (Uploads con mayúscula)
                let rutaImagen = base_url + producto.imagen.replace('uploads/', 'Uploads/');
                preview.src = rutaImagen;
                preview.style.display = 'block';
                textoActual.textContent = 'Imagen actual: ' + producto.imagen.split('/').pop();
            } else {
                preview.style.display = 'none';
                textoActual.textContent = 'No hay imagen asignada';
            }
            
            document.getElementById("frm_edit_produc").dataset.edit = "true";
        } else {
            Swal.fire("Error", "Producto no encontrado", "error");
        }
    } catch (e) {
        console.error("Error al obtener producto:", e);
        Swal.fire("Error", "No se pudo cargar el producto", "error");
    }
}

// Preview de nueva imagen al seleccionar
document.getElementById('imagen').addEventListener('change', function(e) {
    const file = e.target.files[0];
    const preview = document.getElementById('imagen_preview');
    const textoActual = document.getElementById('imagen_actual');

    if (file) {
        const reader = new FileReader();
        reader.onload = function(event) {
            preview.src = event.target.result;
            preview.style.display = 'block';
        };
        reader.readAsDataURL(file);
        textoActual.textContent = 'Nueva imagen: ' + file.name;
    }
});
</script>

<style>
.image-box-rect {
    display: flex;
    flex-direction: column;
    align-items: center;
    background: #fdfdfd;
    border: 2px dashed #ddd;
    border-radius: 8px;
    padding: 15px;
    width: 100%;
    max-width: 300px;
    transition: all 0.3s ease;
}

.image-box-rect:hover {
    border-color: #c72b6c;
    background-color: #fff5f8;
}

.image-box-rect img {
    width: 100%;
    max-height: 200px;
    object-fit: contain;
    border-radius: 6px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.1);
    margin-top: 10px;
}
</style>