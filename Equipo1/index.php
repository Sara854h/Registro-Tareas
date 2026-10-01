<?php
session_start();
$logueado = isset($_SESSION['user_id']);
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tareas | Inicio</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Cormorant+Garamond:ital,wght@0,300..700;1,300..700&family=Instrument+Serif:ital@0;1&family=Roboto:ital,wght@0,100..900;1,100..900&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="frontend/styles/style.css">
</head>
<body>
  <header class="site-header">
    <div class="header-inner">
      <a href="index.php" aria-label="Inicio de Tareas"><img class="logo" src="frontend/img/logo-tareas.svg" alt="Logo Tareas"></a>
      <nav class="navbar" aria-label="Navegación principal">
        <a href="index.php" class="active">Inicio</a>
        <?php if ($logueado): ?>
          <a href="frontend/pages/tareas.php">Tareas</a>
          <a href="frontend/pages/calendario.php">Calendario</a>
          <a href="frontend/pages/logout.php">Cerrar sesión</a>
        <?php else: ?>
          <a href="frontend/pages/login.php">Iniciar sesión</a>
          <a href="frontend/pages/registro.php">Registrarme</a>
        <?php endif; ?>
      </nav>
    </div>
  </header>

  <main>
    <div class="page-shell hero">
      <section class="hero-card">
        <article>
          <h1>Bienvenido a Tareas</h1>
          <p>Organiza tus actividades, consulta tus fechas de entrega y controla el estado de cada tarea desde un solo lugar.</p>
          <div class="button-row">
            <?php if ($logueado): ?>
              <a class="btn btn-primary" href="frontend/pages/tareas.php">Gestionar tareas</a>
              <a class="btn btn-secondary" href="frontend/pages/calendario.php">Ver calendario</a>
            <?php else: ?>
              <a class="btn btn-primary" href="frontend/pages/login.php">Iniciar sesión</a>
              <a class="btn btn-secondary" href="frontend/pages/registro.php">Registrarme</a>
            <?php endif; ?>
          </div>
        </article>
        <aside class="aside-note">Proyecto académico de gestión de tareas.</aside>
      </section>
    </div>
  </main>
  <footer class="site-footer">
  <p>2026 Tareas-Proyecto Académico Desarrollado por Rubi, Sarahi y Gustavo</p>
</footer>
  <script src="frontend/scripts/script.js"></script>
</body>
</html>