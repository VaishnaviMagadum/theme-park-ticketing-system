<?php include'db_connect.php' ?>
<div class="col-lg-12 px-0">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-pink"><i class="fas fa-tags"></i></span>
				Ticket Pricing Packages
			</h3>
			<div class="card-tools">
				<a class="btn btn-sm btn-aurora" href="./index.php?page=new_pricing">
					<i class="fa fa-plus mr-1"></i> Add New Pricing
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover" id="list">
					<thead>
						<tr>
							<th class="text-center" style="width: 60px;">#</th>
							<th>Ticket For</th>
							<th>Ride Access</th>
							<th class="text-right">Adult Price</th>
							<th class="text-right">Child Price</th>
							<th class="text-center" style="width: 130px;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$ride['all'] = "All Rides";
						$ride[0] = "Entrance Only";
						$rides = $conn->query("SELECT * FROM rides order by ride asc");
						while($row=$rides->fetch_assoc()){
							$ride[$row['id']] = ucwords($row['ride']);
						}
						$qry = $conn->query("SELECT * FROM pricing order by name asc ");
						while($row= $qry->fetch_assoc()):
							$r_label = isset($ride[$row['ride_id']]) ? $ride[$row['ride_id']] : 'N/A';
							$r_badge = $row['ride_id'] === 'all' ? 'badge-soft-pink' : ((string)$row['ride_id'] === '0' ? 'badge-soft-cyan' : 'badge-soft-indigo');
						?>
						<tr>
							<td class="text-center font-mono-num text-muted"><b><?php echo $i++ ?></b></td>
							<td><strong style="color: #1e1b4b;"><?php echo ucwords($row['name']) ?></strong></td>
							<td><span class="badge-soft <?php echo $r_badge ?>"><i class="fas fa-ticket-alt"></i> <?php echo $r_label ?></span></td>
							<td class="text-right">
								<span class="badge-soft badge-soft-emerald font-mono-num" style="font-size: 0.85rem;">
									<?php echo number_format($row['adult_price'], 2) ?>
								</span>
							</td>
							<td class="text-right">
								<span class="badge-soft badge-soft-amber font-mono-num" style="font-size: 0.85rem;">
									<?php echo number_format($row['child_price'], 2) ?>
								</span>
							</td>
							<td class="text-center">
								<div class="d-inline-flex" style="gap: 6px;">
									<a href="index.php?page=edit_pricing&id=<?php echo $row['id'] ?>" class="btn btn-icon-soft btn-icon-edit" title="Edit Pricing">
										<i class="fas fa-edit"></i>
									</a>
									<button type="button" class="btn btn-icon-soft btn-icon-delete delete_pricing" data-id="<?php echo $row['id'] ?>" title="Delete Pricing">
										<i class="fas fa-trash"></i>
									</button>
								</div>
							</td>
						</tr>	
						<?php endwhile; ?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
<script>
	$(document).ready(function(){
		$('#list').dataTable()
		$('.view_pricings').click(function(){
			uni_modal("pricings's Details","view_pricings.php?id="+$(this).attr('data-id'),"large")
		})
		$('.delete_pricing').click(function(){
			_conf("Are you sure to delete this pricing?","delete_pricing",[$(this).attr('data-id')])
		})
	})
	function delete_pricing($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_pricing',
			method:'POST',
			data:{id:$id},
			success:function(resp){
				if(resp==1){
					alert_toast("Data successfully deleted",'success')
					setTimeout(function(){
						location.reload()
					},1500)
				}
			}
		})
	}
</script>
