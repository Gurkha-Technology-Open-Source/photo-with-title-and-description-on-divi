<?php
/**
 * Plugin Name: Photo with Title and Description
 * Plugin URI: https://www.gurgurkhatech.com
 * Description: A custom Divi module to display photos or videos with titles and descriptions in a slider.
 * Version: 1.0.0
 * Requires at least: 5.6
 * Requires PHP: 7.4
 * Tested up to: 6.5
 * Author: Gurkha Technology
 * Author URI: https://www.gurgurkhatech.com
 * License: GPL2
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain: ptd-divi-module
 */

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly.
}

/**
 * Show an admin notice if Divi (theme or builder) is not active.
 */
function ptd_divi_missing_notice() {
    echo '<div class="notice notice-error"><p>' . esc_html__( 'Photo with Title and Description requires the Divi Theme or the Divi Builder plugin to be active.', 'ptd-divi-module' ) . '</p></div>';
}

/**
 * Determine if Divi (theme or builder plugin) is active.
 */
function ptd_is_divi_active() {
    // Check theme (supports child themes of Divi as well).
    $theme = wp_get_theme();
    if ( $theme && (
        'Divi' === $theme->get( 'Name' ) || 'Divi' === $theme->get_template() ||
        'Extra' === $theme->get( 'Name' ) || 'Extra' === $theme->get_template()
    ) ) {
        return true;
    }

    // Check Divi Builder plugin.
    if ( ! function_exists( 'is_plugin_active' ) ) {
        include_once ABSPATH . 'wp-admin/includes/plugin.php';
    }
    if ( function_exists( 'is_plugin_active' ) ) {
        if ( is_plugin_active( 'divi-builder/divi-builder.php' ) || is_plugin_active( 'et-builder/et-builder.php' ) ) {
            return true;
        }
    }

    // Fallback: if the builder classes are present.
    if ( class_exists( 'ET_Builder_Extension' ) || did_action( 'divi_extensions_init' ) ) {
        return true;
    }

    return false;
}

add_action( 'admin_notices', function () {
    if ( current_user_can( 'activate_plugins' ) && ! ptd_is_divi_active() ) {
        ptd_divi_missing_notice();
    }
} );

/**
 * Initialize extension after Divi loads its builder and fires divi_extensions_init.
 * Define the ET_Builder_Extension subclass at this time to avoid fatal errors.
 */
function ptd_initialize_extension() {
    if ( ! class_exists( 'ET_Builder_Extension' ) ) {
        // Divi not available; bail quietly (admin notice is handled elsewhere).
        return;
    }

    if ( ! class_exists( 'PTD_Extension' ) ) {
        class PTD_Extension extends ET_Builder_Extension {
            public function __construct() {
                parent::__construct();
                add_action( 'wp_enqueue_scripts', array( $this, 'enqueue_assets' ) );
            }

            public function enqueue_assets() {
                // Register assets
                wp_register_style( 'ptd-swiper-style', plugins_url( 'lib/swiper-bundle.min.css', __FILE__ ) );
                wp_register_style( 'ptd-style', plugins_url( 'css/style.css', __FILE__ ) );

                wp_register_script( 'ptd-swiper-script', plugins_url( 'lib/swiper-bundle.min.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
                wp_register_script( 'ptd-frontend-script', plugins_url( 'js/frontend.js', __FILE__ ), array( 'jquery', 'ptd-swiper-script' ), '1.0.0', true );

                // Conditionally enqueue assets only on pages with the module shortcode in content.
                $post = get_post();
                $has_shortcode = false;
                if ( is_singular() && is_a( $post, 'WP_Post' ) ) {
                    $has_shortcode = has_shortcode( $post->post_content, 'ptd_achievements_showcase' ) || has_shortcode( $post->post_content, 'et_pb_ptd_achievements_showcase' );
                }
                if ( $has_shortcode ) {
                    wp_enqueue_style( 'ptd-swiper-style' );
                    wp_enqueue_style( 'ptd-style' );
                    wp_enqueue_script( 'ptd-swiper-script' );
                    wp_enqueue_script( 'ptd-frontend-script' );
                }
            }

            /**
             * Return an array of modules included with the extension.
             *
             * @return array
             */
            public function get_modules() {
                return array( 'AchievementsShowcaseModule' );
            }

            /**
             * Return the directory name of the extension's modules.
             *
             * @return string
             */
            public function get_module_dir() {
                return 'includes';
            }
        }
    }

    // Load modules (they extend ET_Builder_Module, safe now) and instantiate the extension.
    require_once __DIR__ . '/includes/AchievementsShowcaseModule.php';
    require_once __DIR__ . '/includes/AchievementsShowcaseItem.php';
    new PTD_Extension();
}
add_action( 'divi_extensions_init', 'ptd_initialize_extension' );

/**
 * Admin: Add a lightweight Help page with usage instructions.
 */
function ptd_add_help_page() {
    add_submenu_page(
        'options-general.php',
        __( 'PTD Divi Module – Help', 'ptd-divi-module' ),
        __( 'PTD Divi Module', 'ptd-divi-module' ),
        'manage_options',
        'ptd-divi-module-help',
        'ptd_render_help_page'
    );
}
add_action( 'admin_menu', 'ptd_add_help_page' );

function ptd_render_help_page() {
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    echo '<div class="wrap">';
    echo '<h1>' . esc_html__( 'Photo with Title and Description – Divi Module', 'ptd-divi-module' ) . '</h1>';
    if ( ! ptd_is_divi_active() ) {
        echo '<div class="notice notice-warning"><p>' . esc_html__( 'Divi Theme or Divi Builder plugin is not detected. Activate Divi to use this module.', 'ptd-divi-module' ) . '</p></div>';
    }
    echo '<h2>' . esc_html__( 'How to add your data', 'ptd-divi-module' ) . '</h2>';
    echo '<ol>';
    echo '<li>' . esc_html__( 'Edit or create a page and enable the Divi Builder.', 'ptd-divi-module' ) . '</li>';
    echo '<li>' . esc_html__( 'Insert the "Achievements Showcase" module.', 'ptd-divi-module' ) . '</li>';
    echo '<li>' . esc_html__( 'Click "Add Achievement" to add items. For each item, set Title, Description, and choose Image or Video.', 'ptd-divi-module' ) . '</li>';
    echo '<li>' . esc_html__( 'In the parent module settings, configure arrows, pagination, and autoplay as needed.', 'ptd-divi-module' ) . '</li>';
    echo '<li>' . esc_html__( 'Save and publish the page.', 'ptd-divi-module' ) . '</li>';
    echo '</ol>';
    echo '</div>';
}

/**
 * Add a direct "How to use" link in the Plugins list row.
 */
function ptd_plugin_action_links( $links ) {
    $url = admin_url( 'options-general.php?page=ptd-divi-module-help' );
    $links[] = '<a href="' . esc_url( $url ) . '">' . esc_html__( 'How to use', 'ptd-divi-module' ) . '</a>';
    return $links;
}
add_filter( 'plugin_action_links_' . plugin_basename( __FILE__ ), 'ptd_plugin_action_links' );

/**
 * Register Custom Post Type: Achievements (centrally managed content).
 */
function ptd_register_cpt() {
    $labels = array(
        'name'               => __( 'Achievements', 'ptd-divi-module' ),
        'singular_name'      => __( 'Achievement', 'ptd-divi-module' ),
        'menu_name'          => __( 'Achievements', 'ptd-divi-module' ),
        'name_admin_bar'     => __( 'Achievement', 'ptd-divi-module' ),
        'add_new'            => __( 'Add New', 'ptd-divi-module' ),
        'add_new_item'       => __( 'Add New Achievement', 'ptd-divi-module' ),
        'new_item'           => __( 'New Achievement', 'ptd-divi-module' ),
        'edit_item'          => __( 'Edit Achievement', 'ptd-divi-module' ),
        'view_item'          => __( 'View Achievement', 'ptd-divi-module' ),
        'all_items'          => __( 'All Achievements', 'ptd-divi-module' ),
        'search_items'       => __( 'Search Achievements', 'ptd-divi-module' ),
        'not_found'          => __( 'No achievements found.', 'ptd-divi-module' ),
        'not_found_in_trash' => __( 'No achievements found in Trash.', 'ptd-divi-module' ),
    );

    $args = array(
        'labels'             => $labels,
        'public'             => true,
        'has_archive'        => false,
        'show_in_rest'       => true,
        'menu_position'      => 25,
        'menu_icon'          => 'dashicons-awards',
        'supports'           => array( 'title', 'editor', 'thumbnail' ),
        'rewrite'            => false,
        'show_in_nav_menus'  => false,
    );

    register_post_type( 'ptd_achievement', $args );
}
add_action( 'init', 'ptd_register_cpt' );

/**
 * Meta box for Achievement media fields (media type, video URLs).
 */
function ptd_add_achievement_meta_boxes() {
    add_meta_box(
        'ptd_achievement_media',
        __( 'Achievement Media', 'ptd-divi-module' ),
        'ptd_render_achievement_meta_box',
        'ptd_achievement',
        'normal',
        'default'
    );
}
add_action( 'add_meta_boxes', 'ptd_add_achievement_meta_boxes' );

function ptd_render_achievement_meta_box( $post ) {
    wp_nonce_field( 'ptd_save_achievement_meta', 'ptd_achievement_meta_nonce' );
    $media_type  = get_post_meta( $post->ID, '_ptd_media_type', true );
    $video_url   = get_post_meta( $post->ID, '_ptd_video_url', true );
    $video_self  = get_post_meta( $post->ID, '_ptd_video_self', true );
    if ( empty( $media_type ) ) {
        $media_type = 'image';
    }

    echo '<p><label for="ptd_media_type"><strong>' . esc_html__( 'Media Type', 'ptd-divi-module' ) . '</strong></label><br />';
    echo '<select name="ptd_media_type" id="ptd_media_type">';
    foreach ( array(
        'image' => __( 'Image (use Featured Image)', 'ptd-divi-module' ),
        'video_url' => __( 'Video (URL)', 'ptd-divi-module' ),
        'video_self' => __( 'Video (Self-Hosted URL)', 'ptd-divi-module' ),
    ) as $val => $label ) {
        printf( '<option value="%1$s" %2$s>%3$s</option>', esc_attr( $val ), selected( $media_type, $val, false ), esc_html( $label ) );
    }
    echo '</select></p>';

    echo '<p><label for="ptd_video_url"><strong>' . esc_html__( 'Video URL (YouTube/Vimeo)', 'ptd-divi-module' ) . '</strong></label><br />';
    printf( '<input type="text" class="widefat" name="ptd_video_url" id="ptd_video_url" value="%s" placeholder="https://..." />', esc_attr( $video_url ) );
    echo '</p>';

    echo '<p><label for="ptd_video_self"><strong>' . esc_html__( 'Self-Hosted Video URL', 'ptd-divi-module' ) . '</strong></label><br />';
    printf( '<input type="text" class="widefat" name="ptd_video_self" id="ptd_video_self" value="%s" placeholder="https://example.com/video.mp4" />', esc_attr( $video_self ) );
    echo '</p>';

    echo '<p class="description">' . esc_html__( 'For Image type, set a Featured Image. For videos, paste a URL. Description comes from the main content editor.', 'ptd-divi-module' ) . '</p>';
}

function ptd_save_achievement_meta( $post_id ) {
    if ( ! isset( $_POST['ptd_achievement_meta_nonce'] ) || ! wp_verify_nonce( $_POST['ptd_achievement_meta_nonce'], 'ptd_save_achievement_meta' ) ) {
        return;
    }
    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        return;
    }
    if ( isset( $_POST['post_type'] ) && 'ptd_achievement' === $_POST['post_type'] ) {
        if ( ! current_user_can( 'edit_post', $post_id ) ) {
            return;
        }
    }

    $media_type = isset( $_POST['ptd_media_type'] ) ? sanitize_text_field( wp_unslash( $_POST['ptd_media_type'] ) ) : 'image';
    $video_url  = isset( $_POST['ptd_video_url'] ) ? esc_url_raw( wp_unslash( $_POST['ptd_video_url'] ) ) : '';
    $video_self = isset( $_POST['ptd_video_self'] ) ? esc_url_raw( wp_unslash( $_POST['ptd_video_self'] ) ) : '';

    update_post_meta( $post_id, '_ptd_media_type', $media_type );
    update_post_meta( $post_id, '_ptd_video_url', $video_url );
    update_post_meta( $post_id, '_ptd_video_self', $video_self );
}
add_action( 'save_post_ptd_achievement', 'ptd_save_achievement_meta' );
