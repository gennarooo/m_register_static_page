<?php

m_register_static_page('faq_page', 'FAQs Page');

/**
 * @param string $id		ID to assign to page
 * @param string $label	Label for the static page
 */

function m_register_static_page($id, $label){
	m_add_default_page_admin_settings($id, $label);
	m_add_custom_post_state($id, $label);
}


/**
 * @param string $page		page to check
 * @return bool
 */

function m_is_static_page($page=''){
	if(!$page){
		return null;
	}
	global $post;
	$is_page = false;
	$page_id = get_option($page);
	if( 'page' == get_post_type($post->ID) && $post->ID == $page_id && $page_id != '0') {
		$is_page = true;
  }
  return $is_page;
}


/**
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
 * @param string $page_id		ID to assign to page
 * @param string $state_label	Label for the static page
 */

function m_add_custom_post_state($page_id, $state_label){
	add_filter('display_post_states', function($states)use($page_id, $state_label){
		global $post;
		$state_page_id = get_option($page_id);
		if( 'page' == get_post_type($post->ID) && $post->ID == $state_page_id && $state_page_id != '0') {
			$states[] = __($state_label, 'mosne');
	  }
	  return $states;
	});
}
