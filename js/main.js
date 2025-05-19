document.addEventListener("DOMContentLoaded", () => {
  const adminBtn = document.getElementById("adminBtn");
  const staffBtn = document.getElementById("staffBtn");
  const studentBtn = document.getElementById("studentBtn");

  const adminMessage = document.getElementById("adminMessage");
  const adminForm = document.getElementById('submitAdminForm');
  const passwordAdminInput = document.getElementById("adminPassword");

  adminBtn.addEventListener("click", () => {
    const adminModalEl = document.getElementById("adminModal");
    const adminModal = new bootstrap.Modal(adminModalEl);
    adminModal.show();

    adminMessage.innerHTML = "";
    adminMessage.className = "";

    adminModalEl.addEventListener('shown.bs.modal', () => {
      passwordAdminInput.focus();
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
            setTimeout(() => {
              window.location.href = "admin/dashboard.php";
          }, 1500);
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
