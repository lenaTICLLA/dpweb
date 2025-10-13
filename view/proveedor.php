<div class="container container-glass">
  <div class="d-flex justify-content-between align-items-center mb-4 arriba">
    <h3 class="title">🏭 Lista de Proveedores</h3>
    <div>
      <a href="<?php echo BASE_URL; ?>new-proveedor" class="btn btn-primary me-2">➕ Nuevo Proveedor</a>
      <a href="<?php echo BASE_URL; ?>users" class="btn btn-secondary me-2">👤 Usuarios</a>
      <a href="<?php echo BASE_URL; ?>products" class="btn btn-secondary">📦 Productos</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
      <thead>
        <tr>
          <th>N°</th>
          <th>RUC</th>
          <th>Nombre de Empresa</th>
          <th>Representante</th>
          <th>Teléfono</th>
          <th>Correo</th>
          <th>Dirección</th>
          <th>Estado</th>
          <th>Acciones</th>
        </tr>
      </thead>
      <tbody id="content_proveedores">
        <!-- Aquí se cargan los proveedores -->
      </tbody>
    </table>
  </div>
</div>

<!-- Tu archivo JS -->
<script src="<?php echo BASE_URL; ?>view/funtion/user.js"></script>

<!-- 🔮 ESTILO “GLASS” -->
<style>
/* ======== FONDO GENERAL ======== */
body {
  background: linear-gradient(135deg, #5c0029, #9b3b61, #f5a3c7);
  background-attachment: fixed;
  background-size: cover;
  font-family: 'Poppins', sans-serif;
  color: #fff;
  margin: 0;
  padding: 0;
}

/* ======== CONTENEDOR GLASS ======== */
.container-glass {
  background: rgba(255, 255, 255, 0.12);
  backdrop-filter: blur(12px);
  border-radius: 20px;
  padding: 2rem;
  box-shadow: 0 4px 30px rgba(0, 0, 0, 0.3);
  margin-top: 3rem;
  animation: fadeIn 1s ease forwards;
}

/* ======== TÍTULO ======== */
.title {
  color: #fff;
  font-weight: 600;
  letter-spacing: 1px;
}

/* ======== BOTONES ======== */
.btn-primary {
  background-color: #c72b6c !important;
  border-color: #c72b6c !important;
  border-radius: 10px;
  transition: 0.3s;
}

.btn-primary:hover {
  background-color: #a51d58 !important;
  transform: scale(1.05);
}

.btn-secondary {
  background-color: #ffb6c1 !important;
  border-color: #ffb6c1 !important;
  color: #000 !important;
  border-radius: 10px;
  transition: 0.3s;
}

.btn-secondary:hover {
  background-color: #f28da7 !important;
  transform: scale(1.05);
}

/* ======== TABLA ======== */
.table {
  color: #fff;
  background: rgba(255, 255, 255, 0.08);
  border-radius: 10px;
  overflow: hidden;
}

.table thead {
  background-color: rgba(255, 255, 255, 0.2);
}

.table th {
  color: #fff;
  font-weight: 600;
}

.table tbody tr:hover {
  background-color: rgba(255, 255, 255, 0.15);
  transition: 0.3s;
}

/* ======== SCROLL ======== */
.table-responsive {
  max-height: 480px;
  overflow-y: auto;
  scrollbar-width: thin;
  scrollbar-color: #c72b6c rgba(255, 255, 255, 0.1);
}

.table-responsive::-webkit-scrollbar {
  width: 8px;
}

.table-responsive::-webkit-scrollbar-thumb {
  background-color: #c72b6c;
  border-radius: 4px;
}

/* ======== ANIMACIÓN ======== */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
