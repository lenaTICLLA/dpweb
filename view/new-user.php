
<!-- INICIO DE CUERPO DE PAGINA -->
    <div class="container-fluid">
        <div class="card">
            <h5 class="card-header">Registro de Usuario</h5>
            <form id="frm_user" action="" method="">
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="nro_identidad" class="col-sm-2 col-form-label">Nro de Documento :</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="nro_identidad" id="nro_identidad" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="razon_social" class="col-sm-2 col-form-label">Razón Social :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="razon_social" id="razon_social" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="telefono" class="col-sm-2 col-form-label">Teléfono:</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="telefono" id="telefono" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="correo" class="col-sm-2 col-form-label">Correo :</label>
                        <div class="col-sm-10">
                            <input type="email" class="form-control" name="correo" id="correo" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="departamento" class="col-sm-2 col-form-label">Departamento :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="departamento" name="departamento" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="provincia" class="col-sm-2 col-form-label">Provincia :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="provincia" name="provincia" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="distrito" class="col-sm-2 col-form-label">Distrito:</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="distrito" name="distrito" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="cod_postal" class="col-sm-2 col-form-label">Código Postal :</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="direccion" class="col-sm-2 col-form-label">Dirección :</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="input_rol" class="col-sm-2 col-form-label">Rol :</label>
                        <div class="col-sm-10">
                            <select class="form-control" name="rol" id="rol" required>
                                <option value="" disabled selected>Seleccione</
                                        option>
                                <option value="Administrador">Administrador</
                                        option>
                                <option value="Vendedor">Vendedor</option>
                            </select>
                        </div>
                        <div>
                            <button type="sumit" class="btn btn-outline-primary">Registrar</button>
                            <button type="reset" class="btn btn-outline-success">Limpiar</button>
                            <button type="button" class="btn btn-outline-danger">Cancelar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>    
<!-- FIN DE CUERPO DE PAGINA -->
 <script src="<?php echo BASE_URL; ?>view/function/user.js"></script>
