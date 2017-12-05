<div class="navbar-right">
    <ul class="nav navbar-nav">
       
        <!-- User Account: style can be found in dropdown.less -->
        <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                <i class="glyphicon glyphicon-user"></i>
                <span><span id="nama_admin_head"></span> <i class="caret"></i></span>
            </a>
            <ul class="dropdown-menu">
                <!-- User image -->
                <li class="user-header bg-light-blue">
                    <img id="avatar_head" src="" class="img-circle" alt="User Image" />
                    <p>
                        <span id="nama_admin"></span> - <?php echo $this->user_role == "admin" ? "Super " : ""; ?> Admin
                        <!-- <small>Member since Nov. 2012</small> -->
                    </p>
                </li>
                <!-- Menu Body -->
                <li class="user-body">
                    <div class="text-center">
                        <span id="shuttle"></span> <b>[&nbsp;<span id="kota"></span>&nbsp;]</b> 
                    </div>
                </li>
                <!-- Menu Footer-->
                <li class="user-footer">
                    <div class="pull-left">
                        <!-- <button type="button" id="profile_admin" class="btn btn-default btn-flat">Profile</button> -->
                        <a href="<?php echo site_url("user/profile"); ?>" class="btn btn-default btn-flat">Profile</a>
                    </div>
                    <div class="pull-right">
                        <?php 
                            $http = $this->input->server("REQUEST_SCHEME")."://";
                            $hostname = $http.$this->input->server("HTTP_HOST");
                            $redirect = $hostname.strtolower($this->input->server("REQUEST_URI")); 
                        ?>
                        <a href="<?php echo site_url("user/logout?redirect=".$redirect); ?>" class="btn btn-default btn-flat">Sign out</a>
                    </div>
                </li>
            </ul>
        </li>
    </ul>
</div>

<script type="text/javascript">
    $.ajax({
        url:'<?php echo site_url("user/ajax_user_admin"); ?>',
        type: 'POST',
        dataType: 'json',
        success: function(json) {
            if (json.status == true) {
                $("#nama_admin").text(json.data.nama_admin);
                $("#shuttle").text(json.data.shuttle);
                $("#kota").text(json.data.kota);
                $("#shuttleSide").text(json.data.shuttle);
                $("#kotaSide").text(json.data.kota);
                $("#nama_admin_head").text(json.data.nama_admin);
                $("#nama_admin_side").text(json.data.nama_admin);
                if (json.data.photo_admin == "") {
                    $("#avatar_head").attr("src","<?php echo base_url('assets/image/user_image.png'); ?>");
                    $("#avatar_side").attr("src","<?php echo base_url('assets/image/user_image.png'); ?>");
                } else {
                    var photo = "<?php echo base_url('uploads/'); ?>"+json.data.photo_admin;
                    $("#avatar_head").attr("src",photo);
                    $("#avatar_side").attr("src",photo);
                }
            }
        }
    });

    function seeAllProfile() {
        $.ajax({
            url:'<?php echo site_url("user/ajax_user_admin"); ?>',
            type: 'POST',
            dataType: 'json',
            success: function(json) {
                if (json.status == true) {
                    $("#nama_admin").text(json.data.nama_admin);
                    $("#shuttle").text(json.data.shuttle);
                    $("#kota").text(json.data.kota);
                    $("#shuttleSide").text(json.data.shuttle);
                    $("#kotaSide").text(json.data.kota);
                    $("#nama_admin_head").text(json.data.nama_admin);
                    $("#nama_admin_side").text(json.data.nama_admin);
                    if (json.data.photo_admin == "") {
                        $("#avatar_head").attr("src","<?php echo base_url('assets/image/user_image.png'); ?>");
                        $("#avatar_side").attr("src","<?php echo base_url('assets/image/user_image.png'); ?>");
                    } else {
                        var photo = "<?php echo base_url('uploads/'); ?>"+json.data.photo_admin;
                        $("#avatar_head").attr("src",photo);
                        $("#avatar_side").attr("src",photo);
                    }
                }
            }
        });
    }

    $("#profile_admin").click(function() {
        $("#modal_profile").modal("show");
        $(".modal-title").text("Profile");
    });
</script>