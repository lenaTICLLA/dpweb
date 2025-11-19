document.addEventListener('DOMContentLoaded', function() {
    cargarProductos();

    let inputBusqueda = document.getElementById('busqueda_venta');
    if (inputBusqueda) {
        inputBusqueda.addEventListener('keyup', function () {
            let dato = this.value.trim();
            buscarProductos(dato);
        });
    }
});

async function cargarProductos() {
    try {
        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=buscar_Producto_venta', {
            method: 'POST'
        });

        let res = await respuesta.json();
        let productos = res.data || [];

        console.log("Productos iniciales:", productos);

        // Carousel solo en carga inicial
        cargarCarousel(productos.slice(0, 3));

        renderProductos(productos);

    } catch (error) {
        console.error("Error al cargar productos:", error);
    }
}

async function buscarProductos(dato) {
    try {
        const formData = new FormData();
        formData.append("dato", dato);

        let respuesta = await fetch(base_url + 'control/ProductoController.php?tipo=buscar_Producto_venta', {
            method: 'POST',
            body: formData
        });

        let res = await respuesta.json();
        let productos = res.data || [];

        console.log("Resultados de búsqueda:", productos);

        renderProductos(productos);

    } catch (error) {
        console.error("Error en búsqueda:", error);
    }
}
function renderProductos(productos) {
    let container = document.getElementById('productos-container');
    if (!container) return;

    container.innerHTML = '';

    if (!productos || productos.length === 0) {
        container.innerHTML = '<p class="text-center">No se encontraron productos.</p>';
        return;
    }

    productos.forEach(producto => {
        let imagenSrc = producto.imagen
            ? base_url + producto.imagen
            : 'https://via.placeholder.com/300x200?text=Sin+Imagen';

        let card = document.createElement('div');
        card.className = "col-lg-3 col-md-4 col-sm-6";

        card.innerHTML = `
            <div class="producto-card">
                <img src="${imagenSrc}" class="producto-imagen" alt="${producto.nombre}"
                     onerror="this.src='https://via.placeholder.com/300x200?text=Imagen+No+Disponible'">

                <div class="producto-info">
                    <div class="producto-nombre">${producto.nombre}</div>
                    <div class="producto-categoria">Categoría: ${producto.categoria || "Sin categoría"}</div>
                    <div class="producto-precio">Precio: S/ ${isFinite(parseFloat(producto.precio)) ? parseFloat(producto.precio).toFixed(2) : '0.00'}</div>
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

function cargarCarousel(productos) {
    let carouselInner = document.querySelector('#productos-carousel .carousel-inner');
    let carouselIndicators = document.querySelector('#productos-carousel .carousel-indicators');

    if (!carouselInner || !carouselIndicators) return;

    carouselInner.innerHTML = '';
    carouselIndicators.innerHTML = '';

    productos.forEach((producto, index) => {
        let imagenSrc = producto.imagen
            ? base_url + producto.imagen
            : 'https://via.placeholder.com/800x400?text=Sin+Imagen';

        // Indicadores
        let indicator = document.createElement('button');
        indicator.type = "button";
        indicator.dataset.bsTarget = "#productos-carousel";
        indicator.dataset.bsSlideTo = index;
        indicator.setAttribute("aria-label", `Slide ${index + 1}`);

        if (index === 0) {
            indicator.classList.add("active");
            indicator.setAttribute("aria-current", "true");
        }

        carouselIndicators.appendChild(indicator);

        // Slides
        let item = document.createElement('div');
        item.className = `carousel-item ${index === 0 ? "active" : ""}`;

        item.innerHTML = `
            <img src="${imagenSrc}" class="d-block w-100 producto-imagen-carousel-full"
                onerror="this.src='https://via.placeholder.com/800x400?text=Imagen+No+Disponible'">

            <div class="carousel-caption d-none d-md-block producto-info-carousel-full">
                <h5>${producto.nombre}</h5>
                <p>Categoría: ${producto.categoria || "Sin categoría"}</p>
                <p>Precio: S/ ${parseFloat(producto.precio).toFixed(2)}</p>
            </div>
        `;

        carouselInner.appendChild(item);
    });
}




