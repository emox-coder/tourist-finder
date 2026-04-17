<?php
require_once 'includes/auth.php';
$page_title = 'Manage Three Cards';
include 'includes/header.php';
?>

<div class="admin-card">
    <h2 style="color: #2c3e50; margin-bottom: 20px;">Live Preview</h2>
    <div class="three-cards-preview" id="previewContainer"></div>
</div>

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

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50;">Three Cards Management</h2>
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Card
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
            <tbody id="cardsTableBody"></tbody>
        </table>
    </div>

    <div id="emptyState" class="empty-state" style="display: none;">
        <i class="fas fa-th-large"></i>
        <h3>No Cards Yet</h3>
        <p>Add your first three cards to showcase on the landing page</p>
    </div>
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
                <input type="text" id="title" name="title" required>
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
                <label>Card Image</label>
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
                <button type="submit" class="btn btn-primary">Save Card</button>
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
        <p>Are you sure you want to delete this card?</p>
        <input type="hidden" id="deleteId">
        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()" style="background:#95a5a6; color:white;">Cancel</button>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

<style>
.three-cards-preview { display: grid; grid-template-columns: repeat(3, 1fr); gap: 20px; }
.three-card-preview-item { border: 1px solid #ddd; padding: 15px; border-radius: 8px; text-align: center; }
.three-card-preview-item img { width: 100%; height: 100px; object-fit: cover; border-radius: 4px; }
</style>

<script src="../assets/js/admin-three-cards.js"></script>
<?php include 'includes/footer.html'; ?>
