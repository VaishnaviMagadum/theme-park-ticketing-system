<?php
session_start();
if(!isset($_SESSION['login_id'])){
    header('location:login.php');
    exit;
}
include 'db_connect.php';
ob_start();
if(!isset($_SESSION['system'])){
  $system = $conn->query("SELECT * FROM system_settings")->fetch_array();
  foreach($system as $k => $v){
    $_SESSION['system'][$k] = $v;
  }
}
ob_end_flush();

$current_page = isset($_GET['page']) ? $_GET['page'] : 'home';
$page_meta = [
  'home'         => ['icon' => 'fa-chart-pie',    'sub' => 'Live park overview, ticket analytics & quick operations'],
  'rides_list'   => ['icon' => 'fa-tram',         'sub' => 'Manage all theme park rides and attractions'],
  'new_ride'     => ['icon' => 'fa-plus-circle',  'sub' => 'Add a new attraction to the park lineup'],
  'edit_ride'    => ['icon' => 'fa-edit',         'sub' => 'Update ride name and attraction details'],
  'pricing_list' => ['icon' => 'fa-tags',         'sub' => 'Configure adult and child ticket tiers'],
  'new_pricing'  => ['icon' => 'fa-tag',          'sub' => 'Create a new pricing package for rides or entrance'],
  'edit_pricing' => ['icon' => 'fa-edit',         'sub' => 'Modify existing ticket pricing rates'],
  'ticket_list'  => ['icon' => 'fa-ticket-alt',   'sub' => 'View and manage issued visitor tickets'],
  'new_ticket'   => ['icon' => 'fa-cash-register','sub' => 'Fast POS checkout and instant ticket printing'],
  'edit_ticket'  => ['icon' => 'fa-ticket-alt',   'sub' => 'Update an existing ticket order'],
  'reports'      => ['icon' => 'fa-file-invoice-dollar', 'sub' => 'Filter, analyze and print park sales reports'],
  'user_list'    => ['icon' => 'fa-users-cog',    'sub' => 'Manage administrator and ticketing staff accounts'],
  'new_user'     => ['icon' => 'fa-user-plus',    'sub' => 'Create a new admin or staff account'],
  'edit_user'    => ['icon' => 'fa-user-edit',    'sub' => 'Update user credentials and permissions'],
];
$p_icon = isset($page_meta[$current_page]) ? $page_meta[$current_page]['icon'] : 'fa-compass';
$p_sub  = isset($page_meta[$current_page]) ? $page_meta[$current_page]['sub']  : 'Theme Park Ticketing Control Center';
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'header.php' ?>
<body class="hold-transition sidebar-mini layout-fixed layout-navbar-fixed layout-footer-fixed">
<div class="wrapper">
  <?php include 'topbar.php' ?>
  <?php include 'sidebar.php' ?>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">
    <div class="toast" id="alert_toast" role="alert" aria-live="assertive" aria-atomic="true">
      <div class="toast-body text-white"></div>
    </div>
    <div id="toastsContainerTopRight" class="toasts-top-right fixed"></div>

    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="page-header-banner">
          <div class="page-title-group">
            <div class="page-title-icon">
              <i class="fas <?php echo $p_icon ?>"></i>
            </div>
            <div>
              <h1><?php echo $title ?></h1>
              <p class="page-subtitle"><?php echo $p_sub ?></p>
            </div>
          </div>
          <div class="d-flex align-items-center" style="gap: 8px;">
            <span class="badge-soft badge-soft-indigo">
              <i class="far fa-calendar-alt"></i> <?php echo date('D, M d, Y') ?>
            </span>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
         <?php 
            $page = isset($_GET['page']) ? $_GET['page'] : 'home';
            if(!file_exists($page.".php")){
                include '404.html';
            }else{
                include $page.'.php';
            }
          ?>
      </div><!--/. container-fluid -->
    </section>
    <!-- /.content -->

    <div class="modal fade" id="confirm_modal" role='dialog'>
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"><i class="fas fa-exclamation-circle mr-2"></i>Confirmation</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body py-4">
            <div id="delete_content"></div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id='confirm' onclick="">Continue</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="uni_modal" role='dialog'>
      <div class="modal-dialog modal-md modal-dialog-centered" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body"></div>
          <div class="modal-footer">
            <button type="button" class="btn btn-primary" id='submit' onclick="$('#uni_modal form').submit()">Save</button>
            <button type="button" class="btn btn-secondary" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="uni_modal_right" role='dialog'>
      <div class="modal-dialog modal-full-height modal-md" role="document">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title"></h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
              <span class="fa fa-arrow-right"></span>
            </button>
          </div>
          <div class="modal-body"></div>
        </div>
      </div>
    </div>

    <div class="modal fade" id="viewer_modal" role='dialog'>
      <div class="modal-dialog modal-md" role="document">
        <div class="modal-content">
          <button type="button" class="btn-close" data-dismiss="modal"><span class="fa fa-times"></span></button>
          <img src="" alt="">
        </div>
      </div>
    </div>
  </div>
  <!-- /.content-wrapper -->

  <!-- Control Sidebar -->
  <aside class="control-sidebar control-sidebar-dark"></aside>
  <!-- /.control-sidebar -->

  <!-- Main Footer -->
  <footer class="main-footer d-flex justify-content-between align-items-center flex-wrap">
    <div>
      <strong style="color: #4f46e5;"><?php echo $_SESSION['system']['name'] ?></strong> &mdash; Smart Park Ticketing &amp; Operations Suite
    </div>
    <div class="d-none d-sm-inline-block">
      <span class="badge-soft badge-soft-emerald"><i class="fas fa-shield-alt"></i> Live System</span>
    </div>
  </footer>
</div>
<!-- ./wrapper -->

<?php include 'footer.php' ?>
</body>
</html>
