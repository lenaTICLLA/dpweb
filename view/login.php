<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Iniciar Sesión</title>
    <link rel="stylesheet" href="view/login.css">
</head>
<body>
        <div class="background-container">
        <div class="background-image"></div>
        <div class="background-overlay"></div>
    </div>
    
    <div class="login-container">
        <div class="glass-container">
            <h2>Login</h2>
            <form class="login-form">
                <div class="input-group">
                    <input type="text" id="username" required>
                    <label for="username">Username</label>
                </div>
                
                <div class="input-group">
                    <input type="password" id="password" required>
                    <label for="password">Password</label>
                </div>
                
                <div class="remember-forgot">
                    <label class="checkbox-container">
                        <input type="checkbox" id="remember">
                        <span class="checkmark"></span>
                        Remember me
                    </label>
                    <a href="#" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
                
                <p class="register-link">Don't have an account? <a href="#">Register</a></p>
            </form>
        </div>
    </div>
</body>
</html>