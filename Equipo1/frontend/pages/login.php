<?php
session_start();
$logueado = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tareas | Iniciar sesión</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Serif:ital@0;1&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="../styles/style.css">
</head>
<body>
  <header class="site-header">
  <div class="header-inner">
    <a href="../../index.php" aria-label="Inicio de Tareas"><img class="logo" src="../img/logo-tareas.svg" alt="Logo Tareas"></a>
    <nav class="navbar" aria-label="Navegación principal">
      <a href="../../index.php">Inicio</a>
      <?php if ($logueado): ?>
        <a href="tareas.php">Tareas</a>
        <a href="calendario.php">Calendario</a>
        <a href="logout.php">Cerrar sesión</a>
      <?php else: ?>
        <a href="login.php">Iniciar sesión</a>
        <a href="registro.php">Registrarme</a>
      <?php endif; ?>
    </nav>
  </div>
</header>
  <main>
    <div class="page-shell auth-layout">
      <section class="auth-card">
        <article>
          <h1>Iniciar sesión</h1>
          <p class="subtitle">Accede a tu espacio de tareas.</p>
          <form id="loginForm">
            <div class="form-group">
              <label for="correo">Correo electrónico</label>
              <input id="correo" name="correo" type="email" required autocomplete="email">
            </div>
            <div class="form-group">
              <label for="password">Contraseña</label>
              <input id="password" name="password" type="password" required autocomplete="current-password">
            </div>
            <button class="btn btn-primary full" type="submit">Login</button>
            <div id="loginMessage" class="form-message" aria-live="polite"></div>
          </form>
        </article>
        <aside class="aside-note">
          ¿No tienes cuenta? <a href="registro.php"><strong>Regístrate aquí</strong></a>
        </aside>
      </section>
    </div>
  </main>
  <footer class="site-footer">
  <p>2026 Tareas-Proyecto Académico Desarrollado por Rubi, Sarahi y Gustavo</p>
</footer>
  <script src="../scripts/script.js"></script>
</body>
</html>