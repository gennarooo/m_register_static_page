<?php
/**
 * m_register_static_page
 * 
 * Description:	Add custom static page to Settings->Reading
 * Author:			mosne
 * Author URI:		https://mosne.it
 * Text Domain:	mosne
 */

/**
 * Register the custom static page
 * 
 * @param string $id		ID to assign to page
 * @param string $label	Label for the static page
 * @example m_register_static_page('faq_page', 'FAQs Page');
 */

function m_register_static_page($id, $label){
	m_add_default_page_admin_settings($id, $label);
	m_add_custom_post_state($id, $label);
}


/**
 * Get the ID of the custom static page
 * 
 * @param string $page		page to retrive the id
 * @return int|null
 * @example m_get_static_page_id('faq_page');
 */

function m_get_static_page_id($page=''){
	if(!$page){
		return null;
	}
	$id = get_option($page);
	if(is_plugin_active( 'polylang/polylang.php' )){
		$id = pll_get_post( $id );
	}
	return $id;
}


/**
 * Get the custon static page object
 * 
 * @param string $page		page to retrive
 * @return WP_Post|array|null
 * @example m_get_static_page('faq_page');
 */

function m_get_static_page($page=''){
	if(!$page){
		return null;
	}
	$output = get_post(m_get_static_page_id($page));
	return $output;
}


/**
 * Check if the current page is the custom static page
 * 
 * @param string $page		page to check
 * @return bool
 * @example m_is_static_page('faq_page');
 */

function m_is_static_page($page=''){
	if(!$page){
		return null;
	}
	global $post;
	$is_page = false;
	$page_id = get_option($page);
	if(!is_plugin_active( 'polylang/polylang.php' )){
		if( 'page' == get_post_type($post->ID) && $post->ID == $page_id && $page_id != '0') {
			$is_page = true;
		}
	}else{
		$translations = pll_get_post_translations( $page_id );
		foreach($translations as $t){
			if( 'page' == get_post_type($post->ID) && $post->ID == $t && $t != '0') {
				$is_page = true;
				break;
			}
		}
	}
  return $is_page;
}


/**
 * Add the admin settings to selecet the custom static page
 * 
 * @param string $page_id		ID to assign to page
 * @param string $title			Title for the settings section
 */

function m_add_default_page_admin_settings($page_id, $title){
	add_action('admin_init', function()use($page_id, $title){
		register_setting( 
			'reading',
			$page_id,
			array(
				'type' => 'string', 
				'sanitize_callback' => 'sanitize_text_field',
				'default' => NULL,
			) 
	  	);

		add_settings_field(
			$page_id,
			__($title, 'mosne'),
			function()use($page_id){
				m_add_default_page_admin_selector($page_id);
			},
			'reading',
			'default',
			array( 'label_for' => $page_id )
	  	);
	});

}


/**
 * Generate the page selector
 * 
 * @param string $page_id		ID to assign to page
 * @return array $states
 */

function m_add_default_page_admin_selector($page_id){
	$state_page_id = get_option($page_id);
	$args = array(
		 'posts_per_page'   => -1,
		 'orderby'          => 'name',
		 'order'            => 'ASC',
		 'post_type'        => 'page',
	);
	$items = get_posts( $args );

	echo '<select id="'.$page_id.'" name="'.$page_id.'">';
	echo '<option value="0">'.__('— Select —', 'wordpress').'</option>';

	foreach($items as $item) {

		 $selected = ($state_page_id == $item->ID) ? 'selected="selected"' : '';
		 echo '<option value="'.$item->ID.'" '.$selected.'>'.$item->post_title.'</option>';
	}
	echo '</select>';
}


/**
 * Add post state to custom static page
 * 
 * @param string $page_id		ID to assign to page
 * @param string $state_label	Label for the static page
 */

function m_add_custom_post_state($page_id, $state_label){
	add_filter('display_post_states', function($states)use($page_id, $state_label){
		global $post;
		$state_page_id = get_option($page_id);
		if(!is_plugin_active( 'polylang/polylang.php' )){
			if( 'page' == get_post_type($post->ID) && $post->ID == $state_page_id && $state_page_id != '0') {
				$states[] = __($state_label, 'mosne');
			}
		}else{
			$langs = pll_languages_list();
			foreach($langs as $lang){
				$translation_id = pll_get_post( $state_page_id, $lang );
				if( 'page' == get_post_type($post->ID) && $post->ID == $translation_id && $state_page_id != '0') {
					$states[] = __($state_label, 'mosne');
			}
			}
		}
		
	  return $states;
	});
}
?>