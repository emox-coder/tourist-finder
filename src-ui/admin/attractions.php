<?php
require_once 'includes/auth.php';
requireLogin();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>All Attractions - TAF Admin</title>
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
        
        .filter-controls {
            display: flex;
            gap: 15px;
            margin-bottom: 20px;
            flex-wrap: wrap;
        }
        
        .filter-group {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .filter-group label {
            font-weight: 500;
            color: #555;
        }
        
        .filter-group select,
        .filter-group input {
            padding: 8px 12px;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 14px;
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
        .form-group textarea,
        .form-group select {
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
        
        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        
        .badge-success {
            background: #28a745;
            color: white;
        }
        
        .badge-warning {
            background: #ffc107;
            color: #212529;
        }
        
        .badge-secondary {
            background: #6c757d;
            color: white;
        }
        
        .badge-info {
            background: #17a2b8;
            color: white;
        }
        
        .top-destination-badge {
            background: #ff6b6b;
            color: white;
            font-weight: bold;
        }
        
        .three-cards-badge {
            background: #17a2b8;
            color: white;
            font-weight: bold;
        }
        
        .search-box {
            flex: 1;
            max-width: 300px;
        }
        
        .pagination {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 20px;
        }
        
        .pagination button {
            padding: 8px 12px;
            border: 1px solid #ddd;
            background: white;
            cursor: pointer;
            border-radius: 4px;
        }
        
        .pagination button:hover {
            background: #f8f9fa;
        }
        
        .pagination button.active {
            background: #667eea;
            color: white;
            border-color: #667eea;
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
                <a href="three-cards.php">
                    <i class="fas fa-th-large"></i>
                    Three Cards
                </a>
                <a href="attractions.php" class="active">
                    <i class="fas fa-map-marker-alt"></i>
                    All Attractions
                </a>
                <a href="admin-accounts.php">
                    <i class="fas fa-users-cog"></i>
                    Admin Accounts
                </a>
                <a href="../pages/landing-page.php" target="_blank">
                    <i class="fas fa-external-link-alt"></i>
                    View Website
                </a>
            </nav>
        </aside>

        <main class="main-content">
            <div class="admin-header">
                <h1>All Attractions</h1>
                <div class="admin-user">
                    <span id="admin-email"><?php echo getAdminEmail(); ?></span>
                    <a href="logout.php" class="logout-btn">
                        <i class="fas fa-sign-out-alt"></i>
                        Logout
                    </a>
                </div>
            </div>

            <!-- Stats -->
            <div class="stats-grid">
                <div class="stat-card">
                    <h3 id="totalCount">0</h3>
                    <p>Total Attractions</p>
                </div>
                <div class="stat-card">
                    <h3 id="topDestinationsCount">0</h3>
                    <p>Top Destinations</p>
                </div>
                <div class="stat-card">
                    <h3 id="threeCardsCount">0</h3>
                    <p>Three Cards</p>
                </div>
                <div class="stat-card">
                    <h3 id="regularCount">0</h3>
                    <p>Regular Attractions</p>
                </div>
            </div>

            <!-- Actions -->
            <div class="admin-card">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
                    <h2 style="color: #2c3e50;">Attractions Management</h2>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i>
                        Add Attraction
                    </button>
                </div>

                <!-- Filters -->
                <div class="filter-controls">
                    <div class="filter-group">
                        <label>Category:</label>
                        <select id="categoryFilter" onchange="filterAttractions()">
                            <option value="">All Categories</option>
                            <option value="city">Cities</option>
                            <option value="municipality">Municipalities</option>
                        </select>
                    </div>
                    <div class="filter-group">
                        <label>Type:</label>
                        <select id="typeFilter" onchange="filterAttractions()">
                            <option value="">All Types</option>
                            <option value="1">Top Destinations</option>
                            <option value="0">Regular Attractions</option>
                            <option value="2">Three Cards</option>
                        </select>
                    </div>
                    <div class="filter-group search-box">
                        <label>Search:</label>
                        <input type="text" id="searchInput" placeholder="Search attractions..." oninput="filterAttractions()">
                    </div>
                </div>

                <div class="table-container">
                    <table id="attractionsTable">
                        <thead>
                            <tr>
                                <th>Order</th>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Location</th>
                                <th>Category</th>
                                <th>Type</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody id="attractionsTableBody">
                            <!-- Data will be loaded here -->
                        </tbody>
                    </table>
                </div>

                <div id="emptyState" class="empty-state" style="display: none;">
                    <i class="fas fa-map-marker-alt"></i>
                    <h3>No Attractions Yet</h3>
                    <p>Add your first attraction to get started</p>
                    <button class="btn btn-primary" onclick="openAddModal()">
                        <i class="fas fa-plus"></i>
                        Add Your First Attraction
                    </button>
                </div>
            </div>
        </main>
    </div>

    <!-- Add/Edit Modal -->
    <div class="modal-overlay" id="attractionModal">
        <div class="modal">
            <div class="modal-header">
                <h2 id="modalTitle">Add New Attraction</h2>
                <button class="modal-close" onclick="closeModal()">&times;</button>
            </div>

            <form id="attractionForm">
                <input type="hidden" id="attractionId" name="id">

                <div class="form-group">
                    <label for="name">Name *</label>
                    <input type="text" id="name" name="name" required placeholder="e.g., Alora Water Park">
                </div>

                <div class="form-group">
                    <label for="location">Location *</label>
                    <input type="text" id="location" name="location" required placeholder="e.g., Dipolog City">
                </div>

                <div class="form-group">
                    <label for="category">Category *</label>
                    <select id="category" name="category" required>
                        <option value="">Select Category</option>
                        <option value="city">City</option>
                        <option value="municipality">Municipality</option>
                    </select>
                </div>

                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea id="description" name="description" placeholder="Describe this attraction..."></textarea>
                </div>

                <div class="form-group">
                    <label for="displayOrder">Display Order</label>
                    <input type="number" id="displayOrder" name="displayOrder" value="0" min="0" placeholder="0 = first">
                </div>

                <div class="form-group">
                    <label>
                        <input type="checkbox" id="isTopDestination" name="isTopDestination" value="1">
                        Mark as Top Destination
                    </label>
                </div>

                <div class="form-group">
                    <label>Attraction Image</label>
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
                        Save Attraction
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
            <p style="color: #7f8c8d; margin-bottom: 20px;">Are you sure you want to delete this attraction? This action cannot be undone.</p>
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
                // Load both regular attractions and three cards
                const [attractionsResponse, threeCardsResponse] = await Promise.all([
                    fetch(`${API_BASE}?uri=/api/admin/attractions`),
                    fetch(`${API_BASE}?uri=/api/admin/three-cards`)
                ]);
                
                if (!attractionsResponse.ok || !threeCardsResponse.ok) {
                    throw new Error(`HTTP error: ${attractionsResponse.status} or ${threeCardsResponse.status}`);
                }
                
                const attractionsText = await attractionsResponse.text();
                const threeCardsText = await threeCardsResponse.text();
                
                const attractions = JSON.parse(attractionsText.replace(/^\uFEFF/, '').trim()) || [];
                const threeCards = JSON.parse(threeCardsText.replace(/^\uFEFF/, '').trim()) || [];

                // Combine attractions and three cards
                allAttractions = [
                    ...attractions.map(a => ({...a, type: 'attraction'})),
                    ...threeCards.map(c => ({
                        ...c, 
                        type: 'three_card',
                        name: c.title,
                        location: 'Three Cards Section',
                        category: 'special',
                        is_top_destination: 2 // Use 2 for three cards
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
            const totalCount = filteredAttractions.length;
            const topDestinationsCount = filteredAttractions.filter(a => a.is_top_destination == 1).length;
            const threeCardsCount = filteredAttractions.filter(a => a.is_top_destination == 2).length;
            const regularCount = filteredAttractions.filter(a => a.is_top_destination == 0).length;

            document.getElementById('totalCount').textContent = totalCount;
            document.getElementById('topDestinationsCount').textContent = topDestinationsCount;
            document.getElementById('threeCardsCount').textContent = threeCardsCount;
            document.getElementById('regularCount').textContent = regularCount;
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
                        ${attraction.category === 'city' ? 
                            '<span class="badge badge-success">City</span>' :
                            attraction.category === 'municipality' ?
                            '<span class="badge badge-secondary">Municipality</span>' :
                            '<span class="badge badge-info">Special</span>'
                        }
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
                // Category filter
                if (categoryFilter && attraction.category !== categoryFilter) {
                    return false;
                }

                // Type filter
                if (typeFilter !== '') {
                    if (typeFilter === '2' && attraction.is_top_destination != 2) return false;
                    if (typeFilter === '1' && attraction.is_top_destination != 1) return false;
                    if (typeFilter === '0' && attraction.is_top_destination != 0) return false;
                }

                // Search filter
                if (searchTerm) {
                    const searchFields = [
                        attraction.name.toLowerCase(),
                        attraction.location.toLowerCase(),
                        attraction.description ? attraction.description.toLowerCase() : ''
                    ];
                    
                    if (!searchFields.some(field => field.includes(searchTerm))) {
                        return false;
                    }
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
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteId').setAttribute('data-type', type);
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        async function editAttraction(id, type) {
            if (type === 'three_card') {
                // Redirect to three cards page for editing
                window.location.href = `three-cards.php?edit=${id}`;
                return;
            }

            try {
                const response = await fetch(`../Backend/routes/api.php?uri=/api/admin/attractions/${id}`);
                const attraction = await safeJSONParse(response);

                if (!attraction || attraction.error) {
                    showNotification('Attraction not found', 'error');
                    return;
                }

                document.getElementById('modalTitle').textContent = 'Edit Attraction';
                document.getElementById('attractionId').value = attraction.id;
                document.getElementById('name').value = attraction.name;
                document.getElementById('location').value = attraction.location;
                document.getElementById('category').value = attraction.category;
                document.getElementById('description').value = attraction.description || '';
                document.getElementById('displayOrder').value = attraction.display_order || 0;
                document.getElementById('isTopDestination').checked = attraction.is_top_destination == 1;
                document.getElementById('currentImage').value = attraction.image_url || '';

                if (attraction.image_url) {
                    document.getElementById('previewImg').src = `../${attraction.image_url}`;
                    document.getElementById('imagePreview').classList.add('show');
                } else {
                    document.getElementById('imagePreview').classList.remove('show');
                }

                document.getElementById('attractionModal').classList.add('show');
            } catch (error) {
                console.error('Error loading attraction:', error);
                showNotification('Error loading attraction: ' + error.message, 'error');
            }
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = {
                name: formData.get('name'),
                location: formData.get('location'),
                category: formData.get('category'),
                description: formData.get('description'),
                display_order: parseInt(formData.get('displayOrder')),
                is_top_destination: formData.get('isTopDestination') ? 1 : 0,
                image_url: formData.get('currentImage')
            };

            const id = formData.get('id');
            const url = id ? `../Backend/routes/api.php?uri=/api/admin/attractions/${id}` : '../Backend/routes/api.php?uri=/api/admin/attractions';
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
                    showNotification(id ? 'Attraction updated successfully' : 'Attraction added successfully', 'success');
                    closeModal();
                    loadAttractions();
                } else {
                    showNotification('Error saving attraction', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error saving attraction: ' + error.message, 'error');
            }
        }

        async function confirmDelete() {
            const id = document.getElementById('deleteId').value;
            const type = document.getElementById('deleteId').getAttribute('data-type');

            if (type === 'three_card') {
                // Delete three card
                try {
                    const response = await fetch(`../Backend/routes/api.php?uri=/api/admin/three-cards/${id}`, {
                        method: 'DELETE'
                    });
                    
                    const result = await safeJSONParse(response);
                    
                    if (result.success) {
                        showNotification('Three Card deleted successfully', 'success');
                        closeDeleteModal();
                        loadAttractions();
                    } else {
                        showNotification('Error deleting Three Card', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error deleting Three Card: ' + error.message, 'error');
                }
            } else {
                // Delete regular attraction
                try {
                    const response = await fetch(`../Backend/routes/api.php?uri=/api/admin/attractions/${id}`, {
                        method: 'DELETE'
                    });
                    
                    const result = await safeJSONParse(response);
                    
                    if (result.success) {
                        showNotification('Attraction deleted successfully', 'success');
                        closeDeleteModal();
                        loadAttractions();
                    } else {
                        showNotification('Error deleting attraction', 'error');
                    }
                } catch (error) {
                    console.error('Error:', error);
                    showNotification('Error deleting attraction: ' + error.message, 'error');
                }
            }
        }

        function updateOrder(id, order) {
            fetch(`../Backend/routes/api.php?uri=/api/admin/attractions/${id}`, {
                method: 'PUT',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify({
                    name: document.querySelector(`#attractionsTableBody tr:nth-child(${parseInt(order) + 1}) td:nth-child(3)`).textContent,
                    location: document.querySelector(`#attractionsTableBody tr:nth-child(${parseInt(order) + 1}) td:nth-child(4)`).textContent,
                    category: 'city',
                    description: '',
                    display_order: parseInt(order),
                    is_top_destination: 1,
                    image_url: ''
                })
            })
            .then(response => safeJSONParse(response))
            .then(result => {
                if (result.success) {
                    showNotification('Order updated', 'success');
                    loadAttractions();
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
