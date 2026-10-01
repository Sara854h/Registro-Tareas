const API = {
    tasks: "../../backend/apis/tasks.php",
    auth: "../../backend/apis/auth.php"
};

document.addEventListener("DOMContentLoaded", () => {
    initNavigation();
    initLogin();
    initRegister();
    initTaskPage();
    initCalendarPage();
});

function initNavigation() {
    const current = window.location.pathname.split("/").pop() || "index.php";
    document.querySelectorAll(".navbar a").forEach(link => {
        const target = link.getAttribute("href").split("/").pop();
        if (target === current) link.classList.add("active");
    });
}

function showMessage(element, message, type = "error") {
    if (!element) return;
    element.textContent = message;
    element.className = `form-message ${type}`;
}

async function request(url, options = {}) {
    const response = await fetch(url, {
        headers: { "Content-Type": "application/json", ...(options.headers || {}) },
        ...options
    });
    const data = await response.json().catch(() => ({}));
    if (!response.ok) throw new Error(data.message || "No fue posible completar la solicitud.");
    return data;
}

function initLogin() {
    const form = document.getElementById("loginForm");
    if (!form) return;
    const message = document.getElementById("loginMessage");

    form.addEventListener("submit", async event => {
        event.preventDefault();
        const payload = {
            action: "login",
            correo: form.correo.value.trim(),
            password: form.password.value
        };

        try {
            const data = await request(API.auth, {
                method: "POST",
                body: JSON.stringify(payload)
            });
            sessionStorage.setItem("tareasUser", JSON.stringify(data.user));
            showMessage(message, "Inicio de sesión correcto.", "success");
            setTimeout(() => window.location.href = "tareas.php", 500);
        } catch (error) {
            showMessage(message, error.message);
        }
    });
}

function initRegister() {
    const form = document.getElementById("registerForm");
    if (!form) return;
    const message = document.getElementById("registerMessage");

    form.addEventListener("submit", async event => {
        event.preventDefault();
        if (form.password.value !== form.password2.value) {
            showMessage(message, "Las contraseñas no coinciden.");
            return;
        }

        try {
            const data = await request(API.auth, {
                method: "POST",
                body: JSON.stringify({
                    action: "register",
                    nombre: form.nombre.value.trim(),
                    correo: form.correo.value.trim(),
                    password: form.password.value
                })
            });
            showMessage(message, data.message, "success");
            form.reset();
            setTimeout(() => window.location.href = "login.php", 800);
        } catch (error) {
            showMessage(message, error.message);
        }
    });
}

function getCurrentUser() {
    try {
        return JSON.parse(sessionStorage.getItem("tareasUser")) || null;
    } catch { return null; }
}

function initTaskPage() {
    const form = document.getElementById("taskForm");
    if (!form) return;

    const list = document.getElementById("taskList");
    const message = document.getElementById("taskMessage");
    const idField = document.getElementById("taskId");
    const title = document.getElementById("formTitle");

    document.getElementById("editFormBtn").addEventListener("click", () => {
        if (!idField.value) {
            showMessage(message, "Selecciona una tarea de la lista para editar.");
            return;
        }
        form.requestSubmit();
    });

    document.getElementById("deleteFormBtn").addEventListener("click", async () => {
        if (!idField.value) {
            showMessage(message, "Selecciona una tarea de la lista para eliminar.");
            return;
        }
        await deleteTask(idField.value, list, message);
        resetTaskForm(form, idField, title);
    });

    document.getElementById("logoutBtn")?.addEventListener("click", async () => {
        try {
            await request(API.auth, {
                method: "POST",
                body: JSON.stringify({ action: "logout" })
            });
        } catch (error) {
            console.error(error);
        }
        sessionStorage.removeItem("tareasUser");
        window.location.href = "../../index.php";
    });

    form.addEventListener("submit", async event => {
        event.preventDefault();
        const id = idField.value;
        const payload = {
            nombre_tarea: form.nombre_tarea.value.trim(),
            descripcion: form.descripcion.value.trim(),
            fecha_entrega: form.fecha_entrega.value,
            prioridad: form.prioridad.value
        };

        try {
            if (id) {
                await request(`${API.tasks}?id=${id}`, { method: "PUT", body: JSON.stringify(payload) });
                showMessage(message, "Tarea actualizada.", "success");
            } else {
                await request(API.tasks, { method: "POST", body: JSON.stringify(payload) });
                showMessage(message, "Tarea agregada.", "success");
            }
            resetTaskForm(form, idField, title);
            loadTasks(list, message);
        } catch (error) {
            showMessage(message, error.message);
        }
    });

    loadTasks(list, message);
}

function resetTaskForm(form, idField, title) {
    form.reset();
    idField.value = "";
    title.textContent = "Agregar nueva tarea";
    // El formulario conserva los tres botones solicitados: Agregar, Editar y Eliminar.
}

async function loadTasks(list, message) {
    try {
        const data = await request(API.tasks);
        list.innerHTML = "";
        if (!data.tasks || data.tasks.length === 0) {
            list.innerHTML = `<div class="empty-state">No hay tareas registradas todavía.</div>`;
            return;
        }

        data.tasks.forEach(task => {
            const item = document.createElement("article");
            item.className = "task-item";
            item.innerHTML = `
                <div>
                    <h3>${escapeHtml(task.nombre_tarea)}</h3>
                    <div class="task-meta">${escapeHtml(task.fecha_entrega || "Sin fecha")} · Prioridad: ${escapeHtml(task.prioridad)}</div>
                    <span class="status-badge">${escapeHtml(task.estado)}</span>
                </div>
                <div class="button-row">
                    <button class="btn btn-light edit-task" data-id="${task.id}">Editar</button>
                    <button class="btn btn-danger delete-task" data-id="${task.id}">Eliminar</button>
                </div>`;
            list.appendChild(item);
        });

        list.querySelectorAll(".edit-task").forEach(button => {
            button.addEventListener("click", () => editTask(button.dataset.id));
        });

        list.querySelectorAll(".delete-task").forEach(button => {
            button.addEventListener("click", () => deleteTask(button.dataset.id, list, message));
        });
    } catch (error) {
        showMessage(message, error.message);
    }
}

async function editTask(id) {
    try {
        const data = await request(`${API.tasks}?id=${id}`);
        const task = data.task;
        const form = document.getElementById("taskForm");
        document.getElementById("taskId").value = task.id;
        form.nombre_tarea.value = task.nombre_tarea || "";
        form.descripcion.value = task.descripcion || "";
        form.fecha_entrega.value = task.fecha_entrega || "";
        form.prioridad.value = task.prioridad || "media";
        document.getElementById("formTitle").textContent = "Editar tarea";
        window.scrollTo({ top: 0, behavior: "smooth" });
    } catch (error) {
        showMessage(document.getElementById("taskMessage"), error.message);
    }
}

async function deleteTask(id, list, message) {
    if (!confirm("¿Deseas eliminar esta tarea?")) return;
    try {
        await request(`${API.tasks}?id=${id}`, { method: "DELETE" });
        showMessage(message, "Tarea eliminada.", "success");
        loadTasks(list, message);
    } catch (error) {
        showMessage(message, error.message);
    }
}

function initCalendarPage() {
    const grid = document.getElementById("calendarGrid");
    if (!grid) return;

    const monthLabel = document.getElementById("monthLabel");
    const previous = document.getElementById("previousMonth");
    const next = document.getElementById("nextMonth");
    const tasksContainer = document.getElementById("calendarTasks");
    let currentDate = new Date();
    let allTasks = [];

    async function renderCalendar() {
        try {
            const data = await request(API.tasks);
            allTasks = data.tasks || [];
        } catch (error) {
            document.getElementById("calendarMessage").textContent = error.message;
            allTasks = [];
        }

        const year = currentDate.getFullYear();
        const month = currentDate.getMonth();
        monthLabel.textContent = currentDate.toLocaleDateString("es-MX", { month: "long", year: "numeric" });

        grid.innerHTML = "";
        ["Lun", "Mar", "Mié", "Jue", "Vie", "Sáb", "Dom"].forEach(day => {
            const header = document.createElement("div");
            header.className = "calendar-day-name";
            header.textContent = day;
            grid.appendChild(header);
        });

        const firstDay = new Date(year, month, 1);
        const offset = (firstDay.getDay() + 6) % 7;
        const daysInMonth = new Date(year, month + 1, 0).getDate();
        const prevDays = new Date(year, month, 0).getDate();

        for (let i = offset - 1; i >= 0; i--) {
            grid.appendChild(createCalendarCell(prevDays - i, true));
        }

        for (let day = 1; day <= daysInMonth; day++) {
            const cell = createCalendarCell(day, false);
            const dateKey = `${year}-${String(month + 1).padStart(2,"0")}-${String(day).padStart(2,"0")}`;

            allTasks.filter(t => t.fecha_entrega === dateKey).forEach(task => {
                const taskEl = document.createElement("span");
                taskEl.className = "calendar-task";
                taskEl.textContent = task.nombre_tarea;
                taskEl.title = `${task.nombre_tarea} — ${task.estado}`;
                cell.appendChild(taskEl);
            });
            grid.appendChild(cell);
        }

        const totalCells = grid.children.length - 7;
        const remaining = (7 - (totalCells % 7)) % 7;
        for (let day = 1; day <= remaining; day++) {
            grid.appendChild(createCalendarCell(day, true));
        }

        renderMonthTasks(year, month);
    }

    function renderMonthTasks(year, month) {
        tasksContainer.innerHTML = "";
        const monthTasks = allTasks.filter(task => {
            if (!task.fecha_entrega) return false;
            const date = new Date(`${task.fecha_entrega}T00:00:00`);
            return date.getFullYear() === year && date.getMonth() === month;
        });

        if (!monthTasks.length) {
            tasksContainer.innerHTML = `<div class="empty-state">No hay tareas para este mes.</div>`;
            return;
        }

        monthTasks.forEach(task => {
            const card = document.createElement("article");
            card.className = "calendar-task-card";
            card.innerHTML = `
                <div>
                    <h3>${escapeHtml(task.nombre_tarea)}</h3>
                    <p>Fecha de entrega: ${escapeHtml(task.fecha_entrega)}</p>
                </div>
                <div class="status-control">
                    <label for="status-${task.id}">Estado</label>
                    <select id="status-${task.id}" class="status-select" data-task-id="${task.id}">
                        <option value="pendiente" ${task.estado === "pendiente" ? "selected" : ""}>Pendiente</option>
                        <option value="en proceso" ${task.estado === "en proceso" ? "selected" : ""}>En proceso</option>
                        <option value="finalizada" ${task.estado === "finalizada" ? "selected" : ""}>Finalizada</option>
                    </select>
                </div>`;
            tasksContainer.appendChild(card);
        });

        tasksContainer.querySelectorAll(".status-select").forEach(select => {
            select.addEventListener("change", async () => {
                try {
                    await request(`${API.tasks}?id=${select.dataset.taskId}`, {
                        method: "PUT",
                        body: JSON.stringify({ estado: select.value })
                    });
                    document.getElementById("calendarMessage").textContent = "Estado actualizado correctamente.";
                    renderCalendar();
                } catch (error) {
                    document.getElementById("calendarMessage").textContent = error.message;
                }
            });
        });
    }

    function createCalendarCell(day, muted) {
        const cell = document.createElement("div");
        cell.className = `calendar-cell${muted ? " muted" : ""}`;
        const number = document.createElement("div");
        number.className = "day-number";
        number.textContent = day;
        cell.appendChild(number);
        return cell;
    }

    previous.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() - 1);
        renderCalendar();
    });

    next.addEventListener("click", () => {
        currentDate.setMonth(currentDate.getMonth() + 1);
        renderCalendar();
    });

    renderCalendar();
}

function escapeHtml(value) {
    return String(value ?? "").replace(/[&<>"']/g, char => ({
        "&":"&amp;", "<":"&lt;", ">":"&gt;", '"':"&quot;", "'":"&#039;"
    }[char]));
}
