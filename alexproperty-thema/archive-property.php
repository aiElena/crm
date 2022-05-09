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