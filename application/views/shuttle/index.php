
<?php echo openBox("success","Table Shuttle"); ?>
  <?php if($this->user_role == "admin") : ?>
    <button class="btn btn-primary btn-sm btn-flat" id="actionAddData"><span class="fa fa-plus"></span> Tambah</button> &nbsp;&nbsp;
  <?php endif; ?>
  <button type='button' id='refreshTable' class='btn btn-sm btn-flat'><span class="fa fa-refresh"></span> Refresh</button>
  <br><br>
  <div class="table-responsive">
    <table id="tableShuttle" url="<?php echo site_url('shuttle/list_ajax'); ?>" class="table table-bordered table-striped table-hover dataTable">
        <thead>
            <tr>
                <th>No</th>
                <th>Nama</th>
                <th>Kota</th>
                <?php if($this->user_role == "admin") : ?>
                  <th>Action</th>
                <?php endif; ?>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
  </div>
<?php echo closeBox(); ?>

<div id="formData">
  <?php $this->load->view("shuttle/form_data"); ?>
</div>

<!-- dataTable -->
<script type="text/javascript">
  $(document).ready(function() {

    var actionBtn = "<button type='button'  onclick='' class='btn btn-xs btn-warning btn-flat '> Edit</button> || ";
        actionBtn += "<button type='button'  onclick='' class='btn btn-xs btn-danger btn-flat'> Delete</button>";
    //datatables
    $('#tableShuttle').DataTable({ 

        processing: true, //Feature control the processing indicator.
        serverSide: true, //Feature control DataTables' server-side processing mode.
        ordering: true,
        bFilter: true,
        lengthChange: true,
        responsive: true,
        oLanguage:{
          sSearch: "<i class='fa fa-search fa-fw'></i> Pencarian : ",
          sLengthMenu: "_MENU_ &nbsp;&nbsp;Data Per Halaman",
          sInfo: "Menampilkan _START_ s/d _END_ dari <b>_TOTAL_ data</b>",
          sInfoFiltered: "(difilter dari _MAX_ total data)", 
          sZeroRecords: "Pencarian tidak ditemukan", 
          // "sEmptyTable": "Data kosong", 
          sLoadingRecords: "Harap Tunggu...", 
          oPaginate: {
            "sPrevious": "Prev",
            "sNext": "Next"
          }
        },

        // Load data for the table's content from an Ajax source
        ajax: {
            url: '<?php echo site_url("shuttle/ajax_list"); ?>',
            type: "POST",
        },

        order: [[1, 'asc']],
        columns: [
          {
              data: 'no',
              orderable: false,
              searchable: false
          },
          {
            data: 'nama',
            // defaultContent:"<b>Namanya</b>",
          },
          {data: 'kota'},
          <?php if($this->user_role == "admin") : ?>
          {
            data:null,
            orderable:false,
            searchable:false,
            defaultContent: actionBtn,
            className:"dt-body-center",
          }
          <?php endif; ?>
        ],

        <?php if($this->user_role == "admin") : ?>
        aoColumnDefs:[{
          aTargets:[3],
          fnCreatedCell: function(nTd,sData,oData,iRow,iCol){
            if (iCol == 3) {
              $(nTd.children[0]).attr("onclick","edit_shuttle("+oData.id+")");
              $(nTd.children[1]).attr("onclick","delete_shuttle("+oData.id+")");
            }
          }
        }],
        <?php endif; ?>
    });

    $("#refreshTable").click(function(e){
      $("#tableShuttle").DataTable().ajax.reload(null,false);
    });

  });
</script>
