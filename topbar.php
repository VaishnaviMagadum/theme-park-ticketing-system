<!-- Navbar -->
  <nav class="main-header navbar navbar-expand navbar-light">
    <!-- Left navbar links -->
    <ul class="navbar-nav align-items-center">
      <?php if(isset($_SESSION['login_id'])): ?>
      <li class="nav-item">
        <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
      </li>
      <?php endif; ?>
      <li class="nav-item d-none d-sm-inline-block">
        <a class="nav-link" href="./" role="button">
          <span class="navbar-brand-title"><?php echo $_SESSION['system']['name'] ?></span>
        </a>
      </li>
      <li class="nav-item d-none d-md-inline-block ml-2">
        <span class="park-status-pill">
          <span class="park-status-dot"></span> Park Open
        </span>
      </li>
    </ul>

    <ul class="navbar-nav ml-auto align-items-center" style="gap: 8px;">
      <li class="nav-item d-none d-sm-inline-block">
        <a href="./index.php?page=new_ticket" class="btn btn-sm btn-aurora">
          <i class="fas fa-ticket-alt mr-1"></i> Issue Ticket
        </a>
      </li>
      <li class="nav-item">
        <a class="nav-link" data-widget="fullscreen" href="#" role="button" title="Toggle Fullscreen">
          <i class="fas fa-expand-arrows-alt"></i>
        </a>
      </li>
      <li class="nav-item dropdown">
        <a class="nav-link p-0" data-toggle="dropdown" aria-expanded="false" href="javascript:void(0)">
          <?php
            $fn = isset($_SESSION['login_firstname']) ? $_SESSION['login_firstname'] : 'U';
            $ln = isset($_SESSION['login_lastname']) ? $_SESSION['login_lastname'] : '';
            $initials = strtoupper(substr($fn, 0, 1) . ($ln ? substr($ln, 0, 1) : ''));
          ?>
          <div class="nav-user-chip">
            <span class="nav-user-avatar"><?php echo $initials ?></span>
            <span class="d-none d-sm-inline" style="font-size: 0.86rem; font-weight: 700; color: #1e1b4b;">
              <?php echo ucwords($_SESSION['login_firstname']) ?>
            </span>
            <span class="fa fa-angle-down text-muted" style="font-size: 0.78rem;"></span>
          </div>
        </a>
        <div class="dropdown-menu dropdown-menu-right" aria-labelledby="account_settings">
          <div class="px-3 py-2 border-bottom mb-1">
            <div style="font-weight: 800; font-size: 0.88rem; color: #1e1b4b;"><?php echo ucwords($_SESSION['login_name']) ?></div>
            <div style="font-size: 0.76rem; color: #64748b;"><?php echo $_SESSION['login_email'] ?></div>
          </div>
          <a class="dropdown-item" href="javascript:void(0)" id="manage_account">
            <i class="fa fa-cog mr-2 text-primary"></i> Manage Account
          </a>
          <a class="dropdown-item text-danger" href="ajax.php?action=logout">
            <i class="fa fa-power-off mr-2"></i> Logout
          </a>
        </div>
      </li>
    </ul>
  </nav>
  <!-- /.navbar -->
  <script>
     $('#manage_account').click(function(){
        uni_modal('<i class="fas fa-user-cog mr-2"></i>Manage Account','manage_user.php?id=<?php echo $_SESSION['login_id'] ?>')
      })
  </script>
