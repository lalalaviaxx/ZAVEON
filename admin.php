<?php
// Removed PHP variables for applicants and reviews since they are no longer needed.
?>

<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <title>YG Entertainment - Admin Dashboard</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" />
  <style>
    body {
      font-family: 'Segoe UI', sans-serif;
      margin: 0;
      background-color: #fff;
      color: #000;
    }

    .sidebar {
      height: 100vh;
      background-color: #000;
      padding-top: 20px;
      color: white;
      position: fixed;
      width: 250px;
      border-right: 1px solid #444;
    }

    .sidebar h4 {
      text-align: center;
      margin-bottom: 30px;
      font-weight: bold;
      color: #fff;
    }

    .sidebar a {
      display: block;
      padding: 12px 20px;
      color: #ccc;
      text-decoration: none;
      transition: background-color 0.3s, transform 0.3s;
      border-radius: 8px;
    }

    .sidebar a:hover,
    .sidebar a.active {
      background-color: #222;
      color: #fff;
      transform: translateX(5px);
    }

    .main-content {
      margin-left: 250px;
      padding: 20px;
      background-color: #fff;
      color: #000;
      min-height: 100vh;
    }

    h2 {
      color: #000;
    }

    .card {
      background-color: #f8f9fa;
      border: 1px solid #ccc;
      color: #000;
      border-radius: 12px;
    }

    .table {
      color: #000;
    }

    .table thead {
      background-color: #000;
      color: #fff;
    }

    .form-control {
      background-color: #fff;
      color: #000;
      border: 1px solid #ccc;
    }

    .form-control::placeholder {
      color: #666;
    }

    .btn-dark {
      background-color: #000;
      border: 1px solid #000;
      color: #fff;
    }

    .btn-secondary {
      background-color: #333;
      border: 1px solid #000;
      color: #fff;
    }

    .btn-primary {
      background-color: #000;
      color: #fff;
      border: 1px solid #000;
    }

    .btn-danger {
      background-color: #fff;
      color: #000;
      border: 1px solid #000;
    }

    .btn:hover {
      opacity: 0.9;
    }

    input[type="file"]::file-selector-button {
      background-color: #000;
      color: #fff;
      border: 1px solid #333;
    }

    .section-title {
      margin-bottom: 20px;
      font-weight: bold;
      font-size: 1.8rem;
    }

    .dashboard-box {
      border: 1px solid #000;
      padding: 20px;
      border-radius: 10px;
      background: #fff;
      box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
    }

    .dashboard-box:hover {
      box-shadow: 0 6px 12px rgba(0, 0, 0, 0.2);
    }
  </style>
</head>
<body>

  <!-- Sidebar -->
  <div class="sidebar">
    <h4>YG PANEL</h4>
    <a href="#dashboard" class="active">Dashboard</a>
    <a href="applicant.php">Applicant List</a>
    <a href="applicantstatus.php">Application Status</a>
    <a href="artist.php">Artists</a>
    <a href="settings.php">Settings</a>
    <a href="logout.php">Log Out</a>
  </div>

  <!-- Main Content -->
  <div class="main-content">
    <section id="dashboard">
      <div class="section-title">Dashboard Overview</div>
      <div class="row g-4">
        <div class="col-md-6 col-lg-4">
          <div class="dashboard-box text-center">
            <h4>Total Applicants</h4>
            <p>4</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="dashboard-box text-center">
            <h4>Pending Reviews</h4>
            <p>182</p>
          </div>
        </div>
        <div class="col-md-6 col-lg-4">
          <div class="dashboard-box text-center">
            <h4>Approved Artists</h4>
            <p>76</p>
          </div>
        </div>
      </div>
    </section>
  </div>

  <script>
    const links = document.querySelectorAll(".sidebar a");
    window.addEventListener("hashchange", () => {
      links.forEach(link => link.classList.remove("active"));
      const active = document.querySelector(`.sidebar a[href="${window.location.hash}"]`);
      if (active) active.classList.add("active");
    });
  </script>
</body>
</html>
