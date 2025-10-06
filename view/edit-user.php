<div class="container" style="margin-top: 100px;">
    <div class="card">
        <div class="card-header" style="text-align:center;">
            Editar Usuario
            <?php
                if (isset($_GET["views"])) {
                    $ruta = explode("/", $_GET["views"]);
                    echo $ruta[1];
                }
            ?>
        </div>
        <form id="frm_edit_user" action="" method="">
            <input type="hidden" name="id_persona" id="id_persona" value="<?= $ruta[1]; ?>">
            <div class="card-body">

                <div class="mb-3 row">
                    <label for="nro_identidad" class="col-sm-2 col-form-label">Nro de Documento</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="nro_identidad" name="nro_identidad" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="razon_social" class="col-sm-2 col-form-label">Nombre/Razon Social</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="telefono" class="col-sm-2 col-form-label">Telefono</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="telefono" name="telefono" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="correo" class="col-sm-2 col-form-label">Correo</label>
                    <div class="col-sm-10">
                        <input type="email" class="form-control" id="correo" name="correo" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="departamento" class="col-sm-2 col-form-label">Departamento</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="departamento" name="departamento" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="provincia" class="col-sm-2 col-form-label">Provincia</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="provincia" name="provincia" required>
                    </div>
                </div>
                <div class="mb-3 row">
                    <label for="distrito" class="col-sm-2 col-form-label">Distrito</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="distrito" name="distrito" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="cod_postal" class="col-sm-2 col-form-label">Codigo Postal</label>
                    <div class="col-sm-10">
                        <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="direccion" class="col-sm-2 col-form-label">Direccion</label>
                    <div class="col-sm-10">
                        <input type="text" class="form-control" id="direccion" name="direccion" required>
                    </div>
                </div>

                <div class="mb-3 row">
                    <label for="rol" class="col-sm-2 col-form-label">Rol</label>
                    <div class="col-sm-10">
                        <select class="form-select" aria-label="Default select example" id="rol" name="rol">
                            <option value="" disabled selected>seleccione</option>
                            <option value="admin">Administrador</option>
                            <option value="user">Usuario</option>
                            <option value="proveedor">Proveedor</option>
                        </select>
                    </div>
                </div>

                <div style="display: flex; justify-content:center; gap:20px">
                    <button type="submit" class="btn btn-primary">Actualizar</button>
                    <a href="<?php echo BASE_URL; ?>users" class="btn btn-secondary">Cancelar</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script src="<?php echo BASE_URL; ?>view/function/user.js"></script>
<script>
    edit_user();
</script>