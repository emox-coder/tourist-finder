const API_BASE = '../Backend/routes/api.php';

document.addEventListener('DOMContentLoaded', loadCards);
document.getElementById('cardForm').addEventListener('submit', handleFormSubmit);

async function safeJSONParse(response) {
    if (!response.ok) throw new Error(`HTTP ${response.status}`);
    const text = await response.text();
    const cleanText = text.replace(/^\uFEFF/, '').trim();
    try { return JSON.parse(cleanText); } catch (e) { throw new Error('Invalid JSON'); }
}

async function loadCards() {
    try {
        const response = await fetch(`${API_BASE}?uri=/api/admin/three-cards`);
        const cards = await safeJSONParse(response) || [];
        updateStats(cards);
        renderTable(cards);
        updatePreview(cards);
    } catch (error) {
        showNotification('Error loading: ' + error.message, 'error');
    }
}

function updateStats(cards) {
    document.getElementById('totalCount').textContent = cards.length;
    document.getElementById('activeCount').textContent = `${cards.length}/3`;
}

function renderTable(cards) {
    const tbody = document.getElementById('cardsTableBody');
    const emptyState = document.getElementById('emptyState');
    const table = document.getElementById('cardsTable');

    if (cards.length === 0) {
        table.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    emptyState.style.display = 'none';

    tbody.innerHTML = cards.map(card => `
        <tr>
            <td><input type="number" value="${card.display_order || 0}" onchange="updateOrder(${card.id}, this.value)"></td>
            <td><img src="../${card.image_url}" class="thumbnail"></td>
            <td><strong>${card.title}</strong></td>
            <td>${card.description ? card.description.substring(0, 50) + '...' : ''}</td>
            <td>
                <div class="actions">
                    <button class="btn btn-sm btn-warning" onclick="editCard(${card.id})"><i class="fas fa-edit"></i></button>
                    <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${card.id})"><i class="fas fa-trash"></i></button>
                </div>
            </td>
        </tr>
    `).join('');
}

function updatePreview(cards) {
    const container = document.getElementById('previewContainer');
    if (!cards.length) {
        container.innerHTML = '<p>No cards to preview</p>';
        return;
    }
    container.innerHTML = cards.map(card => `
        <div class="three-card-preview-item">
            <img src="../${card.image_url || 'assets/img/placeholder.svg'}">
            <h4>${card.title}</h4>
            <p>${card.description || ''}</p>
        </div>
    `).join('');
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Card';
    document.getElementById('cardForm').reset();
    document.getElementById('cardId').value = '';
    document.getElementById('imagePreview').classList.remove('show');
    document.getElementById('cardModal').classList.add('show');
}

function closeModal() { document.getElementById('cardModal').classList.remove('show'); }
function openDeleteModal(id) { document.getElementById('deleteId').value = id; document.getElementById('deleteModal').classList.add('show'); }
function closeDeleteModal() { document.getElementById('deleteModal').classList.remove('show'); }

async function editCard(id) {
    try {
        const response = await fetch(`${API_BASE}?uri=/api/admin/three-cards/${id}`);
        const card = await safeJSONParse(response);
        document.getElementById('cardId').value = card.id;
        document.getElementById('title').value = card.title;
        document.getElementById('description').value = card.description || '';
        document.getElementById('displayOrder').value = card.display_order || 0;
        document.getElementById('currentImage').value = card.image_url || '';
        document.getElementById('previewImg').src = `../${card.image_url}`;
        document.getElementById('imagePreview').classList.add('show');
        document.getElementById('cardModal').classList.add('show');
    } catch (e) { showNotification('Error loading', 'error'); }
}

async function handleFormSubmit(e) {
    e.preventDefault();
    const formData = new FormData(e.target);
    const id = formData.get('id');
    const data = {
        title: formData.get('title'),
        description: formData.get('description'),
        display_order: parseInt(formData.get('displayOrder')),
        image_url: formData.get('currentImage')
    };
    const method = id ? 'PUT' : 'POST';
    const url = id ? `${API_BASE}?uri=/api/admin/three-cards/${id}` : `${API_BASE}?uri=/api/admin/three-cards`;
    
    try {
        const res = await fetch(url, { method, headers: { 'Content-Type': 'application/json' }, body: JSON.stringify(data) });
        const result = await safeJSONParse(res);
        if (result.success) { showNotification('Saved'); closeModal(); loadCards(); }
    } catch (e) { showNotification('Error saving', 'error'); }
}

async function confirmDelete() {
    const id = document.getElementById('deleteId').value;
    try {
        const res = await fetch(`${API_BASE}?uri=/api/admin/three-cards/${id}`, { method: 'DELETE' });
        const result = await safeJSONParse(res);
        if (result.success) { showNotification('Deleted'); closeDeleteModal(); loadCards(); }
    } catch (e) { showNotification('Error deleting', 'error'); }
}

function updateOrder(id, order) {
    fetch(`${API_BASE}?uri=/api/admin/three-cards/${id}`, {
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
