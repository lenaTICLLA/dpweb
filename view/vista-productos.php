<!-- Fuentes -->
<link href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@600;700&family=Poppins:wght@300;400;600&display=swap" rel="stylesheet">

<!-- Bootstrap & Icons -->
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.10.5/font/bootstrap-icons.css">

<!-- Estilos personalizados -->
<link rel="stylesheet" href="<?php echo BASE_URL; ?>view/vista-productos.css">

<main class="productos-shell">

    <!-- HEADER -->
    <header class="productos-header">
        <div class="container d-flex justify-content-between align-items-center gap-3 py-3">
            <div>
                <h1 class="brand-title"><i class="bi bi-shop me-2"></i>Punto de Venta</h1>
                <p class="brand-sub">Sistema de ventas · Productos disponibles</p>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark">
                    <i class="bi bi-person-circle me-1"></i>
                    <?php echo $_SESSION['ventas_usuario'] ?? 'Usuario'; ?>
                </span>
            </div>
        </div>
    </header>

    <div class="container-fluid mt-4">
        <div class="row">

            <!-- COLUMNA IZQUIERDA: PRODUCTOS -->
            <div class="col-12 col-lg-8">

                <!-- BUSCADOR -->
                <div class="card card-glass mb-4 p-3">
                    <div class="row g-2 align-items-center">
                        <div class="col-md-8">
                            <div class="input-group">
                                <span class="input-group-text bg-pink border-0 text-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control input-soft" 
                                       placeholder="Buscar por código, nombre o descripción..." 
                                       id="busqueda_venta">
                            </div>
                        </div>
                        <div class="col-md-4">
                            <select class="form-select select-soft" id="filtro_categoria">
                                <option value="">Todas las categorías</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- GRID DE PRODUCTOS -->
                <div id="productos-container" class="row g-3"></div>

                <!-- Sin resultados -->
                <div id="noResultados" class="text-center py-5" style="display:none;">
                    <i class="bi bi-search fs-1 mb-3 d-block opacity-50"></i>
                    <p class="text-white-50">No se encontraron productos</p>
                </div>

                <!-- Loading -->
                <div id="loadingProductos" class="text-center py-5">
                    <div class="spinner-border text-pink" role="status">
                        <span class="visually-hidden">Cargando...</span>
                    </div>
                    <p class="mt-2 text-white-50">Cargando productos...</p>
                </div>

            </div>

            <!-- COLUMNA DERECHA: CARRITO -->
            <div class="col-12 col-lg-4">
                <div class="carrito-container sticky-top" style="top: 20px;">
                    
                    <!-- Header del carrito -->
                    <div class="d-flex justify-content-between align-items-center mb-3">
                        <h5 class="text-white fw-bold mb-0">
                            <i class="bi bi-cart4 me-2 text-pink"></i>Mi Carrito
                        </h5>
                        <span id="contador-carrito" class="badge bg-pink rounded-pill fs-6">0</span>
                    </div>
                    <hr class="border-pink opacity-25">

                    <!-- Lista de productos en carrito -->
                    <div id="lista-carrito" class="mb-3" style="max-height: 45vh; overflow-y: auto;">
                        <div id="carrito-vacio" class="text-center py-4 text-white-50">
                            <i class="bi bi-cart-x fs-1 d-block mb-2"></i>
                            <p>El carrito está vacío</p>
                        </div>
                        <div id="tabla-carrito"></div>
                    </div>

                    <!-- Totales -->
                    <div class="border-top border-pink pt-3">
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50">Subtotal:</span>
                            <span id="subtotal-carrito" class="text-white fw-semibold">S/ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-2">
                            <span class="text-white-50">IGV (18%):</span>
                            <span id="igv-carrito" class="text-white fw-semibold">S/ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between mb-3 fs-5">
                            <strong class="text-white">Total:</strong>
                            <strong id="total-carrito" class="text-pink">S/ 0.00</strong>
                        </div>

                        <!-- Botones -->
                        <div class="d-grid gap-2">
                            <button id="btn-procesar-pago" class="btn btn-pink btn-lg" disabled>
                                <i class="bi bi-credit-card me-2"></i>Procesar Pago
                            </button>
                            <button id="btn-vaciar-carrito" class="btn btn-outline-danger">
                                <i class="bi bi-trash me-2"></i>Vaciar Carrito
                            </button>
                        </div>
                    </div>

                </div>
            </div>

        </div>
    </div>
</main>

<!-- MODAL DE PAGO -->
<div class="modal fade" id="modalPago" tabindex="-1" aria-labelledby="modalPagoLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-dark text-white">
            <div class="modal-header border-pink">
                <h5 class="modal-title" id="modalPagoLabel">
                    <i class="bi bi-credit-card-2-front me-2 text-pink"></i>Procesar Pago
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                
                <!-- Buscar Cliente -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-person-badge me-1"></i>Buscar Cliente (DNI/RUC)
                    </label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="input-documento" 
                               placeholder="Ingrese DNI (8) o RUC (11)" maxlength="11">
                        <button class="btn btn-pink" type="button" id="btn-buscar-cliente">
                            <i class="bi bi-search"></i> Buscar
                        </button>
                    </div>
                    <div id="loading-cliente" class="text-center mt-2" style="display: none;">
                        <div class="spinner-border spinner-border-sm text-pink" role="status"></div>
                        <small class="ms-2">Buscando...</small>
                    </div>
                </div>

                <!-- Datos del Cliente -->
                <div id="datos-cliente" class="card bg-secondary bg-opacity-25 mb-4" style="display: none;">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <span id="badge-origen" class="badge bg-success mb-2">BD Local</span>
                                <h6 id="nombre-cliente" class="mb-1 text-white">-</h6>
                                <small id="doc-cliente" class="text-white-50">DNI: -</small>
                                <br>
                                <small id="dir-cliente" class="text-white-50"></small>
                            </div>
                            <button class="btn btn-sm btn-outline-light" id="btn-limpiar-cliente">
                                <i class="bi bi-x"></i>
                            </button>
                        </div>
                    </div>
                    <input type="hidden" id="id-cliente-seleccionado" value="0">
                </div>

                <!-- Registrar Cliente Nuevo -->
                <div id="form-nuevo-cliente" class="card bg-warning bg-opacity-10 mb-4" style="display: none;">
                    <div class="card-body">
                        <h6 class="text-warning mb-3">
                            <i class="bi bi-person-plus me-1"></i>Registrar Nuevo Cliente
                        </h6>
                        <div class="mb-2">
                            <input type="text" class="form-control form-control-sm" id="nuevo-doc" 
                                   placeholder="DNI/RUC" readonly>
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control form-control-sm" id="nuevo-nombre" 
                                   placeholder="Nombre completo o Razón Social">
                        </div>
                        <div class="mb-2">
                            <input type="text" class="form-control form-control-sm" id="nuevo-direccion" 
                                   placeholder="Dirección (opcional)">
                        </div>
                        <button class="btn btn-warning btn-sm w-100" id="btn-registrar-cliente">
                            <i class="bi bi-save me-1"></i>Guardar Cliente
                        </button>
                    </div>
                </div>

                <!-- Tipo de Pago -->
                <div class="mb-4">
                    <label class="form-label fw-semibold">
                        <i class="bi bi-wallet2 me-1"></i>Método de Pago
                    </label>
                    <div class="row g-2">
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="tipo-pago" id="pago-efectivo" value="EFECTIVO" checked>
                            <label class="btn btn-outline-light w-100" for="pago-efectivo">
                                <i class="bi bi-cash-coin d-block fs-4 mb-1"></i>Efectivo
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="tipo-pago" id="pago-tarjeta" value="TARJETA">
                            <label class="btn btn-outline-light w-100" for="pago-tarjeta">
                                <i class="bi bi-credit-card d-block fs-4 mb-1"></i>Tarjeta
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="tipo-pago" id="pago-yape" value="YAPE">
                            <label class="btn btn-outline-light w-100" for="pago-yape">
                                <i class="bi bi-phone d-block fs-4 mb-1"></i>Yape
                            </label>
                        </div>
                        <div class="col-6">
                            <input type="radio" class="btn-check" name="tipo-pago" id="pago-plin" value="PLIN">
                            <label class="btn btn-outline-light w-100" for="pago-plin">
                                <i class="bi bi-qr-code d-block fs-4 mb-1"></i>Plin
                            </label>
                        </div>
                    </div>
                </div>

                <!-- Resumen -->
                <div class="card bg-pink bg-opacity-25">
                    <div class="card-body">
                        <h6 class="text-pink mb-3"><i class="bi bi-receipt me-1"></i>Resumen de Venta</h6>
                        <div class="d-flex justify-content-between">
                            <span>Subtotal:</span>
                            <span id="modal-subtotal">S/ 0.00</span>
                        </div>
                        <div class="d-flex justify-content-between">
                            <span>IGV (18%):</span>
                            <span id="modal-igv">S/ 0.00</span>
                        </div>
                        <hr class="my-2">
                        <div class="d-flex justify-content-between fs-5 fw-bold">
                            <span>TOTAL:</span>
                            <span id="modal-total" class="text-pink">S/ 0.00</span>
                        </div>
                    </div>
                </div>

            </div>
            <div class="modal-footer border-pink">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i>Cancelar
                </button>
                <button type="button" class="btn btn-success btn-lg" id="btn-confirmar-venta">
                    <i class="bi bi-check-circle me-1"></i>Confirmar Venta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- MODAL ÉXITO -->
<div class="modal fade" id="modalExito" tabindex="-1" data-bs-backdrop="static">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content bg-success text-white text-center">
            <div class="modal-body py-5">
                <i class="bi bi-check-circle fs-1 mb-3 d-block"></i>
                <h4>¡Venta Exitosa!</h4>
                <p id="msg-exito" class="mb-0">Código: V-XXXXX</p>
            </div>
            <div class="modal-footer border-0 justify-content-center">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal" onclick="location.reload()">
                    <i class="bi bi-arrow-repeat me-1"></i>Nueva Venta
                </button>
            </div>
        </div>
    </div>
</div>

<!-- SweetAlert2 -->
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

<!-- Bootstrap JS -->
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<!-- Script de Ventas -->
<script src="<?php echo BASE_URL; ?>view/function/venta.js"></script>