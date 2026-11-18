<?php 
session_start();
include('./db_connect.php');
ob_start();
$system = $conn->query("SELECT * FROM system_settings")->fetch_array();
foreach($system as $k => $v){
  $_SESSION['system'][$k] = $v;
}
ob_end_flush();

if(isset($_SESSION['login_id'])){
  header("location:index.php?page=home");
  exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<?php include 'header.php' ?>
<body class="aurora-login-page">
  <div class="login-orb login-orb-1"></div>
  <div class="login-orb login-orb-2"></div>
  <div class="login-orb login-orb-3"></div>

  <div class="aurora-login-shell">
    <!-- Left Showcase Panel -->
    <div class="aurora-login-hero">
      <div>
        <div class="login-brand-pill">
          <i class="fas fa-ticket-alt" style="color: #fde047;"></i>
          <span>Aurora Park Operations Suite</span>
        </div>
        <h1><?php echo $_SESSION['system']['name'] ?></h1>
        <p class="lead-sub">
          Streamline ride management, dynamic pricing tiers, instant visitor ticketing, and real-time park sales analytics from one vibrant command center.
        </p>

        <ul class="login-feature-list">
          <li>
            <span class="login-feature-icon" style="background: linear-gradient(135deg, #10b981, #06b6d4);">
              <i class="fas fa-bolt"></i>
            </span>
            <span>Instant POS Ticketing &amp; Thermal Stub Printing</span>
          </li>
          <li>
            <span class="login-feature-icon" style="background: linear-gradient(135deg, #f43f5e, #f97316);">
              <i class="fas fa-tags"></i>
            </span>
            <span>Flexible Adult &amp; Child Pricing Packages</span>
          </li>
          <li>
            <span class="login-feature-icon" style="background: linear-gradient(135deg, #f59e0b, #eab308);">
              <i class="fas fa-chart-bar"></i>
            </span>
            <span>Live Revenue Charts &amp; Printable Sales Reports</span>
          </li>
        </ul>
      </div>

      <div class="d-flex align-items-center pt-4 mt-4" style="border-top: 1px solid rgba(255,255,255,0.16); font-size: 0.88rem; font-weight: 600; color: rgba(255,255,255,0.9);">
        <span><i class="fas fa-star mr-2 text-warning"></i> Every ticket unlocks a little magic.</span>
      </div>
    </div>

    <!-- Right Login Form Panel -->
    <div class="aurora-login-form-pane">
      <div class="mb-3 d-flex align-items-center" style="gap: 10px;">
        <div style="width: 44px; height: 44px; border-radius: 14px; background: var(--grad-aurora); color: #fff; display: flex; align-items: center; justify-content: center; font-size: 1.15rem; box-shadow: var(--shadow-glow-indigo);">
          <i class="fas fa-lock-open"></i>
        </div>
        <div>
          <span class="badge-soft badge-soft-indigo">Admin &amp; Staff Portal</span>
        </div>
      </div>

      <h2>Welcome Back</h2>
      <p class="form-sub">Sign in with your park credentials to continue.</p>

      <form action="" id="login-form">
        <div class="form-group">
          <label for="login_email">Email Address</label>
          <div class="login-input-wrap">
            <i class="fas fa-envelope input-icon-left"></i>
            <input type="email" id="login_email" class="form-control" name="email" required placeholder="admin@admin.com" autocomplete="email">
          </div>
        </div>

        <div class="form-group">
          <label for="login_password">Password</label>
          <div class="login-input-wrap">
            <i class="fas fa-lock input-icon-left"></i>
            <input type="password" id="login_password" class="form-control" name="password" required placeholder="Enter your password" autocomplete="current-password">
            <button type="button" class="toggle-pass-btn" id="toggle-pass" title="Show/Hide Password">
              <i class="far fa-eye"></i>
            </button>
          </div>
        </div>

        <div class="d-flex align-items-center justify-content-between mb-4">
          <div class="icheck-primary">
            <input type="checkbox" id="remember">
            <label for="remember" style="text-transform: none; letter-spacing: 0; color: #475569; font-weight: 600 !important;">
              Remember Me
            </label>
          </div>
        </div>

        <button type="submit" class="btn btn-aurora btn-block py-3" style="font-size: 0.98rem;">
          Sign In to Dashboard <i class="fas fa-arrow-right ml-2"></i>
        </button>
      </form>
    </div>
  </div>

<script>
  $(document).ready(function(){
    $('#toggle-pass').click(function(){
      var input = $('#login_password');
      var icon = $(this).find('i');
      if(input.attr('type') === 'password'){
        input.attr('type', 'text');
        icon.removeClass('fa-eye').addClass('fa-eye-slash');
      } else {
        input.attr('type', 'password');
        icon.removeClass('fa-eye-slash').addClass('fa-eye');
      }
    });

    $('#login-form').submit(function(e){
      e.preventDefault()
      start_load()
      if($(this).find('.alert-danger').length > 0 )
        $(this).find('.alert-danger').remove();
      $.ajax({
        url:'ajax.php?action=login',
        method:'POST',
        data:$(this).serialize(),
        error:err=>{
          console.log(err)
          end_load();
        },
        success:function(resp){
          if(resp == 1){
            location.href ='index.php?page=home';
          }else{
            $('#login-form').prepend('<div class="alert alert-danger" style="border-radius:12px; font-weight:600; font-size:0.86rem;"><i class="fas fa-exclamation-triangle mr-2"></i>Username or password is incorrect.</div>')
            end_load();
          }
        }
      })
    })
  })
</script>
<?php include 'footer.php' ?>
</body>
</html>
