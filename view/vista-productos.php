<?php
// vista-productos.php
// Asegúrate de definir BASE_URL antes de incluir esta vista
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Catálogo de Ropa</title>

  <!-- Fuentes -->
  <link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

  <!-- Bootstrap & Icons -->
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

  <!-- Estilos personalizados -->
  <link rel="stylesheet" href="<?php echo BASE_URL; ?>view/vista-productos.css">

  <script>
    const base_url = "<?php echo BASE_URL; ?>";
  </script>
</head>
<body>

<main class="productos-shell">
  <!-- HEADER -->
  <header class="productos-header">
    <div class="container d-flex justify-content-between align-items-center gap-3 py-3">
      <div>
        <h1 class="brand-title">Ropa Premium</h1>
        <p class="brand-sub">Colección destacada · Últimas tendencias</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="<?php echo BASE_URL; ?>products" class="btn btn-sm btn-ghost">
          <i class="bi bi-plus-lg"></i> Nuevo producto
        </a>
        <button id="carritoBtn" class="btn btn-primary btn-sm position-relative">
          <i class="bi bi-cart3"></i>
          <span id="carritoContador" class="badge-contador">0</span>
        </button>
      </div>
    </div>
  </header>

  <!-- CARRUSEL DESTACADOS -->
  <section class="container mt-4">
    <div class="card card-glass mb-4 p-3">
      <h2 class="section-title">Productos Destacados</h2>
      <p class="section-sub">Nuestros favoritos de la semana</p>

      <div id="productos-carousel" class="carousel slide" data-bs-ride="carousel" data-bs-interval="3500">
        <div class="carousel-indicators"></div>
        <div class="carousel-inner d-flex align-items-stretch"></div>

        <button class="carousel-control-prev" type="button" data-bs-target="#productos-carousel" data-bs-slide="prev">
          <span class="carousel-control-prev-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Anterior</span>
        </button>
        <button class="carousel-control-next" type="button" data-bs-target="#productos-carousel" data-bs-slide="next">
          <span class="carousel-control-next-icon" aria-hidden="true"></span>
          <span class="visually-hidden">Siguiente</span>
        </button>
      </div>
    </div>

    <!-- FILTROS -->
    <div class="d-flex gap-3 mb-3 flex-wrap">
      <input id="searchInput" class="form-control form-control-sm input-soft" placeholder="Buscar productos por nombre...">
      <select id="categoryFilter" class="form-select form-select-sm select-soft" style="max-width:220px;">
        <option value="">Todas las categorías</option>
      </select>
    </div>

    <!-- GRID DE PRODUCTOS -->
    <div id="productos-container" class="row g-3"></div>

    <div id="noResultados" class="no-resultados mt-4 text-center" style="display:none;">
      <i class="bi bi-search fs-1 mb-2"></i>
      <p>No se encontraron productos</p>
    </div>
  </section>
</main>

<!-- Modal Detalle -->
<div class="modal fade" id="modalDetalleProducto" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-lg modal-dialog-centered">
    <div class="modal-content modal-producto">
      <div class="modal-header border-0">
        <h5 id="modalProductoTitulo" class="modal-title"></h5>
        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal"></button>
      </div>
      <div class="modal-body">
        <div class="row g-3">
          <div class="col-md-6">
            <img id="modalProductoImg" src="" class="img-fluid rounded modal-producto-img">
          </div>
          <div class="col-md-6">
            <span id="modalProductoCategoria" class="badge bg-soft mb-2"></span>
            <h3 id="modalProductoNombre" class="h4"></h3>
            <p id="modalProductoDetalle" class="text-muted"></p>
            <div class="d-flex gap-3 mt-3">
              <div class="fs-4 fw-bold" id="modalProductoPrecio"></div>
              <div class="text-muted" id="modalProductoStock"></div>
            </div>
            <button class="btn btn-lg btn-primary mt-4" onclick="añadirAlCarritoDesdeModal()">
              <i class="bi bi-cart-plus me-2"></i> Añadir al carrito
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Scripts -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="<?php echo BASE_URL; ?>view/function/vista-productos.js"></script>
</body>
</html>
