<!DOCTYPE html>
<html class="bg-black">
    <head>
        <meta charset="UTF-8">
        <title>TravelMedia | Log in</title>
        <meta content='width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no' name='viewport'>
        <!-- bootstrap 3.0.2 -->
        <link href="<?php echo base_url();?>assets/css/bootstrap.min.css" rel="stylesheet" type="text/css" />
        <!-- font Awesome -->
        <link href="<?php echo base_url();?>assets/css/font-awesome.min.css" rel="stylesheet" type="text/css" />
        <!-- Theme style -->
        <link href="<?php echo base_url();?>assets/css/AdminLTE.css" rel="stylesheet" type="text/css" />

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
          <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
          <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
        <![endif]-->
        <style type="text/css">

	        .bg-black {
	        	    background-color: #87bdd2 !important;
	        }

        	.form-box .header {
			    box-shadow: inset 0px -3px 0px rgb(195, 195, 197);
			    background: #001f3f;
			}

        	.form-box .footer {
			    padding: 10px 20px;
			    background: #020310;
			    color: #444;
			}

			.form-box {
			    width: 360px;
			    margin: 90px auto 0 auto;
			    border: 1px solid #c3c3c5;
			}

			.row {
			    margin-bottom: -45px;
			    margin-top: 25px;
			}

			#btnSign {
				font-size: 25px;
			}

        </style>
    </head>
    <body class="bg-black">
        <div class="margin text-center">
            <h1 style="color: black;">Selamat Datang di TravelMedia.</h1>
        </div>

	    <div class="row">
	    	<div class="col-md-4 col-md-offset-4">
	    		<center><div id="inputMessage"></div></center>
	    	</div>
	    </div>
    	
        <div class="form-box" id="login-box">
            <div class="header">TravelMedia Sign In</div>
            <form action="#" method="post" id="form">
                <div class="body bg-navy">
                    <div class="form-group">
                        <input type="text" name="username" class="form-control" placeholder="Username"/>
                    	<div id="username"></div>
                    </div>
                    <div class="form-group">
                        <input type="password" name="password" class="form-control" placeholder="Password"/>
                        <div id="password"></div>
                    </div>
                </div>
                <div class="footer">                                                               
                    <button type="button" id="btnSign" class="btn bg-blue btn-flat btn-block">Sign in</button>  
                </div>
            </form>
        </div>


        <!-- jQuery 2.0.2 -->
        <script src="<?php echo base_url();?>assets/js/jquery/jquery-2.1.0.min.js"></script>
        <!-- Bootstrap -->
        <script src="<?php echo base_url();?>assets/js/bootstrap.min.js" type="text/javascript"></script>        

    </body>
</html>
<?php 
	$get = $this->input->get("redirect");
	$get = $get == "" ? base_url() : $get;
	$redirect = isset($get) ? $get : base_url();
?>
<script type="text/javascript">

$(document).ready(function() {
	$("#btnSign").click(function() {
		$("#btnSign").attr('disabled',true);
		$("#btnSign").html("Loading...<i class='fa fa-spinner fa-spin'></i>");
		$.ajax({
			url:'<?php echo site_url("user/ajax_login"); ?>',
			type:'POST',
			dataType:'json',
			data:$("#form").serialize(),
			success: function(json) {
				if (json.status == true) {
					$("#inputMessage").html(json.message);
					setTimeout(function() {
						$("#btnSign").attr('disabled',false);
						$("#btnSign").text("Sign in");
						document.location.href = '<?php echo $redirect; ?>';
					},1000);
				} else {
					if (!json.error) {
						$("#inputMessage").html(json.message);
						setTimeout(function() {
							$("#inputMessage").html("");
							$("#btnSign").attr('disabled',false);
							$("#btnSign").text("Sign in");
						},5000);
					} else {
						$("#username").html(json.error.username);
						$("#password").html(json.error.password);
						setTimeout(function() {
							$("#username").html("");
							$("#password").html("");
							$("#btnSign").attr('disabled',false);
							$("#btnSign").text("Sign in");
						},3000);
					}
				}
			}
		});
	});
});
	
</script>