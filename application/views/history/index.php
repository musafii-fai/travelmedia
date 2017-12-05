<div class="nav-tabs-custom">
    <!-- Tabs within a box -->
    <ul class="nav nav-tabs pull-right">
        <li class="pull-left header"><i class="fa fa-inbox"></i>Table History</li>
        
        <li><a href="#dataHistoryAll" data-toggle="tab">Table History All</a></li>
        <li class="active"><a href="#dataHistoryAdmin" data-toggle="tab">Table History Admin</a></li>
    </ul>

    <div class="tab-content">
    	<div class="tab-pane active" id="dataHistoryAdmin">
    		<div class="table-responsive">	
				<button class="btn btn-sm btn-flat" id="btnRefreshAdmin"><i class="fa fa-refresh"></i> Refresh</button>
				<br><br>
				<table id="tableHistoryAdmin" class="table table-striped table-bordered table-condensed">
					<thead>
						<th>No</th>
						<th>Tanggal Input</th>
						<th>Admin</th>
						<th>Asal Shuttle</th>
						<th>Kota Asal</th>
						<th>Tujuan Shuttle</th>
						<th>Kota Tujuan</th>
						<th>Info</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
    	</div>

    	<div class="tab-pane" id="dataHistoryAll">
			<div class="table-responsive">	
				<button class="btn btn-sm btn-flat" id="btnRefreshAll"><i class="fa fa-refresh"></i> Refresh</button>
				<br><br>
				<table id="tableHistoryAll" class="table table-striped table-bordered table-condensed" style="width: 100%;">
					<thead>
						<th>No</th>
						<th>Tanggal Input</th>
						<th>Admin</th>
						<th>Asal Shuttle</th>
						<th>Kota Asal</th>
						<th>Tujuan Shuttle</th>
						<th>Kota Tujuan</th>
						<th>Info</th>
					</thead>
					<tbody></tbody>
				</table>
			</div>
    	</div>
    </div>
</div>

<!-- modal Detail -->
<div id="detail">
	<?php echo modalSaveOpen("buttonDetail","lg","Detail History"); ?>
		<div class="table-responsive">
			<div class="callout callout-info">
				<div class="row">
					<div class="col-md-5">
						<table>	
							<tr>
								<th>Asal Shuttle</th>
								<td style="padding-left: 15px;">:</td>
								<td id="asal_shuttle" style="padding-left: 15px;"></td></tr>
							<tr>
								<th>Tujuan Shuttle</th>
								<td style="padding-left: 15px;">:</td>
								<td id="tujuan_shuttle" style="padding-left: 15px;"></td>
							</tr>
							<tr>
								<th>No Polisi Bus</th>
								<td style="padding-left: 15px;">:</td>
								<td id="no_polisi" style="padding-left: 15px;"></td></tr>
							<tr>
								<th>Nama Supir Bus</th>
								<td style="padding-left: 15px;">:</td>
								<td id="nama_supir" style="padding-left: 15px;"></td>
							</tr>
						</table>
					</div>
					<div class="col-md-7">
						<table >
							<tr>
								<th>Nama Admin</th>
								<td style="padding-left: 15px;">:</td>
								<td id="admin" style="padding-left: 15px;"></td>
							</tr>
							<tr>
								<th>Shuttle Admin</th>
								<td style="padding-left: 15px;">:</td>
								<td id="shuttleAdmin" style="padding-left: 15px;"></td>
							</tr>
							<tr>
								<th>Tanggal Input</th>
								<td style="padding-left: 15px;">:</td>
								<td id="tgl_input" style="padding-left: 15px;"></td>
							</tr>
						</table>
					</div>
				</div>
				

			</div>
			<table class="table table-striped table-bordered">
				<thead>
					<th>No</th>
					<th>Tanggal Pemesanan</th>
					<th>Jam</th>
					<th>Nama Pemesan</th>
					<th>Jenis Kelamin</th>
					<th>Umur</th>
					<th>No Hp</th>
					<th>No Kursi</th>
					<th>Harga</th>
				</thead>
				<tbody id="travellerData">
				</tbody>
				<tfoot>
					<tr>
						<th style="text-align: right;" colspan="8">Total Bayar</th>
						<th id="totalBayar"></th>
					</tr>
				</tfoot>
			</table>
			<div id="inputMessage"></div>
			<?php if($this->user_role == "admin") : ?>
				<button type="button" class="btn btn-danger btn-lg" id="modalDeleteHistory">OK Delete</button>
			<?php endif; ?>
		</div>
	<?php echo modalSaveClose("Cetak PDF","modalButtonCetakPdf"); ?>
</div>

<script type="text/javascript">

function btnCetakPdf(id) {
	url = "<?php echo site_url('history/cetakPdf/'); ?>"+id;
	window.open(url);
}


var idDetail;
var idDelete;

function btnDetail(id) {
	idDelete = id;
	$("#buttonDetail").modal("show");
	$("#modalButtonCetakPdf").hide();
	idDetail = id;
	$("#modalButtonCetakPdf").attr("onclick","btnCetakPdf("+idDetail+")");
	$("#modalDeleteHistory").attr("onclick","btnDeleteHistory("+idDelete+")");

	$.ajax({
		url: '<?php echo site_url("history/detail/") ?>'+idDetail,
		type:'POST',
		dataType:'json',
		cache:false,
		success: function(json) {
			if (json.status == true) {
				/*head Data*/
				$("#admin").html(json.headData.admin);
				$("#shuttleAdmin").html(json.headData.shuttle_admin);
				$("#tgl_input").html(json.headData.tanggal_input);
				$("#asal_shuttle").html(json.headData.asal_shuttle);
				$("#tujuan_shuttle").html(json.headData.tujuan_shuttle);
				$("#no_polisi").html(json.headData.bus_no_polisi);
				$("#nama_supir").html(json.headData.bus_nama_supir);

				/*content Data*/
				var html;
		    	$.each(json.data,function(i,val){
		    		html += "<tr>";
		    		html += "<td>"+val.no+"</td>";
		    		html += "<td>"+val.tanggal_pemesanan+"</td>";
		    		html += "<td>"+val.jam+"</td>";
		    		html += "<td>"+val.nama+"</td>";
		    		html += "<td>"+val.jenis_kelamin+"</td>";
		    		html += "<td>"+val.umur+"</td>";
		    		html += "<td>"+val.no_hp+"</td>";
		    		html += "<td>"+val.nokursi+"</td>";
		    		html += "<td>"+val.harga_nominal+"</td>";
		    		html += "</tr>";
		    	});

		    	$("#travellerData").html(html);
		    	$("#totalBayar").html(json.totalBayar);
		    	$("#inputMessage").html("");
				$("#modalButtonCetakPdf").show();
		    	$("#modalDeleteHistory").hide();
			} else {
				$("#admin").html(json.headData.admin);
				$("#shuttleAdmin").html(json.headData.shuttle_admin);
				$("#tgl_input").html(json.headData.tanggal_input);
				$("#asal_shuttle").html(json.headData.asal_shuttle);
				$("#tujuan_shuttle").html(json.headData.tujuan_shuttle);
				$("#no_polisi").html(json.headData.bus_no_polisi);
				$("#nama_supir").html(json.headData.bus_nama_supir);

				$("#travellerData").html("");
		    	$("#totalBayar").html("");
		    	$("#inputMessage").html(json.message);
		    	$("#modalDeleteHistory").show();
			}
		}
	});
}

function btnDeleteHistory(id) {
	if (confirm("Apakah anda akan mengahapus data ini..?")) {
		$.post("<?php echo site_url("history/delete/"); ?>"+id,function(json) {
			if (json.status == true) {
				$("#inputMessage").html(json.message);
				setTimeout(function() {
					$("#inputMessage").html("");
					$("#buttonDetail").modal("hide");
					reloadTable();
				},1500);
			} else {
				$("#inputMessage").html(json.message);
				setTimeout(function() {
					$("#inputMessage").html("");
				},3000);
			}
		});
	}
}
	

// console.log(idDelete);

$("#btnRefreshAdmin").click(function() {
	reloadTable();
});

$("#btnRefreshAll").click(function() {
	reloadTable();
});

function reloadTable() {
	$("#tableHistoryAdmin").DataTable().ajax.reload(null,false);
	$("#tableHistoryAll").DataTable().ajax.reload(null,false);
}

$(document).ready(function() {


	$("#tableHistoryAdmin").DataTable({
		serverSide:true,
		processing:true,
		responsive:true,

		ajax:{
			url:'<?php echo site_url("history/ajax_list_admin"); ?>',
			type:'POST',
		},

		order:[[1,'DESC']],
		columns:[
			{
				data:'no',
				orderable:false,
				searchable:false,
			},
			{ data:"tanggal_input" },
			{ data:"nama_admin" },
			{ data:"asal_shuttle" },
			{ data:"kota_asal" },
			{ data:"tujuan_shuttle" },
			{ data:"kota_tujuan" },
			{
				data:null,
				orderable:false,
				searchable:false,
				defaultContent:'<button type="button" onclick="" class="btn btn-sm btn-info btn-flat" >Detail</button>',
			}
		],
		aoColumnDefs:[{
			aTargets:[7],
			fnCreatedCell:function(nTd,sData,oData,iRow,iCol){
				if (iCol == 7) {
					$(nTd.children[0]).attr("onclick","btnDetail("+oData.id+")");
				}
			}
		}],
	});

	$("#tableHistoryAll").DataTable({
		serverSide:true,
		processing:true,
		responsive:true,

		ajax:{
			url:'<?php echo site_url("history/ajax_list"); ?>',
			type:'POST',
		},

		order:[[1,'DESC']],
		columns:[
			{
				data:'no',
				orderable:false,
				searchable:false,
			},
			{ data:"tanggal_input" },
			{ data:"nama_admin" },
			{ data:"asal_shuttle" },
			{ data:"kota_asal" },
			{ data:"tujuan_shuttle" },
			{ data:"kota_tujuan" },
			{
				data:null,
				orderable:false,
				searchable:false,
				defaultContent:'<button type="button" onclick="" class="btn btn-sm btn-info btn-flat" >Detail</button>',
			}
		],
		aoColumnDefs:[{
			aTargets:[7],
			fnCreatedCell:function(nTd,sData,oData,iRow,iCol){
				if (iCol == 7) {
					$(nTd.children[0]).attr("onclick","btnDetail("+oData.id+")");
				}
			}
		}],
	});
});
	
</script>