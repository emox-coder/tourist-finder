    // API Base URL
        const API_BASE = '../Backend/routes/api.php';

        // Current admin email (from PHP)
        const currentAdminEmail = '<?php echo getAdminEmail(); ?>';

        // Load admins on page load
        document.addEventListener('DOMContentLoaded', loadAdmins);

        // Form submission
        document.getElementById('adminForm').addEventListener('submit', handleFormSubmit);

        // Password strength checker
        document.getElementById('password').addEventListener('input', checkPasswordStrength);

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

        async function loadAdmins() {
            try {
                const response = await fetch(`${API_BASE}?uri=/api/admin/admins`);
                
                if (!response.ok) {
                    throw new Error(`HTTP ${response.status}: ${response.statusText}`);
                }
                
                const text = await response.text();
                const cleanText = text.replace(/^\uFEFF/, '').trim();
                const admins = JSON.parse(cleanText);

                updateStats(admins);
                renderTable(admins);
            } catch (error) {
                console.error('Error loading admins:', error);
                showNotification('Error loading admins: ' + error.message, 'error');
            }
        }

        function updateStats(admins) {
            document.getElementById('totalAdmins').textContent = admins.length;
            document.getElementById('activeAdmins').textContent = admins.length;
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
                    <td>
                        <strong>${admin.username}</strong>
                        ${admin.email === currentAdminEmail ? '<span class="current-user">YOU</span>' : ''}
                    </td>
                    <td>${admin.email}</td>
                    <td>
                        <span class="badge ${admin.role === 'super_admin' ? 'badge-warning' : 'badge-success'}">
                            ${admin.role === 'super_admin' ? 'Super Admin' : 'Admin'}
                        </span>
                    </td>
                    <td>
                        <span class="badge badge-success">Active</span>
                    </td>
                    <td>${new Date(admin.created_at).toLocaleDateString()}</td>
                    <td>
                        <div class="actions">
                            ${admin.email !== currentAdminEmail ? `
                                <button class="btn btn-sm btn-danger" onclick="openDeleteModal(${admin.id})">
                                    <i class="fas fa-trash"></i> Delete
                                </button>
                            ` : `
                                <span style="color: #999; font-size: 12px;">Current User</span>
                            `}
                        </div>
                    </td>
                </tr>
            `).join('');
        }

        function openAddModal() {
            document.getElementById('modalTitle').textContent = 'Add New Admin Account';
            document.getElementById('adminForm').reset();
            document.getElementById('adminId').value = '';
            document.getElementById('passwordGroup').style.display = 'block';
            document.getElementById('password').required = true;
            document.getElementById('adminModal').classList.add('show');
        }

        function closeModal() {
            document.getElementById('adminModal').classList.remove('show');
        }

        function openDeleteModal(id) {
            document.getElementById('deleteId').value = id;
            document.getElementById('deleteModal').classList.add('show');
        }

        function closeDeleteModal() {
            document.getElementById('deleteModal').classList.remove('show');
        }

        function checkPasswordStrength() {
            const password = document.getElementById('password').value;
            const strengthDiv = document.getElementById('passwordStrength');
            
            if (password.length === 0) {
                strengthDiv.innerHTML = '';
                return;
            }

            let strength = 0;
            if (password.length >= 6) strength++;
            if (password.length >= 10) strength++;
            if (/[a-z]/.test(password) && /[A-Z]/.test(password)) strength++;
            if (/[0-9]/.test(password)) strength++;
            if (/[^a-zA-Z0-9]/.test(password)) strength++;

            let strengthText = '';
            let strengthClass = '';

            if (strength <= 2) {
                strengthText = 'Weak password';
                strengthClass = 'strength-weak';
            } else if (strength <= 3) {
                strengthText = 'Medium strength';
                strengthClass = 'strength-medium';
            } else {
                strengthText = 'Strong password';
                strengthClass = 'strength-strong';
            }

            strengthDiv.innerHTML = `<span class="${strengthClass}">${strengthText}</span>`;
        }

        async function handleFormSubmit(e) {
            e.preventDefault();

            const formData = new FormData(e.target);
            const data = {
                username: formData.get('username'),
                email: formData.get('email'),
                password: formData.get('password'),
                role: formData.get('role')
            };

            const id = formData.get('id');
            const url = '../Backend/routes/api.php?uri=/api/admin/admins';
            const method = 'POST';

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
                    showNotification('Admin account added successfully', 'success');
                    closeModal();
                    loadAdmins();
                } else {
                    showNotification(result.message || 'Error saving admin account', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error saving admin account: ' + error.message, 'error');
            }
        }

        async function confirmDelete() {
            const id = document.getElementById('deleteId').value;

            try {
                const response = await fetch(`../Backend/routes/api.php?uri=/api/admin/admins/${id}`, {
                    method: 'DELETE'
                });
                
                const result = await safeJSONParse(response);
                
                if (result.success) {
                    showNotification('Admin account deleted successfully', 'success');
                    closeDeleteModal();
                    loadAdmins();
                } else {
                    showNotification(result.message || 'Error deleting admin account', 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showNotification('Error deleting admin account: ' + error.message, 'error');
            }
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
export { loadAdmins, openAddModal, closeModal, openDeleteModal, closeDeleteModal, checkPasswordStrength, handleFormSubmit, confirmDelete, showNotification };