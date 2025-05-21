<?php
    $activeTab = $activeTab ?? '';
?>
<div class="sidebar">
    <!-- Hamburger Button -->
    <button class="hamburger-button">
        <i class="bi bi-list"></i>
    </button>
    <hr>
    <div class="sidebar-content">
        <a href="<?= $activeTab === 'dashboard' ? '#' : 'dashboard.php' ?>"
           class="<?= $activeTab === 'dashboard' ? 'active' : '' ?>">
            <i class="bi bi-grid" style="color: #0D67A1; font-size: 24px;"></i> Dashboard
        </a>

        <a href="<?= $activeTab === 'studentList' ? '#' : 'studentList.php' ?>"
           class="<?= $activeTab === 'studentList' ? 'active' : '' ?>">
            <i class="bi bi-people" style="color: #0D67A1; font-size: 24px;"></i> Student List
        </a>

        <a href="<?= $activeTab === 'violations' ? '#' : 'violations.php' ?>"
           class="<?= $activeTab === 'violations' ? 'active' : '' ?>">
            <i class="bi bi-table" style="color: #0D67A1; font-size: 24px;"></i> Violations
        </a>

        <a href="violations.php"
           id="addStudentNav"
           data-bs-toggle="modal"
           data-bs-target="#addStudentModal">
            <i class="bi bi-person-plus" style="color: #0D67A1; font-size: 24px;"></i> Student Registration
        </a>

        <a href="<?= $activeTab === 'control_panel' ? '#' : 'control_panel.php' ?>"
           class="<?= $activeTab === 'control_panel' ? 'active' : '' ?>">
            <i class="bi bi-card-list" style="color: #0D67A1; font-size: 24px;"></i> Control Panel
        </a>

        <!-- Logout Button -->
        <div class="logout-button-wrapper">
            <button type="button" class="btn btn-danger w-100"
                    data-bs-toggle="modal" data-bs-target="#logoutModal"
                    style="background-color: #0D67A1; border-color: #0D67A1;">
                Logout
            </button>
        </div>
    </div>
</div>