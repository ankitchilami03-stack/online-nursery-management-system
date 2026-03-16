<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Delivery Sidebar</title>
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Segoe UI', sans-serif;
      display: flex;
      flex-direction: row;
      height: 100vh;
      background-color: #f8f9fa;
    }

    #adminSidebar {
      background-color: #343a40;
      color: white;
      width: 250px;
      padding: 20px;
      transition: all 0.3s ease;
    }

    #adminSidebar h4 {
      margin-bottom: 30px;
      font-weight: normal;
    }

    #adminSidebar a {
      display: block;
      color: #ccc;
      text-decoration: none;
      margin-bottom: 15px;
      transition: color 0.3s ease;
    }

    #adminSidebar a:hover {
      color: #fff;
    }

    .toggle {
      display: flex;
      justify-content: space-between;
      align-items: center;
      cursor: pointer;
      margin-bottom: 10px;
    }

    .collapse {
      display: none;
    }

    .collapse.show {
      display: block;
    }

    .rotated {
      transform: rotate(180deg);
    }

    #mainContent {
      flex-grow: 1;
      padding: 30px;
    }

    /* Mobile Responsive */
    @media screen and (max-width: 768px) {
      body {
        flex-direction: column;
      }

      #adminSidebar {
        width: 100%;
        padding: 15px;
      }

      #mainContent {
        padding: 15px;
      }

      .toggle {
        font-size: 18px;
      }

      #adminSidebar h4 {
        font-size: 20px;
      }

      #adminSidebar a {
        font-size: 16px;
      }
    }
  </style>
</head>
<body>

  <div id="adminSidebar">
    <h4>📦 Delivery Panel</h4>

    <a href="#"><i class="fas fa-tachometer-alt"></i> Dashboard</a>

    <div class="toggle" onclick="toggleMenu('ordersSubMenu', 'ordersArrow')">
      <span><i class="fas fa-box"></i> Orders</span>
      <i class="fas fa-angle-down" id="ordersArrow"></i>
    </div>
    <div class="collapse" id="ordersSubMenu">
      <a href="#">View Orders</a>
      <a href="#">Assign Orders</a>
      <a href="#">Delivery Boys</a>
    </div>

    <div class="toggle mt-4" onclick="toggleMenu('settingsSubMenu', 'settingsArrow')">
      <span><i class="fas fa-cogs"></i> Settings</span>
      <i class="fas fa-angle-down" id="settingsArrow"></i>
    </div>
    <div class="collapse" id="settingsSubMenu">
      <a href="#">Profile</a>
      <a href="#">Change Password</a>
      <a href="#">Logout</a>
    </div>
  </div>

  <div id="mainContent">
    <h2>Welcome Delivery Agent</h2>
    <p>This is your dashboard. Choose an option from the sidebar.</p>
  </div>

  <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/js/all.min.js"></script>
  <script>
    function toggleMenu(menuId, arrowId) {
      const submenu = document.getElementById(menuId);
      const arrow = document.getElementById(arrowId);
      submenu.classList.toggle('show');
      arrow.classList.toggle('rotated');
    }
  </script>
</body>
</html>
