<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>LENA</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>view/bootstrap/css/bootstrap.min.css">
    <script>
        const base_url ='<?php echo BASE_URL; ?>';
    </script>
</head>

<body>
   <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Logo</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a class="nav-link active" href="#">Home</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Users</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Products</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">categoria</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" aria-current="page"  href="#">Categories</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Clients</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Shops</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="#">Sales</a>
                    </li>

                </ul>
                 </form>
            </div>
        </div>
    </nav>
 <div class="container-fluid">
        <div class="card">
            <h5 class="card-header">Titulo</h5>
            <form id="frm_categoria" action="" method="">
                <div class="card-body">
                    <div class="mb-3 row">
                        <label for="nombre" class="col-sm-2 col-form-label">Nombre Categoría</label>
                        <div class="col-sm-10">
                            <input type="number" class="form-control" name="nombre" id="nombre" required>
                        </div>
                    </div>
                    <div class="mb-3 row">
                        <label for="detalle" class="col-sm-2 col-form-label">Detalle</label>
                        <div class="col-sm-10">
                            <input type="text" class="form-control" name="detalle" id="detalle" required>
                        </div>
                    </div>
                   
                    <div class="mb-3 row">
                    
                        <div>
                            <button type="sumit" class="btn btn-danger">Registrar</button>
                            <button type="reset" class="btn btn-primary">Limpiar</button>
                            <button type="button" class="btn btn-danger">Cancelar</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>

</body>
<script src="<?php echo BASE_URL; ?>view/function/categoria.js"></script>
<script src="<?php echo BASE_URL; ?>view/bootstrap/js/bootstrap.bundle.
min.js"></script>

<!-- sweet alert 2-->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</html>