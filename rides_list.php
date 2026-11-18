<?php include'db_connect.php' ?>
<div class="col-lg-12 px-0">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-indigo"><i class="fas fa-tram"></i></span>
				All Park Rides &amp; Attractions
			</h3>
			<div class="card-tools">
				<a class="btn btn-sm btn-aurora" href="./index.php?page=new_ride">
					<i class="fa fa-plus mr-1"></i> Add New Ride
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover" id="list">
					<thead>
						<tr>
							<th class="text-center" style="width: 60px;">#</th>
							<th>Ride Name</th>
							<th>Description</th>
							<th class="text-center" style="width: 130px;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$icon_grads = [
							'linear-gradient(135deg, #6366f1, #8b5cf6)',
							'linear-gradient(135deg, #f43f5e, #f97316)',
							'linear-gradient(135deg, #10b981, #06b6d4)',
							'linear-gradient(135deg, #f59e0b, #eab308)'
						];
						$qry = $conn->query("SELECT * FROM rides order by ride asc ");
						while($row= $qry->fetch_assoc()):
							$g = $icon_grads[($i - 1) % count($icon_grads)];
						?>
						<tr>
							<td class="text-center font-mono-num text-muted"><b><?php echo $i++ ?></b></td>
							<td>
								<div class="d-flex align-items-center">
									<span class="customer-avatar-pill" style="background: <?php echo $g ?>;"><i class="fas fa-star"></i></span>
									<strong style="color: #1e1b4b;"><?php echo ucwords($row['ride']) ?></strong>
								</div>
							</td>
							<td><span class="text-muted" style="font-size: 0.88rem;"><?php echo $row['description'] ?></span></td>
							<td class="text-center">
								<div class="d-inline-flex" style="gap: 6px;">
									<a href="index.php?page=edit_ride&id=<?php echo $row['id'] ?>" class="btn btn-icon-soft btn-icon-edit" title="Edit Ride">
										<i class="fas fa-edit"></i>
									</a>
									<button type="button" class="btn btn-icon-soft btn-icon-delete delete_ride" data-id="<?php echo $row['id'] ?>" title="Delete Ride">
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
		$('.view_rides').click(function(){
			uni_modal("rides's Details","view_rides.php?id="+$(this).attr('data-id'),"large")
		})
		$('.delete_ride').click(function(){
			_conf("Are you sure to delete this ride?","delete_ride",[$(this).attr('data-id')])
		})
	})
	function delete_ride($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_ride',
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
