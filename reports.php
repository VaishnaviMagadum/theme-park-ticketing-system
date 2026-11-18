<?php include 'db_connect.php' ?>
<div class="col-lg-12 px-0">
	<div class="card card-outline card-primary mb-4">
		<div class="card-body">
			<div class="row align-items-end">
				<div class="col-md-4 form-group mb-md-0">
					<label for="date_from"><i class="far fa-calendar-alt mr-1 text-primary"></i> From Date</label>
					<input type="date" id="date_from" class="form-control" value="<?php echo isset($_GET['date_from']) ? date("Y-m-d",strtotime($_GET['date_from'])) : '' ?>">
				</div>
				<div class="col-md-4 form-group mb-md-0">
					<label for="date_to"><i class="far fa-calendar-check mr-1 text-primary"></i> To Date</label>
					<input type="date" id="date_to" class="form-control" value="<?php echo isset($_GET['date_to']) ? date("Y-m-d",strtotime($_GET['date_to'])) : '' ?>">
				</div>
				<div class="col-md-4 d-flex align-items-center" style="gap: 10px;">
					<button class="btn btn-aurora flex-grow-1" type="button" id='view_report'>
						<i class="fas fa-filter mr-1"></i> Generate Report
					</button>
					<button type="button" class="btn btn-emerald" style="display: none" id="print">
						<i class="fa fa-print mr-1"></i> Print
					</button>
				</div>
			</div>
		</div>
	</div>

	<div class="card">
		<div class="card-header">
			<h3 class="card-title-modern">
				<span class="badge-soft badge-soft-amber"><i class="fas fa-file-invoice-dollar"></i></span>
				Sales Report Breakdown
			</h3>
			<span class="badge-soft badge-soft-indigo" id="range-badge">Select a date range above</span>
		</div>
		<div class="card-body">
			<div class="table-responsive">
				<table class="table table-hover" id="report-list">
					<thead>
						<tr>
							<th class="text-center">#</th>
							<th>Date</th>
							<th>Customer</th>
							<th>Ticket For</th>
							<th class="text-center">Adult Ticket</th>
							<th class="text-right">Adult Price</th>
							<th class="text-center">Child Ticket</th>
							<th class="text-right">Child Price</th>
							<th class="text-right">Total Amount</th>
						</tr>
					</thead>
					<tbody>
						<tr>
							<td colspan="9" class="text-center text-muted py-4">
								<i class="fas fa-calendar-day fa-lg mb-2 d-block text-primary"></i>
								Choose a <b>From</b> and <b>To</b> date and click <b>Generate Report</b> to view sales.
							</td>
						</tr>
					</tbody>
					<tfoot>
						<tr>
							<th colspan="8" class="text-right">Total Sale</th>
							<th id="total" class="text-right font-mono-num" style="font-size: 1.05rem; color: #059669;">0.00</th>
						</tr>
					</tfoot>
				</table>
			</div>
		</div>
	</div>
</div>
<noscript>
	<style>
		table.table{
			width:100%;
			border-collapse: collapse;
			font-family: Arial, sans-serif;
		}
		table.table tr,table.table th, table.table td{
			border:1px solid #cbd5e1;
			padding: 8px;
		}
		.text-center{
			text-align: center;
		}
		.text-right{
			text-align: right;
		}
	</style>
</noscript>
<div class="details d-none">
	<h3 class="text-center"><b>Report as of <span id="drange"></span></b></h3>
</div>
<script>
	function load_report(){
		start_load()
		var date_from = $('#date_from').val()
		var date_to = $('#date_to').val()
		var status = $('#status').val()
		var dates = moment(date_from).format("MMM-D-YYYY") + ' - ' + moment(date_to).format("MMM-D-YYYY")
		$('#drange').text(dates)
		$('#range-badge').text(dates)
		$.ajax({
			url:'ajax.php?action=get_report',
			method:'POST',
			data:{status:status,date_from:date_from,date_to:date_to},
			error:err=>{
				console.log(err)
				alert_toast("An error occured",'error')
				end_load()
			},
			success:function(resp){
				if(typeof resp === 'object' || Array.isArray(resp) || typeof JSON.parse(resp) === 'object'){
					resp = JSON.parse(resp)
					if(Object.keys(resp).length > 0){
						$('#report-list tbody').html('')
						var i = 1;
						var total = 0;
						Object.keys(resp).map(function(k){
							var tr = $('<tr></tr>')
							tr.append('<td class="text-center font-mono-num">'+(i++)+'</td>')
							tr.append('<td>'+(resp[k].date_created)+'</td>')
							tr.append('<td><b>'+(resp[k].name)+'</b></td>')
							tr.append('<td><span class="badge-soft badge-soft-violet">'+(resp[k].ticket_for)+'</span></td>')
							tr.append('<td class="text-center font-mono-num">'+(resp[k].no_adult)+'</td>')
							tr.append('<td class="text-right font-mono-num">'+(resp[k].adult_price)+'</td>')
							tr.append('<td class="text-center font-mono-num">'+(resp[k].no_child)+'</td>')
							tr.append('<td class="text-right font-mono-num">'+(resp[k].child_price)+'</td>')
							tr.append('<td class="text-right font-mono-num" style="font-weight:800; color:#059669;">'+(resp[k].amount)+'</td>')
							var amount = resp[k].amount.replace(/,/g,'')
							amount = amount > 0 ? amount : 0;
							total = parseFloat(total) + parseFloat(amount)
							$('#report-list tbody').append(tr)
						})
						$('#total').text(parseFloat(total).toLocaleString('en-US',{style:'decimal',minimumFractionDigits:2,maximumFractionDigits:2}))
						$('#print').show()
					}else{
						$('#report-list tbody').html('')
						var tr = $('<tr></tr>')
						tr.append('<th class="text-center text-muted py-4" colspan="9">No sales found for this date range.</th>')
						$('#report-list tbody').append(tr)
						$('#total').text('0.00')
						$('#print').hide()
					}
				}
			}
			,complete:function(){
				end_load()
			}
		})
	}
	$('#view_report').click(function(){
		if($('#date_from').val() == '' || $('#date_to').val() == ''){
			alert_toast("Please select dates first.","error")
			return false;
		}
		load_report()
		var date_from = $('#date_from').val()
		var date_to = $('#date_to').val()
		var target = './index.php?page=reports&filtered&date_from='+date_from+'&date_to='+date_to
		window.history.pushState({}, null, target);
	})

	$(document).ready(function(){
		if('<?php echo isset($_GET['filtered']) ?>' == 1)
		load_report()
	})
	$('#print').click(function(){
		start_load()
		var ns = $('noscript').clone()
		var details = $('.details').clone()
		var content = $('#report-list').clone()
		ns.append(details)
		ns.append(content)
		var nw = window.open('','','height=700,width=900')
		nw.document.write(ns.html())
		nw.document.close()
		nw.print()
		setTimeout(function(){
			nw.close()
			end_load()
		},750)
	})
</script>
