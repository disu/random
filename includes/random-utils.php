<?php
/**
 * Provide a admin area view for the plugin
 *
 * This file is used to markup the admin-facing aspects of the plugin.
 *
 * @since      1.0.0
 * @package    random
 * @subpackage random/admin/partials
 * @author     Scribit <wordpress@scribit.it>
 */

/**
 * Find posts ids by post type.
 * Passing true to include_not_published, results will include draft, future, pending and private contents.
 *
 * @since    1.0.0
 */
 
if ( !function_exists("scribit_get_posts_ids") ) {
	function scribit_get_posts_ids($post_type = 'post', $include_not_published = false) {
		
		$post_status = array('publish');
		
		if ($include_not_published)
			$post_status = array_merge($post_status, array('draft', 'pending', 'private', 'future'));
		
		$args = array(
			'posts_per_page' => -1,
			'post_type' => $post_type,
			'post_status' => $post_status,
			'orderby' => 'date',
			'order' => 'DESC',
			'fields' => 'ids'
		);
		$posts = get_posts($args);

		return $posts;
}
}

/**
 * Escape html text for webpage printing.
 *
 * @since    1.0.0
 */
if ( !function_exists("scribit_html_to_text") ) {
	function scribit_html_to_text($html) {
		$html = str_replace('<', '&lt;', $html);
		$html = str_replace('>', '&gt;', $html);
		return $html;
	}
}
