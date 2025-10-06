<div class="container" style="margin-top: 100px;">
        <div class="card">
            <div class="card-header" style="text-align:center;">
                Editar Categoria
                <?php
                if (isset($_GET["views"])) {
                    $ruta = explode("/", $_GET["views"]);
                    echo $ruta[1];
                }
                ?>
            </div>
            <form id="frm_edit_categorie" action="" method="">
                <input type="hidden" name="id_categoria" id="id_categoria" value="<?= $ruta[1]; ?>">
                <div class="card-body">

                    <div class="mb-3 row">
                        <label for="nombre" class="col-sm-2 col-form-label">Nombre</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="nombre" name="nombre" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="detalle" class="col-sm-2 col-form-label">Detalle</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="detalle" name="detalle" required>
                        </div>
                    </div>

                    <div style="display: flex; justify-content:center; gap:20px">
                        <button type="submit" class="btn btn-primary">Actualizar</button>
                        <a href="<?php echo BASE_URL; ?>categorias-lista" class="btn btn-secondary">Cancelar</a>
                    </div>
                </div>
            </form>
        </div>
    </div>

<script src="<?php echo BASE_URL; ?>view/function/categoria.js"></script>
<script>
    edit_categoria();
</script>