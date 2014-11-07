<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Insert title here</title>
</head>
<body>
<h2>Result:</h2>
<?php 
foreach($this->_data["report"]["result"] as $report_line){
?>
<?php echo $report_line; ?><br />
<?php 
}
?>
<hr />
<?php 
	$army1 = $this->_data["report"]["army1"];
	$survivors1 = $army1->get_survivors();
	$indolents1 = $army1->get_indolents();
	$cowards1 = $army1->get_cowards();
	$heroes1 = $army1->get_deceased_heroes();
?>
<h3><?php echo $army1->get_name()?></h3>
<b>Deceased heroes (died in the battle): <?php echo count($heroes1) ?></b>
<br />
<?php 
foreach($heroes1 as $soldier){
?>
- <?php echo $soldier->to_string(); ?><br />
<?php 
}
?>

<b>Alive Heroes (fought to the end): <?php echo count($survivors1) ?></b>
<br />
<?php 
foreach($survivors1 as $soldier){
?>
- <?php echo $soldier->to_string(); ?><br />
<?php 
}
?>

<b>Indolents (gave up the battle): <?php echo count($indolents1) ?></b>
<br />
<?php 
foreach($indolents1 as $soldier){
?>
- <?php echo $soldier->to_string(); ?><br />
<?php 
}
?>

<b>Cowards (fleed the battle): <?php echo count($cowards1) ?></b>
<br />
<?php 
foreach($cowards1 as $soldier){
?>
- <?php echo $soldier->to_string(); ?><br />
<?php 
}
?>


<hr />
<?php 
	$army2 = $this->_data["report"]["army2"];
	$survivors12 = $army2->get_survivors();
	$indolents2 = $army2->get_indolents();
	$cowards2 = $army2->get_cowards();
	$heroes2 = $army2->get_deceased_heroes();
?>
<h3><?php echo $army2->get_name()?></h3>
<b>Deceased heroes (died in the battle): <?php echo count($heroes2) ?></b>
<br />
<?php 
foreach($heroes2 as $soldier){
?>
- <?php echo $soldier->to_string(); ?><br />
<?php 
}
?>

<b>Alive Heroes (fought to the end): <?php echo count($survivors2) ?></b>
<br />
<?php 
foreach($survivors2 as $soldier){
?>
- <?php echo $soldier->to_string(); ?><br />
<?php 
}
?>

<b>Indolents (gave up the battle): <?php echo count($indolents2) ?></b>
<br />
<?php 
foreach($indolents2 as $soldier){
?>
- <?php echo $soldier->to_string(); ?><br />
<?php 
}
?>

<b>Cowards (fleed the battle): <?php echo count($cowards2) ?></b>
<br />
<?php 
foreach($cowards2 as $soldier){
?>
- <?php echo $soldier->to_string(); ?><br />
<?php 
}
?>
<hr />
<h2>Battle log:</h2>
<?php 
foreach($this->_data["log"] as $nr=>$data){
		if(is_array($data)){
			foreach($data as $key=>$value){
?>
<p>
<?php echo $nr; ?>
<?php echo $key; ?>: <?php echo $value; ?>
</p>
<?php
			}
		}else{
?>
<?php echo $data; ?>
<?php
		}
}
?>
</body>
</html>