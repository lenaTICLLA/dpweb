document.addEventListener('DOMContentLoaded', function() {
    cargarProductos();
});

async function cargarProductos() {
    try {
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=buscar_Producto_venta', {
            method: 'POST',
            mode: 'cors',
            cache: 'no-cache',
        });

        let res  = await respuesta.json();
        let json = res.data;
        console.log("Productos recibidos:", json);


        // Cargar carousel de productos destacados (primeros 3 productos)
        cargarCarousel(json.slice(0, 3));

        let container = document.getElementById('productos-container');
        if (container) {
            container.innerHTML = '';

            if (json.length === 0) {
                container.innerHTML = '<div class="col-12"><p class="text-center">No hay productos disponibles.</p></div>';
                return;
            }

            json.forEach(producto => {
                let imagenSrc = producto.imagen ? base_url + producto.imagen : 'https://via.placeholder.com/300x200?text=Sin+Imagen';

                let card = document.createElement('div');
                card.className = 'col-lg-3 col-md-4 col-sm-6';
                card.innerHTML = `
                    <div class="producto-card">
                        <img src="${imagenSrc}" alt="${producto.nombre}" class="producto-imagen" onerror="this.src='https://via.placeholder.com/300x200?text=Imagen+No+Disponible'">
                        <div class="producto-info">
                            <div class="producto-nombre">${producto.nombre}</div>
                            <div class="producto-categoria">Categoría: ${producto.categoria || 'Sin categoría'}</div>
                            <div class="producto-precio">Precio: S/ ${parseFloat(producto.precio).toFixed(2)}</div>
                            <div class="botones">
                                <button class="btn-ver-detalles"><i class="bi bi-info-circle"></i>Ver Detalles</button>
                                <button class="btn-anadir-carrito"><i class="bi bi-cart4"></i>Añadir a Carrito</button>
                            </div>
                        </div>
                    </div>
                `;
                container.appendChild(card);
            });
        }
    } catch (e) {
        console.error("Error al cargar productos:", e);
        let container = document.getElementById('productos-container');
        if (container) {
            container.innerHTML = '<div class="col-12"><p class="text-center text-danger">Error al cargar los productos.</p></div>';
        }
    }
}

function cargarCarousel(productos) {
    let carouselInner = document.querySelector('#productos-carousel .carousel-inner');
    let carouselIndicators = document.querySelector('#productos-carousel .carousel-indicators');
    if (!carouselInner || !carouselIndicators || productos.length === 0) return;

    carouselInner.innerHTML = '';
    carouselIndicators.innerHTML = '';

    // Crear items del carousel (mostrar 1 producto por slide)
    productos.forEach((producto, index) => {
        // Crear indicador
        let indicator = document.createElement('button');
        indicator.type = 'button';
        indicator.setAttribute('data-bs-target', '#productos-carousel');
        indicator.setAttribute('data-bs-slide-to', index);
        indicator.setAttribute('aria-label', `Slide ${index + 1}`);
        if (index === 0) {
            indicator.className = 'active';
            indicator.setAttribute('aria-current', 'true');
        }
        carouselIndicators.appendChild(indicator);

        // Crear item del carousel
        let item = document.createElement('div');
        item.className = `carousel-item ${index === 0 ? 'active' : ''}`;

        let imagenSrc = producto.imagen ? base_url + producto.imagen : 'https://via.placeholder.com/800x400?text=Sin+Imagen';

        item.innerHTML = `
            <img src="${imagenSrc}" class="d-block w-100 producto-imagen-carousel-full" alt="${producto.nombre}" onerror="this.src='https://via.placeholder.com/800x400?text=Imagen+No+Disponible'">
            <div class="carousel-caption d-none d-md-block producto-info-carousel-full">
                <h5 class="producto-nombre-carousel-full">${producto.nombre}</h5>
                <p class="producto-categoria-carousel-full">Categoría: ${producto.categoria || 'Sin categoría'}</p>
                <p class="producto-precio-carousel-full">Precio: S/ ${parseFloat(producto.precio).toFixed(2)}</p>
            </div>
        `;

        carouselInner.appendChild(item);
    });
}