<?php include 'db_connect.php' ?>
<?php
if(isset($_GET['id'])){
	$type_arr = array('',"Admin","Staff");
	$qry = $conn->query("SELECT *,concat(firstname,' ',lastname) as name FROM users where id = ".$_GET['id'])->fetch_array();
	foreach($qry as $k => $v){
		$$k = $v;
	}
}
?>
<div class="container-fluid p-0">
	<div class="card border-0 shadow-none mb-0">
      <div class="p-4 text-center text-white" style="background: linear-gradient(135deg, #4f46e5 0%, #8b5cf6 50%, #ec4899 100%); border-radius: 16px;">
        <div class="mx-auto mb-3 d-flex justify-content-center align-items-center shadow" style="width: 82px; height: 82px; border-radius: 24px; background: rgba(255,255,255,0.22); backdrop-filter: blur(8px); border: 2px solid rgba(255,255,255,0.45); font-size: 1.75rem; font-weight: 800;">
          <?php echo strtoupper(substr($firstname, 0,1).substr($lastname, 0,1)) ?>
        </div>
        <h4 class="mb-1 font-weight-bold"><?php echo ucwords($name) ?></h4>
        <div style="opacity: 0.9; font-size: 0.9rem;"><?php echo $email ?></div>
        <div class="mt-3">
          <span class="badge-soft" style="background: rgba(255,255,255,0.22); color: #ffffff;">
            <i class="fas <?php echo $type == 1 ? 'fa-crown' : 'fa-id-badge' ?>"></i> <?php echo $type_arr[$type] ?>
          </span>
        </div>
      </div>
	</div>
</div>
<div class="modal-footer display p-0 pt-3 m-0 border-0 bg-transparent">
  <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
</div>
<style>
	#uni_modal .modal-footer{
		display: none
	}
	#uni_modal .modal-footer.display{
		display: flex
	}
</style>
