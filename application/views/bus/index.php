<?php echo openBox("info","Table Bus"); ?>
	<div class="table-responsive">
		<?php if($this->user_role == "admin") : ?>
			<button id="btnAdd" class="btn btn-primary btn-sm btn-flat"><i class="fa fa-plus"></i> Tambah</button> &nbsp;&nbsp;
		<?php endif; ?>
		<button id="btnRefresh" class="btn btn-sm btn-flat"><i class="fa fa-refresh"></i> refresh</button><br><br>
		<table id="tableBus" class="table table-bordered table-striped table-hover" style="width: 100%">
			<thead>
				<th>No</th>
				<th>No Polisi</th>
				<th>Nama Supir</th>
				<th>Nama Kenek</th>
				<th>Jumlah Kursi</th>
				<?php if($this->user_role == "admin") : ?>
					<th>Action</th>
				<?php endif; ?>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
<?php echo closeBox(); ?>

<!-- Load Form data -->
<div class="form_data">
	<?php $this->load->view("bus/form_data"); ?>
</div>

<script type="text/javascript">
	$(document).ready(function(){
		actionButton = '<button onclick="" class="btn btn-xs btn-warning btn-flat">Edit</button> || ';
		actionButton += '<button onclick="" class="btn btn-xs btn-danger btn-flat">Delete</button>';
		$("#tableBus").dataTable({
			serverSide:true,
			processing:true,

			ajax:{
				url:'<?php echo site_url("bus/ajax_list"); ?>',
				type:'POST'
			},
			order:[[2,'asc']],
			columns:[
				{ 
					data:'no',
					searchable:false,
					orderable:false,
				},
				{ data:'no_polisi' },
				{ data:'nama_supir' },
				{ data:'nama_kenek' },
				{ data:'jumlah_kursi' },
				<?php if($this->user_role == "admin") : ?>
				{ 
					data: null,
					searchable:false,
					orderable:false,
					defaultContent: actionButton,
					clasName:"td-body-center"
				}
				<?php endif; ?>
			],

			<?php if($this->user_role == "admin") : ?>
			aoColumnDefs:[{
				aTargets:[5],
				fnCreatedCell: function(nTd,sData,oData,iRow,iCol) {
					if (iCol == 5) {
						$(nTd.children[0]).attr("onclick","btnEdit("+oData.id+")");
						$(nTd.children[1]).attr("onclick","btnDelete("+oData.id+")");
					}
				}
			}],
			<?php endif; ?>
		});
	});

</script>