<!--- A MNgo Creation by: Aditya Suman (http://adityasuman.mngo.in)-->
<?php
	include('php/connect_db.php');
?>
<!-----included files------>
	<link type="text/css" href="css/style.css" rel="stylesheet">
	<link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	<script type="text/javascript" src="js/jquery.js"></script>
	
	<meta name="viewport" content ="width= device-width, initial-scale= 1">
	<meta name="robots" content="index, follow">
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8">
	<meta name="language" content="English">
	<meta name="revisit-after" content="1">
	<meta name="author" content="Aditya Suman">

<body>
<!----------warning ajax box---------->
	<div class="warn_box"></div>

<!---------ajax loader box---------->
	<div class="ajax_loader_bckgrnd"></div>
	<div class="ajax_loader_box">

		<div class="close_icon">
			<img src="img/close.png"/>
		</div>
		
		<div class="ajax_loader_content"></div>
		
	</div>

<!----scripts----->
	<script type="text/javascript">
	/*-----for ajax loading divs------*/
		$('.ajax_loader_bckgrnd').click(function()
		{
			$('.ajax_loader_bckgrnd').fadeOut(500);
			$('.ajax_loader_box').fadeOut(500);
		});

		$('.close_icon img').click(function()
		{
			$('.ajax_loader_bckgrnd').fadeOut(500);
			$('.ajax_loader_box').fadeOut(500);
		});
	</script>
</body>