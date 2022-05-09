<?php 

class alexProperty_Wishlist {
	function register(){
		add_action('wp_ajax_alexproperty_add_wishlist',[$this,'alexproperty_add_wishlist']);
		add_action('wp_ajax_alexproperty_remove_wishlist',[$this,'alexproperty_remove_wishlist']);
	}
	public function alexproperty_add_wishlist(){
		if(isset($_POST['alex_property_id']) && isset($_POST['alex_user_id'])){
			$property_id = intval($_POST['alex_property_id']);
			$user_id = intval($_POST['alex_user_id']);
			
			if($property_id > 0 && $user_id > 0){
				if(add_user_meta($user_id, 'alexproperty_wishlist_properties', $property_id)){
					esc_html_e('Succeesful ladded to wishlist', 'alexproperty');
				} else {
					esc_html_e('Failed', 'alexproperty');
				}
			}
		}
		wp_die();
	}
	
	public function alexproperty_remove_wishlist(){
		if(isset($_POST['alex_property_id']) && isset($_POST['alex_user_id'])){
			$property_id = intval($_POST['alex_property_id']);
			$user_id = intval($_POST['alex_user_id']);
			
			if($property_id > 0 && $user_id > 0){
				if(delete_user_meta($user_id, 'alexproperty_wishlist_properties', $property_id)){
					echo 3;//Success
				} else {
					echo 2;//Failed
				}
			} else {
				echo 1;//Bad
			}
		} else {
			echo 1;//Bad
		}
		wp_die();
	}
	public function alexproperty_in_wishlist($user_id, $property_id){
		global $wpdb;
		$result = $wpdb->get_results("SELECT * FROM $wpdb->usermeta WHERE meta_key='alexproperty_wishlist_properties' AND meta_value=".$property_id." AND user_id=".$user_id);
		if(isset($result[0]->meta_value) && $result[0]->meta_value == $property_id){
			return true;
		} else {
			return false;
		}
	}
}
$alexProperty_Wishlist = new alexProperty_Wishlist();
$alexProperty_Wishlist->register();