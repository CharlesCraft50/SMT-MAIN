<?php include 'header.html';?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Select User</title>
    <link rel="stylesheet" href="assets/styles.css">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous"></head>
<body>
    <div class="container mt-5 mb-5" style="margin-top: 5rem !important;">
        <div class="content d-flex justify-content-center align-items-center">
            <h1 class="text-center roboto-black f-blue">SELECT USER TYPE</h1>
        </div>
    </div>

    <div class="container text-center mt-5" style="margin-top: 3rem !important;">
        <div class="row justify-content-center g-8">
            <!-- Admin -->
            <div class="col-4 col-sm-3 col-md-2 d-flex flex-column align-items-center" style="cursor:pointer;">
                <div class="btn-card w-100" id="adminBtn">
                    <i class="bi bi-person-fill"></i>
                </div>
                <p class="mt-4 roboto-medium card-p fs-2">ADMIN</p>
            </div>
            <!-- Staff -->
            <div class="col-4 col-sm-3 col-md-2 d-flex flex-column align-items-center" style="cursor:pointer;">
                <div class="btn-card w-100" id="staffBtn">
                    <i class="bi bi-person-fill"></i>
                </div>
                <p class="mt-4 roboto-medium card-p fs-2">STAFF</p>
            </div>
            <!-- Student -->
            <div class="col-4 col-sm-3 col-md-2 d-flex flex-column align-items-center" style="cursor:pointer;">
                <div class="btn-card w-100" id="studentBtn">
                    <i class="bi bi-person-fill"></i>
                </div>
                <p class="mt-4 roboto-medium card-p fs-2">STUDENT</p>
            </div>
        </div>
    </div>

    <!-- Admin Password Modal -->
    <div class="modal fade" id="adminModal" tabindex="-1" aria-labelledby="adminModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <form class="modal-content" id="submitAdminForm">
                <div class="modal-header">
                    <h5 class="modal-title" id="adminModalLabel">Enter Admin Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="password" class="form-control" id="adminPassword" placeholder="Password">
                    <div id="adminMessage" class="text-center mt-1"></div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary close__btn" data-bs-dismiss="modal">Close</button>
                    <button type="submit" class="btn btn-primary submit__btn" id="confirmAdmin">Submit</button>
                </div>
            </form>
        </div>
    </div>

    <script src="js/main.js" defer></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>