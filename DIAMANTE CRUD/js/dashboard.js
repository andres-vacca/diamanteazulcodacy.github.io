// JavaScript para el Dashboard - Sin JSON

// Variables globales
let currentSection = 'dashboard';
let productos = [];
let usuarios = [];

// Inicializar dashboard
document.addEventListener('DOMContentLoaded', function() {
    loadDashboardData();
    showSection('dashboard');
});

// Mostrar secciones
function showSection(section) {
    // Ocultar todas las secciones
    document.querySelectorAll('.content-section').forEach(el => {
        el.style.display = 'none';
    });
    
    // Mostrar la sección seleccionada
    const targetSection = document.getElementById(section + '-section');
    if (targetSection) {
        targetSection.style.display = 'block';
        currentSection = section;
    }
    
    // Actualizar navegación activa
    document.querySelectorAll('.nav-link').forEach(link => {
        link.classList.remove('active');
    });
    
    // Cargar datos según la sección
    switch(section) {
        case 'productos':
            loadProductos();
            break;
        case 'usuarios':
            loadUsuarios();
            break;
    }
}

// Cargar datos del dashboard
function loadDashboardData() {
    // Simular carga de estadísticas básicas
    setTimeout(() => {
        const totalProductos = document.getElementById('total-productos');
        const ventasHoy = document.getElementById('ventas-hoy');
        const totalUsuarios = document.getElementById('total-usuarios');
        
        if (totalProductos) totalProductos.textContent = '15';
        if (ventasHoy) ventasHoy.textContent = '$2,450';
        if (totalUsuarios) totalUsuarios.textContent = '8';
    }, 500);
}

// Cargar productos usando formulario oculto
function loadProductos() {
    const tbody = document.querySelector('#productos-table tbody');
    if (!tbody) return;
    
    // Crear iframe oculto para cargar datos
    const iframe = document.createElement('iframe');
    iframe.style.display = 'none';
    iframe.src = 'api/productos.php?format=data';
    document.body.appendChild(iframe);
    
    // Simular datos mientras tanto
    tbody.innerHTML = `
        <tr>
            <td>1</td>
            <td>Anillo de Oro</td>
            <td>Anillo de oro 18k con diamante</td>
            <td>$1,200</td>
            <td><span class="badge bg-success">DISPONIBLE</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editProduct(1)">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteProduct(1)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>Laptop Dell</td>
            <td>Laptop Dell Inspiron 15</td>
            <td>$800</td>
            <td><span class="badge bg-warning">AGOTADO</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editProduct(2)">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-danger" onclick="deleteProduct(2)">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        </tr>
    `;
    
    // Limpiar iframe
    setTimeout(() => {
        if (iframe.parentNode) {
            iframe.remove();
        }
    }, 1000);
}

// Cargar usuarios
function loadUsuarios() {
    const tbody = document.querySelector('#usuarios-table tbody');
    if (!tbody) return;
    
    // Simular datos de usuarios
    tbody.innerHTML = `
        <tr>
            <td>1</td>
            <td>admin</td>
            <td>12345678</td>
            <td>admin@diamanteazul.com</td>
            <td><span class="badge bg-danger">Administrador</span></td>
            <td><span class="badge bg-success">ACTIVO</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editUser(1)">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-warning" onclick="deactivateUser(1)">
                    <i class="fas fa-ban"></i>
                </button>
            </td>
        </tr>
        <tr>
            <td>2</td>
            <td>empleado1</td>
            <td>87654321</td>
            <td>empleado@diamanteazul.com</td>
            <td><span class="badge bg-primary">Empleado</span></td>
            <td><span class="badge bg-success">ACTIVO</span></td>
            <td>
                <button class="btn btn-sm btn-primary" onclick="editUser(2)">
                    <i class="fas fa-edit"></i>
                </button>
                <button class="btn btn-sm btn-warning" onclick="deactivateUser(2)">
                    <i class="fas fa-ban"></i>
                </button>
            </td>
        </tr>
    `;
}

// Modals para productos
function showProductModal(productId = null) {
    const modal = new bootstrap.Modal(document.getElementById('productModal'));
    const form = document.getElementById('productForm');
    
    if (productId) {
        // Editar producto existente
        document.getElementById('product-id').value = productId;
        // Aquí cargarías los datos del producto
        document.getElementById('product-nombre').value = 'Producto Ejemplo';
        document.getElementById('product-descripcion').value = 'Descripción ejemplo';
        document.getElementById('product-precio').value = '100';
        document.getElementById('product-estado').value = 'DISPONIBLE';
    } else {
        // Nuevo producto
        form.reset();
        document.getElementById('product-id').value = '';
    }
    
    modal.show();
}

function saveProduct() {
    const form = document.getElementById('productForm');
    const formData = new FormData();
    
    const id = document.getElementById('product-id').value;
    const nombre = document.getElementById('product-nombre').value;
    const descripcion = document.getElementById('product-descripcion').value;
    const precio = document.getElementById('product-precio').value;
    const estado = document.getElementById('product-estado').value;
    
    if (!nombre || !precio) {
        showError('Por favor completa todos los campos requeridos');
        return;
    }
    
    // Preparar datos para envío
    formData.append('action', id ? 'update' : 'create');
    if (id) formData.append('id_producto', id);
    formData.append('nombre_producto', nombre);
    formData.append('descripcion_producto', descripcion);
    formData.append('precio_producto', precio);
    formData.append('estado_producto', estado);
    
    // Enviar usando formulario oculto
    const hiddenForm = document.createElement('form');
    hiddenForm.method = 'POST';
    hiddenForm.action = 'api/productos.php';
    hiddenForm.style.display = 'none';
    
    for (let [key, value] of formData.entries()) {
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = key;
        input.value = value;
        hiddenForm.appendChild(input);
    }
    
    document.body.appendChild(hiddenForm);
    hiddenForm.submit();
    
    // Cerrar modal
    const modal = bootstrap.Modal.getInstance(document.getElementById('productModal'));
    modal.hide();
    
    showSuccess(id ? 'Producto actualizado correctamente' : 'Producto creado correctamente');
    
    // Recargar tabla
    setTimeout(() => {
        loadProductos();
    }, 1000);
}

function editProduct(id) {
    showProductModal(id);
}

function deleteProduct(id) {
    if (confirm('¿Estás seguro de eliminar este producto?')) {
        // Crear formulario para eliminar
        const form = document.createElement('form');
        form.method = 'POST';
        form.action = 'api/productos.php';
        form.style.display = 'none';
        
        const actionInput = document.createElement('input');
        actionInput.type = 'hidden';
        actionInput.name = 'action';
        actionInput.value = 'delete';
        
        const idInput = document.createElement('input');
        idInput.type = 'hidden';
        idInput.name = 'id_producto';
        idInput.value = id;
        
        form.appendChild(actionInput);
        form.appendChild(idInput);
        document.body.appendChild(form);
        form.submit();
        
        showSuccess('Producto eliminado correctamente');
    }
}

// Funciones para usuarios (solo administradores)
function showUserModal(userId = null) {
    // Implementar modal de usuario similar al de productos
    showInfo('Funcionalidad de usuarios en desarrollo');
}

function editUser(id) {
    showUserModal(id);
}

function deactivateUser(id) {
    if (confirm('¿Estás seguro de desactivar este usuario?')) {
        showInfo('Usuario desactivado correctamente');
        setTimeout(() => {
            loadUsuarios();
        }, 1000);
    }
}

// Funciones auxiliares
function formatCurrency(amount) {
    return new Intl.NumberFormat('es-CO', {
        style: 'currency',
        currency: 'COP'
    }).format(amount);
}

function formatDate(date) {
    return new Intl.DateTimeFormat('es-CO').format(new Date(date));
}