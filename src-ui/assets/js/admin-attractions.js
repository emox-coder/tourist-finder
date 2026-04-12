// API Base URL
const API_BASE = '../Backend/routes/api.php';

// Global variables
let allAttractions = [];
let filteredAttractions = [];

// Load attractions on page load
document.addEventListener('DOMContentLoaded', loadAttractions);

// Form submission
document.getElementById('attractionForm').addEventListener('submit', handleFormSubmit);

// Helper function for safe JSON parsing with BOM handling
async function safeJSONParse(response) {
    if (!response.ok) {
        throw new Error(`HTTP ${response.status}: ${response.statusText}`);
    }
    
    const text = await response.text();
    const cleanText = text.replace(/^\uFEFF/, '').trim();
    
    try {
        return JSON.parse(cleanText);
    } catch (parseError) {
        console.error('JSON parse error:', parseError);
        console.error('Response text:', cleanText);
        throw new Error('Invalid server response: ' + cleanText.substring(0, 100));
    }
}

async function loadAttractions() {
    try {
        const [attractionsResponse, threeCardsResponse] = await Promise.all([
            fetch(`${API_BASE}?uri=/api/admin/attractions`),
            fetch(`${API_BASE}?uri=/api/admin/three-cards`)
        ]);
        
        const attractions = await safeJSONParse(attractionsResponse) || [];
        const threeCards = await safeJSONParse(threeCardsResponse) || [];

        allAttractions = [
            ...attractions.map(a => ({...a, type: 'attraction'})),
            ...threeCards.map(c => ({
                ...c, 
                type: 'three_card',
                name: c.title,
                location: 'Three Cards Section',
                category: 'special',
                is_top_destination: 2 
            }))
        ];
        
        filteredAttractions = [...allAttractions];
        updateStats();
        renderTable();
    } catch (error) {
        console.error('Error loading attractions:', error);
        showNotification('Error loading attractions: ' + error.message, 'error');
    }
}

function updateStats() {
    document.getElementById('totalCount').textContent = filteredAttractions.length;
    document.getElementById('topDestinationsCount').textContent = filteredAttractions.filter(a => a.is_top_destination == 1).length;
    document.getElementById('threeCardsCount').textContent = filteredAttractions.filter(a => a.is_top_destination == 2).length;
    document.getElementById('regularCount').textContent = filteredAttractions.filter(a => a.is_top_destination == 0).length;
}

function renderTable() {
    const tbody = document.getElementById('attractionsTableBody');
    const emptyState = document.getElementById('emptyState');
    const table = document.getElementById('attractionsTable');

    if (filteredAttractions.length === 0) {
        table.style.display = 'none';
        emptyState.style.display = 'block';
        return;
    }

    table.style.display = 'table';
    emptyState.style.display = 'none';

    tbody.innerHTML = filteredAttractions.map(attraction => `
        <tr>
            <td>
                <input type="number" value="${attraction.display_order || 0}" 
                       onchange="updateOrder(${attraction.id}, this.value)" 
                       style="width: 60px; padding: 5px; border: 1px solid #e0e0e0; border-radius: 4px;">
            </td>
            <td>
                ${attraction.image_url ? 
                    `<img src="../${attraction.image_url}" class="thumbnail" alt="${attraction.name}">` :
                    '<div style="width: 60px; height: 40px; background: #e0e0e0; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image" style="color: #95a5a6;"></i></div>'
                }
            </td>
            <td><strong>${attraction.name}</strong></td>
            <td>${attraction.location}</td>
            <td>
                <span class="badge badge-${attraction.category === 'city' ? 'success' : attraction.category === 'municipality' ? 'secondary' : 'info'}">
                    ${attraction.category.charAt(0).toUpperCase() + attraction.category.slice(1)}
                </span>
            </td>
            <td>
                ${attraction.is_top_destination == 1 ? 
                    '<span class="badge top-destination-badge">Top Destination</span>' :
                    attraction.is_top_destination == 2 ?
                    '<span class="badge three-cards-badge">Three Cards</span>' :
                    '<span class="badge badge-info">Regular</span>'
                }
            </td>
            <td>
                <div class="actions">
                    <button class="btn btn-sm btn-warning" onclick="editAttraction(${attraction.id}, '${attraction.type}')">
                        <i class="fas fa-edit"></i> Edit
                    </button>
                    <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${attraction.id}, '${attraction.type}')">
                        <i class="fas fa-trash"></i> Delete
                    </button>
                </div>
            </td>
        </tr>
    `).join('');
}

function filterAttractions() {
    const categoryFilter = document.getElementById('categoryFilter').value;
    const typeFilter = document.getElementById('typeFilter').value;
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();

    filteredAttractions = allAttractions.filter(attraction => {
        if (categoryFilter && attraction.category !== categoryFilter) return false;
        if (typeFilter !== '') {
            if (typeFilter === '2' && attraction.is_top_destination != 2) return false;
            if (typeFilter === '1' && attraction.is_top_destination != 1) return false;
            if (typeFilter === '0' && attraction.is_top_destination != 0) return false;
        }
        if (searchTerm) {
            const searchFields = [attraction.name, attraction.location, attraction.description || ''];
            if (!searchFields.some(f => f.toLowerCase().includes(searchTerm))) return false;
        }
        return true;
    });

    updateStats();
    renderTable();
}

function openAddModal() {
    document.getElementById('modalTitle').textContent = 'Add New Attraction';
    document.getElementById('attractionForm').reset();
    document.getElementById('attractionId').value = '';
    document.getElementById('currentImage').value = '';
    document.getElementById('imagePreview').classList.remove('show');
    document.getElementById('attractionModal').classList.add('show');
}

function closeModal() {
    document.getElementById('attractionModal').classList.remove('show');
}

function openDeleteModal(id, type) {
    const deleteIdInput = document.getElementById('deleteId');
    deleteIdInput.value = id;
    deleteIdInput.setAttribute('data-type', type);
    document.getElementById('deleteModal').classList.add('show');
}

function closeDeleteModal() {
    document.getElementById('deleteModal').classList.remove('show');
}

async function editAttraction(id, type) {
    if (type === 'three_card') {
        window.location.href = `three-cards.php?edit=${id}`;
        return;
    }

    try {
        const response = await fetch(`${API_BASE}?uri=/api/admin/attractions/${id}`);
        const attraction = await safeJSONParse(response);

        document.getElementById('modalTitle').textContent = 'Edit Attraction';
        document.getElementById('attractionId').value = attraction.id;
        document.getElementById('name').value = attraction.name;
        document.getElementById('location').value = attraction.location;
        document.getElementById('category').value = attraction.category;
        document.getElementById('description').value = attraction.description || '';
        document.getElementById('displayOrder').value = attraction.display_order || 0;
        document.getElementById('isTopDestination').checked = attraction.is_top_destination == 1;
        document.getElementById('currentImage').value = attraction.image_url || '';

        const previewImg = document.getElementById('previewImg');
        const imagePreview = document.getElementById('imagePreview');
        if (attraction.image_url) {
            previewImg.src = `../${attraction.image_url}`;
            imagePreview.classList.add('show');
        } else {
            imagePreview.classList.remove('show');
        }

        document.getElementById('attractionModal').classList.add('show');
    } catch (error) {
        showNotification('Error loading attraction: ' + error.message, 'error');
    }
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
        is_top_destination: formData.get('isTopDestination') ? 1 : 0,
        image_url: formData.get('currentImage')
    };

    const url = id ? `${API_BASE}?uri=/api/admin/attractions/${id}` : `${API_BASE}?uri=/api/admin/attractions`;
    const method = id ? 'PUT' : 'POST';

    try {
        const response = await fetch(url, {
            method: method,
            headers: { 'Content-Type': 'application/json' },
            body: JSON.stringify(data)
        });

        const result = await safeJSONParse(response);
        if (result.success) {
            showNotification(id ? 'Attraction updated' : 'Attraction added');
            closeModal();
            loadAttractions();
        }
    } catch (error) {
        showNotification('Error saving attraction: ' + error.message, 'error');
    }
}

async function confirmDelete() {
    const deleteIdInput = document.getElementById('deleteId');
    const id = deleteIdInput.value;
    const type = deleteIdInput.getAttribute('data-type');
    const endpoint = type === 'three_card' ? 'three-cards' : 'attractions';

    try {
        const response = await fetch(`${API_BASE}?uri=/api/admin/${endpoint}/${id}`, { method: 'DELETE' });
        const result = await safeJSONParse(response);
        
        if (result.success) {
            showNotification('Deleted successfully');
            closeDeleteModal();
            loadAttractions();
        }
    } catch (error) {
        showNotification('Error deleting: ' + error.message, 'error');
    }
}

function updateOrder(id, order) {
    // Basic implementation for order update
    fetch(`${API_BASE}?uri=/api/admin/attractions/${id}`, {
        method: 'PUT',
        headers: { 'Content-Type': 'application/json' },
        body: JSON.stringify({ display_order: parseInt(order) })
    })
    .then(() => showNotification('Order updated'))
    .catch(err => console.error(err));
}

function previewImage(event) {
    const file = event.target.files[0];
    if (file) {
        if (file.size > 5 * 1024 * 1024) {
            showNotification('File size must be less than 5MB', 'error');
            return;
        }

        const reader = new FileReader();
        reader.onload = function(e) {
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
        const response = await fetch('upload.php', { method: 'POST', body: formData });
        const result = await safeJSONParse(response);

        if (result.success) {
            document.getElementById('currentImage').value = result.path;
            showNotification('Image uploaded');
        } else {
            showNotification(result.message || 'Upload error', 'error');
        }
    } catch (error) {
        showNotification('Upload error: ' + error.message, 'error');
    }
}

function removeImage() {
    document.getElementById('imageInput').value = '';
    document.getElementById('imagePreview').classList.remove('show');
    document.getElementById('currentImage').value = '';
}

// Modal closing logic
document.querySelectorAll('.modal-overlay').forEach(overlay => {
    overlay.addEventListener('click', (e) => {
        if (e.target === overlay) overlay.classList.remove('show');
    });
});
