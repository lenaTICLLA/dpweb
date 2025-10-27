<!-- INICIO DE CUERPO DE PAGINA -->
    <div class="container-fluid">
        <div class="card">
            <h5 class="card-header">Registro de Usuario</h5>
            <form id="frm_edit_proveedor" action="" method="">
                <input type="hidden" id="id_persona" name="id_persona">
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="nro_identidad" class="col-sm-4 col-form-label">Nro de Documento :</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="nro_identidad" name="nro_identidad" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="razon_social" class="col-sm-4 col-form-label">Razon social :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="razon_social" name="razon_social" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="telefono" class="col-sm-4 col-form-label">Telefono :</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="telefono" name="telefono" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="correo" class="col-sm-4 col-form-label">Correo :</label>
                        <div class="col-sm-8">
                            <input type="email" class="form-control" id="correo" name="correo" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="departamento" class="col-sm-4 col-form-label">Departamento :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="departamento" name="departamento" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="provincia" class="col-sm-4 col-form-label">Provincia :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="provincia" name="provincia" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="distrito" class="col-sm-4 col-form-label">Distrito :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="distrito" name="distrito" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="cod_postal" class="col-sm-4 col-form-label">Codigo postal :</label>
                        <div class="col-sm-8">
                            <input type="number" class="form-control" id="cod_postal" name="cod_postal" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="direccion" class="col-sm-4 col-form-label">Direccion :</label>
                        <div class="col-sm-8">
                            <input type="text" class="form-control" id="direccion" name="direccion" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="rol" class="col-sm-4 col-form-label">Rol :</label>
                        <div class="col-sm-8">
                            <select class="form-control" name="rol" id="rol" required>
                        
                                <option value="3">proveedor</option>
                           
                            </select>
                        </div>
                    </div>
                  <button type="submit" class="btn btn-primary" id="btn_guardar_cambios">Guardar Cambios</button>
                   <a href="<?=BASE_URL ?>proveedor" type="button" class="btn btn-danger">Cancelar</a>
                  
                    
                </div>
            </form>
        </div>
    </div>
    </div>
<!-- FIN DE CUERPO DE PAGINA -->


 <script src="<?php echo BASE_URL; ?>view/function/proveedor.js"></script>

<script>
document.addEventListener('DOMContentLoaded', () => {
    let partes = window.location.pathname.split('/');
    let id = partes[partes.length - 1];
    if (!isNaN(id)) {
        edit_proveedor(id); // Cambiado a edit_proveedor
    }
});
</script>



 