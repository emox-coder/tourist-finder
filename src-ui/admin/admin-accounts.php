<?php
require_once 'includes/auth.php';
$page_title = 'Admin Account Management';
include 'includes/header.php';
?>

<div class="stats-grid">
    <div class="stat-card">
        <h3 id="totalAdmins">0</h3>
        <p>Total Admin Accounts</p>
    </div>
    <div class="stat-card">
        <h3 id="activeAdmins">0</h3>
        <p>Active Admins</p>
    </div>
</div>

<div class="admin-card">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
        <h2 style="color: #2c3e50;">Admin Accounts</h2>
        <button class="btn btn-primary" onclick="openAddModal()">
            <i class="fas fa-plus"></i> Add Admin Account
        </button>
    </div>

    <div class="table-container">
        <table id="adminsTable">
            <thead>
                <tr>
                    <th>Username</th>
                    <th>Email</th>
                    <th>Role</th>
                    <th>Status</th>
                    <th>Created</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody id="adminsTableBody"></tbody>
        </table>
    </div>

    <div id="emptyState" class="empty-state" style="display: none;">
        <i class="fas fa-users-cog"></i>
        <h3>No Admin Accounts Yet</h3>
        <p>Add your first admin account to manage the system</p>
    </div>
</div>

<!-- Add/Edit Modal -->
<div class="modal-overlay" id="adminModal">
    <div class="modal">
        <div class="modal-header">
            <h2 id="modalTitle">Add New Admin Account</h2>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <form id="adminForm">
            <input type="hidden" id="adminId" name="id">
            <div class="form-group">
                <label for="username">Username *</label>
                <input type="text" id="username" name="username" required minlength="3">
            </div>
            <div class="form-group">
                <label for="email">Email *</label>
                <input type="email" id="email" name="email" required>
            </div>
            <div class="form-group" id="passwordGroup">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" required minlength="6">
            </div>
            <div class="form-group">
                <label for="role">Role</label>
                <select id="role" name="role" style="width: 100%; padding: 10px; border: 1px solid #ddd; border-radius: 6px;">
                    <option value="admin">Admin</option>
                    <option value="super_admin">Super Admin</option>
                </select>
            </div>
            <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top: 30px;">
                <button type="button" class="btn btn-secondary" onclick="closeModal()" style="background:#95a5a6; color:white;">Cancel</button>
                <button type="submit" class="btn btn-primary">Save Admin Account</button>
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
        <p>Are you sure you want to delete this admin account?</p>
        <input type="hidden" id="deleteId">
        <div style="display: flex; gap: 10px; justify-content: flex-end; margin-top:20px;">
            <button type="button" class="btn btn-secondary" onclick="closeDeleteModal()" style="background:#95a5a6; color:white;">Cancel</button>
            <button type="button" class="btn btn-danger" onclick="confirmDelete()">Delete</button>
        </div>
    </div>
</div>

<script src="../assets/js/admin-accounts.js"></script>
<?php include 'includes/footer.php'; ?>
