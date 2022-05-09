<?php

if(!class_exists('alexPropertyCpt')){
	

	class alexPropertyCpt{
		public function register(){
		add_action('init',[$this,'custom_post_type']);
		add_action('add_meta_boxes',[$this,'add_meta_box_property']);
		add_action('save_post',[$this,'save_metabox'],10,2);
		
		add_action('manage_property_posts_columns', [$this,'custom_columns_for_property']);
		add_action('manage_property_posts_custom_column', [$this,'custom_property_columns_data'],10,2);
		add_filter('manage_edit-property_sortable_columns', [$this,'custom_property_columns_sort']);
		add_action('pre_get_posts', [$this,'custom_property_order']);
		
		
	}
	
	public function add_meta_box_property(){
		add_meta_box(
			'alexproperty_settings',
			'Property Settings',
			[$this, 'metabox_property_html'],
			'property',
			'normal',
			'default'
		);
	}
	
	public function save_metabox($post_id,$post){
		
		if(!isset($_POST['_alexproperty']) || !wp_verify_nonce($_POST['_alexproperty'], 'alexpropertyfields')){
			return $post_id;
		}
		if(defined('DOING_AUTOSAVE') && DOING_AUTOSAVE){
			return $post_id;
		}
		if($post->post_type != 'property'){
			return $post_id;
		}
		
		$post_type = get_post_type_object($post->post_type);
		if(!current_user_can($post_type->cap->edit_post,$post_id)){
			return $post_id;
		}
		
		if(is_null($_POST['alexproperty_price'])){
			delete_post_meta($post_id,'alexproperty_price');
		} else {
			update_post_meta($post_id,'alexproperty_price', sanitize_text_field(intval($_POST['alexproperty_price'])));
		}
		if(is_null($_POST['alexproperty_period'])){
			delete_post_meta($post_id,'alexproperty_period');
		} else {
			update_post_meta($post_id,'alexproperty_period', sanitize_text_field($_POST['alexproperty_period']));
		}
		if(is_null($_POST['alexproperty_type'])){
			delete_post_meta($post_id,'alexproperty_type');
		} else {
			update_post_meta($post_id,'alexproperty_type', sanitize_text_field($_POST['alexproperty_type']));
		}
		if(is_null($_POST['alexproperty_agent'])){
			delete_post_meta($post_id,'alexproperty_agent');
		} else {
			update_post_meta($post_id,'alexproperty_agent', sanitize_text_field($_POST['alexproperty_agent']));
		}
		return $post_id;
	}
	
	
	public function metabox_property_html($post){
		$price = get_post_meta($post->ID, 'alexproperty_price', true);
		$period = get_post_meta($post->ID, 'alexproperty_period', true);
		$type = get_post_meta($post->ID, 'alexproperty_type', true);
		$agent_meta = get_post_meta($post->ID, 'alexproperty_agent', true);
		
		wp_nonce_field('alexpropertyfields','_alexproperty');
		
		echo '
		<p>
			<label for="alexproperty_price">'.esc_html__('Price','alexproperty').'</label>
			<input type="number" id="alexproperty_price" name="alexproperty_price" value="'.esc_html($price).'">
		</p>
		
		<p>
			<label for="alexproperty_period">'.esc_html__('Period','alexproperty').'</label>
			<input type="text" id="alexproperty_period" name="alexproperty_period" value="'.esc_html($period).'">
		</p>
		
		<p>
			<label for="alexproperty_type">'.esc_html__('Type','alexproperty').'</label>
			<select id="alexproperty_type" name="alexproperty_type">
				<option value="">Select Type</option>
				<option value="sale" '.selected('sale',$type,false).'>'.esc_html__('For Sale','alexproperty').'</option>
				<option value="rent" '.selected('rent',$type,false).'>'.esc_html__('For Rent','alexproperty').'</option>
				<option value="sold" '.selected('sold',$type,false).'>'.esc_html__('Sold','alexproperty').'</option>
			</select>
		</p>
		';
		
		$agents = get_posts(array('post_type'=>'agent','numberposts'=>-1));
		
		if($agents){
			echo '<p>
			<label for="alexproperty_agent">'.esc_html__('Agents','alexproperty').'</label>
			<select id="alexproperty_agent" name="alexproperty_agent">
				<option value="">'.esc_html__('Select Agent','alexproperty').'</option>';
		
		
			foreach($agents as $agent){ ?>
			<option value="<?php echo esc_html($agent->ID); ?>" <?php if($agent->ID == $agent_meta){echo 'selected';} ?>><?php echo esc_html($agent->post_title) ?></option>
			<?php }
			
			echo '</select>
			</p>';
		}
	}
	
			public function custom_post_type(){
		
			register_post_type('property',
			array(
				'public' => true,
				'has_archive' => true,
				'rewrite' => ['slug'=>'properties'],
				'label' => esc_html__('Property','alexproperty'),
				'supports' => array('title','editor','thumbnail'),
			));
			register_post_type('agent',
			array(
				'public' => true,
				'has_archive' => true,
				'rewrite' => ['slug'=>'agents'],
				'label' => esc_html__('Agents','alexproperty'),
				'supports' => array('title','editor','thumbnail'),
				'show_in_rest' => true
			));
		
			$labels = array(
				'name'              => esc_html_x( 'Locations', 'taxonomy general name', 'alexcrm' ),
				'singular_name'     => esc_html_x( 'Location', 'taxonomy singular name', 'alexcrm' ),
				'search_items'      => esc_html__( 'Search Locations', 'alexcrm' ),
				'all_items'         => esc_html__( 'All Locations', 'alexcrm' ),
				'view_item'         => esc_html__( 'View Location', 'alexcrm' ),
				'parent_item'       => esc_html__( 'Parent Location', 'alexcrm' ),
				'parent_item_colon' => esc_html__( 'Parent Location:', 'alexcrm' ),
				'edit_item'         => esc_html__( 'Edit Location', 'alexcrm' ),
				'update_item'       => esc_html__( 'Update Location', 'alexcrm' ),
				'add_new_item'      => esc_html__( 'Add New Location', 'alexcrm' ),
				'new_item_name'     => esc_html__( 'New Location Name', 'alexcrm' ),
				'not_found'         => esc_html__( 'No Locations Found', 'alexcrm' ),
				'back_to_items'     => esc_html__( 'Back to Locations', 'alexcrm' ),
				'menu_name'         => esc_html__( 'Location', 'alexcrm' ),
			);
		
			$args = array(
				'hierarchical' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array('slug'=>'properties/location'),
				'labels' => $labels,
			);
		
			register_taxonomy('location','property',$args);
			
			unset($args);
			unset($labels);
		
			$labels = array(
				'name'              => esc_html_x( 'Types', 'taxonomy general name', 'alexcrm' ),
				'singular_name'     => esc_html_x( 'Type', 'taxonomy singular name', 'alexcrm' ),
				'search_items'      => esc_html__( 'Search Types', 'alexcrm' ),
				'all_items'         => esc_html__( 'All Types', 'alexcrm' ),
				'view_item'         => esc_html__( 'View Type', 'alexcrm' ),
				'parent_item'       => esc_html__( 'Parent Type', 'alexcrm' ),
				'parent_item_colon' => esc_html__( 'Parent Type:', 'alexcrm' ),
				'edit_item'         => esc_html__( 'Edit Type', 'alexcrm' ),
				'update_item'       => esc_html__( 'Update Type', 'alexcrm' ),
				'add_new_item'      => esc_html__( 'Add New Type', 'alexcrm' ),
				'new_item_name'     => esc_html__( 'New Type Name', 'alexcrm' ),
				'not_found'         => esc_html__( 'No Types Found', 'alexcrm' ),
				'back_to_items'     => esc_html__( 'Back to Types', 'alexcrm' ),
				'menu_name'         => esc_html__( 'Type', 'alexcrm' ),
			);		
			$args = array(
				'hierarchical' => true,
				'show_ui' => true,
				'show_admin_column' => true,
				'query_var' => true,
				'rewrite' => array('slug'=>'properties/type'),
				'labels' => $labels,
			);
			register_taxonomy('property-type','property',$args);
		}
	
		public function custom_columns_for_property($columns){
			
			$title = $columns['title'];
			$date = $columns['date'];
			$location = $columns['taxonomy-location'];
			$type = $columns['taxonomy-property-type'];
			
			$columns['title'] = $title;
			$columns['date'] = $date;
			$columns['taxonomy-location'] = $location;
			$columns['taxonomy-property-type'] = $type;
			$columns['price'] = esc_html__('Price','alexproperty');
			$columns['offer'] = esc_html__('Offer','alexproperty');
			$columns['agent'] = esc_html__('Agent','alexproperty');
			
			return $columns;
		}
		public function custom_property_columns_data($column, $post_id){
			
			$price = get_post_meta($post_id,'alexproperty_price',true);
			$offer = get_post_meta($post_id,'alexproperty_type',true);
			$agent_id = get_post_meta($post_id,'alexproperty_agent',true);
			if($agent_id){
				$agent = get_the_title($agent_id);
			} else {
				$agent = 'No Agent';
			}
			
			switch($column){
				case 'price':
				    echo esc_html($price);
				    break;
				case 'offer':
				    echo esc_html($offer);
				    break;
				case 'agent':
				    echo esc_html($agent);
				    break;
			}
		}
		
		public function custom_property_columns_sort($columns){
			
			$columns['price'] ='price';
			$columns['offer'] ='offer';
			//$columns['agent'] ='agent';

			return $columns;
		}
		 public function custom_property_order($query){
			 
			if(!is_admin()){
				return;
			}
			$orderby = $query->get('orderby');
			
			if('price' == $orderby){
				$query->set('meta_key','alexproperty_price');
				$query->set('orderby','meta_value_num');
			}
			if('offer' == $orderby){
				$query->set('meta_key','alexproperty_type');
				$query->set('orderby','meta_value');
			}
		 }
	}
	
}	

	if(class_exists('alexPropertyCpt')){
		$alexPropertyCpt = new alexPropertyCpt();
		$alexPropertyCpt->register();	
	}

