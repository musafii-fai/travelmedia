                        </div>
                    </div>
                </section><!-- /.content -->
            </aside><!-- /.right-side -->
        </div><!-- ./wrapper -->

        <!-- <script src="<?php //echo base_url(); ?>assets/js/jquery-ui-1.10.3.min.js"></script> -->
        <!-- Bootstrap -->
        <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js" type="text/javascript"></script>
        <!-- DATA TABES SCRIPT -->
        <script src="<?php echo base_url('assets/datatables.net-bs/js/jquery.dataTables.min.js')?>"></script>
        <script src="<?php echo base_url('assets/datatables.net-bs/js/dataTables.bootstrap.min.js')?>"></script>
        <!-- bootstrap time picker -->
        <script src="<?php echo base_url(); ?>assets/js/plugins/timepicker/bootstrap-timepicker.min.js" type="text/javascript"></script>
        <!-- AdminLTE App -->
        <script src="<?php echo base_url(); ?>assets/js/AdminLTE/app.js" type="text/javascript"></script>
        <!-- FLOT CHARTS -->
        <script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.min.js" type="text/javascript"></script>
        <!-- FLOT RESIZE PLUGIN - allows the chart to redraw when the window is resized -->
        <script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.resize.min.js" type="text/javascript"></script>
        <!-- FLOT PIE PLUGIN - also used to draw donut charts -->
        <script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.pie.min.js" type="text/javascript"></script>
        <!-- FLOT CATEGORIES PLUGIN - Used to draw bar charts -->
        <script src="<?php echo base_url(); ?>assets/js/plugins/flot/jquery.flot.categories.min.js" type="text/javascript"></script>
        
        <!-- datepicker -->
        <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap-datepicker.min.js" type="text/javascript"></script>
        <!-- Select2-->
        <script src="<?php echo base_url(); ?>assets/select2/js/select2.full.min.js" type="text/javascript"></script>

        <script type="text/javascript">
            $(document).ready(function(){
                $(".select2").select2();
            });

            //datepicker
            $('.datepicker').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true,
                orientation: "top auto",
                todayBtn: true,
                todayHighlight: true,
            });
            
        </script>

    </body>
</html>