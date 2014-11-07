<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
</head>
<body>
<?php 
foreach($this->data as $key=>$value){
?>
<p>
<?php echo $key; ?>: <?php echo $value; ?>
</p>
<?php
}
?>
</body>
</html>