let productoActualModal = null;
let carrito = JSON.parse(localStorage.getItem('carrito')) || [];
// Búsqueda en tiempo real
const searchInput = document.getElementById('searchInput');
if (searchInput) {
    searchInput.addEventListener('input', function() {
        filtrarProductos();
    });
}

// Filtro por categoría
const categoryFilter = document.getElementById('categoryFilter');
if (categoryFilter) {
    categoryFilter.addEventListener('change', function() {
        filtrarProductos();
    });
}

function filtrarProductos() {
    const searchTerm = searchInput ? searchInput.value.toLowerCase().trim() : '';
    const selectedCategory = categoryFilter ? categoryFilter.value : '';
    const productosGrid = document.getElementById('productosGrid');
    const productoCards = productosGrid.querySelectorAll('.producto-card');
    const noResultados = document.getElementById('noResultados');
    
    let visibleCount = 0;

    productoCards.forEach((card, index) => {
        const nombre = card.dataset.nombre || '';
        const categoria = card.dataset.categoria || '';
        
        const matchSearch = nombre.includes(searchTerm);
        const matchCategory = !selectedCategory || categoria === selectedCategory;
        
        if (matchSearch && matchCategory) {
            card.style.display = 'block';
            // Animación escalonada al aparecer
            card.style.animation = 'none';
            setTimeout(() => {
                card.style.animation = `fadeInUp 0.6s ease-out forwards ${index * 0.05}s`;
            }, 10);
            visibleCount++;
        } else {
            card.style.display = 'none';
        }
    });

    // Mostrar mensaje si no hay resultados
    if (noResultados) {
        noResultados.style.display = visibleCount === 0 ? 'block' : 'none';
    }
}

async function verDetalleProducto(idProducto) {
    try {
        const response = await fetch(`${base_url}control/ProductoController.php?tipo=obtener_producto&id=${idProducto}`);
        const producto = await response.json();

        if (!producto || Object.keys(producto).length === 0) {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'No se pudo cargar el producto',
                background: '#6D3C52',
                color: '#FADCD5',
                confirmButtonColor: '#FADCD5',
                confirmButtonText: 'Cerrar'
            });
            return;
        }

        // Guardar producto actual para el modal
        productoActualModal = producto;

        // Llenar datos del modal
        document.getElementById('modalProductoTitulo').textContent = producto.nombre || 'Sin nombre';
        document.getElementById('modalProductoNombre').textContent = producto.nombre || 'Sin nombre';
        document.getElementById('modalProductoCategoria').textContent = producto.categoria || 'General';
        document.getElementById('modalProductoDetalle').textContent = producto.detalle || 'Sin descripción';
        document.getElementById('modalProductoPrecio').textContent = `S/ ${parseFloat(producto.precio || 0).toFixed(2)}`;
        document.getElementById('modalProductoStock').textContent = `${producto.stock || 0} disponibles`;
        
        // Imagen del producto
        const imgElement = document.getElementById('modalProductoImg');
        const rutaImagen = producto.imagen ? `${base_url}${producto.imagen}` : `${base_url}assets/img/no-image.png`;
        imgElement.src = rutaImagen;
        imgElement.alt = producto.nombre || 'Producto';

        // Abrir modal con animación
        const modal = new bootstrap.Modal(document.getElementById('modalDetalleProducto'));
        modal.show();

        // Agregar efecto de entrada
        const modalContent = document.querySelector('.modal-producto .modal-content');
        modalContent.style.animation = 'fadeInScale 0.4s ease-out';

    } catch (error) {
        console.error('Error al cargar producto:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Ocurrió un error al cargar el producto',
            background: '#6D3C52',
            color: '#FADCD5',
            confirmButtonColor: '#FADCD5',
            confirmButtonText: 'Cerrar'
        });
    }
}
// FUNCIONES DEL CARRITO
async function agregarAlCarrito(idProducto) {
    try {
        const response = await fetch(`${base_url}control/ProductoController.php?tipo=obtener_producto&id=${idProducto}`);
        const producto = await response.json();

        if (!producto || Object.keys(producto).length === 0) {
            mostrarNotificacion('Error al obtener el producto', 'error');
            return;
        }

        // Verificar stock
        if (producto.stock <= 0) {
            mostrarNotificacion('Producto sin stock disponible', 'warning');
            return;
        }

        // Buscar si el producto ya está en el carrito
        const indexExistente = carrito.findIndex(item => item.id === producto.id);

        if (indexExistente !== -1) {
            // Si existe, incrementar cantidad
            const cantidadActual = carrito[indexExistente].cantidad;
            
            if (cantidadActual >= producto.stock) {
                mostrarNotificacion('No hay más stock disponible', 'warning');
                return;
            }

            carrito[indexExistente].cantidad++;
        } else {
            // Si no existe, agregar nuevo
            carrito.push({
                id: producto.id,
                nombre: producto.nombre,
                precio: parseFloat(producto.precio),
                imagen: producto.imagen,
                cantidad: 1,
                stock: producto.stock
            });
        }

        // Guardar en localStorage
        localStorage.setItem('carrito', JSON.stringify(carrito));

        // Actualizar contador del carrito (si existe)
        actualizarContadorCarrito();

        // Animación del botón
        const btn = event.target.closest('button');
        if (btn) {
            btn.style.transform = 'scale(0.9)';
            setTimeout(() => {
                btn.style.transform = 'scale(1)';
            }, 200);
        }

        // Mostrar notificación
        mostrarNotificacion(`${producto.nombre} agregado al carrito`, 'success');

    } catch (error) {
        console.error('Error al agregar al carrito:', error);
        mostrarNotificacion('Error al agregar al carrito', 'error');
    }
}

function agregarAlCarritoDesdeModal() {
    if (productoActualModal && productoActualModal.id) {
        agregarAlCarrito(productoActualModal.id);
        
        // Cerrar modal después de agregar
        setTimeout(() => {
            const modalElement = document.getElementById('modalDetalleProducto');
            const modal = bootstrap.Modal.getInstance(modalElement);
            if (modal) {
                modal.hide();
            }
        }, 800);
    }
}

function actualizarContadorCarrito() {
    const contadorCarrito = document.getElementById('carritoContador');
    if (contadorCarrito) {
        const totalItems = carrito.reduce((sum, item) => sum + item.cantidad, 0);
        contadorCarrito.textContent = totalItems;
        
        // Animación del contador
        contadorCarrito.style.animation = 'none';
        setTimeout(() => {
            contadorCarrito.style.animation = 'pulse 0.5s ease-in-out';
        }, 10);
    }
}
// SISTEMA DE NOTIFICACIONES
function mostrarNotificacion(mensaje, tipo = 'info') {
    const iconos = {
        success: 'bi-check-circle-fill',
        error: 'bi-x-circle-fill',
        warning: 'bi-exclamation-triangle-fill',
        info: 'bi-info-circle-fill'
    };

    const colores = {
        success: '#28a745',
        error: '#dc3545',
        warning: '#ffc107',
        info: '#17a2b8'
    };

    // Crear elemento de notificación
    const notificacion = document.createElement('div');
    notificacion.className = 'notificacion-custom';
    notificacion.innerHTML = `
        <i class="bi ${iconos[tipo]}"></i>
        <span>${mensaje}</span>
    `;
    
    notificacion.style.cssText = `
        position: fixed;
        top: 20px;
        right: 20px;
        background: linear-gradient(135deg, ${colores[tipo]}, ${adjustColor(colores[tipo], -20)});
        color: white;
        padding: 15px 25px;
        border-radius: 50px;
        box-shadow: 0 8px 25px rgba(0, 0, 0, 0.3);
        z-index: 9999;
        display: flex;
        align-items: center;
        gap: 12px;
        font-weight: 600;
        animation: slideInRight 0.4s ease-out;
        max-width: 350px;
    `;

    document.body.appendChild(notificacion);

    // Eliminar después de 3 segundos
    setTimeout(() => {
        notificacion.style.animation = 'slideOutRight 0.4s ease-out';
        setTimeout(() => {
            notificacion.remove();
        }, 400);
    }, 3000);
}

// Función auxiliar para ajustar color
function adjustColor(color, amount) {
    const clamp = (val) => Math.min(Math.max(val, 0), 255);
    const num = parseInt(color.replace('#', ''), 16);
    const r = clamp((num >> 16) + amount);
    const g = clamp(((num >> 8) & 0x00FF) + amount);
    const b = clamp((num & 0x0000FF) + amount);
    return `#${((r << 16) | (g << 8) | b).toString(16).padStart(6, '0')}`;
}
// ANIMACIONES ADICIONALES
// Animación de scroll suave
document.querySelectorAll('a[href^="#"]').forEach(anchor => {
    anchor.addEventListener('click', function (e) {
        e.preventDefault();
        const target = document.querySelector(this.getAttribute('href'));
        if (target) {
            target.scrollIntoView({
                behavior: 'smooth',
                block: 'start'
            });
        }
    });
});

// Intersection Observer para animaciones al hacer scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observar tarjetas de productos
document.querySelectorAll('.producto-card').forEach(card => {
    observer.observe(card);
});
// EFECTOS DE HOVER MEJORADOS
document.querySelectorAll('.producto-card, .card-destacado').forEach(card => {
    card.addEventListener('mouseenter', function() {
        this.style.transition = 'all 0.4s cubic-bezier(0.4, 0, 0.2, 1)';
    });
});
// AGREGAR ESTILOS DE ANIMACIÓN AL DOM
const styleSheet = document.createElement('style');
styleSheet.textContent = `
    @keyframes slideInRight {
        from {
            transform: translateX(400px);
            opacity: 0;
        }
        to {
            transform: translateX(0);
            opacity: 1;
        }
    }

    @keyframes slideOutRight {
        from {
            transform: translateX(0);
            opacity: 1;
        }
        to {
            transform: translateX(400px);
            opacity: 0;
        }
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
        }
        50% {
            transform: scale(1.2);
        }
    }
`;
document.head.appendChild(styleSheet);

// ============================================
// INICIALIZACIÓN
// ============================================

document.addEventListener('DOMContentLoaded', function() {
    // Actualizar contador del carrito al cargar
    actualizarContadorCarrito();

    // Inicializar tooltips de Bootstrap si existen
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    console.log('Vista de productos cargada correctamente');
    console.log('Productos en carrito:', carrito.length);
});
// MANEJO DE ERRORES DE IMÁGENES
document.querySelectorAll('img').forEach(img => {
    img.addEventListener('error', function() {
        this.src = `${base_url}assets/img/no-image.png`;
        this.style.opacity = '0.5';
    });
});
// PREVENIR ZOOM EN MOBILE AL HACER DOBLE CLICK
let lastTouchEnd = 0;
document.addEventListener('touchend', function (event) {
    const now = (new Date()).getTime();
    if (now - lastTouchEnd <= 300) {
        event.preventDefault();
    }
    lastTouchEnd = now;
}, false);