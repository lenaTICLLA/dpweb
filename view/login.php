<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="view/login.css">
    <script>
    const base_url = '<?= BASE_URL; ?>';
    </script>
</head>
<body>
    <div class="background-container">
        <div class="background-image"></div>
        <div class="background-overlay"></div>
    </div>
    
    <div class="login-container">
        <div class="glass-container">
            <h2>Iniciar Sesión</h2>
            <form class="login-form"  id="frm_login">
                <div class="input-group">
                    <input type="text" id="username" name="username" required>
                    <label for="username">Usuario</label>
                </div>
                
                <div class="input-group">
                    <input type="password" id="password" name="password" required>
                    <label for="password">Contraseña</label>
                </div>
                
                <div class="remember-forgot">
                    <label class="checkbox-container">
                        <input type="checkbox" id="remember">
                        <span class="checkmark"></span>
                        Recuérdame
                    </label>
                    <a href="#" class="forgot-password">¿Olvidaste tu contraseña?</a>
                </div>
                
            <button type="submit" class="login-btn">Ingresar</button>
                
                <p class="register-link">¿No tienes una cuenta? <a href="#">Regístrate</a></p>
            </form>
        </div>
    </div>
    <script src="<?= BASE_URL; ?>view/function/user.js"></script>
    <script src="<?= BASE_URL; ?>view/function/sesion.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
</body>
</html>
