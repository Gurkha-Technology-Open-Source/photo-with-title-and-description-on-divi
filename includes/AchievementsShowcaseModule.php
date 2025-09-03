<?php

class AchievementsShowcaseModule extends ET_Builder_Module {
    public $slug       = 'ptd_achievements_showcase';
    public $vb_support = 'on';
    public $child_slug = 'ptd_achievements_showcase_item';
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

        $output = sprintf(
            '<div class="ptd-achievements-showcase" data-slider-settings=\'%1$s\'>
                <div class="swiper-container">
                    <div class="swiper-wrapper">
                        %2$s
                    </div>
                    %3$s
                    %4$s
                </div>
            </div>',
            esc_attr( wp_json_encode( $slider_settings ) ),
            $content,
            $this->props['show_arrows'] === 'on' ? '<div class="swiper-button-next"></div><div class="swiper-button-prev"></div>' : '',
            $this->props['show_pagination'] === 'on' ? '<div class="swiper-pagination"></div>' : ''
        );

        return $output;
    }
}
