<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: ../../index.php');
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Tareas | Calendario</title>
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
      <a href="tareas.php">Tareas</a>
      <a href="calendario.php">Calendario</a>
      <a href="logout.php">Cerrar sesión</a>
    </nav>
  </div>
</header>
  <main>
    <div class="page-shell">
      <section class="page-title">
        <h1>Calendario</h1>
        <p>Consulta tus fechas de entrega y el estado de cada actividad.</p>
      </section>

      <div class="calendar-layout">
        <section class="card calendar-card">
          <div class="calendar-header">
            <button id="previousMonth" class="btn btn-light" type="button" aria-label="Mes anterior">Anterior</button>
            <h2 id="monthLabel">Calendario</h2>
            <button id="nextMonth" class="btn btn-light" type="button" aria-label="Mes siguiente">Siguiente</button>
          </div>
          <div id="calendarGrid" class="calendar-grid"></div>
          <p id="calendarMessage" class="form-message" aria-live="polite"></p>
          <section>
            <h2>Tareas del mes</h2>
            <div id="calendarTasks"></div>
          </section>
        </section>

        <aside class="card">
          <h2>Estado de la tarea</h2>
          <article>
            <p class="task-meta">En cada tarea puedes abrir el botón de estado y elegir:</p>
            <div class="status-badge">Pendiente</div>
            <div class="status-badge">En proceso</div>
            <div class="status-badge">Finalizada</div>
            <p class="aside-note">El cambio se guarda mediante la API de tareas.</p>
          </article>
        </aside>
      </div>
    </div>
  </main>
  <footer class="site-footer">
  <p>2026 Tareas-Proyecto Académico Desarrollado por Rubi, Sarahi y Gustavo</p>
</footer>
  <script src="../scripts/script.js"></script>
</body>
</html>