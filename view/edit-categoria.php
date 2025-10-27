<div class="container-fluid mt-4">
    <div class="card">
        <h5 class="card-header">Editar Categoría</h5>
        <form id="frm_edit_categories">
            <input type="hidden" id="id_categoria" name="id_categoria" value="<?php echo isset($_GET['id']) ? htmlspecialchars($_GET['id']) : ''; ?>">
            <div class="card-body">
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
                <div class="d-flex justify-content-end gap-2">
                    <button type="submit" class="btn btn-primary" id="btn_guardarCategoria">Actualizar</button>
                    <a href="<?=BASE_URL ?>categoria" type="button" class="btn btn-danger">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>view/function/categories.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let partes = window.location.pathname.split('/');
    let id = partes[partes.length - 1];

    if (!isNaN(id)) {
        obtenerCategoriaPorId(id);
    }
});
</script>