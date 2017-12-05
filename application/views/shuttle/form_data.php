<?php 
    $idTarget = "formModal";
    echo modalSaveOpen($idTarget);
      echo form_open("",array("id" => "formShuttle"));
?>  
        <div class="form-group">
            <label>Nama</label>
            <input type="hidden" name="id">
            <input type="text" class="form-control" name="nama" id="namaShuttle" placeholder="Nama Shuttle">
            <div id="namaError"></div>
        </div>
        <div class="form-group">
            <label>Kota Shuttle</label>
            <select name="kota" id="kotaShuttle" class="form-control">
                <option value="">--Pilih Kota--</option>
                <option value="Medan">Medan</option>
                <option value="Riau">Riau</option>
            </select>
            <div id="kotaError"></div>
        </div>
        <div id="responseInput"></div>
<?php 
      echo form_close(); 
    echo modalSaveClose("Save Shuttle","btnSave");
?>

<!-- MOdal Delete -->
<div id="popupdelete">
  <?php  
     /*modal delete*/
    echo modalDeleteShow("Delete Shuttle");
  ?>
</div>

<script type="text/javascript">

var save_method;
  
  // add ajax
  $("#actionAddData").click(function(){
    save_method = "add";
    $("#formModal").modal("show");
    $(".modal-title").text("Tambah Shuttle");
    $("#formShuttle").each(function(){
      this.reset();
    });
  });

  function edit_shuttle(id) {
    $.ajax({
      url: "<?php echo site_url('shuttle/edit_ajax/'); ?>"+id,
      type: "POST",
      dataType: "json",
      success: function(json){
        $("[name='id']").val(json.data.id);
        $("[name='nama']").val(json.data.nama);
        $("[name='kota']").val(json.data.kota);
        $("#formModal").modal("show");
        $(".modal-title").text("Update Shuttle");
        save_method = "update";
      }
    });
  }

  var idDelete;
  function delete_shuttle(id) {
    $("#deleteModal").modal("show");
    $(".modal-title").text("Delete Shuttle");
    idDelete = id;
    $.ajax({
      url: "<?php echo site_url('shuttle/edit_ajax/'); ?>"+id,
      type: "POST",
      dataType: "json",
      success: function(json){
        var infoData = json.data.nama+" - "+json.data.kota;
        $("#inputMessageDelete").html(infoData);
      }
    });
  }

  $("#modalButtonDelete").click(function(){
      $.ajax({
          url: '<?php echo site_url("shuttle/delete/") ?>'+idDelete,
          type: 'POST',
          dataType: 'json',
          success: function(data){
            if (data.status == true) {
                  $("#contentDelete").hide();
                  $("#inputMessageDelete").html(data.message);
                setTimeout(function(){
                  $("#contentDelete").show();
                  $("#inputMessageDelete").html("");
                  $("#deleteModal").modal("hide");
                  $("#tableShuttle").DataTable().ajax.reload(null,false);
                },1500);
            }
          }
      });
  });

  function saveData() {

    var url;
    if (save_method == "add") {
      url = '<?php echo site_url("shuttle/add"); ?>';
    } else {
      url = '<?php echo site_url("shuttle/update"); ?>';
    }

    $.ajax({
      url: url,
      type: "POST",
      cache: false,
      data: $("#formShuttle").serialize(),
      dataType: 'json',
      success: function(json){
        if (json.status == true) {
          $("#responseInput").html(json.message);
          $("#tableShuttle").DataTable().ajax.reload(null,false);
          setTimeout(function(){
            $("#responseInput").html("");
            $("#formModal").modal("hide");
            $("#formShuttle").each(function(){
                this.reset();
            });
          },2000);
        } else {
          $("#responseInput").html(json.message);
          $("#namaError").html(json.errorNama);
          $("#kotaError").html(json.errorKota);

          setTimeout(function(){
            $("#responseInput").html("");
            $("#namaError").html("");
            $("#kotaError").html("");
          },2000);
        }
      }
    });
  }

  $(document).ready(function(){
    $("#btnSave").click(function(){
      saveData();
    });

  });
</script>