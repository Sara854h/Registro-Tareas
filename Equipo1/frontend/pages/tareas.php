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
  <title>Tareas | Gestión</title>
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
        <h1>Gestión de tareas</h1>
        <p>Agrega, edita y elimina tus actividades.</p>
      </section>

      <div class="dashboard-layout">
        <aside class="card">
          <h2 id="formTitle">Agregar nueva tarea</h2>
          <form id="taskForm" class="task-form">
            <input type="hidden" id="taskId">
            <div class="form-group">
              <label for="nombre_tarea">Nombre de la tarea</label>
              <input id="nombre_tarea" name="nombre_tarea" type="text" required>
            </div>
            <div class="form-group">
              <label for="descripcion">Descripción</label>
              <textarea id="descripcion" name="descripcion" placeholder="Descripción breve"></textarea>
            </div>
            <div class="form-group">
              <label for="fecha_entrega">Fecha de entrega</label>
              <input id="fecha_entrega" name="fecha_entrega" type="date" required>
            </div>
            <div class="form-group">
              <label for="prioridad">Prioridad</label>
              <select id="prioridad" name="prioridad" required>
                <option value="baja">Baja</option>
                <option value="media" selected>Media</option>
                <option value="alta">Alta</option>
              </select>
            </div>
            <div class="button-row">
              <button class="btn btn-primary" type="submit">Agregar</button>
              <button class="btn btn-light" type="button" id="editFormBtn">Editar</button>
              <button class="btn btn-danger" type="button" id="deleteFormBtn">Eliminar</button>
            </div>
            <div id="taskMessage" class="form-message" aria-live="polite"></div>
          </form>
          <button id="logoutBtn" class="btn btn-danger full" type="button">Cerrar sesión</button>
        </aside>

        <section class="card">
          <article>
            <h2>Mis tareas</h2>
            <div id="taskList" class="task-list" aria-live="polite"></div>
          </article>
        </section>
      </div>
    </div>
  </main>
  <footer class="site-footer">
  <p>2026 Tareas-Proyecto Académico Desarrollado por Rubi, Sarahi y Gustavo</p>
</footer>
  <script src="../scripts/script.js"></script>
</body>
</html>