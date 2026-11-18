<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<style>
  textarea{
    resize: none;
  }
</style>
<div class="col-lg-8 mx-auto px-0">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-indigo"><i class="fas fa-tram"></i></span>
				<?php echo isset($id) ? 'Edit Ride Details' : 'Create New Park Ride' ?>
			</h3>
			<a href="./index.php?page=rides_list" class="btn btn-sm btn-secondary">
				<i class="fas fa-arrow-left mr-1"></i> Back to List
			</a>
		</div>
		<div class="card-body">
			<form action="" id="manage-ride">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <div id="msg" class=""></div>

        <div class="form-group">
          <label for="ride_name" class="control-label"><i class="fas fa-star mr-1 text-primary"></i> Ride Name</label>
          <textarea name="ride" id="ride_name" cols="30" rows="2" class="form-control" placeholder="e.g. Thunder Coaster, Sky Ferris Wheel..." required><?php echo isset($ride) ? $ride : '' ?></textarea>
        </div>

        <div class="form-group mb-0">
          <label for="ride_desc" class="control-label"><i class="fas fa-align-left mr-1 text-primary"></i> Description</label>
          <textarea name="description" id="ride_desc" cols="30" rows="4" class="form-control" placeholder="Describe ride highlights, thrill level, or age recommendations..."><?php echo isset($description) ? $description : '' ?></textarea>
        </div>
      </form>
  	</div>
  	<div class="card-footer">
  		<div class="d-flex w-100 justify-content-end align-items-center" style="gap: 10px;">
  			<a class="btn btn-secondary" href="./index.php?page=rides_list">Cancel</a>
  			<button class="btn btn-aurora" form="manage-ride"><i class="fas fa-save mr-1"></i> Save Ride</button>
  		</div>
  	</div>
	</div>
</div>
<script>
	$('#manage-ride').submit(function(e){
		e.preventDefault()
		start_load()
		$.ajax({
			url:'ajax.php?action=save_ride',
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
              location.href = 'index.php?page=rides_list'
					},2000)
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
