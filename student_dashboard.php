<?php
session_start();

if(!isset($_SESSION['user_type'])){
    header("Location: login.php");
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>Student Dashboard - Student Record</title>
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


.dashboard-layout {
    display: flex;
    min-height: 100vh;
}


.sidebar {
    width: 250px;
    background-color: #fff;
    border-right: 3px solid #ff8c00;
    display: flex;
    flex-direction: column;
}

.sidebar-header {
    padding: 25px;
    background-color: #fff5eb;
    border-bottom: 2px solid #ffe4cc;
}

.sidebar-header h2 {
    color: #ff8c00;
    font-size: 20px;
    margin-bottom: 5px;
}

.sidebar-header p {
    color: #666;
    font-size: 14px;
}

.sidebar-menu {
    flex: 1;
    padding: 20px;
}

.menu-item {
    padding: 12px 15px;
    color: #333;
    cursor: pointer;
    border-radius: 3px;
    margin-bottom: 5px;
}

.menu-item.active {
    background-color: #ff8c00;
    color: white;
}

.sidebar-footer {
    padding: 20px;
    border-top: 1px solid #eee;
}


.main-content {
    flex: 1;
    padding: 30px;
    overflow-y: auto;
}

.main-content h3 {
    color: #ff8c00;
    font-size: 24px;
    margin-bottom: 25px;
    padding-bottom: 15px;
    border-bottom: 2px solid #ffe4cc;
}


.student-grid {
    display: grid;
    grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
    gap: 20px;
}


.student-card {
    background: white;
    padding: 20px;
    border-radius: 5px;
    box-shadow: 0 2px 4px rgba(0,0,0,0.1);
    border-left: 4px solid #ff8c00;
    cursor: pointer;
    transition: all 0.3s ease;
}

.student-card:hover {
    box-shadow: 0 4px 8px rgba(0,0,0,0.15);
}

.student-card.expanded {
    grid-column: 1 / -1;
    cursor: default;
    max-width: 800px;
    margin: 0 auto;
    width: 100%;
}

.student-card h3 {
    color: #ff8c00;
    font-size: 18px;
    margin-bottom: 15px;
    border: none;
    padding: 0;
}

.student-card p {
    margin-bottom: 8px;
    color: #555;
    font-size: 14px;
}

.student-card strong {
    color: #333;
}


.form-grid {
    display: grid;
    grid-template-columns: 1fr 1fr;
    gap: 15px;
    margin-top: 15px;
}

.form-control {
    padding: 10px;
    border: 1px solid #ddd;
    border-radius: 3px;
    font-size: 14px;
    width: 100%;
}

.form-control:focus {
    outline: none;
    border-color: #ff8c00;
}


.btn {
    padding: 10px 20px;
    border: none;
    cursor: pointer;
    font-size: 14px;
    border-radius: 3px;
    display: inline-block;
}

.btn-primary {
    background-color: #ff8c00;
    color: white;
}

.btn-primary:hover {
    background-color: #e67e00;
}

.btn-danger {
    background-color: #ff4444;
    color: white;
}

.btn-danger:hover {
    background-color: #cc0000;
}

.btn-block {
    width: 100%;
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


@keyframes fadeInSlide {
    from {
        opacity: 0;
        transform: translateY(20px);
    }
    to {
        opacity: 1;
        transform: translateY(0);
    }
}


@media (max-width: 768px) {
    .dashboard-layout {
        flex-direction: column;
    }
    
    .sidebar {
        width: 100%;
        border-right: none;
        border-bottom: 3px solid #ff8c00;
    }
    
    .form-grid {
        grid-template-columns: 1fr;
    }
}
</style>
</head>
<body>

<div class="dashboard-layout">

    <aside class="sidebar">
        <div class="sidebar-header">
            <h2>Student Record</h2>
            <p>Student Dashboard</p>
        </div>
        <nav class="sidebar-menu">
            <div class="menu-item active">Directory</div>
        </nav>
        <div class="sidebar-footer">
            <button onclick="logout()" class="btn btn-danger btn-block">Logout</button>
        </div>
    </aside>

    <main class="main-content">
        <h3>Student Directory</h3>
        
        <div id="student-list" class="student-grid"></div>
    </main>

</div>

<div id="toast-container"></div>

<script>
let studentsData = [];
let editingId = null;
const currentUserId = <?php echo isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 'null'; ?>;

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

function loadStudents() {
    fetch("api/display_api.php", {
        credentials: 'include'
    })
    .then(res => res.json())
    .then(data => {
        studentsData = data;
        renderStudents();
    })
    .catch(err => {
        console.error('Error loading students:', err);
    });
}

function renderStudents() {
    const list = document.getElementById('student-list');
    list.innerHTML = "";
    studentsData.forEach((s, index) => {
        const isEditing = (editingId == s.student_id);
        const isCurrentUser = (s.student_id == currentUserId);
        const delay = index * 0.05;
        
        let cardContent = "";
        if (isEditing && isCurrentUser) {
            cardContent = `
                <h3>Edit Your Profile</h3>
                <div class="form-grid" style="text-align: left;">
                    <div style="grid-column: 1 / -1; margin-bottom: 0.5rem; color: #ff8c00;">Student ID: ${s.student_id}</div>
                    <input id="edit_name_${s.student_id}" value="${s.name || ''}" class="form-control" placeholder="Name">
                    <input id="edit_course_${s.student_id}" value="${s.course || ''}" class="form-control" placeholder="Course">
                    <input id="edit_year_${s.student_id}" value="${s.year || ''}" class="form-control" placeholder="Year">
                    <input id="edit_section_${s.student_id}" value="${s.section || ''}" class="form-control" placeholder="Section">
                    <input id="edit_dob_${s.student_id}" type="date" value="${s.date_of_birth || ''}" class="form-control">
                    <input id="edit_gender_${s.student_id}" value="${s.gender || ''}" class="form-control" placeholder="Gender">
                    <input id="edit_status_${s.student_id}" value="${s.status || ''}" class="form-control" placeholder="Status">
                    <input id="edit_address_${s.student_id}" value="${s.address || ''}" class="form-control" placeholder="Address">
                    <input id="edit_religion_${s.student_id}" value="${s.religion || ''}" class="form-control" placeholder="Religion">
                    <input id="edit_phone_${s.student_id}" value="${s.phone_number || ''}" class="form-control" placeholder="Phone Number">
                    <input id="edit_email_${s.student_id}" value="${s.email || ''}" class="form-control" placeholder="Email">
                </div>
                <div style="margin-top: 1rem; display: flex; gap: 10px;">
                    <button class="btn btn-primary" onclick="saveStudent(${s.student_id}, event)">Update Profile</button>
                    <button class="btn btn-danger" onclick="cancelEdit(event)">Cancel</button>
                </div>
            `;
        } else {
            cardContent = `
                <h3>${s.name} ${isCurrentUser ? '<span style="font-size: 12px; color: #666; font-weight: normal;">(You)</span>' : ''}</h3>
                <p><strong>ID:</strong> ${s.student_id}</p>
                <p><strong>Course:</strong> ${s.course || 'N/A'}</p>
                <p><strong>Year:</strong> ${s.year || 'N/A'}</p>
                <p><strong>Section:</strong> ${s.section || 'N/A'}</p>
                <p><strong>Email:</strong> ${s.email || 'N/A'}</p>
            `;
        }

        const onClickAttr = (!isEditing && isCurrentUser) ? `onclick="expandCard(${s.student_id})"` : '';
        const cursorStyle = (!isEditing && isCurrentUser) ? 'cursor: pointer;' : 'cursor: default;';

        list.innerHTML += `
        <div class="student-card ${isEditing ? 'expanded' : ''}" 
             style="animation: fadeInSlide 0.5s ease forwards; animation-delay: ${delay}s; opacity: 0; ${cursorStyle}"
             ${onClickAttr}>
            ${cardContent}
        </div>`;
    });
}

function expandCard(id) {
    editingId = id;
    renderStudents();
}

function cancelEdit(e) {
    e.stopPropagation();
    editingId = null;
    renderStudents();
}

function saveStudent(id, e) {
    e.stopPropagation();

    const studentData = {
        name: document.getElementById('edit_name_'+id).value,
        course: document.getElementById('edit_course_'+id).value,
        year: document.getElementById('edit_year_'+id).value,
        section: document.getElementById('edit_section_'+id).value,
        date_of_birth: document.getElementById('edit_dob_'+id).value,
        gender: document.getElementById('edit_gender_'+id).value,
        status: document.getElementById('edit_status_'+id).value,
        address: document.getElementById('edit_address_'+id).value,
        religion: document.getElementById('edit_religion_'+id).value,
        phone_number: document.getElementById('edit_phone_'+id).value,
        email: document.getElementById('edit_email_'+id).value
    };

    for (const key in studentData) {
        if (!studentData[key] || studentData[key].trim() === "") {
            showToast("All fields are required! Please fill the form completely.", "error");
            return;
        }
    }

    fetch("api/update_api.php?id="+id, {
        method: "PUT",
        headers: {"Content-Type": "application/json"},
        credentials: 'include',
        body: JSON.stringify(studentData)
    }).then(res => {
        if(res.ok) {
            showToast("Successfully updated your profile!");
            editingId = null;
            loadStudents();
        } else {
            showToast("Failed to update profile.", "error");
        }
    });
}

function logout(){
    if(confirm("Are you sure you want to log out?")) {
        window.location = "login.php";
    }
}

loadStudents();
</script>

</body>
</html>