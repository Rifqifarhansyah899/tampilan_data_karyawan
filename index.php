<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Employee Management System</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css" rel="stylesheet">
    <link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
    <style>
        .loading {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(255,255,255,0.8);
            display: none;
            justify-content: center;
            align-items: center;
            z-index: 9999;
        }
    </style>
</head>
<body>

<!-- Loading Spinner -->
<div class="loading" id="loadingSpinner">
    <div class="spinner-border text-primary" role="status">
        <span class="visually-hidden">Loading...</span>
    </div>
</div>

<!-- Navbar -->
<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container">
        <a class="navbar-brand" href="#">
            <i class="bi bi-people-fill"></i> Employee Management System
        </a>
    </div>
</nav>

<!-- Main Content -->
<div class="container py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-list-ul"></i> Employee List</h2>
        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#employeeModal">
            <i class="bi bi-plus-lg"></i> Add Employee
        </button>
    </div>

    <!-- Employee Table -->
    <div class="card">
        <div class="card-body">
            <table id="employeeTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Name</th>
                        <th>Position</th>
                        <th>Department</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <!-- Data will be loaded dynamically -->
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Employee Modal -->
<div class="modal fade" id="employeeModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="bi bi-person-plus"></i> <span id="modalTitle">Add Employee</span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <form id="employeeForm">
                    <input type="hidden" id="employeeId">
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">NIP</label>
                            <input type="text" class="form-control" id="nip" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Full Name</label>
                            <input type="text" class="form-control" id="name" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Position</label>
                            <input type="text" class="form-control" id="position" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Department</label>
                            <input type="text" class="form-control" id="department" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" class="form-control" id="email" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Phone</label>
                            <input type="tel" class="form-control" id="phone">
                        </div>
                        <div class="col-md-12 mb-3">
                            <label class="form-label">Address</label>
                            <textarea class="form-control" id="address" rows="2"></textarea>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Join Date</label>
                            <input type="date" class="form-control" id="join_date" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Salary</label>
                            <input type="number" class="form-control" id="salary" required>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Status</label>
                            <select class="form-select" id="status" required>
                                <option value="Active">Active</option>
                                <option value="Inactive">Inactive</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-lg"></i> Cancel
                </button>
                <button type="button" class="btn btn-primary" onclick="saveEmployee()">
                    <i class="bi bi-save"></i> Save
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Toast Notifications -->
<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="toast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header" id="toastHeader">
            <strong class="me-auto" id="toastTitle">Notification</strong>
            <button type="button" class="btn-close" data-bs-dismiss="toast"></button>
        </div>
        <div class="toast-body" id="toastMessage"></div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>

<script>
let table;
const employeeModal = new bootstrap.Modal(document.getElementById('employeeModal'));
const toast = new bootstrap.Toast(document.getElementById('toast'));

// Initialize DataTable
$(document).ready(function() {
    table = $('#employeeTable').DataTable({
        order: [[0, 'desc']],
        responsive: true
    });
    loadEmployees();
});

// Show loading spinner
function showLoading() {
    document.getElementById('loadingSpinner').style.display = 'flex';
}

// Hide loading spinner
function hideLoading() {
    document.getElementById('loadingSpinner').style.display = 'none';
}

// Show toast notification
function showToast(title, message, type = 'success') {
    const toastElement = document.getElementById('toast');
    const header = document.getElementById('toastHeader');
    const titleElement = document.getElementById('toastTitle');
    const messageElement = document.getElementById('toastMessage');

    header.className = 'toast-header text-white';
    header.classList.add(type === 'success' ? 'bg-success' : 'bg-danger');
    titleElement.textContent = title;
    messageElement.textContent = message;
    
    toast.show();
}

// Load all employees
function loadEmployees() {
    showLoading();
    fetch('api.php?action=getAll')
        .then(response => response.json())
        .then(result => {
            if(result.success) {
                displayEmployees(result.data);
            } else {
                showToast('Error', result.message, 'error');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Failed to load employees', 'error');
            hideLoading();
        });
}

// Display employees in DataTable
function displayEmployees(employees) {
    table.clear();
    employees.forEach(emp => {
        table.row.add([
            emp.nip,
            emp.name,
            emp.position,
            emp.department,
            emp.email,
            `<span class="badge bg-${emp.status === 'Active' ? 'success' : 'danger'}">${emp.status}</span>`,
            `<button class="btn btn-sm btn-info me-1" onclick="editEmployee(${emp.id})">
                <i class="bi bi-pencil"></i>
            </button>
            <button class="btn btn-sm btn-danger" onclick="deleteEmployee(${emp.id})">
                <i class="bi bi-trash"></i>
            </button>`
        ]);
    });
    table.draw();
}

// Reset form
function resetForm() {
    document.getElementById('employeeForm').reset();
    document.getElementById('employeeId').value = '';
}

// Save employee (Add/Update)
function saveEmployee() {
    const formData = new FormData();
    const id = document.getElementById('employeeId').value;
    
    formData.append('action', id ? 'update' : 'add');
    if(id) formData.append('id', id);
    
    formData.append('nip', document.getElementById('nip').value);
    formData.append('name', document.getElementById('name').value);
    formData.append('position', document.getElementById('position').value);
    formData.append('department', document.getElementById('department').value);
    formData.append('email', document.getElementById('email').value);
    formData.append('phone', document.getElementById('phone').value);
    formData.append('address', document.getElementById('address').value);
    formData.append('join_date', document.getElementById('join_date').value);
    formData.append('salary', document.getElementById('salary').value);
    formData.append('status', document.getElementById('status').value);

    showLoading();
    fetch('api.php', {
        method: 'POST',
        body: formData
    })
    .then(response => response.json())
    .then(result => {
        if(result.success) {
            loadEmployees();
            employeeModal.hide();
            showToast('Success', result.message);
        } else {
            showToast('Error', result.message, 'error');
        }
        hideLoading();
    })
    .catch(error => {
        console.error('Error:', error);
        showToast('Error', 'Failed to save employee', 'error');
        hideLoading();
    });
}

// Edit employee
function editEmployee(id) {
    showLoading();
    fetch(`api.php?action=get&id=${id}`)
        .then(response => response.json())
        .then(result => {
            if(result.success) {
                const emp = result.data;
                document.getElementById('employeeId').value = emp.id;
                document.getElementById('nip').value = emp.nip;
                document.getElementById('name').value = emp.name;
                document.getElementById('position').value = emp.position;
                document.getElementById('department').value = emp.department;
                document.getElementById('email').value = emp.email;
                document.getElementById('phone').value = emp.phone;
                document.getElementById('address').value = emp.address;
                document.getElementById('join_date').value = emp.join_date;
                document.getElementById('salary').value = emp.salary;
                document.getElementById('status').value = emp.status;
                
                document.getElementById('modalTitle').textContent = 'Edit Employee';
                employeeModal.show();
            } else {
                showToast('Error', result.message, 'error');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Failed to load employee data', 'error');
            hideLoading();
        });
}

// Delete employee
function deleteEmployee(id) {
    if(confirm('Are you sure you want to delete this employee?')) {
        const formData = new FormData();
        formData.append('action', 'delete');
        formData.append('id', id);

        showLoading();
        fetch('api.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(result => {
            if(result.success) {
                loadEmployees();
                showToast('Success', result.message);
            } else {
                showToast('Error', result.message, 'error');
            }
            hideLoading();
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('Error', 'Failed to delete employee', 'error');
            hideLoading();
        });
    }
}

// Reset form when modal is closed
document.getElementById('employeeModal').addEventListener('hidden.bs.modal', function () {
    resetForm();
    document.getElementById('modalTitle').textContent = 'Add Employee';
});
</script>

</body>
</html>