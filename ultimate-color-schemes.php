<?php
/**
 * Plugin Name: Ultimate Color Schemes
 * Description: New and creative admin color schemes.
 * Version: 1.2.0
 * Requires PHP: 5.3
 * Author: Vera
 * License: GPLv2 or later
 * License URI: http://www.gnu.org/licenses/old-licenses/gpl-2.0.html
 * Text Domain: ultimate-color-schemes
 *
 * This program is free software; you can redistribute it and/or modify it under the terms of the GNU
 * General Public License version 2, as published by the Free Software Foundation. You may NOT assume
 * that you can use any other version of the GPL.
 *
 * This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without
 * even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

namespace UCS_Color_Schemes;

const VERSION = '1.2.0';

/**
 * Colors main array.
 *
 * @param string $color The folder name for this color scheme.
 */
function ucs_get_colors( string $color ) {
	$colors = [
		'send'           => [ '#EAC696', '#C8AE7D', '#765827', '#65451F' ],
		'cartoon'        => [ '#FF70AB', '#FFDB5C', '#A1D874' ],
		'oceania'        => [ '#015051', '#04837B', '#01A7A3' ],
		'forest'         => [ '#12372A', '#436850', '#ADBC9F' ],
		'captain'        => [ '#415F77', '#FC5050', '#FFD00C', '#D1E9EA' ],
		'navy'           => [ '#1B1A55', '#535C91', '#9290C3' ],
		'waves'          => [ '#DCF2F1', '#7FC7D9', '#365486', '#0F1035' ],
		'phoenix'        => [ '#900C3F', '#C70039', '#F94C10', '#F8DE22' ],
		'violet'         => [ '#5A639C', '#7776B3', '#9B86BD' ],
		'retro'          => [ '#26355D', '#AF47D2', '#FF8F00', '#FFDB00' ],
		'marshmallow'    => [ '#A8D8EA', '#AA96DA', '#FCBAD3', '#FFFFD2' ],
		'fucsia'         => [ '#570530', '#980F5A', '#AD0372' ],
		'sunset-glory'   => [ '#1D2B53', '#7E2553', '#FF004D', '#FAEF5D' ],
		'grey'           => [ '#373A40', '#686D76', '#8A8A8A' ],
		'lime'           => [ '#0A7029', '#FEDE00', '#C8DF52', '#DBE8D8' ],
		'peach'          => [ '#FBAA60', '#FBC490', '#F67B50', '#A82810' ],
		'coral'          => [ '#F54D3D', '#F0A160', '#B97D60', '#F0CCB0' ],
		'cappuccino'     => [ '#4B3832', '#854442', '#BE9B7B', '#fff4E6' ],
		'gold'           => [ '#A67C00', '#BF9B30', '#FFBF00', '#FFCF40' ],
		'splash'         => [ '#05445E', '#189AB4', '#88b8c9' ],
		'fresh-fruits'   => [ '#347928', '#8dc96a', '#FFFBE6', '#FCCD2A' ],
		'blue'           => [ '#133E87', '#47a9ff', '#CBDCEB', '#F3F3E0' ],
		'beach-ball'     => [ '#FF4242', '#37B48B', '#FCDD4E', '#F9E6DC' ],
		'premium'        => [ '#bd9e40', '#4B0636', '#002D41', '#c2b089' ],
		'mist-marine'    => [ '#8e8e8e', '#004d75', '#7fd1ae' ],
		'emerald-coast'  => [ '#05445e', '#00938e', '#3bba8a' ],
		'petal-passion'  => [ '#fd7f20', '#f55863', '#825896' ],
		'vintage-garden' => [ '#7a871e', '#7f6b4d', '#98c0c5' ]
	];

	// Add default colors.
	global $_wp_admin_css_colors;
	$default_colors = [];
	foreach ( $_wp_admin_css_colors as $color_scheme_key => $color_scheme ) {
		$default_colors[ $color_scheme_key ] = $color_scheme->colors;
	}

	if ( $color !== 'all' && $color !== 'all-default' ) {
		$colors = array_merge( $default_colors, $colors );
		if ( isset( $colors[ $color ] ) ) {
			return $colors[ $color ];
		}
	} elseif ( $color === 'all-default' ) {
		return array_merge( $default_colors, $colors );
	} elseif ( $color === 'all' ) {
		return $colors;
	}

	return [];
}

/**
 * Add settings page.
 */
function settings_page() {
	add_options_page( __( 'Settings', 'ultimate-color-schemes' ), __( 'Ultimate color schemes', 'ultimate-color-schemes' ), 'manage_options', 'ultimate_color_schemes', __NAMESPACE__ . '\settings_page_content' );
}

add_action( 'admin_menu', __NAMESPACE__ . '\settings_page' );

/**
 * Settings page content.
 */
function settings_page_content() {
	$user_roles      = get_editable_roles();
	$options         = get_option( 'ultimate_color_schemes' );
	$default_options = false;
	$colors          = ucs_get_colors( 'all-default' );
	if ( empty( $options ) ) {
		$options         = [
			'administrator' => 'fresh',
		];
		$default_options = true;
	}

	if ( ! current_user_can( 'manage_options' ) ) {
		return;
	}
	?>
	<div class="ultimate-color-schemes">
		<div class="wrap">
			<h2><?php esc_html_e( 'Ultimate Color Schemes', 'ultimate-color-schemes' ); ?></h2>
			<div class="ultimate-color-schemes__wrap ultimate-color-schemes__wrap--title">
				<p class="ultimate-color-schemes__title"><?php esc_html_e( 'Select user roles and schemes', 'ultimate-color-schemes' ); ?></p>
				<button
					class="button button-primary ultimate-color-schemes__save"><?php esc_html_e( 'Save settings', 'ultimate-color-schemes' ); ?></button>
				<span
					class="ultimate-color-schemes__info"><?php esc_html_e( 'All custom color schemes settings for selected user roles will be unavailable', 'ultimate-color-schemes' ); ?></span>
			</div>
			<div class="ultimate-color-schemes__success">
				<p><?php esc_html_e( 'Settings saved', 'ultimate-color-schemes' ); ?></p>
			</div>
			<form class="ultimate-color-schemes__form" action="">
				<input type="hidden" name="ucs_nonce"
				       value="<?php echo esc_html( wp_create_nonce( 'update_ucs' ) ); ?>"/>
				<?php foreach ( $options as $option_key => $option_value ) : ?>
					<div
						class="ultimate-color-schemes__wrap ultimate-color-schemes__wrap--select <?php if ( $default_options ) {
							echo ' hidden';
						} ?>">
						<div class="ultimate-color-schemes__remove">
							<span class="dashicons dashicons-trash"></span>
						</div>
						<div class="ultimate-color-schemes__role">
							<label><?php esc_html_e( 'User role', 'ultimate-color-schemes' ); ?></label>
							<select>
								<?php foreach ( $user_roles as $role => $data ) : ?>
									<option
										value="<?php echo esc_attr( $role ); ?>"<?php if ( $option_key === $role ) {
										echo ' selected="selected"';
									} ?>><?php echo esc_html( $data['name'] ); ?></option>
								<?php endforeach; ?>
							</select>
						</div>
						<div class="ultimate-color-schemes__color">
							<label><?php esc_html_e( 'Color scheme', 'ultimate-color-schemes' ); ?></label>
							<div class="ultimate-color-schemes__current">
								<?php
								$current_colors = ucs_get_colors( $option_value );
								$current_title  = ucwords( str_replace( '-', ' ', $option_value ) );
								?>
								<p><?php echo esc_html( $current_title ); ?></p>
								<div class="ultimate-color-schemes__current--palette">
									<?php foreach ( $current_colors as $color_item ) : ?>
										<div style="background-color: <?php echo esc_attr( $color_item ); ?>">
											&nbsp;
										</div>
									<?php endforeach; ?>
								</div>
								<div class="ultimate-color-schemes__current--edit">
									<span class="dashicons dashicons-edit"></span>
								</div>
							</div>
							<div class="ultimate-color-schemes__schemes">
								<?php foreach ( $colors as $color_scheme => $color ) :
									$color_scheme_title = ucwords( str_replace( '-', ' ', $color_scheme ) );
									?>
									<div
										class="ultimate-color-schemes__scheme <?php if ( $option_value === $color_scheme ) {
											echo ' active';
										} ?>">
										<div class="ultimate-color-schemes__scheme--select">
											<input type="radio"
											       value="<?php echo esc_attr( $color_scheme ); ?>"<?php if ( $option_value === $color_scheme ) {
												echo ' checked="checked"';
											} ?>>
											<p><?php echo esc_attr( $color_scheme_title ); ?></p>
										</div>
										<div class="ultimate-color-schemes__scheme--palette">
											<?php foreach ( $color as $color_item ) : ?>
												<div style="background-color: <?php echo esc_attr( $color_item ); ?>">
													&nbsp;
												</div>
											<?php endforeach; ?>
										</div>
									</div>
								<?php endforeach; ?>
							</div>
						</div>
					</div>
				<?php endforeach; ?>
				<div class="ultimate-color-schemes__plus">+</div>
			</form>
		</div>
	</div>
	<?php
}

/**
 * Add settings link.
 *
 * @param array $links The array of links.
 */
function settings_link( array $links ) {
	$url           = get_admin_url() . "options-general.php?page=ultimate_color_schemes";
	$settings_link = '<a href="' . $url . '">' . __( 'Settings', 'ultimate-color-schemes' ) . '</a>';
	array_unshift( $links, $settings_link );

	return $links;
}

add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), __NAMESPACE__ . '\settings_link' );

/**
 * Save color schemes.
 */
function save_settings() {
	if ( ! isset( $_POST['nonce'] ) || ! wp_verify_nonce( sanitize_text_field( wp_unslash( $_POST['nonce'] ) ), 'update_ucs' ) ) {
		wp_send_json_error( 'Invalid nonce' );
	}

	$data = [];
	if ( isset( $_POST['data'] ) ) {
		$data = array_map( 'sanitize_text_field', wp_unslash( $_POST['data'] ) );
	}
	update_option( 'ultimate_color_schemes', $data );
	wp_send_json_success();
}

add_action( 'wp_ajax_ultimate_color_schemes_save', __NAMESPACE__ . '\save_settings' );
add_action( 'wp_ajax_nopriv_ultimate_color_schemes_save', __NAMESPACE__ . '\save_settings' );

/**
 * Set the default color scheme for user role.
 *
 * @param string $color The current color scheme.
 *
 * @return string The new color scheme.
 */
function set_global_user_role_color( string $color ) {
	$current_user = wp_get_current_user();
	$options      = get_option( 'ultimate_color_schemes' );

	if ( empty( $options ) ) {
		return $color;
	}

	foreach ( $options as $role => $scheme ) {
		if ( in_array( $role, $current_user->roles, true ) ) {
			remove_action( 'admin_color_scheme_picker', 'admin_color_scheme_picker' );

			return $scheme;
		}
	}

	return $color;
}

add_filter( 'get_user_option_admin_color', __NAMESPACE__ . '\set_global_user_role_color' );

/**
 * Helper function to get stylesheet URL.
 *
 * @param string $color The folder name for this color scheme.
 */
function ucs_get_color_url( string $color ) {
	$suffix    = is_rtl() ? '-rtl' : '';
	$file_path = plugin_dir_path( __FILE__ ) . "dist/schemes$suffix/$color/admin.css";
	if ( file_exists( $file_path ) ) {
		return plugins_url( "/dist/schemes$suffix/$color/admin.css", __FILE__ );
	}

	return '';
}

/**
 * Register color schemes.
 */
function add_color_schemes() {
	$color_schemes = ucs_get_colors( 'all' );
	foreach ( $color_schemes as $color_scheme => $colors ) {
		$title = ucwords( str_replace( '-', ' ', $color_scheme ) );
		wp_admin_css_color(
			$color_scheme,
			$title,
			ucs_get_color_url( $color_scheme ),
			$colors,
			array(
				'base'    => '#f1f2f3',
				'focus'   => '#fff',
				'current' => '#fff',
			)
		);
	}
}

add_action( 'admin_init', __NAMESPACE__ . '\add_color_schemes' );

/**
 * Add frontend styles.
 */
function add_front_themes() {
	$suffix    = is_rtl() ? '-rtl' : '';
	$color     = get_user_option( 'admin_color' );
	$file_path = plugin_dir_path( __FILE__ ) . "dist/schemes$suffix/$color/frontend.css";
	if ( file_exists( $file_path ) ) {
		wp_enqueue_style(
			'ucs-themes',
			plugins_url( "dist/schemes$suffix/$color/frontend.css", __FILE__ ),
			array(),
			VERSION
		);
	}
}

add_action( 'wp_enqueue_scripts', __NAMESPACE__ . '\add_front_themes' );

/**
 * Add admin styles.
 */
function add_admin_styles() {
	wp_enqueue_style(
		'ucs-admin',
		plugins_url( "dist/admin/admin.css", __FILE__ ),
		array(),
		VERSION
	);

	wp_enqueue_script(
		'ucs-admin',
		plugins_url( 'dist/admin/admin.js', __FILE__ ),
		array( 'jquery' ),
		VERSION,
		true
	);

	if ( isset( $_GET['page'] ) && $_GET['page'] === 'ultimate_color_schemes' ) {
		wp_enqueue_style(
			'ucs-settings',
			plugins_url( "dist/admin/settings-page/styles.css", __FILE__ ),
			array(),
			VERSION
		);

		wp_enqueue_script(
			'ucs-settings',
			plugins_url( 'dist/admin/settings-page/scripts.js', __FILE__ ),
			array( 'jquery' ),
			VERSION,
			true
		);

		wp_localize_script( 'ucs-settings', 'ucs_ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	}
}

add_action( 'admin_enqueue_scripts', __NAMESPACE__ . '\add_admin_styles' );

/**
 * Add our theme custom properties to the editor.
 */
function add_editor_themes() {
	wp_enqueue_style(
		'ucs-editor-themes',
		plugins_url( 'dist/editor.css', __FILE__ ),
		array(),
		VERSION
	);
}

add_action( 'enqueue_block_editor_assets', __NAMESPACE__ . '\add_editor_themes' );