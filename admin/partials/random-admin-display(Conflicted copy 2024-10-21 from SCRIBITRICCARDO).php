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

include_once plugin_dir_path(__FILE__) . '../../includes/random-utils.php';

/**
 * Handle main admin page
 *
 * @since    1.0.0
 */
function random_admin_page_handler() {

  if (isset($_GET['subpage']) && ($_GET['subpage'] == 'about')) {
      $current_page = esc_attr( wp_unslash( $_GET['subpage']) );
  } else {
      $current_page = 'shortcodes';
  } ?>
	<div class="wrap random random-<?php echo esc_attr($current_page) ?>">
		<span class="clearfix random-title">
			<span class="random-logo"><img src="<?php echo esc_url(plugins_url('../images/logo.png', __FILE__)) ?>"></span>
			<h1><?php echo esc_html__('Random', 'random') ?></h1>
		</span>

		<h2 class="nav-tab-wrapper">
			<a href="tools.php?page=<?php echo esc_attr(RANDOM_PLUGIN_SLUG) ?>" class="nav-tab <?php echo ($current_page == 'shortcodes') ? 'nav-tab-active' : '' ?>">
				<span class="dashicons dashicons-shortcode" aria-hidden="true"></span><?php echo esc_html__('Shortcodes', 'random') ?>
			</a>
			<a style="color:#88C" href="tools.php?page=<?php echo esc_attr(RANDOM_PLUGIN_SLUG) ?>&subpage=about" class="nav-tab <?php echo ($current_page == 'about') ? 'nav-tab-active' : '' ?>">
				<span class="dashicons dashicons-info" aria-hidden="true"></span><?php echo esc_html__('About', 'random') ?>
			</a>
		</h2>
		<div class="random-tab-content"><?php

        switch ($current_page) {
            case 'shortcodes':
                random_admin_page_shortcodes_handler();
                break;

            case 'about':
                random_admin_page_about_handler();
                break;
        } ?>

		</div>
	</div><?php
}

##############################

/**
 * Handle shortcodes build admin page
 *
 * @since    1.0.0
 */
function random_admin_page_shortcodes_handler() {

	$args = array(
		'public'   => true
	);
	$types = get_post_types($args);

   // Default form values
	$default_post_number = 1;
	$default_post_type = 'post';
	$default_content_type = 'title';
	$default_random_shortcode_included_ids = '';
	$default_random_shortcode_excluded_ids = '';

   $content_types = array(
      'url' => esc_html__('Url', 'random'),
      'title' => esc_html__('Title', 'random'),
      'title_content' => esc_html__('Title + Content', 'random'),
      'title_excerpt' => esc_html__('Title + Excerpt', 'random'),
      'content' => esc_html__('Content', 'random'),
      'excerpt' => esc_html__('Excerpt', 'random')
   );

   // Validating and sanitizing
   if (isset($_POST['random_shortcode_post_types'])) {
    	$post_types = array_intersect($types, array_keys( wp_unslash( $_POST['random_shortcode_post_types'] ) ) );
	}
	else{
		$post_types = array();
		$post_types[$default_post_type] = 'on';
	}

	if (isset($_POST['random_shortcode_posts_number']) && is_numeric($_POST['random_shortcode_posts_number']) && ($_POST['random_shortcode_posts_number'] >= 1)) {
		$posts_number = intval($_POST['random_shortcode_posts_number']);
	} else {
		$posts_number = $default_post_number;
	}

	if (isset($_POST['random_shortcode_content_type']) && in_array(esc_url_raw( wp_unslash( $_POST['random_shortcode_content_type'] ) ), array_keys($content_types))) {
    	$post_content_type = esc_url_raw( wp_unslash( $_POST['random_shortcode_content_type'] ) );
	} else {
    	$post_content_type = $default_content_type;
	}

	$random_shortcode_ids = (!isset($_POST['random_shortcode_ids']) || ($_POST['random_shortcode_ids'] == 'include')) ? 'include' : 'exclude';

	if (isset($_POST['random_shortcode_included_ids'])) {
		// Does not sanitize the IDs integer list. Let the original user data into input field. The field will be sanitized in the shortcode output.
		$random_shortcode_included_ids = esc_url_raw( wp_unslash( $_POST['random_shortcode_included_ids'] ) );
	} else {
		$random_shortcode_included_ids = $default_random_shortcode_included_ids;
	}

	if (isset($_POST['random_shortcode_excluded_ids'])) {
		// Does not sanitize the IDs integer list. Let the original user data into input field. The field will be sanitized in the shortcode output.
		$random_shortcode_excluded_ids = esc_url_raw( wp_unslash( $_POST['random_shortcode_excluded_ids'] ) );
	} else {
		$random_shortcode_excluded_ids = $default_random_shortcode_excluded_ids;
	}
	?>

	<div class="scribit_page_description">
		<?php echo esc_html__('Build a shortcode to get random contents and use it wherever you want on your WordPress website.', 'random') ?>
	</div>

	<form method="POST">
		<input type="hidden" name="page" value="<?php echo esc_attr(RANDOM_PLUGIN_SLUG) ?>" />
		<input type="hidden" name="subpage" value="test_shortcode" />

		<table class="form-table">
			<tbody>
				<tr>
					<th scope="row">
						<label><?php echo esc_html__('Post types', 'random') ?></label>
						<p class="scribit_option_description"><?php echo esc_html__('Post types to get random content from.', 'random') ?></p>
					</th>
					<td>
						<?php if (is_array($types))
							foreach ($types as $type) : ?>
								<label for="random_shortcode_post_type_<?php echo esc_attr($type) ?>">
									<input name="random_shortcode_post_types[<?php echo esc_attr($type) ?>]" id="random_shortcode_post_type_<?php echo esc_attr($type) ?>" type="checkbox"
									<?php echo (isset($post_types[$type]) ? 'checked' : '') ?>>
									<?php echo esc_attr(strtoupper($type)) ?>
								</label><br/>
							<?php endforeach ?>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="random_shortcode_posts_number"><?php echo esc_html__('Posts number', 'random') ?></label>
						<p class="scribit_option_description"><?php echo esc_html__('Number of posts to show.', 'random') ?></p>
					</th>
					<td>
						<input type="number" id="random_shortcode_posts_number" name="random_shortcode_posts_number" value="<?php echo esc_attr($posts_number) ?>" min="1" style="width: 80px">
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="random_shortcode_content_type"><?php echo esc_html__('Content type', 'random') ?></label>
						<p class="scribit_option_description"><?php echo esc_html__('Post contents to show.', 'random') ?></p>
					</th>
					<td>
						<select name="random_shortcode_content_type" id="random_shortcode_content_type">
                     <?php foreach ($content_types as $content_type_slug => $content_type_desc): ?>
                           <option value="<?php echo esc_attr($content_type_slug) ?>" <?php echo ($post_content_type == $content_type_slug) ? 'selected="selected"' : '' ?>><?php echo esc_attr($content_type_desc) ?></option>
                     <?php endforeach ?>
						</select>
					</td>
				</tr>

				<tr>
					<th scope="row">
						<label for="random_shortcode_included_ids"><?php echo esc_html__('Post IDs', 'random') ?></label>
					</th>
					<td>
						<table class="scribit_fullwidth">
							<tr>
								<th scope="row">
									<input type="radio" id="random_shortcode_ids_include" name="random_shortcode_ids" value="include" <?php echo ($random_shortcode_ids == 'include') ? 'checked' : ''; ?> >
									<label for="random_shortcode_ids_include"><?php echo esc_html__('Include', 'random') ?></label>
									<p class="scribit_option_description"><?php echo esc_html__('Include only some posts in results indicating their IDs separated by commas.', 'random') ?></p>
								</th>
								<td>
									<input type="text" id="random_shortcode_included_ids" name="random_shortcode_included_ids" value="<?php echo esc_attr($random_shortcode_included_ids) ?>" style="min-width:50%" <?php echo ($random_shortcode_ids != 'include') ? 'class="disabled" readonly' : ''; ?>>
								</td>
							</tr>

							<tr>
								<th scope="row">
									<input type="radio" id="random_shortcode_ids_exclude" name="random_shortcode_ids" value="exclude" <?php echo ($random_shortcode_ids == 'exclude') ? 'checked' : ''; ?>>
									<label for="random_shortcode_ids_exclude"><?php echo esc_html__('Exclude', 'random') ?></label>
									<p class="scribit_option_description"><?php echo esc_html__('Remove some posts from results indicating their IDs separated by commas.', 'random') ?></p>
								</th>
								<td>
									<input type="text" id="random_shortcode_excluded_ids" name="random_shortcode_excluded_ids" value="<?php echo esc_attr($random_shortcode_excluded_ids) ?>" style="min-width:50%" <?php echo ($random_shortcode_ids != 'exclude') ? 'class="disabled" readonly' : ''; ?>>
								</td>
							</tr>
						</table>
					</td>
				</tr>

				<tr>
					<th scope="row"></th>
					<td><input type="submit" class="button button-primary" value="<?php echo esc_html__('Get the shortcode', 'random') ?>" /></td>
				</tr>

				<?php if (isset($_POST['random_shortcode_post_types'])) :  // Form submit

					$code = '[random_post';

					if (isset($post_types) && ((count($post_types) > 1) || !isset($post_types[$default_post_type]))) {
						$code .= ' post_types="'. implode(',', array_keys($post_types)) .'"';
					}

					if ($posts_number != $default_post_number) {
						$code .= ' post_number='. $posts_number;
					}

					if ($post_content_type != $default_content_type) {
						$code .= ' content_type="'. $post_content_type .'"';
					}

					if ( ( $random_shortcode_ids == 'include' ) && ( $random_shortcode_included_ids != $default_random_shortcode_included_ids ) ) {
						// Sanitize IDs integer list
						$random_shortcode_included_ids = array_filter( explode( ",", str_replace( ' ', '', $random_shortcode_included_ids ) ), function($e) {
							return ctype_digit($e);
						});
						
						// Clean single list values
						$random_shortcode_included_ids = array_map( function($e) {
							return ltrim( $e, '0' );
						}, $random_shortcode_included_ids);

						if (count($random_shortcode_included_ids))
							$code .= ' included_ids="'. implode( ',', $random_shortcode_included_ids ) .'"';
					}

					if ( ( $random_shortcode_ids == 'exclude' ) && ( $random_shortcode_excluded_ids != $default_random_shortcode_excluded_ids ) ) {
						// Sanitize IDs integer list
						$random_shortcode_excluded_ids = array_filter( explode( ",", str_replace( ' ', '', $random_shortcode_excluded_ids ) ), function($e) {
							return ctype_digit($e);
						});
						
						// Clean single list values
						$random_shortcode_excluded_ids = array_map( function($e) {
							return ltrim( $e, '0' );
						}, $random_shortcode_excluded_ids);

						if (count($random_shortcode_excluded_ids))
							$code .= ' excluded_ids="'. implode( ',', $random_shortcode_excluded_ids ) .'"';
					}

					$code .= ']';
					?>

					<tr><td colspan="2"><hr/></td></tr>
					<tr>
						<th scope="row">
							<?php echo esc_html__('Shortcode', 'random') ?>
							<p class="scribit_option_description"><?php echo esc_html__('Copy this code and paste wherever you want to show your random contents.', 'random') ?></p>
						</th>

						<td class="scribit-tooltip" onclick="scribit_copyContentToClipboard('random_shortcode_code', '<?php echo esc_html__('Shortcode copied to clipboard', 'random') ?>')">
							<div class="random_shortcode_code" id="random_shortcode_code">
								<?php echo esc_attr($code) ?>
							</div>
							<span class="tooltiptext"><?php echo esc_html__('Copy to clipboard', 'random') ?></span>
						</td>
					</tr>
					<tr>
						<th scope="row"><?php echo esc_html__('Shortcode result example', 'random') ?></th>
						<td class="shortcode_test_result"><?php echo do_shortcode($code); ?></td>
					</tr>
				<?php endif ?>
			</tbody>
		</table>
	</form>

<?php }

##############################

/**
 * Handle about admin page
 *
 * @since    1.0.0
 */
function random_admin_page_about_handler() { ?>

	<table id="random_about_support">
		<tr>
			<td class="scribit_support_description"><?php echo esc_html__('If you like our plugin please feel free to give us 5 stars :)', 'random') ?></td>
			<td><a target="_blank" class="button button-primary scribit_support_button" rel="nofollow" href="https://wordpress.org/support/plugin/random/reviews/">
				<span style="color:#CFC" class="dashicons dashicons-star-filled" aria-hidden="true"></span><?php echo esc_html__('WRITE A PLUGIN REVIEW', 'random') ?>
			</a></td>
		</tr>

		<tr>
			<td class="scribit_support_description"><?php echo esc_html__('If you want to help us to improve our service please Donate a coffe', 'random') ?></td>
			<td><a target="_blank" class="button button-primary scribit_support_button" rel="nofollow" href="https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=riccardosormani@gmail.com&item_name=Random Wordpress plugin donation&no_note=0">
				<span style="color:#FC9" class="dashicons dashicons-coffee" aria-hidden="true"></span><?php echo esc_html__('DONATE WITH PAYPAL', 'random') ?>
			</a></td>
		</tr>

		<tr>
			<td class="scribit_support_description"><?php echo esc_html__('If you want some information about our Company', 'random') ?></td>
			<td><a target="_blank" class="button button-primary scribit_support_button" href="mailto:wordpress@scribit.it">
				<span style="color:#DDD" class="dashicons dashicons-email" aria-hidden="true"></span><?php echo esc_html__('CONTACT US', 'random') ?>
			</a></td>
		</tr>
	</table>

	<br/><hr/>

	<div class="scribit_plugins">
		<h4><?php echo esc_html__('Try other Scribit plugins:', 'random') ?></h4>
		<div class="wp-list-table widefat plugin-install">
			<div class="scribit_plugins">
				<?php $plugin_slug = 'shortcodes-finder'; ?>
				<div class="plugin-card plugin-card-<?php echo esc_attr($plugin_slug) ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3><a href="
								<?php if ( is_multisite() ) : ?>
									<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin='. $plugin_slug ) ) ?>
								<?php else : ?>
									<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin='. $plugin_slug ) ) ?>
								<?php endif ?>
							">Shortcodes Finder<img src="<?php echo esc_url(plugins_url('../images/shortcodes-finder_256.png', __FILE__)) ?>" class="plugin-icon"></a></h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<?php if ( class_exists('Shortcodes_Finder_Admin') ) : ?>
									<li><button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__( 'Active', 'random') ?></button></li>
								<?php else: ?>
									<li><a href="
										<?php if ( is_multisite() ) : ?>
											<?php echo esc_url( network_admin_url( 'plugin-install.php?s='. $plugin_slug .'+scribit&tab=search&type=term' ) ) ?>
										<?php else : ?>
											<?php echo esc_url( admin_url( 'plugin-install.php?s='. $plugin_slug .'+scribit&tab=search&type=term' ) ) ?>
										<?php endif ?>
									" class="button button-primary"><?php echo esc_html__('Install') ?></a></li>
								<?php endif; ?>
								<li><a href="
									<?php if ( is_multisite() ) : ?>
										<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin='. $plugin_slug ) ) ?>
									<?php else : ?>
										<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin='. $plugin_slug ) ) ?>
									<?php endif ?>
								" class="thickbox open-plugin-details-modal"><?php echo esc_html__('More Details') ?></a></li>
							</ul>
						</div>
						<div class="desc column-description">
							<ul>
								<li><?php echo esc_html__('Find every shortcode (by tag or content type) present in your posts, pages and custom type contents', 'random') ?></li>
								<li><?php echo esc_html__('Search unused shortcodes', 'random') ?></li>
								<li><?php echo esc_html__('Disable active or unused/orphan shortcodes', 'random') ?></li>
								<li><?php echo esc_html__('Test your shortcodes before use them in your website', 'random') ?></li>
							</ul>
						</div>
					</div>
				</div>

				<?php $plugin_slug = 'proofreading'; ?>
				<div class="plugin-card plugin-card-<?php echo esc_attr($plugin_slug) ?>">
					<div class="plugin-card-top">
						<div class="name column-name">
							<h3><a href="
								<?php if ( is_multisite() ) : ?>
									<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&plugin='. $plugin_slug ) ) ?>
								<?php else : ?>
									<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&plugin='. $plugin_slug ) ) ?>
								<?php endif ?>
							">Proofreading<img src="<?php echo esc_url(plugins_url('../images/proofreading_256.png', __FILE__)) ?>" class="plugin-icon"></a></h3>
						</div>
						<div class="action-links">
							<ul class="plugin-action-buttons">
								<?php if ( class_exists('Proofreading_Admin') ) : ?>
									<li><button type="button" class="button button-disabled" disabled="disabled"><?php echo esc_html__( 'Active', 'random') ?></button></li>
								<?php else: ?>
									<li><a href="
										<?php if ( is_multisite() ) : ?>
											<?php echo esc_url( network_admin_url( 'plugin-install.php?s='. $plugin_slug .'+scribit&tab=search&type=term' ) ) ?>
										<?php else : ?>
											<?php echo esc_url( admin_url( 'plugin-install.php?s='. $plugin_slug .'+scribit&tab=search&type=term' ) ) ?>
										<?php endif ?>
									" class="button button-primary"><?php echo esc_html__('Install') ?></a></li>
								<?php endif; ?>
								<li><a href="
									<?php if ( is_multisite() ) : ?>
										<?php echo esc_url( network_admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin='. $plugin_slug ) ) ?>
									<?php else : ?>
										<?php echo esc_url( admin_url( 'plugin-install.php?tab=plugin-information&amp;plugin='. $plugin_slug ) ) ?>
									<?php endif ?>
								" class="thickbox open-plugin-details-modal"><?php echo esc_html__('More Details') ?></a></li>
							</ul>
						</div>
						<div class="desc column-description">
							<p><?php echo wp_kses(__('Proofreading plugin allows you to improve the quality of your posts, pages and all your WordPress website.<br/>It gives you the possibility to check the correction of the texts inserted into posts, pages and drafts in less than a second!', 'random'), true) ?></p>
							<p><?php echo esc_html__('18 languages supported.', 'random') ?></p>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>

<?php }
