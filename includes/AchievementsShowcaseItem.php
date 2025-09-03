<?php

class AchievementsShowcaseItem extends ET_Builder_Module {
    public $slug       = 'ptd_achievements_showcase_item';
    public $vb_support = 'on';
    public $type      = 'child';
    public $child_title_var = 'title';

    protected $module_credits = array(
        'module_name' => 'Achievement Item',
        'module_uri'  => 'https://www.gurgurkhatech.com',
        'author'      => 'Gurkha Technology',
        'author_uri'  => 'https://www.gurgurkhatech.com',
    );

    public function init() {
        $this->name = esc_html__( 'Achievement Item', 'ptd-divi-module' );
        $this->advanced_setting_title_text = esc_html__( 'Item', 'ptd-divi-module' );
        $this->settings_text = esc_html__( 'Item Settings', 'ptd-divi-module' );
    }

    public function get_fields() {
        return array(
            'title' => array(
                'label'           => esc_html__( 'Title', 'ptd-divi-module' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Input the title for this achievement.', 'ptd-divi-module' ),
                'toggle_slug'     => 'main_content',
            ),
            'description' => array(
                'label'           => esc_html__( 'Description', 'ptd-divi-module' ),
                'type'            => 'textarea',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Input the description for this achievement.', 'ptd-divi-module' ),
                'toggle_slug'     => 'main_content',
            ),
            'media_type' => array(
                'label'           => esc_html__( 'Media Type', 'ptd-divi-module' ),
                'type'            => 'select',
                'option_category' => 'basic_option',
                'options'         => array(
                    'image' => esc_html__( 'Image', 'ptd-divi-module' ),
                    'video_url' => esc_html__( 'Video (URL)', 'ptd-divi-module' ),
                    'video_self' => esc_html__( 'Video (Self-Hosted)', 'ptd-divi-module' ),
                ),
                'default'         => 'image',
                'toggle_slug'     => 'main_content',
            ),
            'image' => array(
                'label'           => esc_html__( 'Image', 'ptd-divi-module' ),
                'type'            => 'upload',
                'upload_button_text' => esc_attr__( 'Upload an image', 'ptd-divi-module' ),
                'choose_text'      => esc_attr__( 'Choose an Image', 'ptd-divi-module' ),
                'update_text'      => esc_attr__( 'Set As Image', 'ptd-divi-module' ),
                'option_category' => 'basic_option',
                'show_if'         => array(
                    'media_type' => 'image',
                ),
                'toggle_slug'     => 'main_content',
            ),
            'video_url' => array(
                'label'           => esc_html__( 'Video URL', 'ptd-divi-module' ),
                'type'            => 'text',
                'option_category' => 'basic_option',
                'description'     => esc_html__( 'Enter a YouTube or Vimeo URL.', 'ptd-divi-module' ),
                'show_if'         => array(
                    'media_type' => 'video_url',
                ),
                'toggle_slug'     => 'main_content',
            ),
            'video_self' => array(
                'label'           => esc_html__( 'Self-Hosted Video', 'ptd-divi-module' ),
                'type'            => 'upload',
                'data_type'       => 'video',
                'upload_button_text' => esc_attr__( 'Upload a video', 'ptd-divi-module' ),
                'choose_text'      => esc_attr__( 'Choose a Video', 'ptd-divi-module' ),
                'update_text'      => esc_attr__( 'Set As Video', 'ptd-divi-module' ),
                'option_category' => 'basic_option',
                'show_if'         => array(
                    'media_type' => 'video_self',
                ),
                'toggle_slug'     => 'main_content',
            ),
        );
    }

    public function render( $attrs, $content = null, $render_slug ) {
        $title = $this->props['title'];
        $description = $this->props['description'];
        $media_type = $this->props['media_type'];
        $image = $this->props['image'];
        $video_url = $this->props['video_url'];
        $video_self = $this->props['video_self'];

        $media_output = '';

        if ( 'image' === $media_type && ! empty( $image ) ) {
            $media_output = sprintf( '<img src="%1$s" alt="%2$s" />', esc_url( $image ), esc_attr( $title ) );
        } elseif ( 'video_url' === $media_type && ! empty( $video_url ) ) {
            // Basic oEmbed for YouTube/Vimeo
            $media_output = wp_oembed_get( esc_url( $video_url ) );
            if ( ! $media_output ) {
                $media_output = '<div class="ptd-video-fallback">Video not available.</div>';
            }
        } elseif ( 'video_self' === $media_type && ! empty( $video_self ) ) {
            $media_output = sprintf( '<video src="%1$s" controls></video>', esc_url( $video_self ) );
        }

        $output = sprintf(
            '<div class="swiper-slide">
                <div class="ptd-media-container">%1$s</div>
                <div class="ptd-content">
                    <h3 class="ptd-title">%2$s</h3>
                    <div class="ptd-description">%3$s</div>
                </div>
            </div>',
            $media_output,
            esc_html( $title ),
            wpautop( $description )
        );

        return $output;
    }
}
