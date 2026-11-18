<?php if(!isset($conn)){ include 'db_connect.php'; } ?>
<style>
  textarea{
    resize: none;
  }
</style>
<div class="col-lg-12 px-0">
	<div class="card card-outline card-primary">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-emerald"><i class="fas fa-cash-register"></i></span>
				<?php echo isset($id) ? 'Edit Ticket Order' : 'Point of Sale — Issue Ticket' ?>
			</h3>
			<a href="./index.php?page=ticket_list" class="btn btn-sm btn-secondary">
				<i class="fas fa-list mr-1"></i> Issued Tickets
			</a>
		</div>
		<div class="card-body">
			<form action="" id="manage-ticket">
        <input type="hidden" name="id" value="<?php echo isset($id) ? $id : '' ?>">
        <input type="hidden" name="adult_price" value="<?php echo isset($adult_price) ? $adult_price : '' ?>">
        <input type="hidden" name="child_price" value="<?php echo isset($child_price) ? $child_price : '' ?>">
        <input type="hidden" name="ride_id" value="<?php echo isset($ride_id) ? $ride_id : '' ?>">

        <div id="msg" class=""></div>

        <div class="row">
          <!-- Left: Visitor & Package Selection -->
          <div class="col-lg-7 pr-lg-4 mb-4 mb-lg-0">
            <div class="row">
              <div class="col-md-6 form-group">
                <label for="customer_name" class="control-label"><i class="fas fa-user mr-1 text-primary"></i> Customer Name</label>
                <input name="name" id="customer_name" class="form-control" placeholder="Enter visitor full name" value="<?php echo isset($name) ? $name : '' ?>" required>
              </div>
              <div class="col-md-6 form-group">
                <label for="pricing_id" class="control-label"><i class="fas fa-tags mr-1 text-primary"></i> Ticket Package</label>
                <select name="pricing_id" id="pricing_id" class="form-control select2" required>
                  <option value=""></option>
                  <?php 
                    $ride['all'] = "All";
                    $ride[0] = "None";
                    $rides = $conn->query("SELECT * FROM rides order by ride asc");
                    while($row=$rides->fetch_assoc()){
                      $ride[$row['id']] = ucwords($row['ride']);
                    }
                    $pricing = $conn->query("SELECT * FROM pricing order by name asc");
                    while($row=$pricing->fetch_assoc()): 
                  ?>
                  <option value="<?php echo $row['id'] ?>" data-json='<?php echo json_encode($row) ?>' <?php echo isset($pricing_id) && $pricing_id == $row['id'] ?"selected": "" ?>><?php echo ucwords($row['name'].' ('.$ride[$row['ride_id']].')') ?></option>
                  <?php endwhile; ?>
                </select>
              </div>
            </div>

            <div class="row mt-1">
              <div class="col-md-6 form-group">
                <label for="no_adult" class="control-label"><i class="fas fa-user-friends mr-1 text-indigo"></i> No. of Adults</label>
                <input name="no_adult" id="no_adult" class="form-control number text-right font-mono-num" value="<?php echo isset($no_adult) ? $no_adult : 0 ?>" onfocus="$(this).select()" onkeyup="update_tbl()" required>
              </div>
              <div class="col-md-6 form-group">
                <label for="no_child" class="control-label"><i class="fas fa-child mr-1 text-pink"></i> No. of Children</label>
                <input name="no_child" id="no_child" class="form-control number text-right font-mono-num" value="<?php echo isset($no_child) ? $no_child : 0 ?>" onfocus="$(this).select()" onkeyup="update_tbl()" required>
              </div>
            </div>

            <!-- Live Fare Breakdown Card -->
            <div class="pos-breakdown-card mt-3">
              <div class="d-flex justify-content-between align-items-center mb-3">
                <span style="font-weight: 800; font-size: 0.9rem; color: #312e81;">
                  <i class="fas fa-receipt mr-2 text-primary"></i>Ticket Breakdown Summary
                </span>
                <span class="badge-soft badge-soft-indigo">Auto-Calculated</span>
              </div>
              <table class="table table-sm mb-0">
                <thead>
                  <tr>
                    <th>Guest Type</th>
                    <th class="text-right">Unit Price</th>
                    <th class="text-right">Quantity (Pax)</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <th><span class="badge-soft badge-soft-indigo"><i class="fas fa-user"></i> Adult</span></th>
                    <td class="text-right font-mono-num" style="font-weight: 700;" id="aprice">0.00</td>
                    <td class="text-right font-mono-num" style="font-weight: 800; color: #4f46e5;" id="apax">0</td>
                  </tr>
                  <tr>
                    <th><span class="badge-soft badge-soft-pink"><i class="fas fa-child"></i> Child</span></th>
                    <td class="text-right font-mono-num" style="font-weight: 700;" id="cprice">0.00</td>
                    <td class="text-right font-mono-num" style="font-weight: 800; color: #db2777;" id="cpax">0</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>

          <!-- Right: Twilight Gradient Checkout Panel -->
          <div class="col-lg-5">
            <div class="pos-checkout-panel h-100 d-flex flex-column justify-content-between">
              <div style="position: relative; z-index: 2;">
                <div class="d-flex align-items-center justify-content-between mb-3 pb-2" style="border-bottom: 1px solid rgba(255,255,255,0.14);">
                  <span style="font-weight: 800; font-size: 1rem;"><i class="fas fa-wallet mr-2 text-warning"></i>Payment Checkout</span>
                  <span class="badge-soft" style="background: rgba(16,185,129,0.22); color: #6ee7b7;">Instant Print</span>
                </div>

                <div class="form-group">
                  <label for="amount">Amount to Pay</label>
                  <input type="text" class="form-control text-right number" id="amount" name="amount" readonly="" required>
                </div>

                <div class="form-group">
                  <label for="tendered">Amount Tendered</label>
                  <input type="text" class="form-control text-right number" id="tendered" name="tendered" placeholder="0.00" onkeyup="calc_change()" value="<?php echo isset($tendered) ? number_format($tendered) : '' ?>" required>
                </div>

                <div class="form-group mb-0">
                  <label for="change">Change Due</label>
                  <input type="text" class="form-control text-right number" id="change" value="0" readonly="" required>
                </div>
              </div>
            </div>
          </div>
        </div>
      </form>
  	</div>
  	<div class="card-footer">
  		<div class="d-flex w-100 justify-content-end align-items-center" style="gap: 10px;">
  			<a class="btn btn-secondary" href="./index.php?page=ticket_list">Cancel</a>
  			<button class="btn btn-aurora" form="manage-ticket"><i class="fas fa-print mr-1"></i> Save &amp; Print Ticket</button>
  		</div>
  	</div>
	</div>
</div>
<script>
  $('#pricing_id').change(function(){
    var id = $(this).val()
    if(!id) return;
    var data = $(this).find('option[value="'+id+'"]').attr('data-json')
        data = JSON.parse(data)
        $('[name="adult_price"]').val(data.adult_price)
        $('[name="child_price"]').val(data.child_price)
        $('#aprice').text(parseFloat(data.adult_price).toLocaleString("en-US",{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))
        $('#cprice').text(parseFloat(data.child_price).toLocaleString("en-US",{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))
        $('[name="ride_id"]').val(data.ride_id)
        calc()
  })
  function update_tbl(){
    var na = $('[name="no_adult"]').val()
    var nc = $('[name="no_child"]').val()
    $('#apax').text(na)
    $('#cpax').text(nc)
    calc()
  }
  function calc(){
      var aprice = $('#aprice').text().replace(/,/g,'');
      var cprice = $('#cprice').text().replace(/,/g,'');
      var apax = $('#apax').text().replace(/,/g,'');
      var cpax = $('#cpax').text().replace(/,/g,'');
      aprice = parseFloat(aprice) > 0 ? parseFloat(aprice) : 0;
      cprice = parseFloat(cprice) > 0 ? parseFloat(cprice) : 0;
      apax = parseFloat(apax) > 0 ? parseFloat(apax) : 0;
      cpax = parseFloat(cpax) > 0 ? parseFloat(cpax) : 0;

      var amount = (aprice * apax) + (cprice * cpax)
        $('#amount').val(parseFloat(amount).toLocaleString("en-US",{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))
        calc_change()
  }

  function calc_change(){
      var amount = $('#amount').val();
      var tendered = $('#tendered').val();
          amount = amount.replace(/,/g,'')
          tendered = tendered.replace(/,/g,'')
          amount = amount > 0 ? amount : 0;
          tendered = tendered > 0 ? tendered : 0;
      var change = parseFloat(tendered) - parseFloat(amount) 
        $('#change').val(parseFloat(change).toLocaleString("en-US",{style:"decimal",minimumFractionDigits:2,maximumFractionDigits:2}))
  }
  $(document).ready(function(){
  if('<?php echo isset($id) ?>' == 1)
    $('#pricing_id').trigger('change')
    $('[name="no_child"],[name="no_adult"]').trigger('keyup')
  })
	$('#manage-ticket').submit(function(e){
		e.preventDefault()
		start_load()
    $('#msg').html('')
    var amount = $('#amount').val();
    var tendered = $('#tendered').val();
        amount = amount.replace(/,/g,'')
        tendered = tendered.replace(/,/g,'')
        amount = amount > 0 ? amount : 0;
        tendered = tendered > 0 ? tendered : 0;
      if(parseFloat(amount) > parseFloat(tendered)){
        alert_toast("Tendered Amount must be greater than or equal to amount to pay.",'error')
        end_load()
        return false;
      }
		$.ajax({
			url:'ajax.php?action=save_ticket',
			data: new FormData($(this)[0]),
		    cache: false,
		    contentType: false,
		    processData: false,
		    method: 'POST',
		    type: 'POST',
			success:function(resp){
        if(resp){
          resp = JSON.parse(resp)
  				if(resp.status == 1){
  					alert_toast('Data successfully saved',"success");
  					var nw = window.open("print_ticket.php?id="+resp.id,"","height=700,width=900")
            nw.print()
            setTimeout(function(){
              nw.close()
              location.reload()
            },750)
  				}else if(resp.status ==2){
            $('#msg').html('<div class="alert alert-danger" style="border-radius:12px;">Ticket for selected ride and given ticket for already exist.</div>')
            end_load()
          }
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
