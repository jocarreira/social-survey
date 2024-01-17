<?php
namespace Elementor;
if ( ! defined( 'ABSPATH' ) ) exit; // Exit if accessed directly
class Widget_Survey_Maker_Elementor extends Widget_Base {
    public function get_name() {
        return 'survey-maker';
    }
    public function get_title() {
        return __( 'Social Survey', 'survey-maker' );
    }
    public function get_icon() {
        // Icon name from the Elementor font file, as per http://dtbaker.net/web-development/creating-your-own-custom-elementor-widgets/
        return 'survey-elementor-widget-logo';
    }
	public function get_categories() {
		return array( 'general' );
	}
    protected function _register_controls() {
        $this->start_controls_section(
            'section_survey_maker',
            array(
                'label' => esc_html__( 'Social Survey', 'survey-maker' ),
            )
        );
        $this->add_control(
            'important_note',
            array(
                'label' => '',
                'type' => \Elementor\Controls_Manager::RAW_HTML,
                'raw' => '<i class="survey-elementor-widget-logo"></i> '.esc_html__( 'Social Survey', 'survey-maker' ),
                'content_classes' => 'survey-elementor-widget-logo-wrap',
            )
        );
        $this->add_control(
            'survey_title',
            array(
                'label' => __( 'Survey Title', 'survey-maker' ),
                'type' => Controls_Manager::TEXT,
                'default' => '',
                'title' => __( 'Enter the survey title', 'survey-maker' ),
                'placeholder' => __( 'Enter the survey title', 'survey-maker' ),
            )
        );
        $this->add_control(
            'survey_title_alignment',
            array(
                'label' => __( 'Title Alignment', 'survey-maker' ),
                'type' => Controls_Manager::SELECT,
                'default' => 'left',
                'options' => array(
                    'left'      => 'Left',
                    'right'     => 'Right',
                    'center'    => 'Center'
                )
            )
        );
        $this->add_control(
            'survey_selector',
            array(
                'label' => __( 'Select survey', 'survey-maker' ),
                'type' => Controls_Manager::SELECT,
                'default' => $this->get_default_survey(),
                'options' => $this->get_active_surveys()
            )
        );

        $this->end_controls_section();
    }
    protected function render( $instance = array() ) {

        if ( ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'elementor' ) || ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'elementor_ajax' ) ) {
            echo '<style>
                .ays-survey-container .ays-survey-section:first-of-type {
                    display: block!important;
                }

                .ays-survey-container .ays-survey-section-footer,
                .ays-survey-container .ays-survey-answer,
                .ays-survey-container textarea.ays-survey-question-input-textarea,
                .ays-survey-container select,
                .ays-survey-container input {
                    pointer-events: none!important;
                    overflow: hidden!important;
                }
            </style>';
        }

        $settings = $this->get_settings_for_display();
        echo ( isset( $settings['survey_title'] ) && ! empty( $settings['survey_title'] ) ) ? "<h2 style='text-align: {$settings['survey_title_alignment']}'>{$settings['survey_title']}</h2>" : "";
        echo do_shortcode("[ays_survey id={$settings['survey_selector']}]");

    }

    public function get_active_surveys(){
        global $wpdb;
        $current_user = get_current_user_id();
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'surveys';
        $sql = "SELECT id,title FROM {$surveys_table} WHERE status='published'";
        if( ! current_user_can( 'manage_options' ) ){
            $sql .= " AND author_id = ".$current_user." ";
        }
        $results = $wpdb->get_results( $sql, ARRAY_A );
        $options = array();
        foreach ( $results as $result ){
            $options[$result['id']] = $result['title'];
        }
        return $options;
    }

    public function get_default_survey(){
        global $wpdb;
        $current_user = get_current_user_id();
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . 'surveys';
        $sql = "SELECT id FROM {$surveys_table} WHERE status='published'";
        if( ! current_user_can( 'manage_options' ) ){
            $sql .= " AND author_id = ".$current_user." ";
        }
        $sql .= " LIMIT 1;";
        $id = $wpdb->get_var( $sql );

        return intval($id);
    }

    protected function content_template() {}
    public function render_plain_content( $instance = array() ) {}
}