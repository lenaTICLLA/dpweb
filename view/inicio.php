<div class="container py-5">
  <!-- 👋 Bienvenida -->
  <div class="text-center mb-5">
    <h2 class="fw-bold text-dark">👋 Bienvenido, <?= htmlspecialchars($_SESSION['ventas_usuario']); ?></h2>
    <p class="text-muted">Tu ID: <?= $_SESSION['ventas_id']; ?> | Rol: <?= $_SESSION['rol'] ?? 'No definido'; ?></p>
  </div>

  <!-- 🧭 Tarjetas del panel -->
  <div class="row justify-content-center g-4">
    
    <!-- Usuarios -->
    <div class="col-md-3">
      <div class="card card-glass shadow-sm text-center">
        <div class="card-body">
          <i class="bi bi-people display-5 text-pink"></i>
          <h5 class="mt-3">Usuarios</h5>
          <p class="text-muted">Gestión de usuarios registrados.</p>
          <a href="<?= BASE_URL; ?>users" class="btn btn-primary btn-sm">Ver usuarios</a>
        </div>
      </div>
    </div>

    <!-- Productos -->
    <div class="col-md-3">
      <div class="card card-glass shadow-sm text-center">
        <div class="card-body">
          <i class="bi bi-box-seam display-5 text-pink"></i>
          <h5 class="mt-3">Productos</h5>
          <p class="text-muted">Administrar inventario y stock.</p>
          <a href="<?= BASE_URL; ?>produc" class="btn btn-primary btn-sm">Ver productos</a>
        </div>
      </div>
    </div>

    <!-- Clientes -->
    <div class="col-md-3">
      <div class="card card-glass shadow-sm text-center">
        <div class="card-body">
          <i class="bi bi-person-badge display-5 text-pink"></i>
          <h5 class="mt-3">Clientes</h5>
          <p class="text-muted">Visualiza los clientes registrados.</p>
          <a href="<?= BASE_URL; ?>cliente" class="btn btn-primary btn-sm">Ver clientes</a>
        </div>
      </div>
    </div>

    <!-- Ventas -->
    <div class="col-md-3">
      <div class="card card-glass shadow-sm text-center">
        <div class="card-body">
          <i class="bi bi-cart4 display-5 text-pink"></i>
          <h5 class="mt-3">Ventas</h5>
          <p class="text-muted">Historial y control de ventas.</p>
          <a href="#" class="btn btn-primary btn-sm disabled">Próximamente</a>
        </div>
      </div>
    </div>

  </div>

  <!-- 🚪 Botón salir -->
  <div class="text-center mt-5">
    <a href="<?= BASE_URL; ?>logout.php" class="btn btn-danger px-4">
      <i class="bi bi-box-arrow-right"></i> Cerrar sesión
    </a>
  </div>
</div>

<!-- 🎨 Estilos -->
<style>
  body {
    background: linear-gradient(135deg, #5c0029, #9b3b61, #f5a3c7);
    background-attachment: fixed;
    background-size: cover;
    font-family: 'Poppins', sans-serif;
    color: #333;
  }

  .card-glass {
    background: rgba(255, 255, 255, 0.15);
    backdrop-filter: blur(10px);
    border-radius: 20px;
    border: 1px solid rgba(255, 255, 255, 0.2);
    transition: transform 0.3s, box-shadow 0.3s;
  }

  .card-glass:hover {
    transform: translateY(-8px);
    box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
  }

  .text-pink {
    color: #c72b6c;
  }

  .btn-primary {
    background-color: #c72b6c;
    border: none;
  }

  .btn-primary:hover {
    background-color: #a51d58;
  }

  .btn-danger {
    background-color: #e74c3c;
    border: none;
  }

  .btn-danger:hover {
    background-color: #c0392b;
  }
</style>

