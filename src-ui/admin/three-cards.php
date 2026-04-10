<?php
require_once 'includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Three Cards - TAF Admin</title>
    <link rel="stylesheet" href="../assets/css/landing-page.css">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
    <style>
        .admin-layout {
            display: flex;
            min-height: 100vh;
        }
        
        .sidebar {
            width: 250px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            padding: 20px;
            color: white;
        }
        
        .sidebar-logo {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 30px;
        }
        
        .sidebar-logo img {
            width: 40px;
            height: 40px;
        }
        
        .sidebar-nav a {
            display: block;
            padding: 10px 15px;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            margin-bottom: 5px;
            transition: background 0.3s;
        }
        
        .sidebar-nav a:hover, .sidebar-nav a.active {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .main-content {
            flex: 1;
            padding: 30px;
            background: #f5f5f5;
        }
        
        .admin-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        
        .admin-user {
            display: flex;
            align-items: center;
            gap: 15px;
        }
        
        .logout-btn {
            padding: 8px 16px;
            background: #dc3545;
            color: white;
            text-decoration: none;
            border-radius: 6px;
            transition: background 0.3s;
        }
        
        .logout-btn:hover {
            background: #c82333;
        }
        
        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .stat-card h3 {
            font-size: 28px;
            color: #667eea;
            margin-bottom: 5px;
        }
        
        .stat-card p {
            color: #7f8c8d;
            font-size: 14px;
        }
        
        .admin-card {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.05);
        }
        
        .btn {
            padding: 10px 20px;
            border: none;
            border-radius: 6px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 15px rgba(102, 126, 234, 0.4);
        }
        
        .btn-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .btn-danger {
            background: #dc3545;
            color: white;
        }
        
        .btn-sm {
            padding: 6px 12px;
            font-size: 12px;
        }
        
        .table-container {
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
        }
        
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #e0e0e0;
        }
        
        th {
            background: #f8f9fa;
            font-weight: 600;
            color: #2c3e50;
        }
        
        .thumbnail {
            width: 60px;
            height: 40px;
            object-fit: cover;
            border-radius: 4px;
        }
        
        .actions {
            display: flex;
            gap: 8px;
        }
        
        .modal-overlay {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            display: none;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        
        .modal-overlay.show {
            display: flex;
        }
        
        .modal {
            background: white;
            padding: 30px;
            border-radius: 10px;
            max-width: 600px;
            width: 90%;
            max-height: 90vh;
            overflow-y: auto;
        }
        
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
        }
        
        .modal-close {
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }
        
        .form-group {
            margin-bottom: 20px;
        }
        
        .form-group label {
            display: block;
            margin-bottom: 5px;
            font-weight: 500;
            color: #333;
        }
        
        .form-group input,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
        }
        
        .form-group textarea {
            resize: vertical;
            min-height: 100px;
        }
        
        .image-upload-area {
            border: 2px dashed #ddd;
            padding: 40px;
            text-align: center;
            cursor: pointer;
            border-radius: 6px;
            transition: border-color 0.3s;
        }
        
        .image-upload-area:hover {
            border-color: #667eea;
        }
        
        .image-preview {
            margin-top: 15px;
            display: none;
        }
        
        .image-preview.show {
            display: block;
        }
        
        .image-preview img {
            max-width: 100%;
            height: auto;
            border-radius: 6px;
        }
        
        .empty-state {
            text-align: center;
            padding: 60px 20px;
            color: #666;
        }
        
        .empty-state i {
            font-size: 48px;
            margin-bottom: 20px;
            color: #ddd;
        }
        
        .notification {
            position: fixed;
            top: 20px;
            right: 20px;
            padding: 15px 20px;
            border-radius: 6px;
            color: white;
            font-weight: 500;
            z-index: 2000;
            display: none;
        }
        
        .notification.show {
            display: block;
        }
        
        .notification.success {
            background: #28a745;
        }
        
        .notification.error {
            background: #dc3545;
        }
        
        .three-cards-preview {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
            margin-bottom: 30px;
        }
        
        .three-card-preview-item {
            border: 2px solid #e0e0e0;
            border-radius: 10px;
            padding: 20px;
            text-align: center;
            min-height: 200px;
            background: #f8f9fa;
        }
        
        .three-card-preview-item img {
            width: 100%;
            height: 120px;
            object-fit: cover;
            border-radius: 8px;
            margin-bottom: 15px;
        }
        
        .three-card-preview-item h4 {
            margin-bottom: 10px;
            color: #2c3e50;
        }
        
        .three-card-preview-item p {
            font-size: 14px;
            color: #7f8c8d;
        }
    </style>
</head>
<body>
    <div class="admin-layout">
        <aside class="sidebar">
            <div class="sidebar-logo">
                <img src="../assets/img/logo.png" alt="TAF">
                <h2>TAF Admin</h2>
            </div>
            <nav class="sidebar-nav">
                <a href="top-destinations.php">
                    <i class="fas fa-star"></i>
                    Top Destinations
                </a>
                <a href="three-cards.php" class="active">
                    <i class="fas fa-th-large"></i>
                    Three Cards
                </a>
                <a href="attractions.php">
                    <i class="fas fa-map-marker-alt"></i>
                    All Attractions
                </a>
                <a href="admin-accounts.php">
                    <i class="fas fa-users-cog"></i>
                    Admin Accounts
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="admin-header">
                <h1>Manage Three Cards</h1>
                <div class="admin-user">
                    <span id="admin-email"><?php echo getAdminEmail(); ?></span>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="admin-card">
                <h2 style="color: #2c3e50; margin-bottom: 20px;">Live Preview</h2>
                <div class="three-cards-preview" id="previewContainer">
                    <!-- Preview will be loaded here -->
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3 id="totalCount">0</h3>
                    <p>Total Cards</p>
                </div>
                <div class="stat-card">
                    <h3 id="activeCount">0/3</h3>
                    <p>Active Cards (3 Max)</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="admin-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="color: #2c3e50;">Three Cards Management</h2>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i>
                        Add Card
                    </button>
                </div>

                <div class="table-container">
                    <table id="cardsTable">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Image</th>
                                <th>Title</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="cardsTableBody">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="fas fa-th-large"></i>
                    <h3>No Cards Yet</h3>
                    <p>Add your first three cards to showcase on the landing page</p>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i>
                        Add Your First Card
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal-overlay" id="cardModal">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Card</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>

            <form id="cardForm">
                <input type="hidden" id="cardId" name="id">

                <div class="form-group">
                    <label for="title">Title *</label>
                    <input type="text" id="title" name="title" required placeholder="e.g., Adventure Awaits">
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Brief description of the card content"></textarea>
                </div>

                <div class="form-group">
                    <label for="displayOrder">Display Order</label>
                    <input type="number" id="displayOrder" name="displayOrder" value="0" min="0" placeholder="0 = first">
                </div>

                <div class="form-group">
                    <label>Card Image</label>
                    <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">
                        <i class="fas fa-cloud-upload-alt"></i>
                        <p>Click to upload image (JPG, PNG, WebP)</p>
                        <p style="font-size: 12px; margin-top: 5px;">Max size: 5MB</p>
                    </div>
                    <input type="file" id="imageInput" accept="image/*" style="display: none;" onchange="previewImage(event)">
                    <div class="image-preview" id="imagePreview">
                        <img id="previewImg" src="" alt="Preview">
                        <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()" style="margin-top: 10px;">
                            <i class="fas fa-trash"></i> Remove
                        </button>
                    </div>
                    <input type="hidden" id="currentImage" name="currentImage">
                </div>

                <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                    <button type="button" class="btn btn-secondary" onclick="closeModal()" style="background: #95a5a6; color: white;">
                        Cancel
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i>
                        Save Card
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div class="modal-overlay" id="deleteModal">
        <div class="modal" style="max-width: 400px;">
            <div class="modal-header">
                <h2>Confirm Delete</h2>
                <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
            </div>
            <p style="color: #7f8c8d; margin-bottom: 20px;">Are you sure you want to delete this card? This action cannot be undone.</p>
            <input type="hidden" id="deleteId">
            <div style="display: flex; gap: 10px; justify-content: flex-end;">
                <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()" style="background: #95a5a6; color: white;">
                    Cancel
                </button>
                <button type="button" class="btn btn-danger" onclick="confirmDelete()">
                    <i class="fas fa-trash"></i>
                    Delete
                </button>
            </div>
        </div>
    </div>

    <!-- Notification -->
    <div class="notification" id="notification"></div>

    <script>
        // API Base URL
        const API_BASE = '../Backend/routes/api.php';

        // Load cards on page load
        document.addEventListener('DOMContentLoaded', loadCards);

        // Form submission
        document.getElementById('cardForm').addEventListener('submit', handleFormSubmit);

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

        async function loadCards() {
            try {
                const response = await fetch(`${API_BASE}?uri=/api/admin/three-cards`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const text = await response.text();
                const cleanText = text.replace(/^\uFEFF/, '').trim();
                const cards = JSON.parse(cleanText);

                updateStats(cards);
                renderTable(cards);
                updatePreview(cards);
            } catch (error) {
                console.error('Error loading cards:', error);
                showNotification('Error loading cards: ' + error.message, 'error');
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
                    <td>
                        <input type="number" value="${card.display_order || 0}" 
                               onchange="updateOrder(${card.id}, this.value)" 
                               style="width: 60px; padding: 5px; border: 1px solid #e0e0e0; border-radius: 4px;">
                    </td>
                    <td>
                        ${card.image_url ? 
                            `<img src="../${card.image_url}" class="thumbnail" alt="${card.title}">` :
                            '<div style="width: 60px; height: 40px; background: #e0e0e0; border-radius: 8px; display: flex; align-items: center; justify-content: center;"><i class="fas fa-image" style="color: #95a5a6;"></i></div>'
                        }
                    </td>
                    <td><strong>${card.title}</strong></td>
                    <td>${card.description ? card.description.substring(0, 50) + '...' : 'No description'}</td>
                    <td>
                        <div class="actions">
                            <button class="btn btn-sm btn-warning" onclick="editCard(${card.id})">
                                <i class="fas fa-edit"></i> Edit
                            </button>
                            <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${card.id})">
                                <i class="fas fa-trash"></i> Delete
                            </button>
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function updatePreview(cards) {
            const container = document.getElementById('previewContainer');
            
            if (cards.length === 0) {
                container.innerHTML = '<p style="text-align: center; color: #666; grid-column: 1/-1;">No cards to preview</p>';
                return;
            }

            container.innerHTML = cards.map(card => `
                <div class="three-card-preview-item">
                    ${card.image_url ? 
                        `<img src="../${card.image_url || 'assets/img/placeholder.svg'}" alt="${card.title}" onerror="this.src='../assets/img/placeholder.svg'">` :
                        `<div style="width: 100%; height: 120px; background: #e0e0e0; border-radius: 8px; display: flex; align-items: center; justify-content: center; margin-bottom: 15px;">
                            <i class="fas fa-image" style="color: #95a5a6; font-size: 24px;"></i>
                        </div>`
                    }
                    <h4>${card.title}</h4>
                    <p>${card.description || 'No description'}</p>
                </div>
            `).join('');
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Card';
            document.getElementById('cardForm').reset();
            document.getElementById('cardId').value = '';
            document.getElementById('currentImage').value = '';
            document.getElementById('imagePreview').classList.remove('show');
            document.getElementById('cardModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('cardModal').classList.remove('show');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        async function editCard(id) {
            try {
                const response = await fetch(`../Backend/routes/api.php?uri=/api/admin/three-cards/${id}`);
                const card = await safeJSONParse(response);

                if (!card || card.error) {
                    showNotification('Card not found', 'error');
                    return;
                }

                document.getElementById('modalTitle').textContent = 'Edit Card';
                document.getElementById('cardId').value = card.id;
                document.getElementById('title').value = card.title;
                document.getElementById('description').value = card.description || '';
                document.getElementById('displayOrder').value = card.display_order || 0;
                document.getElementById('currentImage').value = card.image_url || '';

                if (card.image_url) {
                    document.getElementById('previewImg').src = `../${card.image_url}`;
                    document.getElementById('imagePreview').classList.add('show');
                } else {
                    document.getElementById('imagePreview').classList.remove('show');
                }

                document.getElementById('cardModal').classList.add('show');
            } catch (error) {
                console.error('Error loading card:', error);
                showNotification('Error loading card: ' + error.message, 'error');
            }
        }

        function checkThreeCardLimit() {
            // Get current cards from the table
            const rows = document.querySelectorAll('#cardsTableBody tr');
            return rows.length >= 3;
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const id = formData.get('id');
            
            // Check if adding new record (no ID) and limit is reached
            if (!id && checkThreeCardLimit()) {
                showNotification('Cannot add more cards. Limit of 3 reached. Please delete existing cards first.', 'error');
                return;
            }

            const data = {
                title: formData.get('title'),
                description: formData.get('description'),
                display_order: parseInt(formData.get('displayOrder')),
                image_url: formData.get('currentImage')
            };

            const url = id ? `../Backend/routes/api.php?uri=/api/admin/three-cards/${id}` : '../Backend/routes/api.php?uri=/api/admin/three-cards';
            const method = id ? 'PUT' : 'POST';

            try {
                const response = await fetch(url, {
                    method: method,
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify(data)
                });

                const result = await safeJSONParse(response);

                if (result.success) {
                    showNotification(id ? 'Card updated successfully' : 'Card added successfully', 'success');
                    closeModal();
                    loadCards();
                } else {
                    showNotification('Error saving card', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error saving card: ' + error.message, 'error');
            }
        }

        async function confirmDelete() {
            const id = document.getElementById('deleteId').value;

            try {
                const response = await fetch(`../Backend/routes/api.php?uri=/api/admin/three-cards/${id}`, {
                    method: 'DELETE'
                });
                
                const result = await safeJSONParse(response);
                
                if (result.success) {
                    showNotification('Card deleted successfully', 'success');
                    closeDeleteModal();
                    loadCards();
                } else {
                    showNotification('Error deleting card', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error deleting card: ' + error.message, 'error');
            }
        }

        function updateOrder(id, order) {
            fetch(`../Backend/routes/api.php?uri=/api/admin/three-cards/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    title: document.querySelector(`#cardsTableBody tr:nth-child(${parseInt(order) + 1}) td:nth-child(3)`).textContent,
                    description: '',
                    display_order: parseInt(order),
                    image_url: ''
                })
            })
            .then(response => safeJSONParse(response))
            .then(result => {
                if (result.success) {
                    showNotification('Order updated', 'success');
                    loadCards();
                }
            })
            .catch(error => console.error('Error:', error));
        }

        function previewImage(event) {
            const file = event.target.files[0];
            if (file) {
                // Validate file size (5MB max)
                if (file.size > 5 * 1024 * 1024) {
                    showNotification('File size must be less than 5MB', 'error');
                    return;
                }

                const reader = new FileReader();
                reader.onload = function(e) {
                    document.getElementById('previewImg').src = e.target.result;
                    document.getElementById('imagePreview').classList.add('show');

                    // Upload image
                    uploadImage(file);
                };
                reader.readAsDataURL(file);
            }
        }

        async function uploadImage(file) {
            const formData = new FormData();
            formData.append('image', file);

            try {
                const response = await fetch('upload.php', {
                    method: 'POST',
                    body: formData
                });

                const result = await safeJSONParse(response);

                if (result.success) {
                    document.getElementById('currentImage').value = result.path;
                    showNotification('Image uploaded successfully', 'success');
                } else {
                    showNotification(result.message || 'Error uploading image', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error uploading image: ' + error.message, 'error');
            }
        }

        function removeImage() {
            document.getElementById('imageInput').value = '';
            document.getElementById('imagePreview').classList.remove('show');
            document.getElementById('currentImage').value = '';
        }

        function showNotification(message, type) {
            const notification = document.getElementById('notification');
            notification.textContent = message;
            notification.className = `notification ${type} show`;

            setTimeout(() => {
                notification.classList.remove('show');
            }, 3000);
        }

        // Close modals when clicking outside
        document.querySelectorAll('.modal-overlay').forEach(overlay => {
            overlay.addEventListener('click', (e) => {
                if (e.target === overlay) {
                    overlay.classList.remove('show');
                }
            });
        });
    </script>
</body>
</html>
