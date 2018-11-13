<!DOCTYPE html>
<html lang="en">
<head>
	
	<title>IFMS | Libraries</title>
     
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1.0" />
	<meta name="description" content="Techsys Inc Softwares" />
	<meta name="author" content="Techsys Inc Softwares" />
	
<?php foreach($this->load_css() as $file): ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>

<?php foreach($this->load_js() as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
	
</head>
<body class="page-body skins-blue">	
	<div class="page-container sidebar-<?=$this->config->sidebar_state;?>">
		<?php if($this->has_sidebar()) include_once('navigation.php');?>
		<div class='main-content'>
			<?php echo $output;?>
		</div>
		<?php include_once('modal.php');?>
	</div>
</body>
<script>
	$(document).ready(function(){
		// $("a").on('click',function(ev){
// 			
			// var url = $(this).attr('href');
// 			
			// if(url!=="#"){
				// $.ajax({
					// url:url,
					// beforeSend:function(){
						// $("#overlay").css("display","block");
					// },
					// success:function(resp){
						// $("#overlay").css("display","none");
						// $('.main-content').html(resp);
						// //alert(url)
					// },error:function(){
// 						
					// }
				// })
			// }
// 			
			// ev.preventDefault();
		// })
	});
</script>
</html>
