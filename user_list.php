<?php include'db_connect.php' ?>
<div class="col-lg-12 px-0">
	<div class="card card-outline card-success">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-cyan"><i class="fas fa-users"></i></span>
				Team &amp; User Accounts
			</h3>
			<div class="card-tools">
				<a class="btn btn-sm btn-aurora" href="./index.php?page=new_user">
					<i class="fa fa-user-plus mr-1"></i> Add New User
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover" id="list">
					<thead>
						<tr>
							<th class="text-center" style="width: 60px;">#</th>
							<th>Name</th>
							<th>Email</th>
							<th>Role</th>
							<th class="text-center" style="width: 140px;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$type = array('',"Admin","Staff");
						$avatar_grads = [
							'linear-gradient(135deg, #6366f1, #8b5cf6)',
							'linear-gradient(135deg, #ec4899, #f43f5e)',
							'linear-gradient(135deg, #10b981, #06b6d4)',
							'linear-gradient(135deg, #f59e0b, #f97316)'
						];
						$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users order by concat(firstname,' ',lastname) asc");
						while($row= $qry->fetch_assoc()):
							$ini = strtoupper(substr($row['firstname'], 0, 1) . substr($row['lastname'], 0, 1));
							$g = $avatar_grads[($i - 1) % count($avatar_grads)];
							$role_badge = $row['type'] == 1 ? 'badge-soft-pink' : 'badge-soft-cyan';
						?>
						<tr>
							<th class="text-center font-mono-num text-muted"><?php echo $i++ ?></th>
							<td>
								<div class="d-flex align-items-center">
									<span class="customer-avatar-pill" style="background: <?php echo $g ?>;"><?php echo $ini ?></span>
									<strong style="color: #1e1b4b;"><?php echo ucwords($row['name']) ?></strong>
								</div>
							</td>
							<td><span class="text-muted" style="font-weight: 600;"><?php echo $row['email'] ?></span></td>
							<td>
								<span class="badge-soft <?php echo $role_badge ?>">
									<i class="fas <?php echo $row['type'] == 1 ? 'fa-crown' : 'fa-id-badge' ?>"></i>
									<?php echo $type[$row['type']] ?>
								</span>
							</td>
							<td class="text-center">
								<div class="dropdown">
									<button type="button" class="btn btn-sm btn-secondary dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
										Actions
									</button>
									<div class="dropdown-menu dropdown-menu-right">
										<a class="dropdown-item view_user" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
											<i class="fas fa-eye mr-2 text-info"></i> View Details
										</a>
										<a class="dropdown-item" href="./index.php?page=edit_user&id=<?php echo $row['id'] ?>">
											<i class="fas fa-edit mr-2 text-primary"></i> Edit User
										</a>
										<div class="dropdown-divider"></div>
										<a class="dropdown-item delete_user text-danger" href="javascript:void(0)" data-id="<?php echo $row['id'] ?>">
											<i class="fas fa-trash mr-2"></i> Delete
										</a>
									</div>
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
		$('.view_user').click(function(){
			uni_modal("<i class='fa fa-id-card mr-2'></i>User Details","view_user.php?id="+$(this).attr('data-id'))
		})
		$('.delete_user').click(function(){
			_conf("Are you sure to delete this user?","delete_user",[$(this).attr('data-id')])
		})
	})
	function delete_user($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_user',
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
