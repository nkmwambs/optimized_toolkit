<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>IFMS | Libraries</title>
     
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Techsys Inc Softwares" />
	<meta name="author" content="Techsys Inc Softwares" />
	
<?php foreach($css_files as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>

<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
	
</head>
<body class="page-body skins-blue">
	<div class="page-container sidebar-collapsed">
		<div style="margin: 20px;">
			<?php echo $output;?>
		</div>
	</div>
</body>
</html>
