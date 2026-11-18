<?php include('db_connect.php') ?>
<?php
// Live metrics for the dashboard
$total_rides   = $conn->query("SELECT * FROM rides")->num_rows;
$total_pricing = $conn->query("SELECT * FROM pricing")->num_rows;

$sales_today_q = $conn->query("SELECT COALESCE(SUM(amount), 0) as sales FROM ticket_list WHERE date(date_created) = '".date('Y-m-d')."'");
$sales_today   = $sales_today_q ? (float)$sales_today_q->fetch_assoc()['sales'] : 0;

$totals_q = $conn->query("SELECT COUNT(*) as orders, COALESCE(SUM(amount), 0) as revenue, COALESCE(SUM(no_adult), 0) as adults, COALESCE(SUM(no_child), 0) as children FROM ticket_list");
$totals   = $totals_q ? $totals_q->fetch_assoc() : ['orders' => 0, 'revenue' => 0, 'adults' => 0, 'children' => 0];
$total_orders   = (int)$totals['orders'];
$total_revenue  = (float)$totals['revenue'];
$total_adults   = (int)$totals['adults'];
$total_children = (int)$totals['children'];
$total_visitors = $total_adults + $total_children;

// Revenue breakdown by pricing package for Chart.js
$pkg_labels = [];
$pkg_sales  = [];
$pkg_q = $conn->query("SELECT p.name, COALESCE(SUM(t.amount), 0) as total_amt FROM pricing p LEFT JOIN ticket_list t ON t.pricing_id = p.id GROUP BY p.id, p.name ORDER BY total_amt DESC");
if($pkg_q){
  while($prow = $pkg_q->fetch_assoc()){
    $pkg_labels[] = ucwords($prow['name']);
    $pkg_sales[]  = (float)$prow['total_amt'];
  }
}

$avatar_grads = [
  'linear-gradient(135deg, #6366f1, #8b5cf6)',
  'linear-gradient(135deg, #ec4899, #f43f5e)',
  'linear-gradient(135deg, #10b981, #06b6d4)',
  'linear-gradient(135deg, #f59e0b, #f97316)',
  'linear-gradient(135deg, #3b82f6, #6366f1)'
];
?>

<!-- Welcome Hero Banner -->
<div class="dashboard-welcome-hero">
  <div class="row align-items-center" style="position: relative; z-index: 2;">
    <div class="col-lg-8 mb-3 mb-lg-0">
      <span class="badge-soft mb-2" style="background: rgba(255,255,255,0.18); color: #fde68a;">
        <i class="fas fa-magic"></i> <?php echo $_SESSION['login_type'] == 1 ? 'Admin Control Center' : 'Staff Ticketing Desk' ?>
      </span>
      <h2>Welcome back, <?php echo ucwords($_SESSION['login_name']) ?>!</h2>
      <p>
        Monitor ride attractions, issue visitor passes in seconds, and track real-time park ticketing revenue.
      </p>
    </div>
    <div class="col-lg-4 text-lg-right d-flex flex-wrap justify-content-lg-end" style="gap: 10px;">
      <a href="./index.php?page=new_ticket" class="btn btn-glass-white">
        <i class="fas fa-plus-circle mr-1"></i> Issue New Ticket
      </a>
      <?php if($_SESSION['login_type'] == 1): ?>
      <a href="./index.php?page=reports" class="btn btn-glass-white">
        <i class="fas fa-chart-line mr-1"></i> Sales Report
      </a>
      <?php endif; ?>
    </div>
  </div>
</div>

<?php if($_SESSION['login_type'] == 1): ?>
<!-- Admin KPI Stat Cards -->
<div class="row">
  <div class="col-12 col-sm-6 col-xl-3">
    <div class="kpi-card kpi-indigo">
      <div class="kpi-top">
        <span class="kpi-label">Total Rides</span>
        <div class="kpi-icon-box"><i class="fas fa-tram"></i></div>
      </div>
      <div class="kpi-value"><?php echo number_format($total_rides) ?></div>
      <div class="kpi-meta"><i class="fas fa-tags"></i> <?php echo number_format($total_pricing) ?> active pricing tiers</div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="kpi-card kpi-emerald">
      <div class="kpi-top">
        <span class="kpi-label">Total Sales Today</span>
        <div class="kpi-icon-box"><i class="fas fa-chart-line"></i></div>
      </div>
      <div class="kpi-value"><?php echo number_format($sales_today, 2) ?></div>
      <div class="kpi-meta"><i class="far fa-calendar-check"></i> <?php echo date('M d, Y') ?></div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="kpi-card kpi-sunset">
      <div class="kpi-top">
        <span class="kpi-label">Tickets Sold</span>
        <div class="kpi-icon-box"><i class="fas fa-ticket-alt"></i></div>
      </div>
      <div class="kpi-value"><?php echo number_format($total_visitors) ?></div>
      <div class="kpi-meta"><i class="fas fa-receipt"></i> Across <?php echo number_format($total_orders) ?> bookings</div>
    </div>
  </div>

  <div class="col-12 col-sm-6 col-xl-3">
    <div class="kpi-card kpi-amber">
      <div class="kpi-top">
        <span class="kpi-label">Total Revenue</span>
        <div class="kpi-icon-box"><i class="fas fa-coins"></i></div>
      </div>
      <div class="kpi-value"><?php echo number_format($total_revenue, 2) ?></div>
      <div class="kpi-meta"><i class="fas fa-users"></i> <?php echo number_format($total_adults) ?> Adults &bull; <?php echo number_format($total_children) ?> Kids</div>
    </div>
  </div>
</div>

<!-- Analytics Charts Row -->
<div class="row">
  <div class="col-lg-7 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h3 class="card-title-modern">
          <span class="badge-soft badge-soft-indigo"><i class="fas fa-chart-bar"></i></span>
          Revenue by Ticket Package
        </h3>
        <span class="badge-soft badge-soft-violet">Live Database</span>
      </div>
      <div class="card-body">
        <div style="height: 270px; position: relative;">
          <canvas id="packageRevenueChart"></canvas>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-5 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h3 class="card-title-modern">
          <span class="badge-soft badge-soft-pink"><i class="fas fa-users"></i></span>
          Visitor Breakdown
        </h3>
        <span class="badge-soft badge-soft-emerald"><?php echo number_format($total_visitors) ?> Passes</span>
      </div>
      <div class="card-body d-flex flex-column justify-content-between">
        <div style="height: 205px; position: relative;">
          <canvas id="visitorMixChart"></canvas>
        </div>
        <div class="row text-center mt-3 pt-2 border-top">
          <div class="col-6 border-right">
            <div style="font-size: 0.74rem; font-weight: 700; text-transform: uppercase; color: #64748b;">Adult Passes</div>
            <div class="font-mono-num" style="font-size: 1.35rem; font-weight: 800; color: #4f46e5;"><?php echo number_format($total_adults) ?></div>
          </div>
          <div class="col-6">
            <div style="font-size: 0.74rem; font-weight: 700; text-transform: uppercase; color: #64748b;">Child Passes</div>
            <div class="font-mono-num" style="font-size: 1.35rem; font-weight: 800; color: #ec4899;"><?php echo number_format($total_children) ?></div>
          </div>
        </div>
      </div>
    </div>
  </div>
</div>

<!-- Recent Tickets + Quick Actions -->
<div class="row">
  <div class="col-lg-8 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h3 class="card-title-modern">
          <span class="badge-soft badge-soft-emerald"><i class="fas fa-receipt"></i></span>
          Recent Ticket Transactions
        </h3>
        <a href="./index.php?page=ticket_list" class="btn btn-sm btn-aurora">View All <i class="fas fa-arrow-right ml-1"></i></a>
      </div>
      <div class="card-body p-0">
        <div class="table-responsive">
          <table class="table">
            <thead>
              <tr>
                <th>Customer</th>
                <th>Package</th>
                <th class="text-center">Guests</th>
                <th class="text-right">Amount</th>
                <th>Date</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $recent_q = $conn->query("SELECT t.*, p.name as ticket_for FROM ticket_list t INNER JOIN pricing p ON p.id = t.pricing_id ORDER BY unix_timestamp(t.date_created) DESC LIMIT 6");
              $idx = 0;
              if($recent_q && $recent_q->num_rows > 0):
                while($r = $recent_q->fetch_assoc()):
                  $parts = explode(' ', trim($r['name']));
                  $ini = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
                  $grad = $avatar_grads[$idx % count($avatar_grads)];
                  $idx++;
              ?>
              <tr>
                <td>
                  <div class="d-flex align-items-center">
                    <span class="customer-avatar-pill" style="background: <?php echo $grad ?>;"><?php echo $ini ?></span>
                    <strong><?php echo ucwords($r['name']) ?></strong>
                  </div>
                </td>
                <td><span class="badge-soft badge-soft-violet"><?php echo ucwords($r['ticket_for']) ?></span></td>
                <td class="text-center">
                  <span class="badge-soft badge-soft-indigo"><?php echo (int)$r['no_adult'] ?>A</span>
                  <span class="badge-soft badge-soft-pink"><?php echo (int)$r['no_child'] ?>C</span>
                </td>
                <td class="text-right font-mono-num" style="font-weight: 800; color: #059669;">
                  <?php echo number_format((float)$r['amount'], 2) ?>
                </td>
                <td class="text-muted" style="font-size: 0.82rem; font-weight: 600;">
                  <?php echo date("M d, Y", strtotime($r['date_created'])) ?>
                </td>
              </tr>
              <?php endwhile; else: ?>
              <tr>
                <td colspan="5" class="text-center text-muted py-4">No tickets issued yet. Click <b>Issue New Ticket</b> to create one!</td>
              </tr>
              <?php endif; ?>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>

  <div class="col-lg-4 mb-4">
    <div class="card h-100">
      <div class="card-header">
        <h3 class="card-title-modern">
          <span class="badge-soft badge-soft-amber"><i class="fas fa-bolt"></i></span>
          Quick Actions
        </h3>
      </div>
      <div class="card-body">
        <a href="./index.php?page=new_ticket" class="quick-action-tile">
          <div class="quick-action-icon" style="background: var(--grad-aurora);"><i class="fas fa-cash-register"></i></div>
          <div>
            <div style="font-weight: 800; font-size: 0.92rem;">Issue New Ticket</div>
            <div style="font-size: 0.78rem; color: #64748b;">Fast POS checkout &amp; print stub</div>
          </div>
        </a>
        <a href="./index.php?page=new_ride" class="quick-action-tile">
          <div class="quick-action-icon" style="background: var(--grad-emerald);"><i class="fas fa-tram"></i></div>
          <div>
            <div style="font-weight: 800; font-size: 0.92rem;">Add New Ride</div>
            <div style="font-size: 0.78rem; color: #64748b;">Expand park rides &amp; attractions</div>
          </div>
        </a>
        <a href="./index.php?page=pricing_list" class="quick-action-tile">
          <div class="quick-action-icon" style="background: var(--grad-sunset);"><i class="fas fa-tags"></i></div>
          <div>
            <div style="font-weight: 800; font-size: 0.92rem;">Manage Pricing Tiers</div>
            <div style="font-size: 0.78rem; color: #64748b;">Update adult &amp; child ticket rates</div>
          </div>
        </a>
        <a href="./index.php?page=reports" class="quick-action-tile mb-0">
          <div class="quick-action-icon" style="background: var(--grad-amber);"><i class="fas fa-file-invoice-dollar"></i></div>
          <div>
            <div style="font-weight: 800; font-size: 0.92rem;">Generate Sales Report</div>
            <div style="font-size: 0.78rem; color: #64748b;">Filter by date &amp; print summary</div>
          </div>
        </a>
      </div>
    </div>
  </div>
</div>

<script>
$(window).on('load', function(){
  if (typeof Chart === 'undefined') return;

  var pkgLabels = <?php echo json_encode($pkg_labels); ?>;
  var pkgSales  = <?php echo json_encode($pkg_sales); ?>;
  var ctx1 = document.getElementById('packageRevenueChart');
  if (ctx1) {
    new Chart(ctx1.getContext('2d'), {
      type: 'bar',
      data: {
        labels: pkgLabels,
        datasets: [{
          label: 'Revenue',
          data: pkgSales,
          backgroundColor: [
            'rgba(99, 102, 241, 0.88)',
            'rgba(236, 72, 153, 0.88)',
            'rgba(16, 185, 129, 0.88)',
            'rgba(245, 158, 11, 0.88)',
            'rgba(6, 182, 212, 0.88)',
            'rgba(139, 92, 246, 0.88)'
          ],
          borderRadius: 10,
          maxBarThickness: 52
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        legend: { display: false },
        scales: {
          yAxes: [{
            ticks: { beginAtZero: true, fontColor: '#64748b', fontStyle: 'bold' },
            gridLines: { color: 'rgba(99, 102, 241, 0.08)', drawBorder: false }
          }],
          xAxes: [{
            ticks: { fontColor: '#1e1b4b', fontStyle: 'bold' },
            gridLines: { display: false }
          }]
        }
      }
    });
  }

  var ctx2 = document.getElementById('visitorMixChart');
  if (ctx2) {
    new Chart(ctx2.getContext('2d'), {
      type: 'doughnut',
      data: {
        labels: ['Adults', 'Children'],
        datasets: [{
          data: [<?php echo $total_adults ?>, <?php echo $total_children ?>],
          backgroundColor: ['#6366f1', '#ec4899'],
          borderWidth: 4,
          borderColor: '#ffffff'
        }]
      },
      options: {
        responsive: true,
        maintainAspectRatio: false,
        cutoutPercentage: 68,
        legend: {
          position: 'bottom',
          labels: { usePointStyle: true, padding: 16, fontColor: '#1e1b4b', fontStyle: 'bold' }
        }
      }
    });
  }
});
</script>

<?php else: ?>
<!-- Staff View -->
<div class="row">
  <div class="col-md-6">
    <div class="kpi-card kpi-emerald">
      <div class="kpi-top">
        <span class="kpi-label">Today's Park Sales</span>
        <div class="kpi-icon-box"><i class="fas fa-cash-register"></i></div>
      </div>
      <div class="kpi-value"><?php echo number_format($sales_today, 2) ?></div>
      <div class="kpi-meta"><i class="far fa-calendar-check"></i> <?php echo date('M d, Y') ?></div>
    </div>
  </div>
  <div class="col-md-6">
    <div class="kpi-card kpi-indigo">
      <div class="kpi-top">
        <span class="kpi-label">Total Bookings</span>
        <div class="kpi-icon-box"><i class="fas fa-ticket-alt"></i></div>
      </div>
      <div class="kpi-value"><?php echo number_format($total_orders) ?></div>
      <div class="kpi-meta"><i class="fas fa-users"></i> <?php echo number_format($total_visitors) ?> total visitor passes</div>
    </div>
  </div>
</div>

<div class="row">
  <div class="col-md-6">
    <a href="./index.php?page=new_ticket" class="quick-action-tile">
      <div class="quick-action-icon" style="background: var(--grad-aurora);"><i class="fas fa-plus"></i></div>
      <div>
        <div style="font-weight: 800;">Issue New Ticket</div>
        <div style="font-size: 0.82rem; color: #64748b;">Open POS checkout to generate visitor tickets</div>
      </div>
    </a>
  </div>
  <div class="col-md-6">
    <a href="./index.php?page=ticket_list" class="quick-action-tile">
      <div class="quick-action-icon" style="background: var(--grad-sunset);"><i class="fas fa-list"></i></div>
      <div>
        <div style="font-weight: 800;">View Issued Tickets</div>
        <div style="font-size: 0.82rem; color: #64748b;">Browse, edit, or manage customer tickets</div>
      </div>
    </a>
  </div>
</div>
<?php endif; ?>
