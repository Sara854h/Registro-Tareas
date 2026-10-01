<?php
session_start();
$logueado = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tareas | Registro</title>
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
          <h1>Regístrate</h1>
          <p class="subtitle">Crea tu cuenta para comenzar.</p>
          <form id="registerForm">
            <div class="form-group">
              <label for="nombre">Nombre</label>
              <input id="nombre" name="nombre" type="text" required>
            </div>
            <div class="form-group">
              <label for="correo">Correo electrónico</label>
              <input id="correo" name="correo" type="email" required>
            </div>
            <div class="form-group">
              <label for="password">Contraseña</label>
              <input id="password" name="password" type="password" minlength="6" required>
            </div>
            <div class="form-group">
              <label for="password2">Confirmar contraseña</label>
              <input id="password2" name="password2" type="password" minlength="6" required>
            </div>
            <button class="btn btn-primary full" type="submit">Registrarme</button>
            <div id="registerMessage" class="form-message" aria-live="polite"></div>
          </form>
        </article>
        <aside class="aside-note">Ya tienes una cuenta? <a href="login.php"><strong>Inicia sesión</strong></a></aside>
      </section>
    </div>
  </main>
  <footer class="site-footer">
  <p>2026 Tareas-Proyecto Académico Desarrollado por Rubi, Sarahi y Gustavo</p>
</footer>
  <script src="../scripts/script.js"></script>
</body>
</html>