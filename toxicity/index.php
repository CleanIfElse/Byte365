<?php
	session_start();
	include('../scripts/include.php');
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title><?php print $settings["sitename"]; ?> - Toxicity Filter</title>
		<link rel="stylesheet" href="../css/mobile.css">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
		<script src="https://ajax.aspnetcdn.com/ajax/jquery/jquery-1.9.0.min.js"></script>
		<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/css/materialize.min.css">
		<link href="https://fonts.googleapis.com/icon?family=Material+Icons" rel="stylesheet">
		<script src="https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.2/js/materialize.min.js"></script>
		<link rel="stylesheet" href="../css/main.css">
	</head>
	<body>
		<div id="container" style="height: 100%; position: relative;">
			<div class="dashboard" style="margin-left: 0px !important; margin: 0 auto !important; width: 100%;">
				<div class="about-filter">
					This tool allows you to test out our toxicity filter API. Click <a href="https://byte365.net/api/?friendly=Message">here</a> to see the raw API.
				</div> <br />
				<table width="100%" cellspacing="0" class="highlight" cellpadding="0">
					<tr>
						<td class="nav" colspan="5">Toxicity Filter (Beta)</td>
					</tr>
					<tbody id="fileBrowser">
						<tr>
							<td class="file-header-first" style="border-right: 1px solid #d4d4d4 !important;">
								<div class="filter-message">
									<span>We've detected a <span id="label-level">0</span>% toxicity rate with this text.</span>
								</div><br />
								<input type="text" class="textbox" id="check-text" placeholder="Enter Text Here" />
							</td>
						</tr>
					</tbody>
				</table>
			</div>
		</div>
		<script src="../js/main.js"></script>
		<script>
		(function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
		(i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
		m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
		})(window,document,'script','https://www.google-analytics.com/analytics.js','ga');

		ga('create', 'UA-105864798-1', 'auto');
		ga('send', 'pageview');

		$('#check-text').on('input',function(e){
	          updateFilter();
	         });

			function updateFilter()
			{
				$.ajax({
				    	type:"GET",
					dataType: "text",
				    	url: "https://byte365.net/api/?friendly=" + $("#check-text").val(),
				    	success: function(data) {
						$("#label-level").text(data);
				        },
				    	error: function(jqXHR, textStatus, errorThrown) {

				        },
				});
			}
		</script>
	</body>
</html>
