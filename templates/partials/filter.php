<div class="wrapper filter_form">
	<?php $alexProperty = new alexProperty(); ?>
	
	<?php $options = get_option('alexproperty_settings_options'); 
	
	if(isset($options['filter_title'])){ echo esc_html($options['filter_title']); }
	if(isset($options['archive_title'])){ echo esc_html($options['archive_title']); }
	
	?>
	
	<form method="post" action="<?php get_post_type_archive_link('property'); ?>">
		<select name="alexproperty_location">
			<option value="">Select Location</option>
			<?php echo $alexProperty->get_terms_hierarchical('location', $_POST['alexproperty_location']); ?>
		</select>
		
		<select name="alexproperty_property-type">
			<option value="">Select Type</option>
			<?php echo $alexProperty->get_terms_hierarchical('property-type', $_POST['alexproperty_property-type']); ?>
		</select>
		
		<input type="text" placeholder="Maximum Price" name="alexproperty_price" value="<?php if(isset($_POST['alexproperty_price'])){echo esc_attr($_POST['alexproperty_price']);} ?>"/>
		 <select name="alexproperty_type">
			<option value="">Select Offer</option>
			<option value="sale" <?php if(isset($_POST['alexproperty_type']) and $_POST['alexproperty_type'] == 'sale') { echo 'selected'; } ?>>For Sale</option>
			<option value="rent" <?php if(isset($_POST['alexproperty_type']) and $_POST['alexproperty_type'] == 'rent') { echo 'selected'; } ?>>For Rent</option>
			<option value="sold" <?php if(isset($_POST['alexproperty_type']) and $_POST['alexproperty_type'] == 'sold') { echo 'selected'; } ?>>Sold</option>
		 </select>
		 <select name="alexproperty_agent">
			<option value="">Select Agent</option>
			<?php
			$agents = get_posts(array('post_type'=>'agent','numberposts'=>-1));
			
			$selected = '';
			if(isset($_POST['alexproperty_agent'])){
				$agent_id = $_POST['alexproperty_agent'];
			}
			
			foreach($agents as $agent){
				echo '<option value="'.$agent->ID.'" '.selected($agent->ID, $agent_id, false).' >'.$agent->post_title.'</option>';
			}
			?>
		 </select>
		<input type="submit" name="submit" value="Filter" />
	</form>
</div>