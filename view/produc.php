<div class="container container-glass">
  <div class="d-flex justify-content-between align-items-center mb-4 arriba">
    <h3 class="title">📦 Lista de Productos</h3>
    <div>
      <a href="<?php echo BASE_URL; ?>products" class="btn btn-primary me-2">➕ Nuevo Producto</a>
    </div>
  </div>

  <div class="table-responsive">
    <table class="table table-bordered align-middle text-center">
    <thead class="table-dark">
        <tr class="text-center">
            <th>Nro</th>
            <th>Codigo</th>
            <th>Nombres</th>
            <th>Detalle</th>
            <th>Precio</th>
            <th>Stock</th>
            <th>Fecha Vencimiento</th>
            <th>categoria</th>
            <th>Acciones</th>
        </tr>
    </thead>
    <tbody id="content_productos">

    </tbody>
</table>
</div>
<script src="<?= BASE_URL ?>view/function/products.js"></script>
<!--script>view_users();</script-->
<style>

body {
  background: linear-gradient(135deg, #5c0029, #9b3b61, #f5a3c7);
  background-attachment: fixed;
  background-size: cover;
  font-family: 'Poppins', sans-serif;
  color: #fff;
  margin: 0;
  padding: 0;
}

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
  text-transform: uppercase;
  font-size: 14px;
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
.image-container {
  display: flex;
  flex-direction: column;
  align-items: center;
  justify-content: center;
  gap: 10px;
  background: #f8f8f8;
  border: 2px dashed #d1d1d1;
  border-radius: 10px;
  padding: 15px;
  width: 250px;
  margin: 0 auto;
  transition: 0.3s;
}

.image-container:hover {
  border-color: #c63d63;
  background: #fff3f7;
}

.image-container input[type="file"] {
  border: none;
  outline: none;
  background: none;
  font-size: 14px;
  cursor: pointer;
  color: #444;
}

.image-container img {
  width: 150px;
  height: 150px;
  object-fit: cover;
  border-radius: 12px;
  box-shadow: 0 2px 6px rgba(0,0,0,0.2);
  transition: transform 0.3s ease;
}

.image-container img:hover {
  transform: scale(1.05);
}

</style>

<!-- Bootstrap CDN -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<!-- Bootstrap Icons -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">
<style>
  .table td .btn { padding: .25rem .45rem; }
  .table td i.bi { font-size: 1rem; }
</style>