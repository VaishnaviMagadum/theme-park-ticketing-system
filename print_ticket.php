<?php include 'db_connect.php' ?>
<?php 
$ride['all'] = "All Rides";
$ride[0] = "Entrance Only";
$rides = $conn->query("SELECT * FROM rides order by ride asc");
while($row=$rides->fetch_assoc()){
  $ride[$row['id']] = ucwords($row['ride']);
}
$ticket = $conn->query("SELECT t.*,p.name as ticket_for FROM ticket_list t inner join pricing p on p.id = t.pricing_id where t.id =".$_GET['id'])->fetch_array();
foreach($ticket as $k => $v){
	$$k = $v;
}
$tarr= array('','Adult','Child');
$ticket_items = $conn->query("SELECT * FROM ticket_items where ticket_id = $id");
?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>Print Ticket | Theme Park</title>
<style>
	* { box-sizing: border-box; font-family: 'Segoe UI', Arial, sans-serif; }
	body { background: #f3f5fc; padding: 20px; margin: 0; }
	.ticket-stub {
		max-width: 340px;
		margin: 0 auto 18px;
		background: #ffffff;
		border-radius: 16px;
		overflow: hidden;
		border: 1.5px solid #e2e8f0;
		box-shadow: 0 8px 20px rgba(30, 27, 75, 0.08);
		page-break-inside: avoid;
	}
	.ticket-header {
		background: linear-gradient(135deg, #4f46e5 0%, #8b5cf6 50%, #ec4899 100%);
		color: #ffffff;
		padding: 14px 16px;
		text-align: center;
	}
	.ticket-header h2 { margin: 0; font-size: 16px; letter-spacing: 0.04em; text-transform: uppercase; }
	.ticket-header .pkg { font-size: 13px; opacity: 0.92; margin-top: 3px; font-weight: 600; }
	.ticket-body { padding: 14px 16px; }
	.ticket-badge {
		display: inline-block;
		padding: 4px 14px;
		border-radius: 99px;
		font-size: 12px;
		font-weight: 800;
		text-transform: uppercase;
		letter-spacing: 0.06em;
	}
	.badge-adult { background: #eef2ff; color: #4338ca; }
	.badge-child { background: #fdf2f8; color: #be185d; }
	.ticket-divider { border-top: 2px dashed #cbd5e1; margin: 12px 0; }
	.ticket-row { display: flex; justify-content: space-between; font-size: 12px; margin-bottom: 6px; color: #475569; }
	.ticket-row strong { color: #0f172a; font-family: 'Consolas', monospace; font-size: 13px; }
	@media print {
		body { background: #ffffff; padding: 0; }
		.ticket-stub { box-shadow: none; border: 1px solid #94a3b8; }
	}
</style>
</head>
<body>
<?php while($row = $ticket_items->fetch_assoc()): ?>
	<div class="ticket-stub">
		<div class="ticket-header">
			<h2>Theme Park Pass</h2>
			<div class="pkg"><?php echo ucwords($ticket_for) ?></div>
		</div>
		<div class="ticket-body">
			<div style="text-align: center; margin-bottom: 8px;">
				<span class="ticket-badge <?php echo $row['type'] == 1 ? 'badge-adult' : 'badge-child' ?>">
					<?php echo $tarr[$row['type']] ?> Admission
				</span>
			</div>
			<div class="ticket-divider"></div>
			<div class="ticket-row">
				<span>Ticket No.</span>
				<strong><?php echo $row['ticket_no'] ?></strong>
			</div>
			<div class="ticket-row">
				<span>Ride Access</span>
				<strong><?php echo isset($ride[$row['ride_id']]) ? $ride[$row['ride_id']] : 'Park' ?></strong>
			</div>
			<div class="ticket-row" style="margin-bottom: 0;">
				<span>Issued Date</span>
				<strong><?php echo date("M d, Y",strtotime($row['date_created'])) ?></strong>
			</div>
		</div>
	</div>
<?php endwhile; ?>
</body>
</html>
