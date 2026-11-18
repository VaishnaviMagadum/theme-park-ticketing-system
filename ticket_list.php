<?php include'db_connect.php' ?>
<div class="col-lg-12 px-0">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-emerald"><i class="fas fa-ticket-alt"></i></span>
				Issued Tickets Directory
			</h3>
			<div class="card-tools">
				<a class="btn btn-sm btn-aurora" href="./index.php?page=new_ticket">
					<i class="fa fa-plus mr-1"></i> Issue New Ticket
				</a>
			</div>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover" id="list">
					<thead>
						<tr>
							<th class="text-center" style="width: 55px;">#</th>
							<th>Date Issued</th>
							<th>Customer</th>
							<th class="text-center">Adult Ticket</th>
							<th class="text-center">Child Ticket</th>
							<th>Ticket For</th>
							<th class="text-center" style="width: 145px;">Action</th>
						</tr>
					</thead>
					<tbody>
						<?php
						$i = 1;
						$avatar_grads = [
							'linear-gradient(135deg, #6366f1, #8b5cf6)',
							'linear-gradient(135deg, #ec4899, #f43f5e)',
							'linear-gradient(135deg, #10b981, #06b6d4)',
							'linear-gradient(135deg, #f59e0b, #f97316)'
						];
						$qry = $conn->query("SELECT t.*,p.name as ticket_for FROM ticket_list t inner join pricing p on p.id = t.pricing_id order by unix_timestamp(t.date_created) desc ");
						while($row= $qry->fetch_assoc()):
							$parts = explode(' ', trim($row['name']));
							$ini = strtoupper(substr($parts[0], 0, 1) . (isset($parts[1]) ? substr($parts[1], 0, 1) : ''));
							$g = $avatar_grads[($i - 1) % count($avatar_grads)];
						?>
						<tr>
							<td class="text-center font-mono-num text-muted"><b><?php echo $i++ ?></b></td>
							<td>
								<span style="font-weight: 700; font-size: 0.85rem; color: #334155;">
									<i class="far fa-clock mr-1 text-primary"></i>
									<?php echo date("M d, Y h:i A",strtotime($row['date_created'])) ?>
								</span>
							</td>
							<td>
								<div class="d-flex align-items-center">
									<span class="customer-avatar-pill" style="background: <?php echo $g ?>;"><?php echo $ini ?></span>
									<strong style="color: #1e1b4b;"><?php echo ucwords($row['name']) ?></strong>
								</div>
							</td>
							<td class="text-center">
								<span class="badge-soft badge-soft-indigo font-mono-num"><?php echo number_format($row['no_adult']) ?> Adults</span>
							</td>
							<td class="text-center">
								<span class="badge-soft badge-soft-pink font-mono-num"><?php echo number_format($row['no_child']) ?> Kids</span>
							</td>
							<td>
								<span class="badge-soft badge-soft-violet"><?php echo ucwords($row['ticket_for']) ?></span>
							</td>
							<td class="text-center">
								<div class="d-inline-flex" style="gap: 6px;">
									<a href="print_ticket.php?id=<?php echo $row['id'] ?>" target="_blank" class="btn btn-icon-soft btn-icon-print" title="Print Ticket Stub">
										<i class="fas fa-print"></i>
									</a>
									<a href="index.php?page=edit_ticket&id=<?php echo $row['id'] ?>" class="btn btn-icon-soft btn-icon-edit" title="Edit Ticket">
										<i class="fas fa-edit"></i>
									</a>
									<button type="button" class="btn btn-icon-soft btn-icon-delete delete_ticket" data-id="<?php echo $row['id'] ?>" title="Delete Ticket">
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
		$('.view_tickets').click(function(){
			uni_modal("tickets's Details","view_ticket.php?id="+$(this).attr('data-id'),"large")
		})
		$('.delete_ticket').click(function(){
			_conf("Are you sure to delete this ticket?","delete_ticket",[$(this).attr('data-id')])
		})
	})
	function delete_ticket($id){
		start_load()
		$.ajax({
			url:'ajax.php?action=delete_ticket',
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
