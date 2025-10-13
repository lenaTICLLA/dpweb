<?php
session_start();
// si no hay sesión activa, redirige al login
if (!isset($_SESSION['usuario'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Inicio | LENA</title>
    <link rel="stylesheet" href="<?php echo BASE_URL; ?>view/bootstrap/css/bootstrap.min.css">

    <style>
        /* 🔸 Estilo global */
        body {
            margin: 0;
            font-family: 'Poppins', sans-serif;
            background: url('<?php echo BASE_URL; ?>view/img/montañas.jpg') no-repeat center center/cover;
            height: 100vh;
            color: white;
            display: flex;
            flex-direction: column;
        }

        /* 🔸 Navbar transparente con efecto vidrio */
        .navbar {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(12px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.2);
        }

        .navbar-brand {
            color: white !important;
            font-weight: 600;
            letter-spacing: 1px;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.9) !important;
            transition: color 0.3s;
        }

        .nav-link:hover {
            color: #ffb6c1 !important;
        }

        /* 🔸 Contenedor principal */
        .container-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
            align-items: center;
            text-align: center;
            backdrop-filter: blur(20px);
            background: rgba(255, 255, 255, 0.1);
            border-radius: 20px;
            padding: 40px 60px;
            margin: 50px auto;
            box-shadow: 0 0 30px rgba(0, 0, 0, 0.25);
            max-width: 600px;
        }

        .container-content h1 {
            font-size: 2.5em;
            font-weight: 600;
            margin-bottom: 15px;
            text-shadow: 0 2px 4px rgba(0,0,0,0.3);
        }

        .container-content p {
            font-size: 1.1em;
            color: #f2f2f2;
        }

        /* 🔸 Botón */
        .btn-custom {
            background: rgba(255, 255, 255, 0.2);
            border: none;
            color: white;
            padding: 12px 30px;
            margin-top: 20px;
            border-radius: 25px;
            font-size: 1.1em;
            transition: 0.3s;
        }

        .btn-custom:hover {
            background: rgba(255, 255, 255, 0.4);
            transform: scale(1.05);
        }

        /* 🔸 Footer */
        footer {
            text-align: center;
            padding: 10px;
            font-size: 0.9em;
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            color: #ddd;
            border-top: 1px solid rgba(255, 255, 255, 0.2);
        }
    </style>

    <script>
        const base_url = '<?php echo BASE_URL; ?>';
    </script>
</head>

<body>
    <!-- NAVBAR -->
    <nav class="navbar navbar-expand-lg">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">LENA</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                    <li class="nav-item"><a class="nav-link active" href="<?= BASE_URL ?>home">Inicio</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>users">Usuarios</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>products">Productos</a></li>
                    <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>category">Categorías</a></li>
                </ul>

                <ul class="navbar-nav">
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle" href="#" data-bs-toggle="dropdown">Menú</a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="#">Perfil</a></li>
                            <li><a class="dropdown-item" href="#">Ajustes</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="#">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- CONTENIDO -->
    <div class="container-content">
        <h1>Bienvenida, LENA 🌸</h1>
        <p>Has iniciado sesión correctamente en tu sistema MVC.</p>
        <p>Selecciona una opción en el menú para comenzar.</p>
        <button class="btn btn-custom">Explorar</button>
    </div>

    <!-- FOOTER -->
    <footer>
        &copy; <?= date('Y') ?> LENA | Sistema MVC
    </footer>

    <script src="<?php echo BASE_URL; ?>view/bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
