<?php
?>
<div class="col-lg-10 mx-auto px-0">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-cyan"><i class="fas fa-user-plus"></i></span>
				<?php echo isset($id) ? 'Edit User Account' : 'Create New User Account' ?>
			</h3>
			<a href="./index.php?page=user_list" class="btn btn-sm btn-secondary">
				<i class="fas fa-arrow-left mr-1"></i> Back to Users
			</a>
		</div>
		<div class="card-body">
			<form action="" id="manage_user">
				<input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
				<div class="row">
					<div class="col-md-6 pr-md-4 border-right">
						<h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-id-badge mr-1"></i> Personal Information</h6>
						<div class="form-group">
							<label for="firstname" class="control-label">First Name</label>
							<input type="text" id="firstname" name="firstname" class="form-control" placeholder="Enter first name" required value="<?php echo isset($firstname) ? $firstname : '' ?>">
						</div>
						<div class="form-group">
							<label for="lastname" class="control-label">Last Name</label>
							<input type="text" id="lastname" name="lastname" class="form-control" placeholder="Enter last name" required value="<?php echo isset($lastname) ? $lastname : '' ?>">
						</div>
						<?php if($_SESSION['login_type'] == 1): ?>
						<div class="form-group">
							<label for="type" class="control-label">User Role</label>
							<select name="type" id="type" class="custom-select">
								<option value="2" <?php echo isset($type) && $type == 2 ? 'selected' : '' ?>>Staff</option>
								<option value="1" <?php echo isset($type) && $type == 1 ? 'selected' : '' ?>>Admin</option>
							</select>
						</div>
						<?php else: ?>
							<input type="hidden" name="type" value="3">
						<?php endif; ?>
					</div>
					<div class="col-md-6 pl-md-4 mt-4 mt-md-0">
						<h6 class="font-weight-bold text-primary mb-3"><i class="fas fa-shield-alt mr-1"></i> Login Credentials</h6>
						<div class="form-group">
							<label class="control-label">Email Address</label>
							<input type="email" class="form-control" name="email" placeholder="user@example.com" required value="<?php echo isset($email) ? $email : '' ?>">
							<small id="msg"></small>
						</div>
						<div class="form-group">
							<label class="control-label">Password</label>
							<input type="password" class="form-control" name="password" placeholder="••••••••" <?php echo !isset($id) ? "required":'' ?>>
							<small class="text-muted"><i><?php echo isset($id) ? "Leave blank if you don't want to change the password":'' ?></i></small>
						</div>
						<div class="form-group">
							<label class="label control-label">Confirm Password</label>
							<input type="password" class="form-control" name="cpass" placeholder="••••••••" <?php echo !isset($id) ? 'required' : '' ?>>
							<small id="pass_match" data-status=''></small>
						</div>
					</div>
				</div>
				<hr class="my-4">
				<div class="d-flex justify-content-end" style="gap: 10px;">
					<button class="btn btn-secondary" type="button" onclick="location.href = 'index.php?page=user_list'">Cancel</button>
					<button class="btn btn-aurora"><i class="fas fa-save mr-1"></i> Save User</button>
				</div>
			</form>
		</div>
	</div>
</div>
<script>
	$('[name="password"],[name="cpass"]').keyup(function(){
		var pass = $('[name="password"]').val()
		var cpass = $('[name="cpass"]').val()
		if(cpass == '' ||pass == ''){
			$('#pass_match').attr('data-status','').html('')
		}else{
			if(cpass == pass){
				$('#pass_match').attr('data-status','1').html('<span class="text-success font-weight-bold"><i class="fas fa-check-circle mr-1"></i>Password Matched.</span>')
			}else{
				$('#pass_match').attr('data-status','2').html('<span class="text-danger font-weight-bold"><i class="fas fa-times-circle mr-1"></i>Password does not match.</span>')
			}
		}
	})
	function displayImg(input,_this) {
	    if (input.files && input.files[0]) {
	        var reader = new FileReader();
	        reader.onload = function (e) {
	        	$('#cimg').attr('src', e.target.result);
	        }
	        reader.readAsDataURL(input.files[0]);
	    }
	}
	$('#manage_user').submit(function(e){
		e.preventDefault()
		$('input').removeClass("border-danger")
		start_load()
		$('#msg').html('')
		if($('[name="password"]').val() != '' && $('[name="cpass"]').val() != ''){
			if($('#pass_match').attr('data-status') != 1){
				if($("[name='password']").val() !=''){
					$('[name="password"],[name="cpass"]').addClass("border-danger")
					end_load()
					return false;
				}
			}
		}
		$.ajax({
			url:'ajax.php?action=save_user',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved.',"success");
					setTimeout(function(){
						location.replace('index.php?page=user_list')
					},750)
				}else if(resp == 2){
					$('#msg').html("<div class='alert alert-danger mt-2' style='border-radius:10px;'>Email already exist.</div>");
					$('[name="email"]').addClass("border-danger")
					end_load()
				}
			}
		})
	})
</script>
