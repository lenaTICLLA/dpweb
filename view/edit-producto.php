<!-- INICIO DE CUERPO DE PAGINA -->
<div class="container-fluid mt-4">
    <div class="card">
        <h5 class="card-header">Registrar Producto</h5>
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
                <div class="mb-3 row">
                    <label for="imagen" class="col-sm-4 col-form-label">Imagen:</label>
                    <div class="col-sm-8">
                        <input type="file" class="form-control" id="imagen" name="imagen" accept="image/*" required>
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
    document.addEventListener('DOMContentLoaded', () => {
        let partes = window.location.pathname.split('/');
        let id = partes[partes.length - 1];

        if (!isNaN(id)) {
            obtenerProductoPorId(id); // Cargar los datos si estamos editando
        }
    });
</script>