<?php
/*
Plugin Name: alexProperty
Plugin URI: 
Description: Plugin CRM
Version: 1.0
Author: Alexandrova
Author URI:
Licence: GPLv2 or later
Text Domain: alexproperty
Domain Path: /lang
*/

if(!defined('ABSPATH')){
	die;
}

define('ALEXPROPERTY_PATH',plugin_dir_path(__FILE__));

if(!class_exists('alexPropertyCpt')){
	require ALEXPROPERTY_PATH . 'inc/class-alexpropertycpt.php';
}

if(!class_exists('Gamajo-Template-Loader')){
	require ALEXPROPERTY_PATH .'inc/class-gamajo-template-loader.php';
}
require ALEXPROPERTY_PATH .'inc/class-alexproperty-template-loader.php';
require ALEXPROPERTY_PATH .'inc/class-alexproperty-shortcodes.php';
require ALEXPROPERTY_PATH .'inc/class-alexproperty-filter-widget.php';
require ALEXPROPERTY_PATH .'inc/class-alexproperty-bookingform.php';
require ALEXPROPERTY_PATH .'inc/class-alexproperty-wishlist.php';

class alexProperty{
	
	
	function register(){
		add_action('admin_enqueue_scripts',[$this,'enqueue_admin']);
		add_action('wp_enqueue_scripts',[$this,'enqueue_front']);
		add_action('plugins_loaded',[$this,'load_text_domain']);
		add_action('widgets_init',[$this,'register_widget']);
		add_action('admin_menu',[$this,'add_menu_item']);
		add_filter('plugin_action_links_'.plugin_basename(__FILE__),[$this,'add_plugin_setting_link']);
		add_action('admin_init',[$this, 'settings_init']);
	}
	
	
	public function settings_init(){
		register_setting('alexproperty_settings','alexproperty_settings_options');
		add_settings_section('alexproperty_settings_section', esc_html__('Settings','alexproperty'), [$this,'alexproperty_settings_section_html'],'alexproperty_settings');
		add_settings_field('filter_title',esc_html__('Title for Filter','alexproperty'), [$this,'filter_title_html'],'alexproperty_settings','alexproperty_settings_section');
		add_settings_field('archive_title',esc_html__('Title for Archive Page','alexproperty'), [$this,'archive_title_html'],'alexproperty_settings','alexproperty_settings_section');
		
	}
	
	public function alexproperty_settings_section_html(){
		esc_html_e('Settings for alexProperty Plugin');
	}
	
	public function filter_title_html(){
		
		$options = get_option('alexproperty_settings_options');
		
		?>
		
		<input type="text" name="alexproperty_settings_options[filter_title]" value="<?php echo isset($options['filter_title']) ? $options['filter_title'] : ""; ?>" />
		
		<?php
	}
	
	public function archive_title_html(){
		
		$options = get_option('alexproperty_settings_options');
		
		?>
		
		<input type="text" name="alexproperty_settings_options[archive_title]" value="<?php echo isset($options['archive_title']) ? $options['archive_title'] : ""; ?>" />
		
		<?php
	}
	
	public function add_plugin_setting_link($link){	
		$alexproperty_link = '<a href="admin.php?page=alexproperty_settings">'. esc_html__('Settings Page','alexproperty') .'</a>';
		array_push($link,$alexproperty_link);
		
		return $link;
	}
	
	public function add_menu_item(){
		add_menu_page(
			esc_html__('alexProperty Settings Page','alexproperty'),
			esc_html__('alexProperty','alexproperty'),
			'manage_options',
			'alexproperty_settings',
			[$this,'main_admin_page'],
			'dashicons-admin-plugins',
			50
		);
	}
	
	public function main_admin_page(){
		require_once ALEXPROPERTY_PATH . 'admin/welcome.php';
	}
	
	public function register_widget(){
		register_widget('alexproperty_filter_widget');
	}
	
	
	public function get_terms_hierarchical($tax_name,$current_term){
		$taxonomy_terms = get_terms($tax_name,['hide_empty'=>'false','parent'=>0]);
		
		$html = '';
		if(!empty($taxonomy_terms)){
			foreach($taxonomy_terms as $term){
				if($current_term == $term->term_id){
					$html .= '<option value="'.$term->term_id.'" selected >'.$term->name.'</option>';
				} else {
					$html .= '<option value="'.$term->term_id.'" >'.$term->name.'</option>';
				}
				$child_terms = get_terms($tax_name,['hide_empty'=>false,'parent'=>$term->term_id]);
				
				if(!empty($child_terms)){
					foreach($child_terms as $child){
						if($current_term == $child->term_id){
							$html .= '<option value="'.$child->term_id.'" selected > - '.$child->name.'</option>';
						} else {
							$html.= '<option value="'.$child->term_id.'" > - '.$child->name.'</option>';
						}	
					}
				}
			}
		}
		return $html;
	}
	

	
	function load_text_domain(){
		load_plugin_textdomain('alexproperty', false, dirname(plugin_basename(__FILE__)).'/lang');
	}
	
	public function enqueue_admin(){
		wp_enqueue_style('alexProperty_style_admin',plugins_url('/assets/css/admin/style.css',__FILE__));
		wp_enqueue_script('alexProperty_script_admin',plugins_url('/assets/js/admin/scripts.js',__FILE__),array('jquery'),'1.0',true);
	}
		public function enqueue_front(){
		wp_enqueue_style('alexProperty_style',plugins_url('/assets/css/front/style.css',__FILE__));
		wp_enqueue_script('alexProperty_script_front',plugins_url('/assets/js/front/scripts.js',__FILE__),array('jquery'),'1.0',true);
		wp_enqueue_script('jquery-form');
			
	}
	
	static function activation(){
		flush_rewrite_rules();
	}
	static function deactivation(){
		flush_rewrite_rules();
	}
}
if(class_exists('alexProperty')){
	$alexProperty = new alexProperty();
	$alexProperty->register();
}
register_activation_hook(__FILE__, array($alexProperty, 'activation') );
register_deactivation_hook(__FILE__, array($alexProperty, 'deactivation') );