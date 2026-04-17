<?php
require_once 'includes/auth.php';
$page_title = 'All Attractions';
include 'includes/header.php';
?>

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

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50;">Attractions Management</h2>
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Attraction
        </button>
    </div>

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
            <tbody id="attractionsTableBody"></tbody>
        </table>
    </div>

    <div id="emptyState" class="empty-state" style="display: none;">
        <i class="fas fa-map-marker-alt"></i>
        <h3>No Attractions Yet</h3>
        <p>Add your first attraction to get started</p>
    </div>
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
                <input type="text" id="name" name="name" required>
            </div>
            <div class="form-group">
                <label for="location">Location *</label>
                <input type="text" id="location" name="location" required>
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
                <textarea id="description" name="description"></textarea>
            </div>
            <div class="form-group">
                <label for="displayOrder">Display Order</label>
                <input type="number" id="displayOrder" name="displayOrder" value="0">
            </div>
            <div class="form-group">
                <label><input type="checkbox" id="isTopDestination" name="isTopDestination" value="1"> Mark as Top Destination</label>
            </div>
            <div class="form-group">
                <label>Attraction Image</label>
                <div class="image-upload-area" onclick="document.getElementById('imageInput').click()">
                    <i class="fas fa-cloud-upload-alt"></i>
                    <p>Click to upload image</p>
                </div>
                <input type="file" id="imageInput" accept="image/*" style="display: none;" onchange="previewImage(event)">
                <div class="image-preview" id="imagePreview">
                    <img id="previewImg" src="" alt="Preview">
                    <button type="button" class="btn btn-sm btn-danger" onclick="removeImage()">Remove</button>
                </div>
                <input type="hidden" id="currentImage" name="currentImage">
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()" style="background:#95a5a6; color:white;">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Attraction</button>
            </div>
        </form>
    </div>
</div>

<!-- Delete Modal -->
<div class="modal-overlay" id="deleteModal">
    <div class="modal" style="max-width: 400px;">
        <div class="modal-header">
            <h2>Confirm Delete</h2>
            <button class="modal-close" onclick="closeDeleteModal()">&times;</button>
        </div>
        <p>Are you sure you want to delete this attraction?</p>
        <input type="hidden" id="deleteId">
        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()" style="background:#95a5a6; color:white;">Cancel</button>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

<script src="../assets/js/admin-attractions.js"></script>
<?php include 'includes/footer.html'; ?>
