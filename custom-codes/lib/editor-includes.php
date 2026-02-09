<?php

/**
 * Files and data to include editor page view.
 *
 * @since   2.0.0
 * @package Custom_Codes
 */
defined( 'ABSPATH' ) || die( 'No script kiddies please!' );
/**
 * Register scripts and styles.
 */
function codes_load_sources() {
    global $post;
    // Early exit if on another post type.
    if ( !$post || 'custom-code' !== $post->post_type ) {
        return;
    }
    wp_register_script(
        'codes_scripts',
        CODES_PLUGIN_URL . 'assets/script/script.min.js',
        array('jquery'),
        CODES_VERSION,
        true
    );
    // Register the main style.
    wp_register_style(
        'codes_styles',
        CODES_PLUGIN_URL . 'assets/style/style.css',
        array(),
        CODES_VERSION
    );
}

add_action( 'admin_enqueue_scripts', 'codes_load_sources' );
/**
 * Bring data to frontend.
 */
function codes_bring_data() {
    global $post, $codes_lang_groups, $codes_langs;
    // Early exit if on another post type.
    if ( !$post || 'custom-code' !== $post->post_type ) {
        return;
    }
    // Backend data to frontend.
    $data = array(
        'ajaxUrl'            => admin_url( 'post.php?custom-codes-saving' ),
        'postName'           => $post->post_name,
        'postID'             => $post->ID,
        'langGroups'         => $codes_lang_groups,
        'langs'              => $codes_langs,
        'isPremium'          => codes_fs()->is_premium(),
        'isPremiumOnly'      => codes_fs()->is__premium_only(),
        'postLanguage'       => get_post_meta( $post->ID, '_codes_language', true ),
        'postLocation'       => ( get_post_meta( $post->ID, '_codes_location', true ) === '' ? 'frontend' : get_post_meta( $post->ID, '_codes_location', true ) ),
        'postUseBreakpoints' => ( get_post_meta( $post->ID, '_codes_show_breakpoints', true ) === '' ? true : get_post_meta( $post->ID, '_codes_show_breakpoints', true ) ),
        'postRoles'          => ( get_post_meta( $post->ID, '_codes_adminroles', true ) === '' ? array() : get_post_meta( $post->ID, '_codes_adminroles', true ) ),
        'saveCount'          => ( get_post_meta( $post->ID, '_codes_savecount', true ) === '' ? 0 : get_post_meta( $post->ID, '_codes_savecount', true ) ),
        'userTheme'          => ( get_user_meta( get_current_user_id(), '_codes_theme', true ) === '' ? 'dark' : get_user_meta( get_current_user_id(), '_codes_theme', true ) ),
        'userFontSize'       => ( get_user_meta( get_current_user_id(), '_codes_fontsize', true ) === '' ? 14 : get_user_meta( get_current_user_id(), '_codes_fontsize', true ) ),
        'userIndent'         => ( get_user_meta( get_current_user_id(), '_codes_indent', true ) === '' ? 'space-4' : get_user_meta( get_current_user_id(), '_codes_indent', true ) ),
        'custom_codes_uri'   => CODES_FOLDER_URL,
        'lastEditedText'     => codes_last_edited_text( $post ),
        'ajaxSave'           => get_option( '_codes_ajax' ),
        'playSound'          => get_option( '_codes_sound' ),
        'commandS'           => get_option( '_codes_shortcut' ),
        'useEmmet'           => get_option( '_codes_emmet' ),
        'initialStyleTab'    => get_option( '_codes_initial_editor' ),
        'query-desktop'      => get_option( '_codes_desktop' ),
        'query-tablet-l'     => get_option( '_codes_tablet_l' ),
        'query-tablet-p'     => get_option( '_codes_tablet_p' ),
        'query-mobile-l'     => get_option( '_codes_phone_l' ),
        'query-mobile-p'     => get_option( '_codes_phone_p' ),
        'query-retina'       => get_option( '_codes_retina' ),
    );
    wp_localize_script( 'codes_scripts', 'customCodesData', $data );
}

add_action( 'admin_enqueue_scripts', 'codes_bring_data' );
/**
 * Audio element.
 */
function codes_audio_element() {
    global $post;
    // Early exit if on another post type.
    if ( !$post || 'custom-code' !== $post->post_type ) {
        return;
    }
    ?>
	<script>
		var codes_audioElement = document.createElement('audio' );
		codes_audioElement.setAttribute('src', '<?php 
    echo esc_url( CODES_PLUGIN_URL ) . 'assets/sound/glass.mp3';
    ?>' );
	</script>
	<?php 
}

add_action( 'admin_head', 'codes_audio_element' );