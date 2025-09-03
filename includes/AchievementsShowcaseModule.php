<?php

class AchievementsShowcaseModule extends ET_Builder_Module {
    public $slug       = 'et_pb_ptd_achievements_showcase';
    public $vb_support = 'on';
    public $child_slug = 'et_pb_ptd_achievements_showcase_item';
    public $child_item_text = 'Add Achievement';

    protected $module_credits = array(
        'module_name' => 'Achievements Showcase',
        'module_uri'  => 'https://www.gurgurkhatech.com',
        'author'      => 'Gurkha Technology',
        'author_uri'  => 'https://www.gurgurkhatech.com',
    );

    /**
     * Initializes the module.
     *
     * @since 1.0.0
     */
    public function init() {
        $this->name = esc_html__( 'Achievements Showcase', 'ptd-divi-module' );
    }

    /**
     * Gets the module's fields.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_fields() {
        return array(
            // Data source
            'content_source' => array(
                'label'           => esc_html__( 'Content Source', 'ptd-divi-module' ),
                'type'            => 'select',
                'options'         => array(
                    'manual' => esc_html__( 'Manual (use child items)', 'ptd-divi-module' ),
                    'global' => esc_html__( 'Global (use Achievements CPT)', 'ptd-divi-module' ),
                ),
                'default'         => 'manual',
                'option_category' => 'basic_option',
                'toggle_slug'     => 'content',
            ),
            'posts_per_page' => array(
                'label'           => esc_html__( 'Number of Achievements', 'ptd-divi-module' ),
                'type'            => 'range',
                'default'         => '10',
                'range_settings'  => array(
                    'min'  => 1,
                    'max'  => 50,
                    'step' => 1,
                ),
                'show_if'         => array(
                    'content_source' => 'global',
                ),
                'toggle_slug'     => 'content',
            ),
            // Slider settings
            'show_arrows' => array(
                'label'   => esc_html__( 'Show Arrows', 'ptd-divi-module' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Yes', 'ptd-divi-module' ),
                    'off' => esc_html__( 'No', 'ptd-divi-module' ),
                ),
                'default' => 'on',
                'toggle_slug' => 'elements',
            ),
            'show_pagination' => array(
                'label'   => esc_html__( 'Show Pagination', 'ptd-divi-module' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Yes', 'ptd-divi-module' ),
                    'off' => esc_html__( 'No', 'ptd-divi-module' ),
                ),
                'default' => 'on',
                'toggle_slug' => 'elements',
            ),
            'autoplay' => array(
                'label'   => esc_html__( 'Autoplay', 'ptd-divi-module' ),
                'type'    => 'yes_no_button',
                'options' => array(
                    'on'  => esc_html__( 'Yes', 'ptd-divi-module' ),
                    'off' => esc_html__( 'No', 'ptd-divi-module' ),
                ),
                'default' => 'off',
                'toggle_slug' => 'elements',
            ),
            'autoplay_speed' => array(
                'label'       => esc_html__( 'Autoplay Speed (ms)', 'ptd-divi-module' ),
                'type'        => 'range',
                'default'     => '3000',
                'range_settings' => array(
                    'min'  => 500,
                    'max'  => 10000,
                    'step' => 100,
                ),
                'show_if'     => array(
                    'autoplay' => 'on',
                ),
                'toggle_slug' => 'elements',
            ),
        );
    }

    /**
     * Gets the module's advanced fields configuration.
     *
     * @since 1.0.0
     *
     * @return array
     */
    public function get_advanced_fields_config() {
        return array(
            'fonts'                 => array(
                'title' => array(
                    'label'    => esc_html__( 'Title', 'ptd-divi-module' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} .ptd-title",
                    ),
                ),
                'body'   => array(
                    'label'    => esc_html__( 'Body', 'ptd-divi-module' ),
                    'css'      => array(
                        'main' => "{$this->main_css_element} .ptd-description",
                    ),
                ),
            ),
            'text'                  => array(
                'use_text_orientation'  => false,
                'use_background_layout' => true,
                'options' => array(
                    'background_layout' => array(
                        'label'   => esc_html__( 'Text Color', 'ptd-divi-module' ),
                        'options' => array(
                            'light' => esc_html__( 'Dark', 'ptd-divi-module' ),
                            'dark'  => esc_html__( 'Light', 'ptd-divi-module' ),
                        ),
                    ),
                ),
            ),
        );
    }

    /**
     * Renders the module output.
     *
     * @since 1.0.0
     *
     * @param array  $attrs       List of attributes.
     * @param string $content     Content being rendered.
     * @param string $render_slug Slug of the module being rendered.
     *
     * @return string
     */
    public function render( $attrs, $content = null, $render_slug ) {
        $slider_settings = array(
            'show_arrows'     => $this->props['show_arrows'],
            'show_pagination' => $this->props['show_pagination'],
            'autoplay'        => $this->props['autoplay'],
            'autoplay_speed'  => $this->props['autoplay_speed'],
        );

        // Build slides
        $slides_html = '';
        if ( 'global' === $this->props['content_source'] ) {
            $ppp = absint( $this->props['posts_per_page'] );
            if ( ! $ppp ) { $ppp = 10; }
            $query = new \WP_Query( array(
                'post_type'      => 'ptd_achievement',
                'posts_per_page' => $ppp,
                'post_status'    => 'publish',
                'no_found_rows'  => true,
            ) );
            if ( $query->have_posts() ) {
                while ( $query->have_posts() ) {
                    $query->the_post();
                    $post_id     = get_the_ID();
                    $title       = get_the_title();
                    $description = get_the_content();
                    $media_type  = get_post_meta( $post_id, '_ptd_media_type', true );
                    $video_url   = get_post_meta( $post_id, '_ptd_video_url', true );
                    $video_self  = get_post_meta( $post_id, '_ptd_video_self', true );
                    if ( empty( $media_type ) ) { $media_type = 'image'; }

                    $media_output = '';
                    if ( 'image' === $media_type ) {
                        $img = get_the_post_thumbnail_url( $post_id, 'full' );
                        if ( $img ) {
                            $media_output = sprintf( '<img src="%1$s" alt="%2$s" />', esc_url( $img ), esc_attr( $title ) );
                        }
                    } elseif ( 'video_url' === $media_type && ! empty( $video_url ) ) {
                        $embed = wp_oembed_get( esc_url( $video_url ) );
                        $media_output = $embed ? $embed : '<div class="ptd-video-fallback">' . esc_html__( 'Video not available.', 'ptd-divi-module' ) . '</div>';
                    } elseif ( 'video_self' === $media_type && ! empty( $video_self ) ) {
                        $media_output = sprintf( '<video src="%1$s" controls></video>', esc_url( $video_self ) );
                    }

                    $slides_html .= sprintf(
                        '<div class="swiper-slide">
                            <div class="ptd-media-container">%1$s</div>
                            <div class="ptd-content">
                                <h3 class="ptd-title">%2$s</h3>
                                <div class="ptd-description">%3$s</div>
                            </div>
                        </div>',
                        $media_output,
                        esc_html( $title ),
                        wpautop( wp_kses_post( $description ) )
                    );
                }
                wp_reset_postdata();
            }
        } else {
            // Manual: use child items HTML
            $slides_html = $content;
        }

        $output = sprintf(
            '<div class="ptd-achievements-showcase" data-slider-settings=\'%1$s\'>
                <div class="swiper-container">
                    <div class="swiper-wrapper">%2$s</div>
                    %3$s
                    %4$s
                </div>
            </div>',
            esc_attr( wp_json_encode( $slider_settings ) ),
            $slides_html,
            $this->props['show_arrows'] === 'on' ? '<div class="swiper-button-next"></div><div class="swiper-button-prev"></div>' : '',
            $this->props['show_pagination'] === 'on' ? '<div class="swiper-pagination"></div>' : ''
        );

        return $output;
    }
}
