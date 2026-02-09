<?php
/**
 * Plugin uninstallation actions.
 *
 * @since   2.0.0
 * @package Custom_Codes
 */

defined( 'ABSPATH' ) || die( 'No script kiddies please!' );


/**
 * Folder deleter function.
 *
 * @param string $dir Directory path to delete.
 */
function codes_delete_directory( $dir ) {
	global $wp_filesystem;

	if ( empty( $wp_filesystem ) ) {
		include_once ABSPATH . '/wp-admin/includes/file.php';
		WP_Filesystem();
	}

	if ( ! file_exists( $dir ) ) {
		return true;
	}

	if ( ! is_dir( $dir ) ) {
		return wp_delete_file( $dir );
	}

	foreach ( scandir( $dir ) as $item ) {
		if ( '.' === $item || '..' === $item ) {
			continue;
		}

		if ( ! codes_delete_directory( $dir . DIRECTORY_SEPARATOR . $item ) ) {
			return false;
		}
	}

	return $wp_filesystem->rmdir( $dir );
}


/**
 * Cleanup the posts and settings.
 */
function codes_fs_uninstall_cleanup() {

	// Delete all the codes if requested.
	if ( ! get_option( '_codes_store' ) ) {

		// DELETE THE MEDIA QUERIES.
		delete_option( '_codes_output_order' );
		delete_option( '_codes_desktop' );
		delete_option( '_codes_tablet_l' );
		delete_option( '_codes_tablet_p' );
		delete_option( '_codes_phone_l' );
		delete_option( '_codes_phone_p' );
		delete_option( '_codes_retina' );
		delete_option( '_codes_store' );

		// DELETE POSTS.
		$codes_posts = get_posts(
			array(
				'numberposts' => -1,
				'post_type'   => 'custom-code',
				'post_status' => 'any',
			)
		);

		foreach ( $codes_posts as $codes_post ) {
			wp_delete_post( $codes_post->ID, true );
		}

		// DELETE THE CODES DIRECTORY.
		codes_delete_directory( CODES_FOLDER_DIR );

	}

	// DELETE USER META.
	delete_metadata( 'user', 0, '_codes_theme', '', true );
	delete_metadata( 'user', 0, '_codes_fontsize', '', true );
	delete_metadata( 'user', 0, '_codes_indent', '', true );

	// DELETE GLOBAL OPTIONS.
	delete_option( '_codes_ajax' );
	delete_option( '_codes_sound' );
	delete_option( '_codes_shortcut' );
	delete_option( '_codes_emmet' );
	delete_option( '_codes_version' );
	delete_option( '_codes_admin_bar' );
	delete_option( '_codes_initial_editor' );

	// DELETE AI OPTIONS.
	delete_option( '_codes_ai_provider' );
	delete_option( '_codes_openai_key' );
	delete_option( '_codes_google_key' );
	delete_option( '_codes_google_models' );
	delete_option( '_codes_openai_models' );
	delete_option( 'codes_ai_notice_dismissed' );
}
codes_fs()->add_action( 'after_uninstall', 'codes_fs_uninstall_cleanup' );
