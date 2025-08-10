
<!-- h1 -->
<h1>H1</h1>
<!-- h1 -->

<!-- h2 -->
<h2>H2</h2>
<!-- h2 -->


<!-- ul -->
<ul>	
	<?php  foreach ($list as $key => $value) { ?>
		
	<!-- li -->		
	<li>
		<a href="<?php echo $value; ?>">
			<?php echo $key; ?>
		</a>
	</li>
	<!-- li -->
			
	<?php } ?>
</ul>
<!-- ul -->
