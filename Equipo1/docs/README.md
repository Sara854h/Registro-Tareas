# Tareas — Proyecto Académico

Sitio web de gestión de tareas desarrollado únicamente con PHP, HTML, CSS y JavaScript.

## Arquitectura

```text
Equipo1/
├── frontend/
│   ├── pages/
│   │   ├── login.php
│   │   ├── registro.php
│   │   ├── tareas.php
│   │   └── calendario.php
│   ├── styles/style.css
│   ├── scripts/script.js
│   └── img/logo-tareas.svg
├── backend/
│   ├── services/TaskService.php
│   ├── models/Task.php
│   ├── controllers/TaskController.php
│   ├── connections/connection.php
│   └── apis/
│       ├── tasks.php
│       └── auth.php
├── docs/
│   ├── database.sql
│   └── README.md
└── index.php
```

## Ejecución con PHP y MariaDB

1. Asegúrate de tener MariaDB y PHP instalados.
2. Verifica que el usuario de MariaDB sea `equipo1` con contraseña `159753`.
3. Importa `docs/database.sql` en MariaDB.
4. Desde la carpeta donde está `index.php`, ejecuta:
   `php -S 127.0.0.1:8000 -t .`
5. Abre:
   `http://127.0.0.1:8000/`

## API de tareas

- `GET /backend/apis/tasks.php` — listar tareas.
- `GET /backend/apis/tasks.php?id=1` — obtener una tarea.
- `POST /backend/apis/tasks.php` — crear una tarea.
- `PUT /backend/apis/tasks.php?id=1` — editar una tarea.
- `DELETE /backend/apis/tasks.php?id=1` — eliminar una tarea.

Ejemplo POST:

```json
{
  "nombre_tarea": "Entregar proyecto",
  "descripcion": "Subir el proyecto académico",
  "fecha_entrega": "2026-10-15",
  "prioridad": "alta"
}
```

## Estados

Los estados permitidos son:

- pendiente
- en proceso
- finalizada

## Nota académica

La base de datos agrega el campo `descripcion` para conservar el dato solicitado en el formulario de tareas.

## Páginas

1. `index.php` — bienvenida con botones de inicio de sesión y registro.
2. `login.php` — correo/usuario, contraseña y acceso.
3. `registro.php` — nombre, correo, contraseña y registro.
4. `tareas.php` — alta, edición y eliminación de tareas.
5. `calendario.php` — calendario, nombre de tarea y selector de estado: pendiente, en proceso y finalizada.


## Control de acceso

- Sin sesión iniciada, el menú no muestra `Tareas` ni `Calendario`.
- `tareas.php` y `calendario.php` redirigen al inicio si no existe una sesión válida.
- La API `tasks.php` devuelve HTTP 401 si se intenta usar sin iniciar sesión.
- `logout.php` destruye la sesión y regresa al inicio.

## Base de datos utilizada

- Servidor: `localhost`
- Base de datos: `tareas_db`
- Usuario: `equipo1`
- Contraseña: `159753`
