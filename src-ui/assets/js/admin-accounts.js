const API_BASE = '../Backend/routes/api.php';

document.addEventListener('DOMContentLoaded', loadAdmins);
document.getElementById('adminForm').addEventListener('submit', handleFormSubmit);

async function safeJSONParse(response) {
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const text = await response.text();
    const cleanText = text.replace(/^\uFEFF/, '').trim();
    try { return JSON.parse(cleanText); } catch (e) { throw new Error('Invalid JSON'); }
}

async function loadAdmins() {
    try {
        const response = await fetch(`${API_BASE}?uri=/api/admin/admins`);
        const admins = await safeJSONParse(response) || [];
        updateStats(admins);
        renderTable(admins);
    } catch (error) {
        showNotification('Error loading admins: ' + error.message, 'error');
    }
}

function updateStats(admins) {
    document.getElementById('totalAdmins').textContent = admins.length;
    document.getElementById('activeAdmins').textContent = admins.length; // Simplified
}

function renderTable(admins) {
    const tbody = document.getElementById('adminsTableBody');
    const emptyState = document.getElementById('emptyState');
    const table = document.getElementById('adminsTable');

    if (admins.length === 0) {
        table.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    emptyState.style.display = 'none';

    tbody.innerHTML = admins.map(admin => `
        <tr>
            <td><strong>${admin.username}</strong></td>
            <td>${admin.email}</td>
            <td><span class="badge badge-info">${admin.role || 'Admin'}</span></td>
            <td><span class="badge badge-success">Active</span></td>
            <td>${admin.created_at || 'N/A'}</td>
            <td>
                <div class="actions">
                    <button class="btn btn-sm btn-warning" onclick="editAdmin(${admin.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${admin.id})"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
    `).join('');
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Admin Account';
    document.getElementById('adminForm').reset();
    document.getElementById('adminId').value = '';
    document.getElementById('password').required = true;
    document.getElementById('adminModal').classList.add('show');
}

function closeModal() { document.getElementById('adminModal').classList.remove('show'); }
function openDeleteModal(id) { document.getElementById('deleteId').value = id; document.getElementById('deleteModal').classList.add('show'); }
function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('show'); }

async function editAdmin(id) {
    try {
        const response = await fetch(`${API_BASE}?uri=/api/admin/admins/${id}`);
        const admin = await safeJSONParse(response);
        document.getElementById('modalTitle').textContent = 'Edit Admin Account';
        document.getElementById('adminId').value = admin.id;
        document.getElementById('username').value = admin.username;
        document.getElementById('email').value = admin.email;
        document.getElementById('role').value = admin.role || 'admin';
        document.getElementById('password').required = false; // Optional on edit
        document.getElementById('adminModal').classList.add('show');
    } catch (e) { showNotification('Error loading', 'error'); }
}

async function handleFormSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = formData.get('id');
    const data = {
        username: formData.get('username'),
        email: formData.get('email'),
        role: formData.get('role')
    };
    if (formData.get('password')) data.password = formData.get('password');

    const method = id ? 'PUT' : 'POST';
    const url = id ? `${API_BASE}?uri=/api/admin/admins/${id}` : `${API_BASE}?uri=/api/admin/admins`;
    
    try {
        const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
        const result = await safeJSONParse(res);
        if (result.success) { showNotification('Saved successfully'); closeModal(); loadAdmins(); }
    } catch (e) { showNotification('Error saving', 'error'); }
}

async function confirmDelete() {
    const id = document.getElementById('deleteId').value;
    try {
        const res = await fetch(`${API_BASE}?uri=/api/admin/admins/${id}`, { method: 'DELETE' });
        const result = await safeJSONParse(res);
        if (result.success) { showNotification('Deleted successfully'); closeDeleteModal(); loadAdmins(); }
    } catch (e) { showNotification('Error deleting', 'error'); }
}
