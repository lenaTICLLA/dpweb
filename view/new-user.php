
    <!-- INICIO DE CUERPO DE PAGINA-->
    <div class="container-fluid">
        <div class="card">
            <center>   
                <h5 class="card-header">NUEVO USUARIO</h5>
            </center> 
            <form id="frm_user" action="" method="">
                <div class="card-body">
                    <div class="mb-3 row">

                        <div class="mb-3 row">
                            <label for="nro_identidad" class="col-sm-4 col-form-label"><strong>nro documento:</strong></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="nro_identidad" name="nro_identidad" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="razon_social" class="col-sm-4 col-form-label"><strong>razon social:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="telefono" class="col-sm-4 col-form-label"><strong>telefono:</strong></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="telefono" name="telefono" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="correo" class="col-sm-4 col-form-label"><strong>correo:</strong></label>
                            <div class="col-sm-8">
                                <input type="email" class="form-control" id="correo" name="correo" required>
                            </div>
                        </div>

                        <div class="mb-3 row">
                            <label for="departamento" class="col-sm-4 col-form-label"><strong>departamento:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="departamento" name="departamento" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="provincia" class="col-sm-4 col-form-label"><strong>provincia:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="provincia" name="provincia" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="distrito" class="col-sm-4 col-form-label"><strong>distrito:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="distrito" name="distrito" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="cod_postal" class="col-sm-4 col-form-label"><strong>cod postal:</strong></label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="direccion" class="col-sm-4 col-form-label"><strong>direccion:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="direccion" name="direccion" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="rol" class="col-sm-4 col-form-label"><strong>rol:</strong></label>
                            <div class="col-sm-8">
                                <select class="form-control" name="rol" id="rol" required>
                                    <option value="" disabled selected>seleccione</option>
                                    <option value="administrador">administrador</option>
                                    <option value="vendedor">vendedor</option>
                                    <option value="cajero">cajero</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                                <button type="reset" class="btn btn-warning">Limpiar</button>
                                <!--<button type="button" class="btn btn-danger">Cancelar</button>-->
                                <a href="<?=BASE_URL ?>users" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <!-- FIN DE CUERPO DE PAGINA-->
    <script src="<?php echo BASE_URL; ?>view/funtion/user.js"></script>
