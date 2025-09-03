<?php

class AchievementsShowcaseModule extends ET_Builder_Module {
    public $slug       = 'ptd_achievements_showcase';
    public $vb_support = 'on';
    public $child_slug = 'ptd_achievements_showcase_item';

    protected $module_credits = array(
        'module_name' => 'Achievements Showcase',
        'module_uri'  => 'https://www.gurgurkhatech.com',
        'author'      => 'Gurkha Technology',
        'author_uri'  => 'https://www.gurgurkhatech.com',
    );

    public function init() {
        $this->name = esc_html__( 'Achievements Showcase', 'ptd-divi-module' );
    }

    public function get_fields() {
        return array(
            // Will be populated with advanced fields in get_advanced_fields
        );
    }

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
            'slider_settings' => array(
                'label'           => esc_html__( 'Slider Settings', 'ptd-divi-module' ),
                'toggle_slug'     => 'elements',
                'sub_toggle'      => 'slider',
                'options'         => array(
                    'show_arrows' => array(
                        'label'   => esc_html__( 'Show Arrows', 'ptd-divi-module' ),
                        'type'    => 'yes_no_button',
                        'options' => array(
                            'on'  => esc_html__( 'Yes', 'ptd-divi-module' ),
                            'off' => esc_html__( 'No', 'ptd-divi-module' ),
                        ),
                        'default' => 'on',
                    ),
                    'show_pagination' => array(
                        'label'   => esc_html__( 'Show Pagination', 'ptd-divi-module' ),
                        'type'    => 'yes_no_button',
                        'options' => array(
                            'on'  => esc_html__( 'Yes', 'ptd-divi-module' ),
                            'off' => esc_html__( 'No', 'ptd-divi-module' ),
                        ),
                        'default' => 'on',
                    ),
                    'autoplay' => array(
                        'label'   => esc_html__( 'Autoplay', 'ptd-divi-module' ),
                        'type'    => 'yes_no_button',
                        'options' => array(
                            'on'  => esc_html__( 'Yes', 'ptd-divi-module' ),
                            'off' => esc_html__( 'No', 'ptd-divi-module' ),
                        ),
                        'default' => 'off',
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
                    ),
                ),
            ),
        );
    }

    public function render( $attrs, $content = null, $render_slug ) {
        $this->enqueue_assets( $render_slug );

        $slider_settings = array(
            'show_arrows'     => $this->props['show_arrows'],
            'show_pagination' => $this->props['show_pagination'],
            'autoplay'        => $this->props['autoplay'],
            'autoplay_speed'  => $this->props['autoplay_speed'],
        );

        $output = sprintf(
            '<div class="ptd-achievements-showcase" data-slider-settings='%1$s'>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        %2$s
                    </div>
                    %3$s
                    %4$s
                </div>
            </div>',
            esc_attr( json_encode( $slider_settings ) ),
            $this->props['content'],
            $this->props['show_arrows'] === 'on' ? '<div class="swiper-button-next"></div><div class="swiper-button-prev"></div>' : '',
            $this->props['show_pagination'] === 'on' ? '<div class="swiper-pagination"></div>' : ''
        );

        return $output;
    }

    public function enqueue_assets( $render_slug ) {
        wp_enqueue_style( 'ptd-swiper-style', plugins_url( '../lib/swiper-bundle.min.css', __FILE__ ) );
        wp_enqueue_style( 'ptd-style', plugins_url( '../css/style.css', __FILE__ ) );

        wp_enqueue_script( 'ptd-swiper-script', plugins_url( '../lib/swiper-bundle.min.js', __FILE__ ), array( 'jquery' ), '1.0.0', true );
        wp_enqueue_script( 'ptd-frontend-script', plugins_url( '../js/frontend.js', __FILE__ ), array( 'jquery', 'ptd-swiper-script' ), '1.0.0', true );
    }
}
