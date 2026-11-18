<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<style>
  textarea{
    resize: none;
  }
</style>
<div class="col-lg-9 mx-auto px-0">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-pink"><i class="fas fa-tag"></i></span>
				<?php echo isset($id) ? 'Edit Ticket Pricing' : 'Create Pricing Tier' ?>
			</h3>
			<a href="./index.php?page=pricing_list" class="btn btn-sm btn-secondary">
				<i class="fas fa-arrow-left mr-1"></i> Back to Pricing
			</a>
		</div>
		<div class="card-body">
			<form action="" id="manage-pricing">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div class="row">
          <div class="col-md-12">
            <div id="msg" class=""></div>
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="pricing_name" class="control-label"><i class="fas fa-ticket-alt mr-1 text-primary"></i> Ticket For</label>
                <input name="name" id="pricing_name" class="form-control" placeholder="e.g. Entrance, Ride All U Can, VIP Pass" value="<?php echo isset($name) ? $name : '' ?>" required>
              </div>
              <div class="col-md-6 form-group">
                <label for="ride_id" class="control-label"><i class="fas fa-tram mr-1 text-primary"></i> Ride/s Included</label>
                <select name="ride_id" id="ride_id" class="form-control select2" required>
                  <option value=""></option>
                  <option value="0" <?php echo isset($ride_id) && (string)$ride_id === '0' ? "selected" : '' ?>>None (Entrance Only)</option>
                  <option value="all" <?php echo isset($ride_id) && $ride_id == 'all' ? "selected" : '' ?>>All Rides</option>
                  <?php 
                    $rides = $conn->query("SELECT * FROM rides order by ride asc");
                    while($row=$rides->fetch_assoc()): 
                  ?>
                  <option value="<?php echo $row['id'] ?>" <?php echo isset($ride_id) && (string)$ride_id === (string)$row['id'] ? "selected" : '' ?>><?php echo ucwords($row['ride']) ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>

            <div class="row mt-2">
              <div class="col-md-6 form-group">
                <label for="adult_price" class="control-label"><i class="fas fa-user mr-1 text-success"></i> Adult Price</label>
                <input name="adult_price" id="adult_price" class="form-control number text-right font-mono-num" value="<?php echo isset($adult_price) ? $adult_price : 0 ?>" onfocus="if($(this).val() == 0 ){$(this).select()}" required>
              </div>
              <div class="col-md-6 form-group">
                <label for="child_price" class="control-label"><i class="fas fa-child mr-1 text-warning"></i> Child Price</label>
                <input name="child_price" id="child_price" class="form-control number text-right font-mono-num" value="<?php echo isset($child_price) ? $child_price : 0 ?>" onfocus="if($(this).val() == 0 ){$(this).select()}" required>
              </div>
            </div>
          </div>
        </div>
      </form>
  	</div>
  	<div class="card-footer">
  		<div class="d-flex w-100 justify-content-end align-items-center" style="gap: 10px;">
  			<a class="btn btn-secondary" href="./index.php?page=pricing_list">Cancel</a>
  			<button class="btn btn-aurora" form="manage-pricing"><i class="fas fa-save mr-1"></i> Save Pricing</button>
  		</div>
  	</div>
	</div>
</div>
<script>
	$('#manage-pricing').submit(function(e){
		e.preventDefault()
		start_load()
    $('#msg').html('')
		$.ajax({
			url:'ajax.php?action=save_pricing',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
				if(resp == 1){
					alert_toast('Data successfully saved',"success");
					setTimeout(function(){
              location.href = 'index.php?page=pricing_list'
					},2000)
				}else if(resp ==2){
          $('#msg').html('<div class="alert alert-danger" style="border-radius:12px;"><i class="fas fa-exclamation-triangle mr-2"></i>Pricing for selected ride and given ticket for already exist.</div>')
          end_load()
        }
			}
		})
	})
  function displayImgCover(input,_this) {
      if (input.files && input.files[0]) {
          var reader = new FileReader();
          reader.onload = function (e) {
            $('#cover').attr('src', e.target.result);
          }
          reader.readAsDataURL(input.files[0]);
      }
  }
</script>
