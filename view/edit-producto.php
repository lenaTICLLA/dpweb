<!-- INICIO DE CUERPO DE PAGINA -->
    <div class="container-fluid mt-4">
    <div class="card">
        <h5 class="card-header">Actualizar Producto</h5>
        <form id="frm_edit_produc">
            <input type="hidden" id="id_producto" name="id_producto">
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
                        <input type="text" class="form-control" id="nombre" name="nombre" required aria-describedby="nombreHelp">

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
                <div class="mb-3 row align-items-center">
                    <label for="imagen" class="col-sm-4 col-form-label">Imagen:</label>
  <div class="col-sm-8">
    <div class="image-box-rect">
      <input type="file" name="imagen" id="imagen" accept="image/*" class="form-control form-control-sm">
      <img id="preview_product_img" src="uploads/default.png" alt="Vista previa del producto">
    </div>
    <input type="hidden" id="imagen_actual" name="imagen_actual">
  </div>
</div>

                <div class="mb-3 row">
                    <label for="id_categoria" class="col-sm-4 col-form-label">Categoría:</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="id_categoria" name="id_categoria" required>
                            <option value="">Seleccione una categoría</option>
                            <!-- Las opciones se cargarán dinámicamente con JavaScript -->
                        </select>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="id_persona" class="col-sm-4 col-form-label">Proveedor:</label>
                    <div class="col-sm-8">
                        <select class="form-control" id="id_persona" name="id_persona" required>
                            <option value="">Seleccionar Proveedor</option>
                        
                        </select>
                    </div>
                </div>
                <div class="d-flex justify-content-end gap-2">
                    <input type="hidden" name="imagen_actual" id="imagen_actual" value="">
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

    // Esperar a que se carguen las categorías y los proveedores
    await Promise.all([
        cargarCategorias(),
        cargarProveedores()
    ]);

    // Luego cargar los datos del producto
    if (!isNaN(id)) {
        await obtenerProductoPorId(id);
    }
});
</script>

<style>
.image-box-rect {
  display: flex;
  flex-direction: column;
  align-items: center;
  background: #fdfdfd;
  border: 1px solid #ddd;
  border-radius: 8px;
  padding: 10px;
  width: 100%;
  max-width: 280px;
  transition: all 0.3s ease;
}

.image-box-rect:hover {
  border-color: #c72b6c; /* vino o cereza */
  background-color: #fff5f8;
}

.image-box-rect input[type="file"] {
  font-size: 13px;
  margin-bottom: 8px;
  cursor: pointer;
}

.image-box-rect img {
  width: 100%;
  height: 160px;
  object-fit: cover;
  border-radius: 6px;
  box-shadow: 0 1px 4px rgba(0,0,0,0.1);
  transition: transform 0.3s ease;
}

.image-box-rect img:hover {
  transform: scale(1.03);
}
</style>

