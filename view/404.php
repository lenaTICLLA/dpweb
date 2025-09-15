<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>¡Oops! Página no encontrada (Error 404)</title>
    <link rel="stylesheet" href="view/error404.css">


</head>

<body>
    <div class="background-container">
        <div class="background-image"></div>
        <div class="background-overlay"></div>
    </div>

    <div class="error-container">
        <div class="glass-container">
            <div class="error-icon">
                <div class="number-404">
                    <span class="four">4</span>
                    <span class="zero">0</span>
                    <span class="four">4</span>
                </div>
            </div>

            <h1>¡Oops! Página No Encontrada</h1>
            <p class="error-message">
                La página que estás buscando parece haber desaparecido en las montañas.
                Puede que haya sido movida, eliminada o simplemente no existe.
            </p>

            <div class="error-suggestions">
                <h3>¿Qué puedes hacer?</h3>
                <ul>
                    <li>Verifica la URL en la barra de direcciones</li>
                    <li>Regresa a la página anterior</li>
                    <li>Ve a la página principal</li>
                    <li>Usa el buscador para encontrar lo que necesitas</li>
                </ul>
            </div>

            <div class="action-buttons">
                <button onclick="history.back()" class="btn-secondary">
                    <span>←</span> Página Anterior
                </button>
            </div>
        </div>
    </div>

    <script>
        // Función para el buscador
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.querySelector('.search-input');
            const searchBtn = document.querySelector('.search-btn');

            function performSearch() {
                const query = searchInput.value.trim();
                if (query) {
                    // Aquí puedes implementar tu lógica de búsqueda
                    // Por ejemplo, redirigir a una página de búsqueda
                    window.location.href = `/search?q=${encodeURIComponent(query)}`;
                }
            }

            searchBtn.addEventListener('click', performSearch);

            searchInput.addEventListener('keypress', function(e) {
                if (e.key === 'Enter') {
                    performSearch();
                }
            });
        });
    </script>
</body>

</html>