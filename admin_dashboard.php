<?php
session_start();

if(!isset($_SESSION['user_type']) || $_SESSION['user_type'] != 'admin'){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Admin Dashboard - Student Record</title>
<script src="https://unpkg.com/@phosphor-icons/web"></script>
<style>
* {
    margin: 0;
    padding: 0;
    box-sizing: border-box;
}

body {
    font-family: Arial, sans-serif;
    background-color: #f5f5f5;
    color: #333;
}


.navbar {
    background-color: #fff;
    border-bottom: 3px solid #ff8c00;
    padding: 15px 30px;
    display: flex;
    justify-content: space-between;
    align-items: center;
}

.navbar-brand {
    font-size: 20px;
    font-weight: bold;
    color: #ff8c00;
}

.brand-badge {
    background-color: #ff8c00;
    color: white;
    padding: 3px 10px;
    font-size: 12px;
    border-radius: 3px;
    margin-left: 10px;
}

.navbar-user {
    display: flex;
    align-items: center;
    gap: 15px;
}


.main-content {
    padding: 30px;
    max-width: 1400px;
    margin: 0 auto;
}


.stats-grid {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    gap: 20px;
    margin-bottom: 30px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-left: 4px solid #ff8c00;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.stat-value {
    font-size: 32px;
    font-weight: bold;
    color: #ff8c00;
    margin-bottom: 5px;
}

.stat-label {
    color: #666;
    font-size: 14px;
}


.panel {
    background: white;
    padding: 20px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
}

.panel-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #ffe4cc;
}

.panel-title {
    font-size: 18px;
    font-weight: bold;
    color: #333;
}


.btn {
    padding: 8px 16px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    border-radius: 3px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
}

.btn-success {
    background-color: #ff8c00;
    color: white;
}

.btn-success:hover {
    background-color: #e67e00;
}

.btn-warning {
    background-color: #ffa500;
    color: white;
}

.btn-danger {
    background-color: #ff4444;
    color: white;
}

.btn-outline-light {
    background-color: transparent;
    border: 1px solid #ff8c00;
    color: #ff8c00;
    padding: 6px 12px;
    cursor: pointer;
    border-radius: 3px;
}

.btn-outline-light:hover {
    background-color: #ff8c00;
    color: white;
}


.filters-wrapper {
    display: flex;
    gap: 15px;
    margin-bottom: 20px;
}

.form-control {
    padding: 8px 12px;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-size: 14px;
}

.filters-wrapper .form-control {
    min-width: 150px;
}

.filters-wrapper input.form-control {
    min-width: 250px;
}

.table-wrapper {
    overflow-x: auto;
}

table {
    width: 100%;
    border-collapse: collapse;
}

th {
    background-color: #fff5eb;
    color: #333;
    padding: 12px;
    text-align: left;
    font-weight: bold;
    border-bottom: 2px solid #ff8c00;
}

td {
    padding: 12px;
    border-bottom: 1px solid #eee;
}

tr:hover {
    background-color: #fff9f5;
}


.badge {
    padding: 4px 10px;
    border-radius: 3px;
    font-size: 12px;
    font-weight: normal;
}

.badge-active {
    background-color: #ff8c00;
    color: white;
}

.badge-dropped {
    background-color: #999;
    color: white;
}

.badge-default {
    background-color: #ddd;
    color: #666;
}


.modal-overlay {
    display: none;
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0,0,0,0.5);
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.modal-overlay.show {
    display: flex;
}

.modal-content {
    background: white;
    padding: 25px;
    width: 90%;
    max-width: 600px;
    max-height: 90vh;
    overflow-y: auto;
    border-radius: 5px;
}

.modal-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 20px;
    padding-bottom: 15px;
    border-bottom: 2px solid #ffe4cc;
}

.modal-title {
    font-size: 18px;
    font-weight: bold;
    color: #ff8c00;
}

.close-btn {
    background: none;
    border: none;
    font-size: 20px;
    cursor: pointer;
    color: #999;
}

.close-btn:hover {
    color: #333;
}


.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
}

.form-full {
    grid-column: 1 / -1;
}

.form-control:focus {
    outline: none;
    border-color: #ff8c00;
}


#toast-container {
    position: fixed;
    top: 20px;
    right: 20px;
    z-index: 2000;
}

.toast {
    background: white;
    border-left: 4px solid #ff8c00;
    padding: 15px 20px;
    margin-bottom: 10px;
    box-shadow: 0 2px 8px rgba(0,0,0,0.2);
    opacity: 0;
    transform: translateX(100%);
    transition: all 0.3s ease;
}

.toast.show {
    opacity: 1;
    transform: translateX(0);
}

.toast.error {
    border-left-color: #ff4444;
}


@media (max-width: 768px) {
    .stats-grid {
        grid-template-columns: repeat(2, 1fr);
    }
    
    .filters-wrapper {
        flex-direction: column;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>

<header class="navbar">
    <div class="navbar-brand">
        <i class="ph ph-graduation-cap" style="color: #ff8c00; font-size: 1.5rem; vertical-align: middle; margin-right: 8px;"></i>
        Student Management
        <span class="brand-badge">ADMIN</span>
    </div>
    <div class="navbar-user">
        <span>ADMIN</span>
        <button class="btn-outline-light" onclick="logout()">Sign Out</button>
    </div>
</header>

<main class="main-content">
    
    <div class="stats-grid">
        <div class="stat-card">
            <div class="stat-value" id="stat-total">0</div>
            <div class="stat-label">Total Students</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-active">0</div>
            <div class="stat-label">Active</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-incomplete">0</div>
            <div class="stat-label">Incomplete</div>
        </div>
        <div class="stat-card">
            <div class="stat-value" id="stat-inactive">0</div>
            <div class="stat-label">Inactive / Dropped</div>
        </div>
    </div>

    <div class="panel">
        <div class="panel-header">
            <div class="panel-title">
                <i class="ph ph-file-text" style="color: #ff8c00; font-size: 1.2rem; vertical-align: middle; margin-right: 5px;"></i>
                All Students
            </div>
            <button class="btn btn-success" onclick="openModal()"><i class="ph ph-plus"></i> Add Student</button>
        </div>
        
        <div class="filters-wrapper">
            <input type="text" id="searchInput" class="form-control" placeholder="Search name, email, course..." oninput="renderTable()">
            <select class="form-control" id="filterStatus" onchange="renderTable()">
                <option value="">All Status</option>
                <option value="Active">Active</option>
                <option value="Dropped">Dropped</option>
                <option value="Inactive">Inactive</option>
                <option value="Incomplete">Incomplete</option>
            </select>
            <select class="form-control" id="filterYear" onchange="renderTable()">
                <option value="">All Years</option>
                <option value="1">1</option>
                <option value="2">2</option>
                <option value="3">3</option>
                <option value="4">4</option>
            </select>
        </div>

        <div class="table-wrapper">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Yr</th>
                        <th>Sec</th>
                        <th>Email</th>
                        <th>Phone</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="table"></tbody>
            </table>
        </div>
    </div>

</main>

<div class="modal-overlay" id="studentModal">
    <div class="modal-content">
        <div class="modal-header">
            <div class="modal-title" id="modalTitle">Add Student</div>
            <button class="close-btn" onclick="closeModal()"><i class="ph ph-x"></i></button>
        </div>
        
        <div class="form-grid">
            <input id="name" placeholder="Name" class="form-control form-full">
            <input id="course" placeholder="Course (e.g. BS INFORMATION TECHNOLOGY)" class="form-control form-full">
            <input id="email" placeholder="Email" type="email" class="form-control form-full">
            
            <input id="username" placeholder="Username" class="form-control">
            <input id="password" type="password" placeholder="Password" class="form-control">
            
            <input id="year" placeholder="Year (e.g. 2)" class="form-control">
            <input id="section" placeholder="Section (e.g. A)" class="form-control">
            
            <input id="phone_number" placeholder="Phone Number" class="form-control">
            <select id="status" class="form-control">
                <option value="" disabled selected>Select Status</option>
                <option value="Active">Active</option>
                <option value="Dropped">Dropped</option>
                <option value="Inactive">Inactive</option>
                <option value="Incomplete">Incomplete</option>
            </select>
            
            <input id="date_of_birth" type="date" class="form-control" title="Date of Birth">
            <select id="gender" class="form-control">
                <option value="" disabled selected>Select Gender</option>
                <option value="Male">Male</option>
                <option value="Female">Female</option>
            </select>

            <input id="address" placeholder="Address" class="form-control form-full">
            <input id="religion" placeholder="Religion" class="form-control form-full">
        </div>
        
        <div style="margin-top: 20px; display: flex; justify-content: flex-end; gap: 10px;">
            <button class="btn" style="background: #ddd; color: #333;" onclick="closeModal()">Cancel</button>
            <button class="btn btn-success" id="submitBtn" onclick="saveStudent()">Save Student</button>
        </div>
    </div>
</div>

<div id="toast-container"></div>

<script>
let studentsData = [];
let editId = null;

function showToast(message, type = 'success') {
    const container = document.getElementById('toast-container');
    const toast = document.createElement('div');
    toast.className = `toast ${type}`;
    toast.innerText = message;
    container.appendChild(toast);
    setTimeout(() => toast.classList.add('show'), 10);
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => { if(container.contains(toast)) container.removeChild(toast); }, 300);
    }, 3000);
}

function load(){
    fetch("api/display_api.php")
    .then(res=>res.json())
    .then(data=>{
        studentsData = data;
        updateStats();
        renderTable();
    });
}

function updateStats() {
    document.getElementById('stat-total').innerText = studentsData.length;
    let active = 0, inc = 0, inactive = 0;
    studentsData.forEach(s => {
        const st = (s.status || '').toLowerCase();
        if (st === 'active') active++;
        else if (st === 'incomplete') inc++;
        else if (st === 'inactive' || st === 'dropped') inactive++;
    });
    document.getElementById('stat-active').innerText = active;
    document.getElementById('stat-incomplete').innerText = inc;
    document.getElementById('stat-inactive').innerText = inactive;
}

function renderTable() {
    const table = document.getElementById('table');
    table.innerHTML = "";
    const searchTerm = document.getElementById('searchInput').value.toLowerCase();
    const filterStatus = document.getElementById('filterStatus').value.toLowerCase();
    const filterYear = document.getElementById('filterYear').value;
    
    const filteredData = studentsData.filter(s => {
        const matchesSearch = (s.name && s.name.toLowerCase().includes(searchTerm)) ||
                              (s.email && s.email.toLowerCase().includes(searchTerm)) ||
                              (s.course && s.course.toLowerCase().includes(searchTerm));
        const matchesStatus = filterStatus === "" || (s.status && s.status.toLowerCase() === filterStatus);
        const matchesYear = filterYear === "" || (s.year && s.year.toString() === filterYear);
        return matchesSearch && matchesStatus && matchesYear;
    });

    filteredData.forEach(s => {
        let statusBadge = 'badge-default';
        if (s.status) {
            const stat = s.status.toLowerCase();
            if (stat === 'active') statusBadge = 'badge-active';
            else if (stat === 'inactive' || stat === 'dropped') statusBadge = 'badge-dropped';
        }
        const statusHtml = s.status ? `<span class="badge ${statusBadge}">${s.status}</span>` : '';

        table.innerHTML += `
        <tr>
            <td>${s.student_id}</td>
            <td style="font-weight:600; color:#ff8c00;">${s.name}</td>
            <td>${s.course || ''}</td>
            <td>${s.year || ''}</td>
            <td>${s.section || ''}</td>
            <td>${s.email || ''}</td>
            <td>${s.phone_number || ''}</td>
            <td>${statusHtml}</td>
            <td style="white-space: nowrap;">
                <button class="btn btn-warning" onclick="editStudent(${s.student_id})"><i class="ph ph-pencil-simple"></i> Edit</button>
                <button class="btn btn-danger" onclick="del(${s.student_id})"><i class="ph ph-trash"></i></button>
            </td>
        </tr>`;
    });
}

function openModal() {
    document.getElementById('studentModal').classList.add('show');
}

function closeModal() {
    document.getElementById('studentModal').classList.remove('show');
    clearForm();
}

function saveStudent(){
    const studentData = {
        name: document.getElementById('name').value,
        course: document.getElementById('course').value,
        year: document.getElementById('year').value,
        section: document.getElementById('section').value,
        date_of_birth: document.getElementById('date_of_birth').value,
        gender: document.getElementById('gender').value,
        status: document.getElementById('status').value,
        address: document.getElementById('address').value,
        religion: document.getElementById('religion').value,
        phone_number: document.getElementById('phone_number').value,
        email: document.getElementById('email').value,
        username: document.getElementById('username').value,
        password: document.getElementById('password').value
    };

    for (const key in studentData) {
        if (!studentData[key] || studentData[key].trim() === "") {
            showToast("All fields are required! Please fill the form completely.", "error");
            return;
        }
    }

    if(editId === null) {
        fetch("api/insert_data_api.php",{
            method:"POST",
            headers:{"Content-Type":"application/json"},
            body:JSON.stringify(studentData)
        }).then(res => {
            if(res.ok) {
                showToast("Successfully added student!");
                closeModal();
                load();
            } else {
                showToast("Failed to add student.", "error");
            }
        });
    } else {
        fetch("api/update_api.php?id="+editId,{
            method:"PUT",
            headers:{"Content-Type":"application/json"},
            body:JSON.stringify(studentData)
        }).then(res => {
            if(res.ok) {
                showToast("Successfully updated student!");
                closeModal();
                load();
            } else {
                showToast("Failed to update student.", "error");
            }
        });
    }
}

function editStudent(id) {
    const s = studentsData.find(student => student.student_id == id);
    if(s) {
        editId = id;
        document.getElementById('name').value = s.name || '';
        document.getElementById('course').value = s.course || '';
        document.getElementById('year').value = s.year || '';
        document.getElementById('section').value = s.section || '';
        document.getElementById('date_of_birth').value = s.date_of_birth || '';
        document.getElementById('gender').value = s.gender || '';
        document.getElementById('status').value = s.status || '';
        document.getElementById('address').value = s.address || '';
        document.getElementById('religion').value = s.religion || '';
        document.getElementById('phone_number').value = s.phone_number || '';
        document.getElementById('email').value = s.email || '';
        document.getElementById('username').value = s.username || '';
        document.getElementById('password').value = s.password || '';
        
        document.getElementById('modalTitle').innerText = "Update Student";
        document.getElementById('submitBtn').innerText = "Update Student";
        openModal();
    }
}

function clearForm() {
    document.querySelectorAll('.form-control').forEach(input => input.value = '');
    editId = null;
    document.getElementById('modalTitle').innerText = "Add Student";
    document.getElementById('submitBtn').innerText = "Save Student";
}

function del(id){
    if(confirm("Are you sure you want to delete this student?")) {
        fetch("api/delete_api.php?id="+id, {method:"DELETE"})
        .then(res => {
            if(res.ok) {
                showToast("Successfully deleted student!");
                load();
            } else {
                showToast("Failed to delete student.", "error");
            }
        });
    }
}

function logout(){
    if(confirm("Are you sure you want to log out?")) {
        window.location = "login.php";
    }
}

load();
</script>

</body>
</html>