<!-- Left side column. contains the logo and sidebar -->
<aside class="left-side sidebar-offcanvas">                
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img id="avatar_side" src="" class="img-circle" alt="User Image" />
            </div>
            <div class="pull-left info">
                <p id="nama_admin_side"></p>
                <a href="#"><span id="shuttleSide"></span> <b>[&nbsp;<span id="kotaSide"></span>&nbsp;]</b></a>
                <br><br>
                <a href="#" id="locked"><i class="fa fa-lock"></i> Lockscreen</a>
            </div>
        </div>
        
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <?php $className = $this->router->class; ?>
            <li class="<?php echo $className == 'dashboard' ? 'active' : ''; ?>">
                <a href="<?php echo base_url(); ?>">
                    <i class="fa fa-dashboard"></i><span>Dashboard</span>
                </a>
            </li>
            <li class="<?php echo $className == 'pemesanan' ? 'active' : ''; ?>">
                <a href="<?php echo site_url("pemesanan"); ?>">
                    <i class="fa fa-money"></i><span>Pemesanan</span>
                </a>
            </li>
            <li class="<?php echo $className == 'history' ? 'active' : ''; ?>">
                <a href="<?php echo site_url("history"); ?>">
                    <i class="fa fa-list-alt"></i><span>History Laporan</span>
                </a>
            </li>
            <li class="<?php echo $className == 'traveller' ? 'active' : ''; ?>">
                <a href="<?php echo site_url("traveller"); ?>">
                    <i class="fa fa-male fa-female"></i><span>Traveller</span>
                </a>
            </li>

            <?php 
                if ($className == 'route' || $className == 'jamkeberangkatan' || $className == 'bus' || $className == 'shuttle' || $className == 'user') {
                    $activeTreeview = " active ";
                } else {
                    $activeTreeview = "";
                }
             ?>
            <!-- example treeview -->
            <li class="treeview <?php echo $activeTreeview; ?>">
                <a href="#">
                    <i class="fa fa-sitemap"></i> <span>Data Master</span>
                    <i class="fa fa-angle-left pull-right"></i>
                </a>
                <ul class="treeview-menu">
                     <li class="<?php echo $className == 'route' ? 'active' : ''; ?>">
                        <a href="<?php echo site_url("route"); ?>">
                            <i class="fa fa-road"></i><span>Route Perjalanan</span>
                        </a>
                    </li>
                    <li class="<?php echo $className == 'jamkeberangkatan' ? 'active' : ''; ?>">
                        <a href="<?php echo site_url("jamkeberangkatan"); ?>">
                            <i class="fa fa-clock-o"></i><span>Jam Keberangkatan</span>
                        </a>
                    </li>
                    <li class="<?php echo $className == 'bus' ? 'active' : ''; ?>">
                        <a href="<?php echo site_url("bus"); ?>">
                            <i class="fa fa-truck"></i><span>Bus</span>
                        </a>
                    </li>
                    <li class="<?php echo $className == 'shuttle' ? 'active' : ''; ?>">
                        <a href="<?php echo site_url("shuttle"); ?>">
                            <i class="fa fa-building-o"></i><span>Shuttle</span>
                        </a>
                    </li>
                    <li class="<?php echo $className == 'user' ? 'active' : ''; ?>">
                        <a href="<?php echo site_url("user"); ?>">
                            <i class="fa fa-users"></i><span>User Admin</span>
                        </a>
                    </li>
                </ul>
            </li>
        </ul>
    </section>
    <!-- /.sidebar -->
</aside>
<?php 
    $http = $this->input->server("REQUEST_SCHEME")."://";
    $hostname = $http.$this->input->server("HTTP_HOST");
    $redirect = $hostname.strtolower($this->input->server("REQUEST_URI")); 
?>

<script type="text/javascript">
    $("#locked").click(function() {
        redirect = '<?php echo $redirect; ?>';
        $.ajax({
            url: '<?php echo site_url("dashboard/locked"); ?>',
            type:'POST',
            dataType:'json',
            success: function(json) {
                if (json.status == true) {
                    document.location.href = '<?php echo site_url("dashboard/lockscreen?redirect="); ?>'+redirect;
                }
            }
        });
    });
</script>