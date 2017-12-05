<!-- <h4 class="page-header">
    Informasi Data
</h4> -->
<!-- Small boxes (Stat box) -->
<div class="row">
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-aqua">
            <div class="inner">
                <h3 id="countPemesanan">
                    150
                </h3>
                <p>
                    Pemesanan
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-money"></i>
            </div>
            <a href="#" id="pemesananInfo" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-green">
            <div class="inner">
                <h3>
                    <?php echo $jam_count; ?>
                </h3>
                <p>
                    Jam Keberangkatan
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-clock-o"></i>
            </div>
            <a href="#" id="jam" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-yellow">
            <div class="inner">
                <h3>
                    <?php echo $shuttle_count; ?>
                </h3>
                <p>
                    Shuttle
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-building-o"></i>
            </div>
            <a href="#" id="shuttleInfo" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
    <div class="col-lg-3 col-xs-6">
        <div class="small-box bg-red">
            <div class="inner">
                <h3>
                    <?php echo $user_count; ?>
                </h3>
                <p>
                    User Admin
                </p>
            </div>
            <div class="icon">
                <i class="fa fa-users"></i>
            </div>
            <a href="#" id="user" class="small-box-footer">
                More info <i class="fa fa-arrow-circle-right"></i>
            </a>
        </div>
    </div>
</div>

<!-- <h4 class="page-header">
    Chart Pemesanan
</h4> -->
<div class="row">
    <div class="col-md-12">
        <!-- Bar chart -->
        <div class="box box-primary">
            <div class="box-header">
                <i class="fa fa-bar-chart-o"></i>
                <h3 class="box-title">
                    Chart Bulanan, &nbsp;
                    Tahun : <?php echo date("Y"); ?>
                    &nbsp; &nbsp;
                    <button type="button" class="btn btn-flat btn-sm" id="btnRefreshChart"><i class="fa fa-refresh"></i> Refresh</button>
                </h3>

                <!-- <select name="tahunChart" style="margin-top: 12px;">
                    <option value="2016">2016</option>
                    <option value="2017" selected>2017</option>
                    <option value="2018">2018</option>
                </select> -->
            </div>
            <div class="box-body">
                <div id="bar-chart" style="height: 300px;"></div>
            </div>
        </div>
    </div>
</div>


<!-- Modal Pemesanan -->
<div class="Pemesanan">
    <?php echo modalSaveOpen("modalPemesanan"); ?>
        <?php echo openBox("info"); ?>
            <h3>Untuk info lebih detail pemesanan, Silahkan lihat di menu pemesanan.!</h3>
            <h4>Atau klik tombol pemesanan di bawah ini..</h4>
            <button type="button" id="btnInfoPemesanan" class="btn btn-flat btn-lg btn-info">Pemesanan</button>
        <?php echo closeBox(); ?>
    <?php echo modalSaveClose(); ?>
</div>

<!-- Modal jam keberangkatan -->
<div class="jamkeberangkatan">
    <?php echo modalSaveOpen("modalJam"); ?>
        <?php echo openBox("success"); ?>
            <div class="table-responsive">
                <table id="tableJam" class="table table-striped" style="width: 100%">
                    <thead>
                        <th>No</th>
                        <th>Jam</th>
                    </thead>
                    <tbody>    
                    </tbody>
                </table>
            </div>
        <?php echo closeBox(); ?>
    <?php echo modalSaveClose("","modalButtonJam"); ?>
</div>

<!-- Modal shuttle -->
<div class="shuttle">
    <?php echo modalSaveOpen("modalShuttle"); ?>
        <?php echo openBox("warning"); ?>
            <div class="table-responsive">
                <table id="tableShuttle" class="table table-striped" style="width: 100%">
                    <thead>
                        <th>No</th>
                        <th>Shuttle</th>
                        <th>Kota</th>
                    </thead>
                    <tbody>    
                    </tbody>
                </table>
            </div>
        <?php echo closeBox(); ?>
    <?php echo modalSaveClose("Shuttle","modalButtonShuttle"); ?>
</div>

<!-- Modal useradmin -->
<div class="useradmin">
     <?php echo modalSaveOpen("modalUseradmin"); ?>
         <?php echo openBox("danger"); ?>
            <div class="table-responsive">
                <table id="tableUseradmin" class="table table-striped" style="width: 100%">
                    <thead>
                        <th>No</th>
                        <th>Username</th>
                        <th>Nama</th>
                        <th>Shuttle</th>
                        <th>Role</th>
                    </thead>
                    <tbody>    
                    </tbody>
                </table>
            </div>
        <?php echo closeBox(); ?>
    <?php echo modalSaveClose("Useradmin","modalButtonUser"); ?>
</div>

<script type="text/javascript">

    $.post('<?php echo site_url("dashboard/chartPemesanan"); ?>',function(json) {
            $("#countPemesanan").text(json.count);
        });

    function countPemesanan() {
        $.post('<?php echo site_url("dashboard/chartPemesanan"); ?>',function(json) {
            $("#countPemesanan").text(json.count);
        });
    }

    $("#pemesananInfo").click(function() {
        $("#modalPemesanan").modal("show");
        $(".modal-title").text("Informasi Pemesanan");
    });

    $("#btnInfoPemesanan").click(function() {
        window.location.href = '<?php echo site_url("pemesanan#dataPemesananAll"); ?>';
    })

    $("#jam").click(function() {
        $("#modalJam").modal("show");
        $(".modal-title").text("Informasi Jam Keberangkatan");
    });

    $("#shuttleInfo").click(function() {
        $("#modalShuttle").modal("show");
        $(".modal-title").text("Informasi Shuttle");
    });

    $("#user").click(function() {
        $("#modalUseradmin").modal("show");
        $(".modal-title").text("Informasi User admin");
    });

    $("#modalButtonSave").hide();
    $("#modalButtonJam").hide();
    $("#modalButtonShuttle").hide();
    $("#modalButtonUser").hide();

    $(document).ready(function() {
        
        $("#tableJam").dataTable({
            serverSide:true,
            processing:true,
            responsive:true,
            ajax:{
                url: '<?php echo site_url("jamkeberangkatan/info") ?>',
                type:'POST'
            },
            order:[[1,'asc']],
            columnDefs:[
                {
                    targets:[0],
                    orderable:false,

                }
            ]
        });

        $("#tableShuttle").dataTable({
            serverSide:true,
            processing:true,
            responsive:true,
            ajax:{
                url: '<?php echo site_url("shuttle/info") ?>',
                type:'POST'
            },
            order:[[1,'asc']],
            columns:[
                {
                    data:'no',
                    orderable:false,
                    searchable:false
                },
                {data:'nama'},
                {data:'kota'}
            ],
        });

        $("#tableUseradmin").dataTable({
            serverSide:true,
            processing:true,
            responsive:true,
            ajax:{
                url: '<?php echo site_url("user/info") ?>',
                type:'POST'
            },
            order:[[2,'asc']],
            columnDefs:[
                {
                    targets:[0,1],
                    orderable:false,

                }
            ]
        });

    });
</script>

<script type="text/javascript">

    $("#btnRefreshChart").click(function() {
        $(".fa-refresh").addClass("fa-spin");

        setTimeout(function() {
            countPemesanan();
            chartBar();
            $(".fa-refresh").removeClass("fa-spin");
        },1000);
    });

    function chartBar() {
        $.ajax({
            url: '<?php echo site_url("dashboard/chartPemesanan"); ?>',
            type:'POST',
            dataType:'json',
            success:function(json) {
                if (json.status == true) {
                     plot(json);
                }
            }
        });  
    }

    function plot(json) {
        $.plot("#bar-chart", [json], {
            grid: {
                borderWidth: 1,
                borderColor: "#f3f3f3",
                tickColor: "#f3f3f3"
            },
            series: {
                bars: {
                    show: true,
                    barWidth: 0.5,
                    align: "center"
                }
            },
            xaxis: {
                mode: "categories",
                tickLength: 0
            }
        });
    }

    $(document).ready(function() {
        /*
         * BAR CHART
         * ---------                 
         */

        /*var bar_data = {
            data: [ ["Januari", 10], ["Februari", 8], ["Maret", 4], ["April", 30], ["May", 17], ["Juni", 9], ["Juli", 10], ["Agustus", 8], ["September", 40], ["Oktober", 0], ["Nopember", 0], ["Desember", 0] ],
            color: "#3c8dbc"
        };*/
        $.ajax({
            url: '<?php echo site_url("dashboard/chartPemesanan"); ?>',
            type:'POST',
            dataType:'json',
            success:function(json) {
                if (json.status == true) {
                    plot(json);
                }
            }
        });  
        /* END BAR CHART */ 
    });
</script>