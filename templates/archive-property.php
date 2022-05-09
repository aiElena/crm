<?php
get_header();
?>

<?php $alexProperty_Template->get_template_part('partials/filter'); ?>
	<div class="wrapper archive_property">
		<?php
		
		if(!empty($_POST['submit'])){
			
			$args = array(
				'post_type' => 'property',
				'posts_per_page' => -1,
				'meta_query' => array('relation'=>'AND'),
				'tax_query' => array('relation'=>'AND'),	
			);
			
			if(isset($_POST['alexproperty_type']) && $_POST['alexproperty_type'] !='') {
				array_push($args['meta_query'],array(
					'key' => 'alexproperty_type',
					'value' => esc_attr($_POST['alexproperty_type']),
				));
			}
			
			if(isset($_POST['alexproperty_period']) && $_POST['alexproperty_period'] !='') {
				array_push($args['meta_query'],array(
					'key' => 'alexproperty_period',
					'value' => esc_attr($_POST['alexproperty_period']),
				));
			}			
			
			
			
			
			if(isset($_POST['alexproperty_price']) && $_POST['alexproperty_price'] !='') {
				array_push($args['meta_query'],array(
					'key' => 'alexproperty_price',
					'value' => esc_attr($_POST['alexproperty_price']),
					'type' => 'numeric',
					'compare' => '<=',
				));
			}
			
			

			
			
			if(isset($_POST['alexproperty_agent']) && $_POST['alexproperty_agent'] !='') {
				array_push($args['meta_query'],array(
					'key' => 'alexproperty_agent',
					'value' => esc_attr($_POST['alexproperty_agent']),
				));
			}
			if(isset($_POST['alexproperty_property-type']) && $_POST['alexproperty_property-type'] != '') {
				array_push($args['tax_query'],array(
					'taxonomy' => 'property-type',
					'terms' => esc_attr($_POST['alexproperty_property-type']),
				));
			}
			if(isset($_POST['alexproperty_location']) && $_POST['alexproperty_location'] != '') {
				array_push($args['tax_query'],array(
					'taxonomy' => 'location',
					'terms' => esc_attr($_POST['alexproperty_location']),
				));
			}

			
		$properties = new WP_Query($args);
		
		if ( $properties->have_posts() ) {
			while ( $properties->have_posts() ) {
				$properties->the_post();
			$alexProperty_Template->get_template_part('partials/content');
				
		}
			
		} else {
			echo '<p>' .esc_html__('No Properties','alexproperty'). '</p>';
		}		

		} else {
		
		if(have_posts()){
			while(have_posts()) {
				the_post(); 
			$alexProperty_Template->get_template_part('partials/content');
				
		}
			posts_nav_link();
		} else {
			echo '<p>' .esc_html__('No Properties','alexproperty'). '</p>';
		}
	}
	?>
	</div>
<?php
get_footer();