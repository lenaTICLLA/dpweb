
    <div class="container-fluid">
        <div class="card">
            <center>   
                <h5 class="card-header">INGRESA LOS DATOS</h5>
            </center> 
            <form id="frm_category" action="" method="">
                <div class="card-body">
                    <div class="mb-3 row">

                        <div class="mb-3 row">
                            <label for="nombre" class="col-sm-4 col-form-label"><strong>nombre:</strong></label>
                            <div class="col-sm-8">
                                <input type="text" class="form-control" id="nombre" name="nombre" required>
                            </div>
                        </div>
                        <div class="mb-3 row">
                            <label for="detalle" class="col-sm-4 col-form-label"><strong>detalle:</strong></label>
                            <div class="col-sm-8">
                                <textarea class="form-control" name="detalle" id="detalle"></textarea>
                                <!--
                                <input type="textarea" class="form-control" id="detalle" name="detalle" required>
                                -->
                            </div>
                            <style>
                                #detalle{
                                    height: 150px;
                                }
                            </style>
                        </div>
                        
                        <div class="mb-3 row">
                            <label class="col-sm-4 col-form-label"></label>
                            <div class="col-sm-8">
                                <button type="submit" class="btn btn-primary">Registrar</button>
                                <button type="reset" class="btn btn-warning">Limpiar</button>
                                
                                <a href="<?=BASE_URL ?>category" class="btn btn-danger">Cancelar</a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    
<script src="<?php echo BASE_URL; ?>view/funtion/category.js"></script>
