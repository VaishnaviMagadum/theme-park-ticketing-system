  <aside class="main-sidebar sidebar-dark-primary elevation-4">
    <div class="dropdown">
      <a href="./" class="brand-link">
        <div class="sidebar-brand-icon">
          <i class="fas fa-ticket-alt"></i>
        </div>
        <div class="sidebar-brand-text">
          <span class="sidebar-brand-name">Aurora Park</span>
          <span class="sidebar-role-badge">
            <?php echo $_SESSION['login_type'] == 1 ? 'Administrator' : 'Ticketing Staff' ?>
          </span>
        </div>
      </a>
    </div>
    <div class="sidebar pb-4 mb-4">
      <nav class="mt-2">
        <ul class="nav nav-pills nav-sidebar flex-column nav-flat" data-widget="treeview" role="menu" data-accordion="false">
          <li class="sidebar-section-label">Overview</li>
          <li class="nav-item dropdown">
            <a href="./" class="nav-link nav-home">
              <i class="nav-icon fas fa-tachometer-alt"></i>
              <p>Dashboard</p>
            </a>
          </li>

          <li class="sidebar-section-label">Park Operations</li>
          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_ride">
              <i class="nav-icon fas fa-tram"></i>
              <p>
                Rides
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_ride" class="nav-link nav-new_ride tree-item">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=rides_list" class="nav-link nav-rides_list tree-item">
                  <i class="fas fa-list-ul nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li> 
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_pricing">
              <i class="nav-icon fas fa-tags"></i>
              <p>
                Pricing
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_pricing" class="nav-link nav-new_pricing tree-item">
                  <i class="fas fa-plus-circle nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=pricing_list" class="nav-link nav-pricing_list tree-item">
                  <i class="fas fa-list-ul nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li> 
          <?php endif; ?>

          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_ticket">
              <i class="nav-icon fas fa-ticket-alt"></i>
              <p>
                Ticketing
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_ticket" class="nav-link nav-new_ticket tree-item">
                  <i class="fas fa-cash-register nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=ticket_list" class="nav-link nav-ticket_list tree-item">
                  <i class="fas fa-receipt nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>

          <?php if($_SESSION['login_type'] == 1): ?>
          <li class="sidebar-section-label">Analytics &amp; Team</li>
          <li class="nav-item dropdown">
            <a href="./index.php?page=reports" class="nav-link nav-reports">
              <i class="nav-icon fas fa-file-invoice-dollar"></i>
              <p>Sales Report</p>
            </a>
          </li>   
          <li class="nav-item">
            <a href="#" class="nav-link nav-edit_user">
              <i class="nav-icon fas fa-users"></i>
              <p>
                Users
                <i class="right fas fa-angle-left"></i>
              </p>
            </a>
            <ul class="nav nav-treeview">
              <li class="nav-item">
                <a href="./index.php?page=new_user" class="nav-link nav-new_user tree-item">
                  <i class="fas fa-user-plus nav-icon"></i>
                  <p>Add New</p>
                </a>
              </li>
              <li class="nav-item">
                <a href="./index.php?page=user_list" class="nav-link nav-user_list tree-item">
                  <i class="fas fa-address-book nav-icon"></i>
                  <p>List</p>
                </a>
              </li>
            </ul>
          </li>
          <?php endif; ?>
        </ul>
      </nav>
    </div>
  </aside>
  <script>
  	$(document).ready(function(){
      var page = '<?php echo isset($_GET['page']) ? $_GET['page'] : 'home' ?>';
  		var s = '<?php echo isset($_GET['s']) ? $_GET['s'] : '' ?>';
      if(s!='')
        page = page+'_'+s;
  		if($('.nav-link.nav-'+page).length > 0){
             $('.nav-link.nav-'+page).addClass('active')
  			if($('.nav-link.nav-'+page).hasClass('tree-item') == true){
            $('.nav-link.nav-'+page).closest('.nav-treeview').siblings('a').addClass('active')
  				$('.nav-link.nav-'+page).closest('.nav-treeview').parent().addClass('menu-open')
  			}
        if($('.nav-link.nav-'+page).hasClass('nav-is-tree') == true){
          $('.nav-link.nav-'+page).parent().addClass('menu-open')
        }
  		}
  	})
  </script>
