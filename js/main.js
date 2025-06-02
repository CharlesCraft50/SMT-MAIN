document.addEventListener("DOMContentLoaded", () => {
  const adminBtn = document.getElementById("adminBtn");
  const staffBtn = document.getElementById("staffBtn");
  const studentBtn = document.getElementById("studentBtn");

  const adminMessage = document.getElementById("adminMessage");
  const adminMainBtn = document.getElementById('adminMainBtn');
  const adminTwoBtn = document.getElementById('adminTwoBtn');
  const adminForm = document.getElementById('submitAdminForm');
  const adminTwoForm = document.getElementById('submitAdminTwoForm');
  const passwordAdminInput = document.getElementById("adminPassword");
  const passwordAdminTwoInput = document.getElementById("adminTwoPassword");

  adminBtn.addEventListener("click", () => {
    const adminModalEl = document.getElementById("adminModal");
    const adminModal = new bootstrap.Modal(adminModalEl);
    adminModal.show();

    adminMessage.innerHTML = "";
    adminMessage.className = "";
  });

  adminMainBtn.addEventListener("click", () => {
    const adminPasswordModalEl = document.getElementById("adminPasswordModal");
    const adminPasswordModal = new bootstrap.Modal(adminPasswordModalEl);
    adminPasswordModal.show();

    adminMessage.innerHTML = "";
    adminMessage.className = "";
    passwordAdminInput.value = "";

    adminPasswordModalEl.addEventListener('shown.bs.modal', () => {
      passwordAdminInput.focus();
    }, { once: true });
  });

  adminTwoBtn.addEventListener("click", () => {
    const adminTwoPasswordModalEl = document.getElementById("adminTwoPasswordModal");
    const adminTwoPasswordModal = new bootstrap.Modal(adminTwoPasswordModalEl);
    adminTwoPasswordModal.show();

    adminMessage.innerHTML = "";
    adminMessage.className = "";
    passwordAdminTwoInput.value = "";

    adminTwoPasswordModalEl.addEventListener('shown.bs.modal', () => {
      passwordAdminTwoInput.focus();
    }, { once: true });
  });


  staffBtn.addEventListener("click", () => {
    window.location.href = "staff/";
  });

  studentBtn.addEventListener("click", () => {
      window.location.href = "student/";
  });

  adminForm.addEventListener("submit", (e) => {
    e.preventDefault();

    let post = {
      password: passwordAdminInput.value
    };

    fetch("php-api/AccessAdmin.php", {
        method: 'post',
        body: JSON.stringify(post),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then((response) => {
        return response.json()
    }).then((res) => {
        if (res.status === 'success') {
            console.log(res.message);
            adminMessage.innerHTML = "Successful!. Redirecting...";
            adminMessage.className = "text-success mt-1";
            window.location.href = "admin/dashboard.php";
        } else if(res.status == 'failed') {
          console.log(res.message + ' pass: ' + post.password);
          adminMessage.innerHTML = "Incorrect password. Please try again.";
          adminMessage.className = "text-danger";
        }
    }).catch((error) => {
        console.log(error)
    });
  });

  adminTwoForm.addEventListener("submit", (e) => {
    e.preventDefault();

    let post = {
      passwordTwo: passwordAdminTwoInput.value
    };

    fetch("php-api/AccessAdmin.php", {
        method: 'post',
        body: JSON.stringify(post),
        headers: {
            'Accept': 'application/json',
            'Content-Type': 'application/json'
        }
    }).then((response) => {
        return response.json()
    }).then((res) => {
        if (res.status === 'success') {
            console.log(res.message);
            adminMessage.innerHTML = "Successful!. Redirecting...";
            adminMessage.className = "text-success mt-1";
            window.location.href = "admin-two/studentList.php";
        } else if(res.status == 'failed') {
          console.log(res.message + ' pass: ' + post.password);
          adminMessage.innerHTML = "Incorrect password. Please try again.";
          adminMessage.className = "text-danger";
        }
    }).catch((error) => {
        console.log(error)
    });
  });
});
