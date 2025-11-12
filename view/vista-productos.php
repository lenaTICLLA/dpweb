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
        <h1 class="brand-title">Productos</h1>
        <p class="brand-sub">Colección destacada · Últimas tendencias</p>
      </div>
      <div class="d-flex align-items-center gap-2">
        <a href="<?php echo BASE_URL; ?>products" class="btn btn-sm btn-ghost">
          <i class="bi bi-plus-lg"></i> Nuevo producto
        </a>
      </div>
    </div>
  </header>

  <div class="container-fluid mt-4">
    <div class="row">

      <!-- COLUMNA IZQUIERDA -->
      <div class="col-12 col-lg-9">

        <!-- FILTROS -->
        <div class="d-flex gap-3 mb-3 flex-wrap">
          <input id="searchInput" class="form-control form-control-sm input-soft" placeholder="Buscar productos por nombre...">
          <select id="categoryFilter" class="form-select form-select-sm select-soft" style="max-width:220px;">
            <option value="">Todas las categorías</option>
          </select>
        </div>

        <!-- CARRUSEL DESTACADOS -->
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

        <!-- GRID DE PRODUCTOS -->
        <div id="productos-container" class="row g-3"></div>

        <div id="noResultados" class="no-resultados mt-4 text-center" style="display:none;">
          <i class="bi bi-search fs-1 mb-2"></i>
          <p>No se encontraron productos</p>
        </div>

      </div>

      <!-- COLUMNA DERECHA: CARRITO -->
      <div class="col-12 col-lg-3 position-sticky top-0" style="height:100vh; overflow-y:auto;">
        <div class="carrito-container bg-white rounded-3 shadow-lg p-4 h-100 border-start border-3 border-pink">
          <div class="d-flex justify-content-between align-items-center mb-3">
            <h4 class="text-pink fw-bold mb-0"><i class="bi bi-cart4 me-2"></i>Mi Carrito</h4>
            <span id="contador-carrito" class="badge bg-pink rounded-pill fs-6">0</span>
          </div>
          <hr class="text-pink opacity-25">

          <div id="lista-carrito" class="mb-4" style="max-height:58vh; overflow-y:auto;">
            <div class="table-responsive">
              <table class="table table-sm table-hover align-middle text-dark">
                <thead class="table-pink">
                  <tr>
                    <th>Producto</th>
                    <th class="text-center">Cant.</th>
                    <th class="text-end">Precio</th>
                    <th class="text-end">Total</th>
                    <th class="text-center">Acciones</th>
                  </tr>
                </thead>
                <tbody id="tabla-productos">
                  <!-- Productos dinámicos -->
                </tbody>
              </table>
            </div>
          </div>

          <div class="border-top pt-3">
            <div class="d-flex justify-content-between mb-2">
              <strong>Subtotal:</strong>
              <span id="subtotal-carrito">S/ 0.00</span>
            </div>
            <div class="d-flex justify-content-between mb-2">
              <strong>IGV:</strong>
              <span>18%</span>
            </div>
            <div class="d-flex justify-content-between fs-5 mb-3">
              <strong>Total:</strong>
              <strong id="total-carrito" class="text-pink">S/ 0.00</strong>
            </div>

            <div class="d-flex gap-2">
              <button id="btn-vaciar-carrito" class="btn btn-outline-danger w-50">Vaciar</button>
              <button id="btn-proceder-pago" class="btn btn-pink w-50">Pagar</button>
            </div>
          </div>
        </div>
      </div>

    </div>
  </div>
</main>

<!-- Scripts -->
<script src="<?php echo BASE_URL; ?>view/function/vista-productos.js"></script>
<script src="<?php echo BASE_URL; ?>view/function/venta.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>