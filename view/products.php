<!-- INICIO DE CUERPO DE PAGINA -->
<div class="container-fluid mt-4">
    <div class="card">
        <h5 class="card-header">Registrar Producto</h5>
        <form id="frm_produc" action="" method="" enctype="multipart/form-data">
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
                            <input type="file" name="imagen" id="imagen" accept="image/*" class="form-control form-control-sm mb-2">
                            <img id="preview_product_img" src="" alt="Vista previa" style="display: none;">
                        </div>
                        <small id="imagen_nombre" class="text-muted d-block mt-1">No hay imagen seleccionada</small>
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
                    <button type="submit" class="btn btn-success">Registrar</button>
                    <button type="reset" class="btn btn-info" id="btn_limpiar">Limpiar</button>
                    <a href="<?=BASE_URL ?>produc" type="button" class="btn btn-danger">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>
<!-- FIN DE CUERPO DE PAGINA -->

<script src="<?php echo BASE_URL; ?>view/function/products.js"></script>
<script>
    cargarCategorias();
    cargarProveedores();

    // Preview de imagen al seleccionar archivo
    document.getElementById('imagen').addEventListener('change', function(e) {
        const file = e.target.files[0];
        const preview = document.getElementById('preview_product_img');
        const nombreImg = document.getElementById('imagen_nombre');

        if (file) {
            const reader = new FileReader();
            reader.onload = function(event) {
                preview.src = event.target.result;
                preview.style.display = 'block';
            };
            reader.readAsDataURL(file);
            nombreImg.textContent = file.name;
        } else {
            preview.src = '';
            preview.style.display = 'none';
            nombreImg.textContent = 'No hay imagen seleccionada';
        }
    });

    // Limpiar preview al resetear formulario
    document.getElementById('btn_limpiar').addEventListener('click', function() {
        setTimeout(function() {
            document.getElementById('preview_product_img').style.display = 'none';
            document.getElementById('preview_product_img').src = '';
            document.getElementById('imagen_nombre').textContent = 'No hay imagen seleccionada';
        }, 10);
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