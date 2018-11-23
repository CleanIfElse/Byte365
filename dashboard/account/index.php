<?php
	session_start();
	include('../../scripts/include.php');
	checkLogin();
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - My Account</title>
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<?php print $header; ?>
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<link rel="stylesheet" href="../../css/main.css">
		<link rel="stylesheet" href="../../css/mobile.css">
	</head>
	<body>
		<div id="container" style="height: 100%; position: relative;">
			<?php print $sideNav; ?>
			<div class="dashboard">
				<div class="row mobilehide">
     					<div class="col s4 usage">
						<span class="usage-text">Cloud Usage</span><br />
						<span class="usage-data"><?php print accountUsage(); ?> of <?php print accountLimits(); ?> - <?php print percentUsage(accountUsage(true), accountLimits(true)); ?>%</span>

						<div class="progress">
     							<div class="determinate" style="width: <?php print percentUsage(accountUsage(true), accountLimits(true)); ?>%;"></div>
 						</div> <br />

						<span class="usage-data">
							<strong>Files</strong>: <?php print totalFiles(); ?><br />
						</span>
					</div>

					<div class="col s6 usage" style="margin-left: 20px; z-index: 999 !important;">
   					    <span class="usage-text">Edit Profile</span><br />
					    <div class="form-container" style="width: 100% !important; border: none !important;">
						    <form action="" method="POST">
		   					    <span class="usage-data">
								    <img class="circle" id="prof-image" src="https://byte365.net/uploads/pictures/default.png" style="height: 80px; width: 80px;">
								    <form method="post" action="index.php" id="picture-changer">
									    <input type="file" name="imagevalue" id="profpic">
									    <input type="button" onclick="changePicture()" value="Upload">
								    </form>
		   					    </span>
					    	    </form>
					    </div>
   				    </div>
			     </div>

			     <div class="mobileshow">
				     <div class="admin-container">
					     <div class="admin-nav">Cloud Usage</div>
					     <div class="admin-content">
							<span class="usage-data"><?php print accountUsage(); ?> of <?php print accountLimits(); ?> - <?php print percentUsage(accountUsage(true), accountLimits(true)); ?>%</span>

							<div class="progress">
									<div class="determinate" style="width: <?php print percentUsage(accountUsage(true), accountLimits(true)); ?>%;"></div>
							</div> <br />

							<span class="usage-data">
								<strong>Files</strong>: <?php print totalFiles(); ?><br />
							</span>
					     </div>
				     </div> <br />

				     <div class="admin-container">
					     <div class="admin-nav">Edit Profile</div>
					     <div class="admin-content">
						     <form action="" method="POST">
	 	   					    <span class="usage-data">
	 	   						    <input type="text" name="first" placeholder="First Name" />
	 							    <input type="text" name="last" placeholder="Last Name" />
	 							    <input type="text" name="email" placeholder="Email" />
								    <div style="clear: both;"></div>
	 	   					    </span>
					    		</form>
					     </div>
				     </div>
			     </div>
			</div>
		</div>
		<script src="../../js/main.js"></script>

		<script type="text/javascript">
		function changePicture()
		{
			var data = new FormData(document.getElementById("picture-changer"));

			$.ajax({
			type: "POST",
			    url: "https://byte365.net/api/",
			    data: new FormData($('#profpic')[0]),
			    processData: false,
			    contentType: false,
			    dataType: "text",
			    success: function (data) {
			       alert(data);
			    }
			});
		}
		</script>
	</body>
</html>
