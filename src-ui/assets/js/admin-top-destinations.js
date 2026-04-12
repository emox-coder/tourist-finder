const API_BASE = '../Backend/routes/api.php';

document.addEventListener('DOMContentLoaded', loadDestinations);
document.getElementById('destinationForm').addEventListener('submit', handleFormSubmit);

async function safeJSONParse(response) {
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const text = await response.text();
    const cleanText = text.replace(/^\uFEFF/, '').trim();
    try { return JSON.parse(cleanText); } catch (e) { throw new Error('Invalid JSON'); }
}

async function loadDestinations() {
    try {
        const response = await fetch(`${API_BASE}?uri=/api/admin/attractions`);
        const allAttractions = await safeJSONParse(response) || [];
        const topDestinations = allAttractions.filter(a => a.is_top_destination == 1);
        const cities = topDestinations.filter(a => a.category === 'city');
        const municipalities = topDestinations.filter(a => a.category === 'municipality');
        updateStats(cities, municipalities);
        renderTable(cities, municipalities);
    } catch (error) {
        showNotification('Error loading: ' + error.message, 'error');
    }
}

function updateStats(cities, municipalities) {
    document.getElementById('totalCount').textContent = cities.length + municipalities.length;
    document.getElementById('cityCount').textContent = `${cities.length}/6`;
    document.getElementById('municipalityCount').textContent = `${municipalities.length}/6`;
}

function renderTable(cities, municipalities) {
    const tbody = document.getElementById('destinationsTableBody');
    const emptyState = document.getElementById('emptyState');
    const table = document.getElementById('destinationsTable');

    if (cities.length === 0 && municipalities.length === 0) {
        table.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    emptyState.style.display = 'none';

    let html = '';
    const renderRow = (dest, type) => `
        <tr>
            <td><input type="number" value="${dest.display_order || 0}" onchange="updateOrder(${dest.id}, this.value)"></td>
            <td><img src="../${dest.image_url}" class="thumbnail"></td>
            <td><strong>${dest.name}</strong></td>
            <td>${dest.location}</td>
            <td><span class="badge badge-${type === 'city' ? 'success' : 'secondary'}">${type}</span></td>
            <td>
                <div class="actions">
                    <button class="btn btn-sm btn-warning" onclick="editDestination(${dest.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${dest.id})"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
    `;

    if (cities.length) {
        html += `<tr class="section-header"><td colspan="6">Cities (${cities.length}/6)</td></tr>`;
        cities.forEach(d => html += renderRow(d, 'city'));
    }
    if (municipalities.length) {
        html += `<tr class="section-header"><td colspan="6">Municipalities (${municipalities.length}/6)</td></tr>`;
        municipalities.forEach(d => html += renderRow(d, 'municipality'));
    }
    tbody.innerHTML = html;
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Destination';
    document.getElementById('destinationForm').reset();
    document.getElementById('destinationId').value = '';
    document.getElementById('imagePreview').classList.remove('show');
    document.getElementById('destinationModal').classList.add('show');
}

function closeModal() { document.getElementById('destinationModal').classList.remove('show'); }
function openDeleteModal(id) { document.getElementById('deleteId').value = id; document.getElementById('deleteModal').classList.add('show'); }
function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('show'); }

async function editDestination(id) {
    try {
        const response = await fetch(`${API_BASE}?uri=/api/admin/attractions/${id}`);
        const dest = await safeJSONParse(response);
        document.getElementById('destinationId').value = dest.id;
        document.getElementById('name').value = dest.name;
        document.getElementById('location').value = dest.location;
        document.getElementById('category').value = dest.category;
        document.getElementById('description').value = dest.description || '';
        document.getElementById('displayOrder').value = dest.display_order || 0;
        document.getElementById('currentImage').value = dest.image_url || '';
        document.getElementById('previewImg').src = `../${dest.image_url}`;
        document.getElementById('imagePreview').classList.add('show');
        document.getElementById('destinationModal').classList.add('show');
    } catch (e) { showNotification('Error loading', 'error'); }
}

async function handleFormSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = formData.get('id');
    const data = {
        name: formData.get('name'),
        location: formData.get('location'),
        category: formData.get('category'),
        description: formData.get('description'),
        display_order: parseInt(formData.get('displayOrder')),
        is_top_destination: 1,
        image_url: formData.get('currentImage')
    };
    const method = id ? 'PUT' : 'POST';
    const url = id ? `${API_BASE}?uri=/api/admin/attractions/${id}` : `${API_BASE}?uri=/api/admin/attractions`;
    
    try {
        const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
        const result = await safeJSONParse(res);
        if (result.success) { showNotification('Saved'); closeModal(); loadDestinations(); }
    } catch (e) { showNotification('Error saving', 'error'); }
}

async function confirmDelete() {
    const id = document.getElementById('deleteId').value;
    try {
        const res = await fetch(`${API_BASE}?uri=/api/admin/attractions/${id}`, { method: 'DELETE' });
        const result = await safeJSONParse(res);
        if (result.success) { showNotification('Deleted'); closeDeleteModal(); loadDestinations(); }
    } catch (e) { showNotification('Error deleting', 'error'); }
}

function updateOrder(id, order) {
    fetch(`${API_BASE}?uri=/api/admin/attractions/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ display_order: parseInt(order) })
    }).then(() => showNotification('Order updated'));
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        const reader = new FileReader();
        reader.onload = e => {
            document.getElementById('previewImg').src = e.target.result;
            document.getElementById('imagePreview').classList.add('show');
            uploadImage(file);
        };
        reader.readAsDataURL(file);
    }
}

async function uploadImage(file) {
    const formData = new FormData();
    formData.append('image', file);
    try {
        const res = await fetch('upload.php', { method: 'POST', body: formData });
        const result = await safeJSONParse(res);
        if (result.success) {
            document.getElementById('currentImage').value = result.path;
            showNotification('Uploaded');
        }
    } catch (e) { showNotification('Upload error', 'error'); }
}

function removeImage() {
    document.getElementById('imageInput').value = '';
    document.getElementById('imagePreview').classList.remove('show');
    document.getElementById('currentImage').value = '';
}
