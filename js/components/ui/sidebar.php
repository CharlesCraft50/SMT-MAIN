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

        <!-- Control Panel with Submenu -->
        <div class="nav-item-with-submenu">
            <a href="#" 
               class="nav-item <?= $activeTab === 'control_panel' ? 'active' : '' ?>"
               id="controlPanelToggle"
               data-bs-toggle="collapse" 
               data-bs-target="#controlPanelSubmenu" 
               aria-expanded="false">
                <i class="bi bi-card-list" style="color: #0D67A1; font-size: 24px;"></i> 
                Control Panel
                <i class="bi bi-chevron-down submenu-arrow" style="color: #0D67A1; margin-left: auto; transition: transform 0.3s;"></i>
            </a>
            
            <!-- Submenu -->
        <div class="collapse submenu show" id="controlPanelSubmenu">
            <a href="addViolation.php" class="submenu-item <?= $activeTab === 'add_violation' ? 'active' : '' ?>">
                Add Violation
            </a>
            <a href="exceptionPlanner.php" class="submenu-item <?= $activeTab === 'exception_days' ? 'active' : '' ?>">
                Exception Days
            </a>
            <a href="scanningMode.php" class="submenu-item <?= $activeTab === 'violation_scanning' ? 'active' : '' ?>">
                General Settings
            </a>
        </div>

        </div>

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

<style>
/* Submenu Styles */
.nav-item-with-submenu {
    position: relative;
}

.nav-item {
    display: flex;
    align-items: center;
    justify-content: space-between;
    text-decoration: none;
    padding: 12px 15px;
    color: #333;
    border-radius: 8px;
    margin-bottom: 0px !important;
    margin-top: 0 !important;
    transition: background-color 0.3s ease;
}

.nav-item:hover {
    background-color: #f8f9fa;
    text-decoration: none;
    color: #0D67A1;
}

.nav-item.active {
    background-color: #0D67A1;
    color: white !important;
}

.nav-item.active .submenu-arrow {
    color: white !important;
}

.submenu {
    margin-top: 0;
    margin-left: 15px; /* shifts submenu to the right */
    background-color: transparent; /* removes background */
    border-radius: 0; /* optional: remove rounding if transparent */
    overflow: hidden;
}


.submenu-item {
    display: block;
    padding: 10px 15px 10px 45px; /* Extra left padding for indentation */
    color: #666;
    text-decoration: none;
    font-size: 14px;
    border-left: 3px solid transparent;
    transition: all 0.3s ease;
}

.submenu-item:hover {
    background-color: #e9ecef;
    color: #0D67A1;
    text-decoration: none;
    border-left-color: #0D67A1;
}

.submenu-item.active {
    background-color: #0D67A1;
    color: white !important;
    border-left-color: #0D67A1;
}

.submenu-arrow {
    font-size: 12px !important;
    transition: transform 0.3s ease;
}

/* Rotate arrow when expanded */
.nav-item[aria-expanded="true"] .submenu-arrow {
    transform: rotate(180deg);
}
</style>

<script>
// Optional: Add JavaScript for additional functionality
document.addEventListener('DOMContentLoaded', function() {
    const controlPanelToggle = document.getElementById('controlPanelToggle');
    const submenu = document.getElementById('controlPanelSubmenu');
    
    // Update arrow rotation when submenu is toggled
    submenu.addEventListener('show.bs.collapse', function() {
        controlPanelToggle.setAttribute('aria-expanded', 'true');
    });
    
    submenu.addEventListener('hide.bs.collapse', function() {
        controlPanelToggle.setAttribute('aria-expanded', 'false');
    });
});
</script>