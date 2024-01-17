<?php
/**
 * The public-facing functionality of the plugin.
 *
 * @link       http://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/includes
 * @author     AYS Pro LLC <info@ays-pro.com>
 */
class Survey_Maker_Data {

    public static function get_survey_validated_data_from_array( $survey, $attr ){
        global $wpdb;

        // Array for survey validated options
        $settings = array();
        $name_prefix = 'survey_';
        
        // ID
        $id = ( isset($attr['id']) ) ? absint( intval( $attr['id'] ) ) : null;
        $settings['id'] = $id;

        // Survey options
        $options = array();
        if( isset( $survey->options ) && $survey->options != '' ){
            $options = json_decode( $survey->options, true );
        }

        $settings[ 'options' ] = $options;

        $survey_answers_alignment_grid_types = array(
            "space_around",
            "space_between",
        );

        // =======================  //  ======================= // ======================= // ======================= // ======================= //


        // =============================================================
        // ======================    Styles Tab    =====================
        // ========================    START    ========================


            // Survey Theme
            $settings[ $name_prefix . 'theme' ] = (isset($options[ $name_prefix . 'theme' ]) && $options[ $name_prefix . 'theme' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'theme' ] ) ) : 'classic_light';
            $settings[ $name_prefix . 'is_minimal' ] = $settings[ $name_prefix . 'theme' ] == 'minimal' ? true : false;
            $settings[ $name_prefix . 'is_modern' ] = $settings[ $name_prefix . 'theme' ] == 'modern' ? true : false;
            $settings[ $name_prefix . 'is_business' ] = $settings[ $name_prefix . 'theme' ] == 'business' ? true : false;
            $settings[ $name_prefix . 'is_elegant' ] = $settings[ $name_prefix . 'theme' ] == 'elegant' ? true : false;
            
            // Survey Color
            $settings[ $name_prefix . 'color' ] = (isset($options[ $name_prefix . 'color' ]) && $options[ $name_prefix . 'color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'color' ] ) ) : '#ff5722'; // '#673ab7'

            // Background color
            $settings[ $name_prefix . 'background_color' ] = (isset($options[ $name_prefix . 'background_color' ]) && $options[ $name_prefix . 'background_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'background_color' ] ) ) : '#fff';

            // Text Color
            $settings[ $name_prefix . 'text_color' ] = (isset($options[ $name_prefix . 'text_color' ]) && $options[ $name_prefix . 'text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'text_color' ] ) ) : '#333';

            // Buttons text Color
            $settings[ $name_prefix . 'buttons_text_color' ] = (isset($options[ $name_prefix . 'buttons_text_color' ]) && $options[ $name_prefix . 'buttons_text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'buttons_text_color' ] ) ) :  $settings[ $name_prefix . 'text_color' ];

            // Width
            $settings[ $name_prefix . 'width' ] = (isset($options[ $name_prefix . 'width' ]) && $options[ $name_prefix . 'width' ] != '') ? absint ( intval( $options[ $name_prefix . 'width' ] ) ) : '';

            // Survey Width by percentage or pixels
            $settings[ $name_prefix . 'width_by_percentage_px' ] = (isset($options[ $name_prefix . 'width_by_percentage_px' ]) && $options[ $name_prefix . 'width_by_percentage_px' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'width_by_percentage_px' ] ) ) : 'pixels';
            
            // Mobile width
            $settings[ $name_prefix . 'mobile_width' ] = (isset($options[ $name_prefix . 'mobile_width' ]) && $options[ $name_prefix . 'mobile_width' ] != '') ? absint ( intval( $options[ $name_prefix . 'mobile_width' ] ) ) : '';

            // Survey mobile width by percentage or pixels
            $settings[ $name_prefix . 'mobile_width_by_percent_px' ] = (isset($options[ $name_prefix . 'mobile_width_by_percent_px' ]) && $options[ $name_prefix . 'mobile_width_by_percent_px' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'mobile_width_by_percent_px' ] ) ) : 'pixels';

            // Survey container max width
            $settings[ $name_prefix . 'mobile_max_width' ] = (isset($options[ $name_prefix . 'mobile_max_width' ]) && $options[ $name_prefix . 'mobile_max_width' ] != '') ? absint ( intval( $options[ $name_prefix . 'mobile_max_width' ] ) ) : '';

            // Custom class for survey container
            $settings[ $name_prefix . 'custom_class' ] = (isset($options[ $name_prefix . 'custom_class' ]) && $options[ $name_prefix . 'custom_class' ] != '') ? stripslashes( esc_attr( $options[ $name_prefix . 'custom_class' ] ) ) : '';

            // Custom CSS
            $settings[ $name_prefix . 'custom_css' ] = (isset($options[ $name_prefix . 'custom_css' ]) && $options[ $name_prefix . 'custom_css' ] != '') ? stripslashes( html_entity_decode( $options[ $name_prefix . 'custom_css' ] ) ) : '';

            // Survey logo
            $settings[ $name_prefix . 'logo' ] = (isset($options[ $name_prefix . 'logo' ]) && $options[ $name_prefix . 'logo' ] != '') ? stripslashes( esc_attr( $options[ $name_prefix . 'logo' ] ) ) : '';

            // Survey logo position
            $settings[ $name_prefix . 'logo_image_position' ] = (isset($options[ $name_prefix . 'logo_image_position' ]) && $options[ $name_prefix . 'logo_image_position' ] != '') ? esc_attr( $options[ $name_prefix . 'logo_image_position' ] ) : 'right';

            // Survey logo title
            $settings[ $name_prefix . 'logo_title' ] = (isset($options[ $name_prefix . 'logo_title' ]) && $options[ $name_prefix . 'logo_title' ] != '') ? esc_attr( $options[ $name_prefix . 'logo_title' ] ) : '';

            // Survey title alignment
            $settings[ $name_prefix . 'title_alignment' ] = (isset( $options[ $name_prefix . 'title_alignment' ] ) && $options[ $name_prefix . 'title_alignment' ] != '') ? esc_attr( $options[ $name_prefix . 'title_alignment' ] ) : 'left';

            // Survey title font size
            $settings[ $name_prefix . 'title_font_size' ] = (isset( $options[ $name_prefix . 'title_font_size' ] ) && $options[ $name_prefix . 'title_font_size' ] != '' && $options[ $name_prefix . 'title_font_size' ] != '0' ) ? esc_attr( $options[ $name_prefix . 'title_font_size' ] ) : 30;

            // Survey title font size mobile
            $settings[ $name_prefix . 'title_font_size_for_mobile' ] = (isset( $options[ $name_prefix . 'title_font_size_for_mobile' ] ) && $options[ $name_prefix . 'title_font_size_for_mobile' ] != '' && $options[ $name_prefix . 'title_font_size_for_mobile' ] != '0' ) ? esc_attr( $options[ $name_prefix . 'title_font_size_for_mobile' ] ) : 30;

            // Survey title box shadow
            $settings[ $name_prefix . 'title_box_shadow_enable' ] = (isset( $options[ $name_prefix . 'title_box_shadow_enable' ] ) && $options[ $name_prefix . 'title_box_shadow_enable' ] == 'on' ) ? true : false;

            // === Survey title box shadow offsets start ===
                // Survey title box shadow offset x
                $settings[ $name_prefix . 'title_text_shadow_x_offset' ] = ( isset($options[ $name_prefix . 'title_text_shadow_x_offset' ] ) && $options[ $name_prefix . 'title_text_shadow_x_offset' ] != "") ? esc_attr($options[ $name_prefix . 'title_text_shadow_x_offset' ]) : 0;
                // Survey title box shadow offset y
                $settings[ $name_prefix . 'title_text_shadow_y_offset' ] = ( isset($options[ $name_prefix . 'title_text_shadow_y_offset' ] ) && $options[ $name_prefix . 'title_text_shadow_y_offset' ] != "") ? esc_attr($options[ $name_prefix . 'title_text_shadow_y_offset' ]) : 0;
                // Survey title box shadow offset z
                $settings[ $name_prefix . 'title_text_shadow_z_offset' ] = ( isset($options[ $name_prefix . 'title_text_shadow_z_offset' ] ) && $options[ $name_prefix . 'title_text_shadow_z_offset' ] != "") ? esc_attr($options[ $name_prefix . 'title_text_shadow_z_offset' ]) : 10;
            // === Survey title box shadow offsets end ===

            // Survey title box shadow color
            $settings[ $name_prefix . 'title_box_shadow_color' ] = (isset( $options[ $name_prefix . 'title_box_shadow_color' ] ) && $options[ $name_prefix . 'title_box_shadow_color' ] != '' ) ? esc_attr( $options[ $name_prefix . 'title_box_shadow_color' ] ) : '#333';

            // Survey section title font size PC
            $settings[ $name_prefix . 'section_title_font_size' ] = (isset( $options[ $name_prefix . 'section_title_font_size' ] ) && $options[ $name_prefix . 'section_title_font_size' ] != '' && $options[ $name_prefix . 'section_title_font_size' ] != '0' ) ? esc_attr( $options[ $name_prefix . 'section_title_font_size' ] ) : 32;

            // Survey section title font size Mobile
            $settings[ $name_prefix . 'section_title_font_size_mobile' ] = (isset( $options[ $name_prefix . 'section_title_font_size_mobile' ] ) && $options[ $name_prefix . 'section_title_font_size_mobile' ] != '' && $options[ $name_prefix . 'section_title_font_size_mobile' ] != '0' ) ? esc_attr( $options[ $name_prefix . 'section_title_font_size_mobile' ] ) : 32;

            // Survey section title alignment
            $settings[ $name_prefix . 'section_title_alignment' ] = (isset( $options[ $name_prefix . 'section_title_alignment' ] ) && $options[ $name_prefix . 'section_title_alignment' ] != '') ? esc_attr( $options[ $name_prefix . 'section_title_alignment' ] ) : 'left';

            // Survey section description alignment
            $settings[ $name_prefix . 'section_description_alignment' ] = (isset( $options[ $name_prefix . 'section_description_alignment' ] ) && $options[ $name_prefix . 'section_description_alignment' ] != '') ? esc_attr( $options[ $name_prefix . 'section_description_alignment' ] ) : 'left';

            // Survey section description font size
            $settings[ $name_prefix . 'section_description_font_size' ] = (isset( $options[ $name_prefix . 'section_description_font_size' ] ) && $options[ $name_prefix . 'section_description_font_size' ] != '' && $options[ $name_prefix . 'section_description_font_size' ] != '0' ) ? esc_attr( $options[ $name_prefix . 'section_description_font_size' ] ) : 14;

            // Survey section description font size mobile
            $settings[ $name_prefix . 'section_description_font_size_mobile' ] = (isset( $options[ $name_prefix . 'section_description_font_size_mobile' ] ) && $options[ $name_prefix . 'section_description_font_size_mobile' ] != '' && $options[ $name_prefix . 'section_description_font_size_mobile' ] != '0' ) ? esc_attr( $options[ $name_prefix . 'section_description_font_size_mobile' ] ) : 14;

            // Survey cover photo
            $settings[ $name_prefix . 'cover_photo' ] = (isset($options[ $name_prefix . 'cover_photo' ]) && $options[ $name_prefix . 'cover_photo' ] != '') ? stripslashes( esc_attr( $options[ $name_prefix . 'cover_photo' ] ) ) : '';

            // Survey cover photo height
            $settings[ $name_prefix . 'cover_photo_height' ] = (isset($options[ $name_prefix . 'cover_photo_height' ]) && $options[ $name_prefix . 'cover_photo_height' ] != '') ? esc_attr( $options[ $name_prefix . 'cover_photo_height' ] ) : 150;
            
            // Survey cover photo mobile height
            $settings[ $name_prefix . 'cover_photo_mobile_height' ] = (isset($options[ $name_prefix . 'cover_photo_mobile_height' ]) && $options[ $name_prefix . 'cover_photo_mobile_height' ] != '') ? esc_attr( $options[ $name_prefix . 'cover_photo_mobile_height' ] ) : $settings[ $name_prefix . 'cover_photo_height' ];

            // Survey cover photo position
            $settings[ $name_prefix . 'cover_photo_position' ] = (isset($options[ $name_prefix . 'cover_photo_position' ]) && $options[ $name_prefix . 'cover_photo_position' ] != '') ? esc_attr( $options[ $name_prefix . 'cover_photo_position' ] ) : "center_center";

            // Survey cover photo object fit
            $settings[ $name_prefix . 'cover_photo_object_fit' ] = (isset($options[ $name_prefix . 'cover_photo_object_fit' ]) && $options[ $name_prefix . 'cover_photo_object_fit' ] != '') ? esc_attr( $options[ $name_prefix . 'cover_photo_object_fit' ] ) : "cover";

            // Survey cover only first section
            $settings[ $name_prefix . 'cover_only_first_section' ] = (isset($options[ $name_prefix . 'cover_only_first_section' ]) && $options[ $name_prefix . 'cover_only_first_section' ] == 'on') ? true : false;

            // =========== Questions Styles Start ===========

                // Question font size
                $settings[ $name_prefix . 'question_font_size' ] = (isset($options[ $name_prefix . 'question_font_size' ]) && $options[ $name_prefix . 'question_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_font_size' ] ) ) : 16;

                // Question font size mobile
                $settings[ $name_prefix . 'question_font_size_mobile' ] = (isset($options[ $name_prefix . 'question_font_size_mobile' ]) && $options[ $name_prefix . 'question_font_size_mobile' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_font_size_mobile' ] ) ) : 16;

                // Question title alignment
                $settings[ $name_prefix . 'question_title_alignment' ] = (isset($options[ $name_prefix . 'question_title_alignment' ]) && $options[ $name_prefix . 'question_title_alignment' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'question_title_alignment' ] ) ) : 'left';

                // Question Image Width
                $settings[ $name_prefix . 'question_image_width' ] = (isset($options[ $name_prefix . 'question_image_width' ]) && $options[ $name_prefix . 'question_image_width' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_image_width' ] ) ) : '';

                // Question Image Height
                $settings[ $name_prefix . 'question_image_height' ] = (isset($options[ $name_prefix . 'question_image_height' ]) && $options[ $name_prefix . 'question_image_height' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_image_height' ] ) ) : '';

                // Question Image sizing
                $settings[ $name_prefix . 'question_image_sizing' ] = (isset($options[ $name_prefix . 'question_image_sizing' ]) && $options[ $name_prefix . 'question_image_sizing' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'question_image_sizing' ] ) ) : 'cover';
                
                // Question padding
                $settings[ $name_prefix . 'question_padding' ] = (isset($options[ $name_prefix . 'question_padding' ]) && $options[ $name_prefix . 'question_padding' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_padding' ] ) ) : 24;

                // Question caption text color
                $settings[ $name_prefix . 'question_caption_text_color' ] = (isset($options[ $name_prefix . 'question_caption_text_color' ]) && $options[ $name_prefix . 'question_caption_text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'question_caption_text_color' ] ) ) : $settings[ $name_prefix . 'text_color' ];

                // Question caption text alignment
                $settings[ $name_prefix . 'question_caption_text_alignment' ] = (isset($options[ $name_prefix . 'question_caption_text_alignment' ]) && $options[ $name_prefix . 'question_caption_text_alignment' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'question_caption_text_alignment' ] ) ) : 'center';
                
                // Question caption font size
                $settings[ $name_prefix . 'question_caption_font_size' ] = (isset($options[ $name_prefix . 'question_caption_font_size' ]) && $options[ $name_prefix . 'question_caption_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_caption_font_size' ] ) ) : 16;

                // Question caption font size on mobile
                $options[ $name_prefix . 'question_caption_font_size_on_mobile' ]  = isset($options[ $name_prefix . 'question_caption_font_size_on_mobile' ]) ? $options[ $name_prefix . 'question_caption_font_size_on_mobile' ] : $settings[ $name_prefix . 'question_caption_font_size' ];
                $settings[ $name_prefix . 'question_caption_font_size_on_mobile' ] = (isset($options[ $name_prefix . 'question_caption_font_size_on_mobile' ]) && $options[ $name_prefix . 'question_caption_font_size_on_mobile' ] != '') ? absint ( intval( $options[ $name_prefix . 'question_caption_font_size_on_mobile' ] ) ) : 16;

                // Question caption text transform
                $settings[ $name_prefix . 'question_caption_text_transform' ] = (isset($options[ $name_prefix . 'question_caption_text_transform' ]) && $options[ $name_prefix . 'question_caption_text_transform' ] != '') ? esc_attr ( $options[ $name_prefix . 'question_caption_text_transform' ] )  : 'none';

            // =========== Questions Styles End   =========== 


            // =========== Answers Styles Start ===========

                // Answer font size
                $settings[ $name_prefix . 'answer_font_size' ] = (isset($options[ $name_prefix . 'answer_font_size' ]) && $options[ $name_prefix . 'answer_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'answer_font_size' ] ) ) : 15;

                // Answer font size mobile
                $settings[ $name_prefix . 'answer_font_size_on_mobile' ] = (isset($options[ $name_prefix . 'answer_font_size_on_mobile' ]) && $options[ $name_prefix . 'answer_font_size_on_mobile' ] != '') ? absint ( intval( $options[ $name_prefix . 'answer_font_size_on_mobile' ] ) ) : 15;

                // Answer view
                $settings[ $name_prefix . 'answers_view' ] = (isset($options[ $name_prefix . 'answers_view' ]) && $options[ $name_prefix . 'answers_view' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'answers_view' ] ) ) : 'list';

                // Answer view alignment
                $settings[ $name_prefix . 'answers_view_alignment' ] = (isset($options[ $name_prefix . 'answers_view_alignment' ]) && $options[ $name_prefix . 'answers_view_alignment' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'answers_view_alignment' ] ) ) : 'space-around';
                $settings[ $name_prefix . 'answers_view_alignment' ] = ($settings[ $name_prefix . 'answers_view' ] == 'list' && in_array($settings[ $name_prefix . 'answers_view_alignment' ] , $survey_answers_alignment_grid_types)) ? 'flex-start' : $settings[ $name_prefix . 'answers_view_alignment' ];
                
                // Answer view grid column count
                $settings[ $name_prefix . 'grid_view_count' ] = (isset($options[ $name_prefix . 'grid_view_count' ]) && $options[ $name_prefix . 'grid_view_count' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'grid_view_count' ] ) ) : 'adaptive';

                // Answer object-fit
                $settings[ $name_prefix . 'answers_object_fit' ] = (isset($options[ $name_prefix . 'answers_object_fit' ]) && $options[ $name_prefix . 'answers_object_fit' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'answers_object_fit' ] ) ) : 'cover';

                // Answer padding
                $settings[ $name_prefix . 'answers_padding' ] = (isset($options[ $name_prefix . 'answers_padding' ]) && $options[ $name_prefix . 'answers_padding' ] != '') ? absint ( intval( $options[ $name_prefix . 'answers_padding' ] ) ) : 8;

                // Answer Gap
                $settings[ $name_prefix . 'answers_gap' ] = (isset($options[ $name_prefix . 'answers_gap' ]) && $options[ $name_prefix . 'answers_gap' ] != '') ? absint ( intval( $options[ $name_prefix . 'answers_gap' ] ) ) : 0;

                // Answer image size
                $settings[ $name_prefix . 'answers_image_size' ] = (isset($options[ $name_prefix . 'answers_image_size' ]) && $options[ $name_prefix . 'answers_image_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'answers_image_size' ] ) ) : 195;

                // Stars Color
                $settings[ $name_prefix . 'stars_color' ] = (isset($options[ $name_prefix . 'stars_color' ]) && $options[ $name_prefix . 'stars_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'stars_color' ] ) ) : '#fc0';

                // Buttons background color (is here to use $survey_buttons_bg_color this var below)
                $settings[ $name_prefix . 'buttons_bg_color' ] = (isset($options[ $name_prefix . 'buttons_bg_color' ]) && $options[ $name_prefix . 'buttons_bg_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'buttons_bg_color' ] ) ) : '#fff';

                // Slider questions bubble colors
                // Check theme to use the correct color for Slider question colors
                switch ( $settings[ $name_prefix . 'theme' ] ) {
                    case 'modern':
                    case 'business':
                    case 'elegant':
                        $slider_question_bubble_default_bg_color  = $settings[ $name_prefix . 'buttons_bg_color' ];
                        $slider_question_bubble_default_text_color = $settings[ $name_prefix . 'buttons_text_color' ];
                        break;
                    default:
                        $slider_question_bubble_default_bg_color  = $settings[ $name_prefix . 'color' ];
                        $slider_question_bubble_default_text_color = $settings[ $name_prefix . 'text_color' ];
                        break;
                }
                $settings[ $name_prefix . 'slider_question_bubble_bg_color' ]   = (isset($options[ $name_prefix . 'slider_question_bubble_bg_color' ]) && $options[ $name_prefix . 'slider_question_bubble_bg_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'slider_question_bubble_bg_color' ] ) ) : $slider_question_bubble_default_bg_color;
                $settings[ $name_prefix . 'slider_question_bubble_text_color' ] = (isset($options[ $name_prefix . 'slider_question_bubble_text_color' ]) && $options[ $name_prefix . 'slider_question_bubble_text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'slider_question_bubble_text_color' ] ) ) : $slider_question_bubble_default_text_color;

            // =========== Answers Styles End   ===========


            // =========== Buttons Styles Start ===========

                // Buttons size
                $settings[ $name_prefix . 'buttons_size' ] = (isset($options[ $name_prefix . 'buttons_size' ]) && $options[ $name_prefix . 'buttons_size' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'buttons_size' ] ) ) : 'medium';

                // Buttons font size
                $settings[ $name_prefix . 'buttons_font_size' ] = (isset($options[ $name_prefix . 'buttons_font_size' ]) && $options[ $name_prefix . 'buttons_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_font_size' ] ) ) : 14;

                // Buttons mobile font size
                $settings[ $name_prefix . 'buttons_mobile_font_size' ] = (isset($options[ $name_prefix . 'buttons_mobile_font_size' ]) && $options[ $name_prefix . 'buttons_mobile_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_mobile_font_size' ] ) ) : $settings[ $name_prefix . 'buttons_font_size' ];

                // Buttons Left / Right padding
                $settings[ $name_prefix . 'buttons_left_right_padding' ] = (isset($options[ $name_prefix . 'buttons_left_right_padding' ]) && $options[ $name_prefix . 'buttons_left_right_padding' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_left_right_padding' ] ) ) : 24;

                // Buttons Top / Bottom padding
                $settings[ $name_prefix . 'buttons_top_bottom_padding' ] = (isset($options[ $name_prefix . 'buttons_top_bottom_padding' ]) && $options[ $name_prefix . 'buttons_top_bottom_padding' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_top_bottom_padding' ] ) ) : 0;

                // Buttons border radius
                $settings[ $name_prefix . 'buttons_border_radius' ] = (isset($options[ $name_prefix . 'buttons_border_radius' ]) && $options[ $name_prefix . 'buttons_border_radius' ] != '') ? absint ( intval( $options[ $name_prefix . 'buttons_border_radius' ] ) ) : 4;

                // Buttons alignment
                $settings[ $name_prefix . 'buttons_alignment' ] = (isset($options[ $name_prefix . 'buttons_alignment' ]) && $options[ $name_prefix . 'buttons_alignment' ] != '') ? esc_attr( $options[ $name_prefix . 'buttons_alignment' ] )  : 'left';     

                // Buttons top distance
                $settings[ $name_prefix . 'buttons_top_distance' ] = (isset($options[ $name_prefix . 'buttons_top_distance' ]) && $options[ $name_prefix . 'buttons_top_distance' ] != '') ?  absint ( intval( $options[ $name_prefix . 'buttons_top_distance' ] ) ) : 10; 

            // ===========  Buttons Styles End  ===========

            // =========== Admin note Styles Start ===========

                // Admin note color
                $settings[ $name_prefix . 'admin_note_color' ] = (isset($options[ $name_prefix . 'admin_note_color' ]) && $options[ $name_prefix . 'admin_note_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'admin_note_color' ] ) ) : "#000000";

                // Admin note text transform size
                $settings[ $name_prefix . 'admin_note_text_transform' ] = (isset($options[ $name_prefix . 'admin_note_text_transform' ]) && $options[ $name_prefix . 'admin_note_text_transform' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'admin_note_text_transform' ] ) ) : '#none';

                // Admin note font size
                $settings[ $name_prefix . 'admin_note_font_size' ] = (isset($options[ $name_prefix . 'admin_note_font_size' ]) && $options[ $name_prefix . 'admin_note_font_size' ] != '') ? absint ( intval( $options[ $name_prefix . 'admin_note_font_size' ] ) ) : 11;

            // ===========  Admin note Styles End  ===========


        // =============================================================
        // ======================    Styles Tab    =====================
        // ========================     END     ========================


        // =======================  //  ======================= // ======================= // ======================= // ======================= //


        // =============================================================
        // =====================  Start page Tab  ======================
        // ========================    START   =========================

            // Start page title
            $settings[ $name_prefix . 'start_page_title' ]  = (isset($options[ $name_prefix . 'start_page_title' ]) &&  $options[ $name_prefix . 'start_page_title' ] != '') ? stripslashes( $options[ $name_prefix . 'start_page_title' ] ) : '';

            // Start page description
            $settings[ $name_prefix . 'start_page_description' ]  = (isset($options[ $name_prefix . 'start_page_description' ]) &&  $options[ $name_prefix . 'start_page_description' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'start_page_description' ] ) ) : '';

            // Start page Background color
            $settings[ $name_prefix . 'start_page_background_color' ] = (isset($options[ $name_prefix . 'start_page_background_color' ]) && $options[ $name_prefix . 'start_page_background_color' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'start_page_background_color' ] ) ) : '#fff';

            // Start page Text Color
            $settings[ $name_prefix . 'start_page_text_color' ] = (isset($options[ $name_prefix . 'start_page_text_color' ]) && $options[ $name_prefix . 'start_page_text_color' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'start_page_text_color' ] ) ) : '#333';

            // Custom class for Start page container
            $settings[ $name_prefix . 'start_page_custom_class' ] = (isset($options[ $name_prefix . 'start_page_custom_class' ]) && $options[ $name_prefix . 'start_page_custom_class' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'start_page_custom_class' ] ) ) : '';


        // =============================================================
        // =====================  Start page Tab  ======================
        // ========================     END     ========================


        // =======================  //  ======================= // ======================= // ======================= // ======================= //


        // =============================================================
        // ======================  Settings Tab  =======================
        // ========================    START   =========================

            // Show survey title
            $options[ $name_prefix . 'show_title' ] = isset($options[ $name_prefix . 'show_title' ]) ? $options[ $name_prefix . 'show_title' ] : 'on';
            $settings[ $name_prefix . 'show_title' ] = (isset($options[ $name_prefix . 'show_title' ]) && $options[ $name_prefix . 'show_title' ] == 'on') ? true : false;

            // Show survey section header
            $options[ $name_prefix . 'show_section_header' ] = isset($options[ $name_prefix . 'show_section_header' ]) ? $options[ $name_prefix . 'show_section_header' ] : 'on';
            $settings[ $name_prefix . 'show_section_header' ] = (isset($options[ $name_prefix . 'show_section_header' ]) && $options[ $name_prefix . 'show_section_header' ] == 'on') ? true : false;

            // Enable start page
            $options[ $name_prefix . 'enable_start_page' ] = isset($options[ $name_prefix . 'enable_start_page' ]) ? $options[ $name_prefix . 'enable_start_page' ] : 'off';
            $settings[ $name_prefix . 'enable_start_page' ] = (isset($options[ $name_prefix . 'enable_start_page' ]) && $options[ $name_prefix . 'enable_start_page' ] == 'on') ? true : false;

            // Enable randomize answers
            $options[ $name_prefix . 'enable_randomize_answers' ] = isset($options[ $name_prefix . 'enable_randomize_answers' ]) ? $options[ $name_prefix . 'enable_randomize_answers' ] : 'off';
            $settings[ $name_prefix . 'enable_randomize_answers' ] = (isset($options[ $name_prefix . 'enable_randomize_answers' ]) && $options[ $name_prefix . 'enable_randomize_answers' ] == 'on') ? true : false;

            // Enable randomize questions
            $options[ $name_prefix . 'enable_randomize_questions' ] = isset($options[ $name_prefix . 'enable_randomize_questions' ]) ? $options[ $name_prefix . 'enable_randomize_questions' ] : 'off';
            $settings[ $name_prefix . 'enable_randomize_questions' ] = (isset($options[ $name_prefix . 'enable_randomize_questions' ]) && $options[ $name_prefix . 'enable_randomize_questions' ] == 'on') ? true : false;

            // Enable rtl direction
            $options[ $name_prefix . 'enable_rtl_direction' ] = isset($options[ $name_prefix . 'enable_rtl_direction' ]) ? $options[ $name_prefix . 'enable_rtl_direction' ] : 'off';
            $settings[ $name_prefix . 'enable_rtl_direction' ] = (isset($options[ $name_prefix . 'enable_rtl_direction' ]) && $options[ $name_prefix . 'enable_rtl_direction' ] == 'on') ? true : false;

            // Enable confirmation box for leaving the page
            $options[ $name_prefix . 'enable_leave_page' ] = isset($options[ $name_prefix . 'enable_leave_page' ]) ? $options[ $name_prefix . 'enable_leave_page' ] : 'on';
            $settings[ $name_prefix . 'enable_leave_page' ] = (isset($options[ $name_prefix . 'enable_leave_page' ]) && $options[ $name_prefix . 'enable_leave_page' ] == 'on') ? true : false;

            // Enable clear answer button
            $options[ $name_prefix . 'enable_clear_answer' ] = isset($options[ $name_prefix . 'enable_clear_answer' ]) ? $options[ $name_prefix . 'enable_clear_answer' ] : 'off';
            $settings[ $name_prefix . 'enable_clear_answer' ] = (isset($options[ $name_prefix . 'enable_clear_answer' ]) && $options[ $name_prefix . 'enable_clear_answer' ] == 'on') ? true : false;
            
            // Enable previous button
            $options[ $name_prefix . 'enable_previous_button' ] = isset($options[ $name_prefix . 'enable_previous_button' ]) ? $options[ $name_prefix . 'enable_previous_button' ] : 'off';
            $settings[ $name_prefix . 'enable_previous_button' ] = (isset($options[ $name_prefix . 'enable_previous_button' ]) && $options[ $name_prefix . 'enable_previous_button' ] == 'on') ? true : false;

            // Enable Survey Start loader
            $options[ $name_prefix . 'enable_survey_start_loader' ] = isset($options[ $name_prefix . 'enable_survey_start_loader' ]) ? $options[ $name_prefix . 'enable_survey_start_loader' ] : 'off';
            $settings[ $name_prefix . 'enable_survey_start_loader' ] = (isset($options[ $name_prefix . 'enable_survey_start_loader' ]) && $options[ $name_prefix . 'enable_survey_start_loader' ] == 'on') ? true : false;
            $settings[ $name_prefix . 'before_start_loader' ] = (isset($options[ $name_prefix . 'before_start_loader' ]) && $options[ $name_prefix . 'before_start_loader' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'before_start_loader' ] ) ) : 'default';

            // Allow HTML in answers
            $options[ $name_prefix . 'allow_html_in_answers' ] = isset($options[ $name_prefix . 'allow_html_in_answers' ]) ? $options[ $name_prefix . 'allow_html_in_answers' ] : 'off';
            $settings[ $name_prefix . 'allow_html_in_answers' ] = (isset($options[ $name_prefix . 'allow_html_in_answers' ]) && $options[ $name_prefix . 'allow_html_in_answers' ] == 'on') ? true : false;

            // Allow HTML in section description
            $options[ $name_prefix . 'allow_html_in_section_description' ] = isset($options[ $name_prefix . 'allow_html_in_section_description' ]) ? $options[ $name_prefix . 'allow_html_in_section_description' ] : 'off';
            $settings[ $name_prefix . 'allow_html_in_section_description' ] = (isset($options[ $name_prefix . 'allow_html_in_section_description' ]) && $options[ $name_prefix . 'allow_html_in_section_description' ] == 'on') ? true : false;


            //---- Schedule Start  ---- //

                // Schedule the Survey
                $options[ $name_prefix . 'enable_schedule' ] = isset($options[ $name_prefix . 'enable_schedule' ]) ? $options[ $name_prefix . 'enable_schedule' ] : 'off';
                $settings[ $name_prefix . 'enable_schedule' ] = (isset($options[ $name_prefix . 'enable_schedule' ]) && $options[ $name_prefix . 'enable_schedule' ] == 'on') ? true : false;

                if ( $settings[ $name_prefix . 'enable_schedule' ] ) {
                    $activateTimeVal = (isset($options[ $name_prefix . 'schedule_active' ]) && $options[ $name_prefix . 'schedule_active' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'schedule_active' ] ) ) : current_time( 'mysql' );
                    $deactivateTimeVal = (isset($options[ $name_prefix . 'schedule_deactive' ]) && $options[ $name_prefix . 'schedule_deactive' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'schedule_deactive' ] ) ) : current_time( 'mysql' );

                    $activateTime = strtotime($activateTimeVal);
                    $settings[ $name_prefix . 'schedule_active' ] = date('Y-m-d H:i:s', $activateTime);

                    $deactivateTime = strtotime($deactivateTimeVal);
                    $settings[ $name_prefix . 'schedule_deactive' ] = date('Y-m-d H:i:s', $deactivateTime);
                } else {
                    $settings[ $name_prefix . 'schedule_active' ] = current_time( 'mysql' );
                    $settings[ $name_prefix . 'schedule_deactive' ] = current_time( 'mysql' );
                }

                // Show timer
                $options[ $name_prefix . 'schedule_show_timer' ] = isset($options[ $name_prefix . 'schedule_show_timer' ]) ? $options[ $name_prefix . 'schedule_show_timer' ] : 'off';
                $settings[ $name_prefix . 'schedule_show_timer' ] = (isset($options[ $name_prefix . 'schedule_show_timer' ]) && $options[ $name_prefix . 'schedule_show_timer' ] == 'on') ? true : false;

                // Show countdown / start date
                $settings[ $name_prefix . 'show_timer_type' ] = (isset($options[ $name_prefix . 'show_timer_type' ]) && $options[ $name_prefix . 'show_timer_type' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'show_timer_type' ] ) ) : 'countdown';

                // Pre start message
                $settings[ $name_prefix . 'schedule_pre_start_message' ] = (isset($options[ $name_prefix . 'schedule_pre_start_message' ]) &&  $options[ $name_prefix . 'schedule_pre_start_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'schedule_pre_start_message' ] ) ) : __("The survey will be available soon!", SURVEY_MAKER_NAME);

                // Expiration message
                $settings[ $name_prefix . 'schedule_expiration_message' ] = (isset($options[ $name_prefix . 'schedule_expiration_message' ]) &&  $options[ $name_prefix . 'schedule_expiration_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'schedule_expiration_message' ] ) ) : __("This survey has expired!", SURVEY_MAKER_NAME);

                // Expiration message
                $settings[ $name_prefix . 'dont_show_survey_container' ] = (isset($options[ $name_prefix . 'dont_show_survey_container' ]) && $options[ $name_prefix . 'dont_show_survey_container' ] == 'on') ? true : false;
            //---- Schedule End  ---- //

            $settings[ $name_prefix . 'edit_previous_submission' ] = (isset($options[ $name_prefix . 'edit_previous_submission' ]) && $options[ $name_prefix . 'edit_previous_submission' ] == 'on') ? true : false;

            // Auto numbering
            $settings[ $name_prefix . 'auto_numbering' ] = (isset($options[ $name_prefix . 'auto_numbering' ]) &&  $options[ $name_prefix . 'auto_numbering' ] != '') ? stripslashes( $options[ $name_prefix . 'auto_numbering' ] )  : 'none';

            // Auto numbering questions
            $settings[ $name_prefix . 'auto_numbering_questions' ] = (isset($options[ $name_prefix . 'auto_numbering_questions' ]) &&  $options[ $name_prefix . 'auto_numbering_questions' ] != '') ? stripslashes( $options[ $name_prefix . 'auto_numbering_questions' ] )  : 'none';
            $options[ $name_prefix . 'enable_question_numbering_by_sections' ] = isset($options[ $name_prefix . 'enable_question_numbering_by_sections' ]) ? $options[ $name_prefix . 'enable_question_numbering_by_sections' ] : 'on';
            $settings[ $name_prefix . 'enable_question_numbering_by_sections' ] = (isset($options[ $name_prefix . 'enable_question_numbering_by_sections' ]) && $options[ $name_prefix . 'enable_question_numbering_by_sections' ] == 'on') ? true  : false;
            
            // Autofill information
            $options[ $name_prefix . 'enable_i_autofill' ] = isset($options[ $name_prefix . 'enable_i_autofill' ]) ? $options[ $name_prefix . 'enable_i_autofill' ] : 'off';
            $settings[ $name_prefix . 'enable_i_autofill' ] = (isset($options[ $name_prefix . 'enable_i_autofill' ]) && $options[ $name_prefix . 'enable_i_autofill' ] == 'on') ? true : false;
            
            // Allow collecting information of logged in users
            $options[ $name_prefix . 'allow_collecting_logged_in_users_data' ] = isset($options[ $name_prefix . 'allow_collecting_logged_in_users_data' ]) ? $options[ $name_prefix . 'allow_collecting_logged_in_users_data' ] : 'off';
            $settings[ $name_prefix . 'allow_collecting_logged_in_users_data' ] = (isset($options[ $name_prefix . 'allow_collecting_logged_in_users_data' ]) && $options[ $name_prefix . 'allow_collecting_logged_in_users_data' ] == 'on') ? true : false;
            
            // Enable copy protection
            $options[ $name_prefix . 'enable_copy_protection' ] = isset($options[ $name_prefix . 'enable_copy_protection' ]) ? $options[ $name_prefix . 'enable_copy_protection' ] : 'off';
            $settings[ $name_prefix . 'enable_copy_protection' ] = (isset($options[ $name_prefix . 'enable_copy_protection' ]) && $options[ $name_prefix . 'enable_copy_protection' ] == 'on') ? true : false;
            
            // Expand/Collapse questions
            $options[ $name_prefix . 'enable_expand_collapse_question' ] = isset($options[ $name_prefix . 'enable_expand_collapse_question' ]) ? $options[ $name_prefix . 'enable_expand_collapse_question' ] : 'off';
            $settings[ $name_prefix . 'enable_expand_collapse_question' ] = (isset($options[ $name_prefix . 'enable_expand_collapse_question' ]) && $options[ $name_prefix . 'enable_expand_collapse_question' ] == 'on') ? true : false;
            
            // Questions text to speech enable
            $options[ $name_prefix . 'question_text_to_speech' ] = isset($options[ $name_prefix . 'question_text_to_speech' ]) ? $options[ $name_prefix . 'question_text_to_speech' ] : 'off';
            $settings[ $name_prefix . 'question_text_to_speech' ] = (isset($options[ $name_prefix . 'question_text_to_speech' ]) && $options[ $name_prefix . 'question_text_to_speech' ] == 'on') ? true : false;
            
            // Enable full screen
            $options[ $name_prefix . 'full_screen_mode' ] = isset($options[ $name_prefix . 'full_screen_mode' ]) ? $options[ $name_prefix . 'full_screen_mode' ] : 'off';
            $settings[ $name_prefix . 'full_screen_mode' ] = (isset($options[ $name_prefix . 'full_screen_mode' ]) && $options[ $name_prefix . 'full_screen_mode' ] == 'on') ? true : false;

            $options[ $name_prefix . 'full_screen_button_color' ] = isset($options[ $name_prefix . 'full_screen_button_color' ]) ? $options[ $name_prefix . 'full_screen_button_color' ] : $settings[ $name_prefix . 'text_color' ];
            $settings[ $name_prefix . 'full_screen_button_color' ] = (isset($options[ $name_prefix . 'full_screen_button_color' ]) && $options[ $name_prefix . 'full_screen_button_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'full_screen_button_color' ] ) ) : $settings[ $name_prefix . 'text_color' ];

            // Survey progress bar
            $options[ $name_prefix . 'enable_progress_bar' ] = isset($options[ $name_prefix . 'enable_progress_bar' ]) ? $options[ $name_prefix . 'enable_progress_bar' ] : 'off';
            $settings[ $name_prefix . 'enable_progress_bar' ] = (isset($options[ $name_prefix . 'enable_progress_bar' ]) && $options[ $name_prefix . 'enable_progress_bar' ] == 'on') ? true : false;
            $settings[ $name_prefix . 'hide_section_pagination_text' ] = ( isset( $options[ $name_prefix . 'hide_section_pagination_text' ] ) && $options[ $name_prefix . 'hide_section_pagination_text' ] == "on" ) ? true : false;
            $settings[ $name_prefix . 'pagination_positioning' ] = ( isset( $options[ $name_prefix . 'pagination_positioning' ] ) && $options[ $name_prefix . 'pagination_positioning' ] != "" ) ? esc_attr($options[ $name_prefix . 'pagination_positioning' ]) : "none";
            $settings[ $name_prefix . 'hide_section_bar' ] = ( isset( $options[ $name_prefix . 'hide_section_bar' ] ) && $options[ $name_prefix . 'hide_section_bar' ] == "on" ) ? true : false;
            $settings[ $name_prefix . 'progress_bar_text' ] = ( isset( $options[ $name_prefix . 'progress_bar_text' ] ) && $options[ $name_prefix . 'progress_bar_text' ] != "" ) ? stripslashes(esc_attr($options[ $name_prefix . 'progress_bar_text' ])) : 'Page';

            $options[ $name_prefix . 'pagination_text_color' ] = isset($options[ $name_prefix . 'pagination_text_color' ]) ? $options[ $name_prefix . 'pagination_text_color' ] : $settings[ $name_prefix . 'text_color' ];
            $settings[ $name_prefix . 'pagination_text_color' ] = (isset($options[ $name_prefix . 'pagination_text_color' ]) && $options[ $name_prefix . 'pagination_text_color' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'pagination_text_color' ] ) ) : $settings[ $name_prefix . 'text_color' ];
    
            // Survey show sections questions count
            $options[ $name_prefix . 'show_sections_questions_count' ] = ( isset( $options[ $name_prefix . 'show_sections_questions_count' ] ) ) ? $options[ $name_prefix . 'show_sections_questions_count' ] : "off";
            $settings[ $name_prefix . 'show_sections_questions_count' ] = ( isset( $options[ $name_prefix . 'show_sections_questions_count' ] ) && $options[ $name_prefix . 'show_sections_questions_count' ] == "on" ) ? true : false;

            // Survey required questions message
            $options[ $name_prefix . 'required_questions_message' ] = ( isset( $options[ $name_prefix . 'required_questions_message' ] ) ) ? $options[ $name_prefix . 'required_questions_message' ] : "This is a required question";
            $settings[ $name_prefix . 'required_questions_message' ] = ( isset( $options[ $name_prefix . 'required_questions_message' ] ) && $options[ $name_prefix . 'required_questions_message' ] != "" ) ? __(stripslashes(esc_attr($options[ $name_prefix . 'required_questions_message' ])) , "survey-maker") : '';

            // Enable chat mode
            $options[ $name_prefix . 'enable_chat_mode' ] = isset($options[ $name_prefix . 'enable_chat_mode' ]) ? $options[ $name_prefix . 'enable_chat_mode' ] : 'off';
            $settings[ $name_prefix . 'enable_chat_mode' ] = (isset($options[ $name_prefix . 'enable_chat_mode' ]) && $options[ $name_prefix . 'enable_chat_mode' ] == 'on') ? true : false;

            // Enable terms and conditions
            $options[ $name_prefix . 'enable_terms_and_conditions' ] = isset($options[ $name_prefix . 'enable_terms_and_conditions' ]) ? esc_attr($options[ $name_prefix . 'enable_terms_and_conditions' ]) : 'off';
            $settings[ $name_prefix . 'enable_terms_and_conditions' ] = (isset($options[ $name_prefix . 'enable_terms_and_conditions' ]) && $options[ $name_prefix . 'enable_terms_and_conditions' ] == 'on') ? true : false;
            $settings[ $name_prefix . 'terms_and_conditions_data' ] = (isset($options[ $name_prefix . 'terms_and_conditions_data' ]) &&  !empty($options[ $name_prefix . 'terms_and_conditions_data' ])) ?  $options[ $name_prefix . 'terms_and_conditions_data' ]  : array();

            //Terms and conditions required message
            $options[ $name_prefix . 'enable_terms_and_conditions_required_message' ] = (isset($options[ 'enable_terms_and_conditions_required_message' ]) && $options['enable_terms_and_conditions_required_message' ] != '') ? $options['enable_terms_and_conditions_required_message' ] : 'off';
            $settings['enable_terms_and_conditions_required_message' ] = (isset($options['enable_terms_and_conditions_required_message' ]) && $options['enable_terms_and_conditions_required_message' ] == 'on') ? true : false;

        // =============================================================
        // ======================  Settings Tab  =======================
        // ========================    START   =========================


        // =======================  //  ======================= // ======================= // ======================= // ======================= //


        // =============================================================
        // =================== Results Settings Tab  ===================
        // ========================    START   =========================


            // Redirect after submit
            $options[ $name_prefix . 'redirect_after_submit' ] = isset($options[ $name_prefix . 'redirect_after_submit' ]) ? $options[ $name_prefix . 'redirect_after_submit' ] : 'off';
            $settings[ $name_prefix . 'redirect_after_submit' ] = (isset($options[ $name_prefix . 'redirect_after_submit' ]) && $options[ $name_prefix . 'redirect_after_submit' ] == 'on') ? true : false;

            // Redirect URL
            $settings[ $name_prefix . 'submit_redirect_url' ] = (isset($options[ $name_prefix . 'submit_redirect_url' ]) && $options[ $name_prefix . 'submit_redirect_url' ] != '') ? stripslashes ( esc_url( $options[ $name_prefix . 'submit_redirect_url' ] ) ) : '';

            // Redirect delay (sec)
            $settings[ $name_prefix . 'submit_redirect_delay' ] = (isset($options[ $name_prefix . 'submit_redirect_delay' ]) && $options[ $name_prefix . 'submit_redirect_delay' ] != '') ? absint ( intval( $options[ $name_prefix . 'submit_redirect_delay' ] ) ) : '';

            // Redirect in new tab
            $settings[ $name_prefix . 'submit_redirect_new_tab' ] = (isset($options[ $name_prefix . 'submit_redirect_new_tab' ]) && $options[ $name_prefix . 'submit_redirect_new_tab' ] == 'on') ? true : false;

            // Enable EXIT button
            $options[ $name_prefix . 'enable_exit_button' ] = isset($options[ $name_prefix . 'enable_exit_button' ]) ? $options[ $name_prefix . 'enable_exit_button' ] : 'off';
            $settings[ $name_prefix . 'enable_exit_button' ] = (isset($options[ $name_prefix . 'enable_exit_button' ]) && $options[ $name_prefix . 'enable_exit_button' ] == 'on') ? true : false;

            // Redirect URL
            $settings[ $name_prefix . 'exit_redirect_url' ] = (isset($options[ $name_prefix . 'exit_redirect_url' ]) && $options[ $name_prefix . 'exit_redirect_url' ] != '') ? stripslashes ( esc_url( $options[ $name_prefix . 'exit_redirect_url' ] ) ) : '';

            // Enable restart button
            $options[ $name_prefix . 'enable_restart_button' ] = isset($options[ $name_prefix . 'enable_restart_button' ]) ? $options[ $name_prefix . 'enable_restart_button' ] : 'off';
            $settings[ $name_prefix . 'enable_restart_button' ] = (isset($options[ $name_prefix . 'enable_restart_button' ]) && $options[ $name_prefix . 'enable_restart_button' ] == 'on') ? true : false;

            // Show summary after submission
            $options[ $name_prefix . 'show_summary_after_submission' ] = isset($options[ $name_prefix . 'show_summary_after_submission' ]) ? $options[ $name_prefix . 'show_summary_after_submission' ] : 'off';
            $settings[ $name_prefix . 'show_summary_after_submission' ] = (isset($options[ $name_prefix . 'show_summary_after_submission' ]) && $options[ $name_prefix . 'show_summary_after_submission' ] == 'on') ? true : false;
            
            // Show only current users summary
            $options[ $name_prefix . 'show_current_user_results' ]  = isset($options[ $name_prefix . 'show_current_user_results' ]) ? $options[ $name_prefix . 'show_current_user_results' ] : 'off';
            $settings[ $name_prefix . 'show_current_user_results' ] = (isset($options[ $name_prefix . 'show_current_user_results' ]) && $options[ $name_prefix . 'show_current_user_results' ] == 'on') ? true : false;

            // Show summary after submission results
            $settings[ $name_prefix . 'show_submission_results' ] = (isset( $options[ $name_prefix . 'show_submission_results' ] ) && $options[ $name_prefix . 'show_submission_results' ] != '' ) ? stripslashes( $options[ $name_prefix . 'show_submission_results' ] ) : 'summary';

            // Thank you message
            $settings[ $name_prefix . 'final_result_text' ]  = (isset($options[ $name_prefix . 'final_result_text' ]) &&  $options[ $name_prefix . 'final_result_text' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'final_result_text' ] ) ) : '';

            // Select survey loader
            $settings[ $name_prefix . 'loader' ] = (isset($options[ $name_prefix . 'loader' ]) && $options[ $name_prefix . 'loader' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'loader' ] ) ) : 'default';
            
            // Loader custom gif
            $settings[ $name_prefix . 'loader_gif' ] = (isset($options[ $name_prefix . 'loader_gif' ]) &&  $options[ $name_prefix . 'loader_gif' ] != '') ? esc_url( $options[ $name_prefix . 'loader_gif' ] )  : '';
            $settings[ $name_prefix . 'loader_gif_width' ] = (isset($options[ $name_prefix . 'loader_gif_width' ]) &&  $options[ $name_prefix . 'loader_gif_width' ] != '') ? esc_attr( $options[ $name_prefix . 'loader_gif_width' ] )  : '100';

            // Social share buttons
            $options[ $name_prefix . 'social_buttons' ]   = ( isset( $options[ $name_prefix . 'social_buttons' ] ) && $options[ $name_prefix . 'social_buttons' ] == 'on' ) ? true : false;
            $settings[ $name_prefix . 'social_buttons' ]  = ( isset( $options[ $name_prefix . 'social_buttons' ] ) && $options[ $name_prefix . 'social_buttons' ] ) ? true : false;
            // Linkedin
            $options[ $name_prefix . 'social_button_ln' ] = ( isset( $options[ $name_prefix . 'social_button_ln' ] ) && $options[ $name_prefix . 'social_button_ln' ] == 'on' ) ? true : false;
            $settings[ $name_prefix . 'social_button_ln' ] = ( isset( $options[ $name_prefix . 'social_button_ln' ] ) && $options[ $name_prefix . 'social_button_ln' ] == 'on' ) ? true : false;
            // Facebook
            $options[ $name_prefix . 'social_button_fb' ] = ( isset( $options[ $name_prefix . 'social_button_fb' ] ) && $options[ $name_prefix . 'social_button_fb' ] == 'on' ) ? true : false;
            $settings[ $name_prefix . 'social_button_fb' ] = ( isset( $options[ $name_prefix . 'social_button_fb' ] ) && $options[ $name_prefix . 'social_button_fb' ] ) ? true : false;
            // Twitter
            $options[ $name_prefix . 'social_button_tr' ] = ( isset( $options[ $name_prefix . 'social_button_tr' ] ) && $options[ $name_prefix . 'social_button_tr' ] == 'on' ) ? true : false;
            $settings[ $name_prefix . 'social_button_tr' ] = ( isset( $options[ $name_prefix . 'social_button_tr' ] ) && $options[ $name_prefix . 'social_button_tr' ] ) ? true : false;
            // Vk
            $options[ $name_prefix . 'social_button_vk' ] = ( isset( $options[ $name_prefix . 'social_button_vk' ] ) && $options[ $name_prefix . 'social_button_vk' ] == 'on' ) ? true : false;
            $settings[ $name_prefix . 'social_button_vk' ] = ( isset( $options[ $name_prefix . 'social_button_vk' ] ) && $options[ $name_prefix . 'social_button_vk' ] ) ? true : false;


        // =============================================================
        // =================== Results Settings Tab  ===================
        // ========================    END    ==========================


        // =======================  //  ======================= // ======================= // ======================= // ======================= //

        // =============================================================
        // =================== Conditions Settings Tab =================
        // ========================   START   ==========================

            // Show all conditions results
            $options[ $name_prefix . 'condition_show_all_results' ] = isset($options[ $name_prefix . 'condition_show_all_results' ]) ? $options[ $name_prefix . 'condition_show_all_results' ] : 'off';
            $settings[ $name_prefix . 'condition_show_all_results' ] = (isset($options[ $name_prefix . 'condition_show_all_results' ]) && $options[ $name_prefix . 'condition_show_all_results' ] == 'on') ? true : false;

        // =============================================================
        // =================== Conditions Settings Tab =================
        // ========================    END    ==========================

        // =======================  //  ======================= // ======================= // ======================= // ======================= //

        // =======================  //  ======================= // ======================= // ======================= // ======================= //

        // =============================================================
        // ===================    Limitation Tab     ===================
        // ========================    START   =========================

            // Maximum number of attempts per user
            $options[ $name_prefix . 'limit_users' ] = isset($options[ $name_prefix . 'limit_users' ]) ? $options[ $name_prefix . 'limit_users' ] : 'off';
            $settings[ $name_prefix . 'limit_users' ] = (isset($options[ $name_prefix . 'limit_users' ]) && $options[ $name_prefix . 'limit_users' ] == 'on') ? true : false;

            // Detects users by IP / ID
            $settings[ $name_prefix . 'limit_users_by' ] = (isset($options[ $name_prefix . 'limit_users_by' ]) && $options[ $name_prefix . 'limit_users_by' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'limit_users_by' ] ) ) : 'ip';

            // Attempts count
            $settings[ $name_prefix . 'max_pass_count' ] = (isset($options[ $name_prefix . 'max_pass_count' ]) && $options[ $name_prefix . 'max_pass_count' ] != '') ? absint ( intval( $options[ $name_prefix . 'max_pass_count' ] ) ) : 1;

            // Limitation Message
            $settings[ $name_prefix . 'limitation_message' ] = (isset($options[ $name_prefix . 'limitation_message' ]) &&  $options[ $name_prefix . 'limitation_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'limitation_message' ] ) ) : '';
            
            // Redirect Url
            $settings[ $name_prefix . 'redirect_url' ] = (isset($options[ $name_prefix . 'redirect_url' ]) && $options[ $name_prefix . 'redirect_url' ] != '') ?  $options[ $name_prefix . 'redirect_url' ] : '';
            
            // Redirect delay
            $settings[ $name_prefix . 'redirect_delay' ] = (isset($options[ $name_prefix . 'redirect_delay' ]) && $options[ $name_prefix . 'redirect_delay' ] != '') ? absint ( intval( $options[ $name_prefix . 'redirect_delay' ] ) ) : 1;

            // Only for logged in users
            $options[ $name_prefix . 'enable_logged_users' ] = isset($options[ $name_prefix . 'enable_logged_users' ]) ? $options[ $name_prefix . 'enable_logged_users' ] : 'off';
            $settings[ $name_prefix . 'enable_logged_users' ] = (isset($options[ $name_prefix . 'enable_logged_users' ]) && $options[ $name_prefix . 'enable_logged_users' ] == 'on') ? true : false;

            // Message - Only for logged in users
            $settings[ $name_prefix . 'logged_in_message' ] = (isset($options[ $name_prefix . 'logged_in_message' ]) &&  $options[ $name_prefix . 'logged_in_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'logged_in_message' ] ) ) : '';

            // Show login form
            $options[ $name_prefix . 'show_login_form' ] = isset($options[ $name_prefix . 'show_login_form' ]) ? $options[ $name_prefix . 'show_login_form' ] : 'off';
            $settings[ $name_prefix . 'show_login_form' ] = (isset($options[ $name_prefix . 'show_login_form' ]) && $options[ $name_prefix . 'show_login_form' ] == 'on') ? true : false;

            //Only for users role 
            $options[ $name_prefix . 'enable_for_user_role' ] = isset($options[ $name_prefix . 'enable_for_user_role' ]) ? $options[ $name_prefix . 'enable_for_user_role' ] : 'off';
            $settings[ $name_prefix . 'enable_for_user_role' ] = (isset($options[ $name_prefix . 'enable_for_user_role' ]) && $options[ $name_prefix . 'enable_for_user_role' ] == 'on') ? true : false;

            // User role
            $settings[ $name_prefix . 'user_roles' ] = (isset($options[ $name_prefix . 'user_roles' ]) &&  !empty($options[ $name_prefix . 'user_roles' ])) ?  $options[ $name_prefix . 'user_roles' ]  : array();

            // Message - Only for user role
            $settings[ $name_prefix . 'user_roles_message' ] = (isset($options[ $name_prefix . 'user_roles_message' ]) &&  $options[ $name_prefix . 'user_roles_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'user_roles_message' ] ) ) : '';

            // Only for selected user
            $options[ $name_prefix . 'enable_for_user' ] = (isset( $options[ $name_prefix . 'enable_for_user' ] ) && $options[ $name_prefix . 'enable_for_user' ] == 'on') ? stripslashes ( $options[ $name_prefix . 'enable_for_user' ] ) : 'off';
            $settings[ $name_prefix . 'enable_for_user' ] = (isset($options[ $name_prefix . 'enable_for_user' ]) && $options[ $name_prefix . 'enable_for_user' ] == 'on') ? true : false;

            //selected User
            $settings[ $name_prefix . 'user' ] = (isset( $options[ $name_prefix . 'user' ] ) && !empty($options[ $name_prefix . 'user' ])) ? $options[ $name_prefix . 'user' ] : array();

            // Message - Only for selected user
            $settings[ $name_prefix . 'user_message' ] = (isset( $options[ $name_prefix . 'user_message' ] ) && $options[ $name_prefix . 'user_message' ] != '') ? stripslashes ( $options[ $name_prefix . 'user_message' ] ) : '';

            //limitation takers count
            $options[ $name_prefix . 'enable_takers_count' ] = (isset( $options[ $name_prefix . 'enable_takers_count' ] ) && $options[ $name_prefix . 'enable_takers_count' ] == 'on') ? stripslashes ( $options[ $name_prefix . 'enable_takers_count' ] ) : 'off';
            $settings[ $name_prefix . 'enable_takers_count' ] = (isset($options[ $name_prefix . 'enable_takers_count' ]) && $options[ $name_prefix . 'enable_takers_count' ] == 'on') ? true : false;

            //Takers Count
            $settings[ $name_prefix . 'takers_count' ] = (isset($options[ $name_prefix . 'takers_count' ]) && $options[ $name_prefix . 'takers_count' ] != '') ? absint ( intval( $options[ $name_prefix . 'takers_count' ] ) ) : 1;

            // Password survey
            $options[ $name_prefix . 'enable_password' ] = ! isset( $options[ $name_prefix . 'enable_password' ] ) ? 'off' : $options[ $name_prefix .'enable_password' ];
            $settings[ $name_prefix . 'enable_password' ] = (isset( $options[ $name_prefix . 'enable_password' ] ) && $options[ $name_prefix . 'enable_password' ] == 'on') ? true : false;
            $settings[ $name_prefix . 'password_survey' ] = (isset( $options[ $name_prefix . 'password_survey' ] ) && $options[ $name_prefix . 'password_survey' ] != '') ? $options[ $name_prefix .'password_survey' ] : '';
            $settings[ $name_prefix . 'password_type' ]   = (isset( $options[ $name_prefix . 'password_type' ] )   && $options[ $name_prefix . 'password_type' ] != '') ? $options[ $name_prefix .'password_type' ] : 'general';
            
            // Message - Password
            $settings[ $name_prefix . 'password_message' ] = (isset($options[ $name_prefix . 'password_message' ]) &&  $options[ $name_prefix . 'password_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'password_message' ] ) ) : '';

            //Limit user by country
            $settings[ $name_prefix . 'enable_limit_by_country' ] = (isset($options[ $name_prefix .'enable_limit_by_country']) && $options[ $name_prefix .'enable_limit_by_country'] == 'on') ? true : false;
            $settings[ $name_prefix . 'limit_country' ] = (isset($options[ $name_prefix .'limit_country']) && $options[ $name_prefix .'limit_country'] != '') ? explode('***', esc_attr($options[ $name_prefix .'limit_country'])) : array();       
        // =============================================================
        // ===================    Limitation Tab     ===================
        // ========================    END    ==========================


        // =======================  //  ======================= // ======================= // ======================= // ======================= //


        // =============================================================
        // =====================    E-Mail Tab     =====================
        // ========================    START   =========================


            // Send Mail To User
            $options[ $name_prefix . 'enable_mail_user' ] = isset($options[ $name_prefix . 'enable_mail_user' ]) ? $options[ $name_prefix . 'enable_mail_user' ] : 'off';
            $settings[ $name_prefix . 'enable_mail_user' ] = (isset($options[ $name_prefix . 'enable_mail_user' ]) && $options[ $name_prefix . 'enable_mail_user' ] == 'on') ? true : false;

            // Send email to user | Custom | SendGrid
            $settings[ $name_prefix . 'send_mail_type' ] = (isset($options[ $name_prefix . 'send_mail_type' ]) && $options[ $name_prefix . 'send_mail_type' ] != '') ? stripslashes ( sanitize_text_field( $options[ $name_prefix . 'send_mail_type' ] ) ) : 'custom';
            
            // Email message
            $settings[ $name_prefix . 'mail_message' ] = (isset($options[ $name_prefix . 'mail_message' ]) &&  $options[ $name_prefix . 'mail_message' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'mail_message' ] ) ) : '';
        
            // Send single summary to users
            $options[ $name_prefix . 'summary_single_email_to_users' ] = isset($options[ $name_prefix . 'summary_single_email_to_users' ]) ? $options[ $name_prefix . 'summary_single_email_to_users' ] : 'on';
            $settings[ $name_prefix . 'summary_single_email_to_users' ] = (isset($options[ $name_prefix . 'summary_single_email_to_users' ]) && $options[ $name_prefix . 'summary_single_email_to_users' ] == 'on') ? true : false;

            // SendGrid template id
            $settings[ $name_prefix . 'sendgrid_template_id' ] = (isset($options[ $name_prefix . 'sendgrid_template_id']) && $options[ $name_prefix . 'sendgrid_template_id'] != '') ? $options[ $name_prefix . 'sendgrid_template_id'] : '';

            // Send email to admin
            $options[ $name_prefix . 'enable_mail_admin' ] = isset($options[ $name_prefix . 'enable_mail_admin' ]) ? $options[ $name_prefix . 'enable_mail_admin' ] : 'off';
            $settings[ $name_prefix . 'enable_mail_admin' ] = (isset($options[ $name_prefix . 'enable_mail_admin' ]) && $options[ $name_prefix . 'enable_mail_admin' ] == 'on') ? true : false;

            // Send email to site admin ( SuperAdmin )
            $options[ $name_prefix . 'send_mail_to_site_admin' ] = isset($options[ $name_prefix . 'send_mail_to_site_admin' ]) ? $options[ $name_prefix . 'send_mail_to_site_admin' ] : 'on';
            $settings[ $name_prefix . 'send_mail_to_site_admin' ] = (isset($options[ $name_prefix . 'send_mail_to_site_admin' ]) && $options[ $name_prefix . 'send_mail_to_site_admin' ] == 'on') ? true : false;

            // Additional emails
            $settings[ $name_prefix . 'additional_emails' ] = (isset($options[ $name_prefix . 'additional_emails' ]) && $options[ $name_prefix . 'additional_emails' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'additional_emails' ] ) ) : '';

            // Email message
            $settings[ $name_prefix . 'mail_message_admin' ] = (isset($options[ $name_prefix . 'mail_message_admin' ]) &&  $options[ $name_prefix . 'mail_message_admin' ] != '') ? stripslashes( wpautop( $options[ $name_prefix . 'mail_message_admin' ] ) ) : '';

            // Send submission report to admin
            $options[ $name_prefix . 'send_submission_report' ] = isset($options[ $name_prefix . 'send_submission_report' ]) ? $options[ $name_prefix . 'send_submission_report' ] : 'off';
            $settings[ $name_prefix . 'send_submission_report' ] = (isset($options[ $name_prefix . 'send_submission_report' ]) && $options[ $name_prefix . 'send_submission_report' ] == 'on') ? true : false;

            //---- Email configuration Start  ---- //

                // From email 
                $settings[ $name_prefix . 'email_configuration_from_email' ] = (isset($options[ $name_prefix . 'email_configuration_from_email' ]) &&  $options[ $name_prefix . 'email_configuration_from_email' ] != '') ? stripslashes( sanitize_email( $options[ $name_prefix . 'email_configuration_from_email' ] ) ) : '';

                // From name
                $settings[ $name_prefix . 'email_configuration_from_name' ] = (isset($options[ $name_prefix . 'email_configuration_from_name' ]) && $options[ $name_prefix . 'email_configuration_from_name' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'email_configuration_from_name' ] ) ) : '';

                // Subject
                $settings[ $name_prefix . 'email_configuration_from_subject' ] = (isset($options[ $name_prefix . 'email_configuration_from_subject' ]) && $options[ $name_prefix . 'email_configuration_from_subject' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'email_configuration_from_subject' ] ) ) : '';

                // Reply to email
                $settings[ $name_prefix . 'email_configuration_replyto_email' ] = (isset($options[ $name_prefix . 'email_configuration_replyto_email' ]) &&  $options[ $name_prefix . 'email_configuration_replyto_email' ] != '') ? stripslashes( sanitize_email( $options[ $name_prefix . 'email_configuration_replyto_email' ] ) ) : '';

                // Reply to name
                $settings[ $name_prefix . 'email_configuration_replyto_name' ] = (isset($options[ $name_prefix . 'email_configuration_replyto_name' ]) && $options[ $name_prefix . 'email_configuration_replyto_name' ] != '') ? stripslashes ( esc_attr( $options[ $name_prefix . 'email_configuration_replyto_name' ] ) ) : '';

            //---- Email configuration End ---- //


        // =============================================================
        // =====================    E-Mail Tab     =====================
        // ========================    END    ==========================

        return $settings;
    }

    public static function get_surveys($ordering = ''){
        global $wpdb;
        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "surveys";

        $sql = "SELECT id,title
                FROM {$surveys_table} WHERE `status` = 'published'";

        if($ordering != ''){
            $sql .= ' ORDER BY id '.$ordering;
        }

        $surveys = $wpdb->get_results( $sql , "ARRAY_A" );

        return $surveys;
    }

    public static function get_survey_current_column( $survey_id , $column, $status = 'published'){
        global $wpdb;
        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "surveys";

        $sql = "SELECT ".sanitize_text_field($column)."
                FROM {$surveys_table} WHERE `id`= ".sanitize_text_field($survey_id)." AND `status` = '".sanitize_text_field($status)."' ";
        $current_column = $wpdb->get_var( $sql );

        return $current_column;
    }


    public static function get_survey_by_id( $id ){
        global $wpdb;
        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "surveys";

        $sql = "SELECT *
                FROM {$surveys_table}
                WHERE id=" . esc_sql( absint( $id ) );

        $survey = $wpdb->get_row( $sql );

        return $survey;
    }

    public static function get_survey_category_by_id( $id ){
        global $wpdb;
        $survey_cat_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "survey_categories";

        $sql = "SELECT *
                FROM {$survey_cat_table}
                WHERE id=" . esc_sql( absint( $id ) );

        $category = $wpdb->get_row( $sql );

        return $category;
    }

    public static function get_question_category_by_id( $id ){
        global $wpdb;
        $question_cat_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "question_categories";

        $sql = "SELECT *
                FROM {$question_cat_table}
                WHERE id=" . esc_sql( absint( $id ) );

        $category = $wpdb->get_row( $sql );

        return $category;
    }

    public static function get_question_by_id( $id, $return_as = 'object' ){
        global $wpdb;
        $questions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "questions";
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";

		$return_type = 'OBJECT';
		if( $return_as == 'array' ){
			$return_type = 'ARRAY_A';
		}

        $sql = "SELECT *
                FROM {$questions_table}
                WHERE id=" . esc_sql( absint( $id ) ) . " LIMIT 1";

        $question = $wpdb->get_row( $sql, $return_type );

        $sql = "SELECT *
                FROM {$answers_table}
                WHERE question_id=" . esc_sql( $id );

        $answers = $wpdb->get_results( $sql, $return_type );

	    if( $return_as == 'array' ) {
		    $question['answers'] = $answers;
	    }else{
		    $question->answers = $answers;
	    }

        return $question;
    }

    /**
	 * @param $id
	 *
	 * @return array
	 */
    public static function get_section_questions_ids_by_section_id( $id ) {
        global $wpdb;

        if ( empty( $id ) ) {
            return array();
        }

	    $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";

        $sql = "SELECT id FROM {$table} WHERE `section_id` = " . esc_sql( $id ) ." ORDER BY ordering;";
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        if(! empty( $result ) ){
	        $questions_ids = array();
	        foreach ( $result as $qdata ){
		        $questions_ids[] = $qdata['id'];
	        }
            return $questions_ids;
        }

        return array();
    }

    public static function ays_survey_question_results_by_id( $survey_id, $questions_ids = null, $filters = array() ){
        global $wpdb;

        $filters_where_condition = "";
        $sql_for_post_filter = '';
        $submission_count_and_ids = Survey_Maker_Data::get_submission_count_and_ids( $survey_id, $filters );
        $submission_ids_arr = ( isset( $submission_count_and_ids['submission_ids_arr'] ) && ! empty( $submission_count_and_ids['submission_ids_arr'] ) ) ? Survey_Maker_Data::recursive_sanitize_text_field( $submission_count_and_ids['submission_ids_arr'] ) : array();
        $filters['filter_submission_ids'] = $submission_ids_arr;
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = self::get_filters_where_condition($filters, true);
            if (!is_null($filters['filter_submission_ids']) && !empty($filters['filter_submission_ids'])) {
                $sql_for_post_filter = ' AND `submission_id` IN('.esc_sql( implode( ',', $filters['filter_submission_ids'] ) ).') ';
            }
        }

        if($survey_id === null){
            return array(
                'total_count' => 0,
                'questions' => array()
            );
        }

	    $question_by_ids = Survey_Maker_Data::get_question_by_ids( $questions_ids );

		$question_obj = $question_by_ids[0];

        $submitions_questiions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $answer_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";
        $question_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $survey_section_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections";
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $sql_for_current_user = "";
        $sql_for_current_user_general = "";
        $sql_for_recent_survey = "";
        $sql_for_recent_survey_general = "";


        $sql_for_current_user = isset($user_id) && $user_id != "" ? "AND s_q.user_id = ".$user_id : "";
        $sql_for_current_user_general = isset($user_id) && $user_id != "" ? "AND `user_id` = ".$user_id : "";
        

        $survey_options_sql = "SELECT options FROM {$surveys_table} WHERE id =". absint( $survey_id );
        $survey_options = $wpdb->get_var( $survey_options_sql );

        $survey_options = isset( $survey_options ) && $survey_options != '' ? json_decode( $survey_options, true ) : array();

        // Allow HTML in answers
        $survey_options[ 'survey_allow_html_in_answers' ] = isset($survey_options[ 'survey_allow_html_in_answers' ]) ? $survey_options[ 'survey_allow_html_in_answers' ] : 'off';
        $allow_html_in_answers = (isset($survey_options[ 'survey_allow_html_in_answers' ]) && $survey_options[ 'survey_allow_html_in_answers' ] == 'on') ? true : false;

        $ays_question_id = $questions_ids;

        if($ays_question_id == null){
            return array(
                'total_count' => 0,
                'questions' => array()
            );
        }

	    $radio_question_types = array(
		    "radio",
		    "select",
		    "yesorno",
	    );
	    $text_question_types = array(
		    "text",
		    "short_text",
		    "number",
		    "phone",
		    "email",
		    "name",
	    );

		$answer_id_result = array();
		// if( in_array( $question_obj->type, $radio_question_types ) ) {
			$questions_ids_arr = $ays_question_id;
			$answer_id         = "SELECT a.id, a.answer, COUNT(s_q.answer_id) AS answer_count
                    FROM {$answer_table} AS a
                    LEFT JOIN {$submitions_questiions_table} AS s_q 
                    ON a.id = s_q.answer_id
                    WHERE s_q.survey_id=" . absint( $survey_id ) . " AND s_q.question_id IN ( " . implode( ',', $questions_ids ) . " ) " . $sql_for_post_filter . $sql_for_current_user . $sql_for_recent_survey . "
                    GROUP BY a.id";

			$answer_id_result = $wpdb->get_results( $answer_id, 'ARRAY_A' );
		// }

		$for_checkbox_result = array();
		if( $question_obj->type == 'checkbox' ) {
			$for_checkbox = "SELECT a.id, a.answer, COUNT(s_q.answer_id) AS answer_count
                    FROM {$answer_table} AS a
                    LEFT JOIN {$submitions_questiions_table} AS s_q 
                    ON a.id = s_q.answer_id OR FIND_IN_SET( a.id, s_q.user_answer )
                    WHERE s_q.type = 'checkbox' AND s_q.question_id IN ( " . implode( ',', $questions_ids ) . " )
                    AND s_q.survey_id=" . absint( $survey_id ) . " ". $sql_for_post_filter . $sql_for_current_user . $sql_for_recent_survey . "                    
                    GROUP BY a.id";

			$for_checkbox_result = $wpdb->get_results( $for_checkbox, 'ARRAY_A' );
		}

		$matrix_answers_columns  = array();
		if( $question_obj->type == 'matrix_scale' ) {
			$for_matrix_scale        = "SELECT `question_id` , `answer_id`, `user_answer`, COUNT(`user_answer`) AS col_count
                                FROM {$submitions_questiions_table}
                                WHERE `type` = 'matrix_scale' AND `survey_id`=" . absint( $survey_id ) . " AND question_id IN ( " . implode( ',', $questions_ids ) . " ) " . $sql_for_post_filter . $sql_for_current_user_general . $sql_for_recent_survey_general . "
                                GROUP BY `question_id` , `answer_id`, `user_answer`";
			$for_matrix_scale_result = $wpdb->get_results( $for_matrix_scale, 'ARRAY_A' );
			foreach ( $for_matrix_scale_result as $mat_key => $mat_val ) {
				$matrix_answers_columns[ $mat_val['question_id'] ][] = array(
					'answer_id'   => $mat_val['answer_id'],
					'user_answer' => $mat_val['user_answer'],
					'col_count'   => $mat_val['col_count'],
				);
			}
		}

        // for matrix scale checkbox
		$matrix_checkbox_answers_columns       = array();
		$matrix_checkbox_answers_columns_new   = array();
		$matrix_checkbox_answers_columns_count = array();
		$count_matrix_column_values            = array();
		if( $question_obj->type == 'matrix_scale_checkbox' ) {
			$for_matrix_scale_checkbox             = "SELECT `question_id` , `answer_id`, `user_answer`
                                FROM {$submitions_questiions_table}
                                WHERE `type` = 'matrix_scale_checkbox' AND `survey_id`=" . absint( $survey_id ) . " AND question_id IN ( " . implode( ',', $questions_ids ) . " ) " . $sql_for_post_filter . $sql_for_current_user_general . $sql_for_recent_survey_general . "
                                GROUP BY `question_id` , `user_answer` , `answer_id`, `submission_id` ";
			$for_matrix_scale_result_checkbox      = $wpdb->get_results( $for_matrix_scale_checkbox, 'ARRAY_A' );
			foreach ( $for_matrix_scale_result_checkbox as $mat_key => $mat_val ) {
				$answered_columns = explode( ",", $mat_val['user_answer'] );
				foreach ( $answered_columns as $a_key => $b_key ) {
					$count_matrix_column_values[ $mat_val['answer_id'] ][] = $b_key;
				}
				$matrix_checkbox_answers_columns[ $mat_val['question_id'] ][ $mat_val['answer_id'] ] = array_count_values( $count_matrix_column_values[ $mat_val['answer_id'] ] );
			}

			foreach ( $matrix_checkbox_answers_columns as $very_new_key => $very_new_value ) {
				foreach ( $very_new_value as $t => $z ) {
					$matrix_checkbox_answers_columns_count[ $t ] = array_sum( $z );
					foreach ( $z as $r => $y ) {
						$matrix_checkbox_answers_columns_new[ $very_new_key ][] = array(
							'answer_id'   => strval( $t ),
							'user_answer' => strval( $r ),
							'col_count'   => strval( $y ),
						);

					}
				}
			}
		}

        // Star list and Slider list question type
        $listed_answers = array();
		if( $question_obj->type == 'slider_list' || $question_obj->type == 'star_list' ) {
            $group_by_sub_id = $sql_for_post_filter != '' ? ', `submission_id`' : '';
			$for_star_list     = "SELECT `question_id` ,
                                 `answer_id`,
                                 `type`,
                                 COUNT(`user_answer`) AS answered_count,
                                 SUM(`user_answer`)   AS answered_sum
                                 FROM {$submitions_questiions_table}
                                 WHERE `type` = '". $question_obj->type . "'
                                 AND `survey_id`=" . absint( $survey_id ) . " ". $sql_for_post_filter . $sql_for_current_user_general . $sql_for_recent_survey_general . "
                                 GROUP BY `question_id` , `answer_id` ".$group_by_sub_id;
			$for_listed_result = $wpdb->get_results( $for_star_list, 'ARRAY_A' );

			foreach ( $for_listed_result as $mat_key => $mat_val ) {
				$listed_answers[ $mat_val['type'] ][ $mat_val['question_id'] ][ $mat_val['answer_id'] ] = array(
					'answered_count' => $mat_val['answered_count'],
					'answered_sum'   => $mat_val['answered_sum'],
					'answer_id'      => $mat_val['answer_id'],
				);
			}
		}

        $answer_count = array();
        $question_type = '';
        foreach ($answer_id_result as $key => $answer_count_by_id) {
            $ays_survey_answer_count = (isset($answer_count_by_id['answer_count']) && $answer_count_by_id['answer_count'] !="") ? absint(intval($answer_count_by_id['answer_count'])) : '';
            $answer_count[$answer_count_by_id['id']] = $ays_survey_answer_count;
        }

        foreach ($for_checkbox_result as $key => $answer_count_by_id) {
            $ays_survey_answer_count = (isset($answer_count_by_id['answer_count']) && $answer_count_by_id['answer_count'] !="") ? absint(intval($answer_count_by_id['answer_count'])) : '';
            $answer_count[$answer_count_by_id['id']] = $ays_survey_answer_count;
        }


        $select_answer_q_type = "SELECT type, user_answer, id, question_id
            FROM {$submitions_questiions_table}
            WHERE user_answer != '' 
                AND type != 'checkbox' AND question_id IN ( ". implode(',', $questions_ids ) ." )
                " . $sql_for_post_filter .$sql_for_current_user_general . $sql_for_recent_survey_general." 
                AND survey_id=". absint( $survey_id );

        $submission_answer_other = "SELECT question_id, answer_id, user_variant
            FROM {$submitions_questiions_table}
            WHERE user_variant != '' AND question_id IN ( ". implode(',', $questions_ids ) ." )
                ". $sql_for_post_filter .$sql_for_current_user_general . $sql_for_recent_survey_general."
                AND survey_id=". absint( $survey_id );


        $result_answers_q_type = $wpdb->get_results($select_answer_q_type,'ARRAY_A');
        $result_answers_other = $wpdb->get_results($submission_answer_other,'ARRAY_A');
        $text_answer = array();
        foreach($result_answers_q_type as $key => $result_answer_q_type){
            $text_answer[$result_answer_q_type['type']][$result_answer_q_type['question_id']][] = $result_answer_q_type['user_answer'];
        }

        $other_answers = array();
        $other_answers_all = array();
        foreach($result_answers_other as $key => $result_answer_other){
            if( intval( $result_answer_other['answer_id'] ) == 0 ){
                $other_answers[$result_answer_other['question_id']][] = $result_answer_other['user_variant'];
            }
            $other_answers_all[$result_answer_other['question_id']][] = $result_answer_other['user_variant'];
        }

        $text_types = array(
            'text',
            'short_text',
            'number',
            'phone',
            'name',
            'email',
            'linear_scale',
            'star',
            'date',
            'time',
            'date_time',
            'range',
            'upload',
            'hidden',
        );

        //Question types different charts
        $ays_submissions_count  = array();
        $question_results = array();

        $total_count = 0;
        foreach ($question_by_ids as $key => $question) {

            // Matrix Scale
            $question_m_type = isset($question->type) && $question->type == "matrix_scale" ? true : false;
            $question_m_c_type = isset($question->type) && $question->type == "matrix_scale_checkbox" ? true : false;
            $matrix_answer_ids = array();
            $matrix_columns = array();
            $matrix_answers = array();
            $question_cloumns = array();

            $answers = $question->answers;
            $question_id = $question->id;
            $question_title = $question->question;
            $question_description = $question->question_description;

            $question_options = $question->options;
            $question_options = json_decode($question_options, true);
            if($question_m_type || $question_m_c_type){
                $question_cloumns = array();
                if( isset( $question_options['matrix_columns'] ) ){
                    foreach ($question_options['matrix_columns'] as $column_key => &$column_val) {
                        $column_val = stripslashes( esc_attr($column_val) );
                    }                    
                    if( is_array( $question_options['matrix_columns'] ) ){
                        $question_cloumns = $question_options['matrix_columns'];
                    }else{
                        $question_cloumns = json_decode($question_options['matrix_columns'], true);
                    }
                }
                if($question_m_c_type){
                    $collected_matrix_answers_columns = $matrix_checkbox_answers_columns_new;
                }
                else{
                    $collected_matrix_answers_columns = $matrix_answers_columns;

                }
                if(isset($collected_matrix_answers_columns[$question_id])){
                    foreach($collected_matrix_answers_columns[$question_id] as $mat_key => $mat_val){
                        if(isset($question_cloumns[$mat_val['user_answer']])){
                            $matrix_columns[$mat_val['user_answer']][$mat_val['answer_id']] = $mat_val['col_count'];
                        }
                    }
                }
            }

            //questions
            $question_results[$question_id]['question_id'] = $question_id;
            $question_results[$question_id]['question'] = $question_title;
            $question_results[$question_id]['question_description'] = $question_description;
            $ays_answer = array();
            $ays_all_answer = array();
            $question_answer_ids = array();
            foreach ($answers as $key => $answer) {
                $answer_id = $answer->id;
                $answer_title = $answer->answer;

                $matrix_answer_ids[] = $answer_id;

                $ays_answer[$answer_id] = isset( $answer_count[$answer_id] ) ? $answer_count[$answer_id] : 0;
                if($question_m_c_type){
                    $ays_all_answer[] = isset( $matrix_checkbox_answers_columns_count[$answer_id] ) ? $matrix_checkbox_answers_columns_count[$answer_id] : 0;
                }
                else{
                    $ays_all_answer[] = isset( $answer_count[$answer_id] ) ? $answer_count[$answer_id] : 0;

                }
                $question_answer_ids[$answer_id] = $allow_html_in_answers ? sanitize_text_field( $answer_title ) : $answer_title;
            }

            $matrix_header = array('');
            $matrix_body = array();
            foreach($matrix_answer_ids as $row_key => $row_val){
                $matrix_body[$row_val][] = $question_answer_ids[$row_val];
            }

            if(isset($question_cloumns)){
                foreach($question_cloumns as $col_key => $col_val){
                    if(!isset($matrix_columns[$col_key])){
                        $matrix_columns[$col_key] = array();
                    }

                    foreach($matrix_answer_ids as $row_key => $row_val){
                        if(!isset($matrix_columns[$col_key][$row_val])){
                            $matrix_columns[$col_key][$row_val] = 0;
                        }
                        $matrix_body[$row_val][] = intval( $matrix_columns[$col_key][$row_val] );
                    }

                    $matrix_header[] = $col_val;
                }
            }

            $matrix_result_all = array_merge( array( $matrix_header ), $matrix_body );

            //sum of submissions count per questions
            if($question->type == "checkbox"){
                $sub_checkbox_count = self::ays_survey_get_submission_count($question->id, $question->type, $survey_id, $sql_for_current_user_general, $sql_for_recent_survey_general, $filters);
                $sum_of_count = $sub_checkbox_count;
            }elseif( $question_m_type || $question_m_c_type){
                $sub_checkbox_count = self::ays_survey_get_submission_count($question->id, $question->type, $survey_id, $sql_for_current_user_general, $sql_for_recent_survey_general, $filters);
                $sum_of_count = $sub_checkbox_count;
            }elseif( $question->type == 'star_list' || $question->type == 'slider_list' ){
                $sum_of_count = self::ays_survey_get_submission_count($question->id, $question->type, $survey_id, $sql_for_current_user_general, $sql_for_recent_survey_general, $filters);
            }else{
                $sum_of_count = array_sum( array_values( $ays_answer ) );
            }

            $question_results[$question_id]['otherAnswers'] = isset( $other_answers[$question->id] ) ? $other_answers[$question->id] : array();

            if( in_array( $question->type, $text_types ) ){
                $question_ls_options = json_decode($question->options, true);
                if($question->type == 'linear_scale'){
                    $scale_from     = isset($question_ls_options['linear_scale_1']) && $question_ls_options['linear_scale_1'] != "" ? stripslashes($question_ls_options['linear_scale_1']) : "";
                    $scale_to       = isset($question_ls_options['linear_scale_2']) && $question_ls_options['linear_scale_2'] != "" ? stripslashes($question_ls_options['linear_scale_2']) : "";
                    $scale_length   = isset($question_ls_options['scale_length']) && $question_ls_options['scale_length'] != "" ? $question_ls_options['scale_length'] : "";
                    $question_results[$question_id]['labels'] = array(
                        'from'      => $scale_from,
                        'to'        => $scale_to,
                        'length'    => $scale_length
                    );
                }
                if($question->type == 'star'){
                    $scale_from     = isset($question_ls_options['star_1']) && $question_ls_options['star_1'] != "" ? stripslashes($question_ls_options['star_1']) : "";
                    $scale_to       = isset($question_ls_options['star_2']) && $question_ls_options['star_2'] != "" ? stripslashes($question_ls_options['star_2']) : "";
                    $scale_length   = isset($question_ls_options['star_scale_length']) && $question_ls_options['star_scale_length'] != "" ? $question_ls_options['star_scale_length'] : "";
                    $question_results[$question_id]['labels'] = array(
                        'from'      => $scale_from,
                        'to'        => $scale_to,
                        'length'    => $scale_length
                    );
                }


                $question_results[$question_id]['answers'] = isset( $text_answer[$question->type] ) ? $text_answer[$question->type] : '';
                $question_results[$question_id]['answerTitles'] = isset( $text_answer[$question->type] ) ? $text_answer[$question->type] : '';
                $question_results[$question_id]['sum_of_answers_count'] = isset( $text_answer[$question->type][$question->id] ) ? count( $text_answer[$question->type][$question->id] ) : 0;
                $question_results[$question_id]['sum_of_same_answers']  = isset( $text_answer[$question->type][$question->id] ) ? array_count_values( $text_answer[$question->type][$question->id] ) : 0;

                if($question->type == "upload"){
                    if(isset($text_answer[$question->type])){
                        if(isset($text_answer[$question->type][$question->id])){
                            $upload_answers = $text_answer[$question->type][$question->id];
                            foreach($upload_answers as $u_key => $u_value){
                                $u_answer = "";
                                if($u_value != "0" && $u_value != ""){
                                    $u_answer = $u_value;
                                }
                                if($u_answer){
                                    $question_results[$question_id]['answers_name'][$question->id][] = wp_basename($u_answer);
                                }
                                else{
                                    $question_results[$question_id]['answers_name'][$question->id][] = "";
                                }
                            }
                        }
                    }
                    else{
                        $question_results[$question_id]['answers_name'][$question->id][] = "";
                    }
                }
            }else{
                $question_results[$question_id]['answers'] = $ays_answer;
                $question_results[$question_id]['answerTitles'] = $question_answer_ids;
                $question_results[$question_id]['sum_of_answers_count'] = $sum_of_count;
                $question_results[$question_id]['allAnswers'] = $ays_all_answer;
                if( $sum_of_count == 0 ){
                    $question_results[$question_id]['answers'] = array();
                }
            }

            // Answers for charts
            if( !empty( $question_results[$question_id]['otherAnswers'] ) ){
                $question_results[$question_id]['answers'][0] = count( $question_results[$question_id]['otherAnswers'] );
                $question_results[$question_id]['answerTitles'][0] = __( '"Other" answer(s)', SURVEY_MAKER_NAME );
                $question_results[$question_id]['same_other_count'] = array_count_values( $question_results[$question_id]['otherAnswers'] );

                if($question->type == "radio" || $question->type == "yesorno"){
                    $question_results[$question_id]['sum_of_answers_count'] += count( $question_results[$question_id]['otherAnswers'] );
                }
            }
            //

            $total_count += intval( $question_results[$question_id]['sum_of_answers_count'] );
            $question_results[$question_id]['question_type'] = $question->type;
            $question_results[$question_id]['matrix_data'] = $matrix_result_all;
            if($question->type == "star_list"){
                $question_results[$question_id]['star_list_data'] = isset( $listed_answers['star_list'] ) ? $listed_answers['star_list'] : "";
                $question_results[$question_id]['question_options'] = $question_options['star_list_stars_length'];
            }
            if($question->type == "slider_list"){
                $question_results[$question_id]['slider_list_data'] = isset( $listed_answers['slider_list'] ) ? $listed_answers['slider_list'] : array();
                $question_results[$question_id]['slider_list_range_length'] = $question_options['slider_list_range_length'];
                $question_results[$question_id]['slider_list_range_step_length'] = $question_options['slider_list_range_step_length'];
                $question_results[$question_id]['slider_list_range_min_value'] = $question_options['slider_list_range_min_value'];
                $question_results[$question_id]['slider_list_range_default_value'] = $question_options['slider_list_range_default_value'];
                $question_results[$question_id]['slider_list_range_calculation_type'] = $question_options['slider_list_range_calculation_type'];
            }
            if($question->type == "range"){
                $question_results[$question_id]['range_min_value'] = $question_options['range_min_value'];
                $question_results[$question_id]['range_length'] = $question_options['range_length'];
            }
        }

        return array(
            'total_count' => $total_count,
            'questions' => $question_results
        );
    }

    public static function get_question_by_ids( $ids , $is_google_sheet = false, $is_email = false, $is_duplicate = false, $custom_order = false ){
        global $wpdb;
        $questions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "questions";
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";

        $order_by = '';
        if($is_email){
            $order_by .= "section_id,";
        }
        if($is_duplicate){
            $order_by .= 'id';
        }
        else{
            $order_by .= 'ordering';
        }

        if ( is_array( $ids ) ) {
            $qids = esc_sql( implode( ',', $ids ) );
        } else {
            $qids = esc_sql( $ids );
        }

        if($custom_order && !$is_email){
            $order_by = "FIELD(id," . esc_sql( $qids ) . ")";
        }

        $sql = "SELECT *
                FROM {$questions_table}
                WHERE id IN (" . esc_sql( $qids ) . ")
                ORDER BY ".$order_by;

        $questions = $wpdb->get_results( $sql );
        $questions_with_id_keys = array();
        foreach ( $questions as $key => &$question ) {

            $sql = "SELECT *
                    FROM {$answers_table}
                    WHERE question_id=" . esc_sql( absint( $question->id ) ) ."
                    ORDER BY ordering";

            $answers = $wpdb->get_results( $sql );

            $question->answers = $answers;
            if($is_google_sheet){
                $questions_with_id_keys[$question->id] = $question;
            }
        }

        if($is_google_sheet && !empty($questions_with_id_keys)){
            return $questions_with_id_keys;
        }

        return $questions;
    }

    public static function get_questions_titles_by_ids( $ids ){
        global $wpdb;
        $questions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "questions";
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";

        $qids = esc_sql( implode( ',', $ids ) );

        $sql = "SELECT *
                FROM {$questions_table}
                WHERE id IN (" . $qids . ")
                ORDER BY ordering";

        $questions = $wpdb->get_results( $sql );
        $questions_arr = array();
        foreach ( $questions as $key => $question ) {

            $questions_arr[ $question->id ] = $question->question;

        }

        return $questions_arr;
    }

    public static function get_section_by_id($id, $return_as = 'object'){
        global $wpdb;
        $sections_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "sections";
        $return_type = 'OBJECT';
	    if( $return_as == 'array' ){
		    $return_type = 'ARRAY_A';
	    }


        $sid = esc_sql( absint( $id ) );

        $sql = "SELECT *
                FROM {$sections_table}
                WHERE id={$sid}
                ORDER BY ordering LIMIT 1";

        $section = $wpdb->get_row( $sql, $return_type );

        return $section;
    }

    public static function get_answer_by_id($id){
        global $wpdb;
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";

        $sql = "SELECT *
                FROM {$answers_table}
                WHERE id=" . esc_sql( absint( $id ) );

        $answer = $wpdb->get_row( $sql );

        return $answer;
    }
    
    public static function get_answer_var_by_id($id , $column){
        global $wpdb;
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";

        $sql = "SELECT ".$column."
                FROM {$answers_table}
                WHERE id=" . esc_sql( absint( $id ) );

        $answer = $wpdb->get_var($sql);

        return $answer;
    }
    
    public static function get_question_var_by_id($id , $column ){
        global $wpdb;
        $questions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "questions";


        $sql = "SELECT ".$column." FROM " . $questions_table. " WHERE id = " . $id;

        $questions_var = $wpdb->get_var($sql);

        return $questions_var;
    }

    public static function get_answers_by_question_id($id){
        global $wpdb;
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";

        $sql = "SELECT *
                FROM {$answers_table}
                WHERE question_id=" . esc_sql( absint( $id ) ) . "
                ORDER BY ordering";

        $answers = $wpdb->get_results( $sql, 'ARRAY_A' );

        if(! empty($answers) ){
            return $answers;
        }

        return array();
    }

    public static function get_survey_questions_count($id){
        global $wpdb;
        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "surveys";

        $sql = "SELECT `questions_count`
                FROM {$surveys_table}
                WHERE id=" . esc_sql( absint( $id ) );

        $questions_str = $wpdb->get_var( $sql );
        $count = intval( $questions_str );

        return $count;
    }

    public static function get_sections_by_survey_id( $ids ) {
        global $wpdb;
        if (empty($ids)) {
            return array();
        }
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections";

        $sql = "SELECT * FROM {$table} WHERE `id` IN (" . esc_sql( $ids ) .") ORDER BY `ordering`;";
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        if(! empty($result) ){
            return $result;
        }

        return array();
    }

    public static function get_questions_by_section_id( $section_id, $question_ids, $in_submission = false ) {
        global $wpdb;
        if (empty($question_ids) || empty($section_id)) {
            return array();
        }
        $submission_query = '';
        if($in_submission){
            $submission_query = "AND type != 'html'";
        }
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";

        $sql = "SELECT * FROM {$table} WHERE `section_id` = ". absint( $section_id ) ." AND `id` IN (" . esc_sql( $question_ids ) .") ".$submission_query." ORDER BY ordering;";
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        if(! empty($result) ){
            return $result;
        }

        return array();
    }

    public static function get_answers_by_question_id_aro( $question_id ) {
        global $wpdb;
        if (empty($question_id)) {
            return false;
        }
        $table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";

        $sql = "SELECT * FROM {$table} WHERE `question_id` = ". absint( $question_id );
        $result = $wpdb->get_results( $sql, 'ARRAY_A' );

        return $result;
    }

    public static function sort_array_keys_by_array($array, $orderArray) {
        $ordered = array();
        foreach ($orderArray as $key) {
            if (array_key_exists('ays-question-'.$key, $array)) {
                $ordered['ays-question-'.$key] = $array['ays-question-'.$key];
                unset($array['ays-question-'.$key]);
            }
        }
        return $ordered + $array;
    }

    public static function sort_simple_array_keys_by_array($array, $orderArray) {
        $ordered = array();
        foreach ($orderArray as $key) {
            if (array_key_exists($key, $array)) {
                $ordered[$key] = $array[$key];
                unset($array[$key]);
            }
        }
        return $ordered + $array;
    }

    public static function replace_message_variables($content, $data){
        foreach($data as $variable => $value){
            $content = str_replace("%%".$variable."%%", $value, $content);
        }
        return $content;
    }

    public static function get_question_type_question_id($question_id){
        global $wpdb;
        $questions_table = $wpdb->prefix . "socialsurv_questions";
        $question_id = absint(intval($question_id));
        
        $question_type = $wpdb->get_var("SELECT type FROM {$questions_table} WHERE id={$question_id};");
        if($question_type == ''){
            $question_type = 'radio';
        }
        
        return $question_type;
    }

    public static function hex2rgba($color, $opacity = false){

        $default = 'rgb(0,0,0)';

        //Return default if no color provided
        if (empty($color))
            return $default;

        //Sanitize $color if "#" is provided
        if ($color[0] == '#') {
            $color = substr($color, 1);
        }else{
            return $color;
        }

        //Check if color has 6 or 3 characters and get values
        if (strlen($color) == 6) {
            $hex = array($color[0] . $color[1], $color[2] . $color[3], $color[4] . $color[5]);
        } elseif (strlen($color) == 3) {
            $hex = array($color[0] . $color[0], $color[1] . $color[1], $color[2] . $color[2]);
        } else {
            return $default;
        }

        //Convert hexadec to rgb
        $rgb = array_map('hexdec', $hex);

        //Check if opacity is set(rgba or rgb)
        if ($opacity) {
            if (abs($opacity) > 1)
                $opacity = 1.0;
            $output = 'rgba(' . implode(",", $rgb) . ',' . $opacity . ')';
        } else {
            $output = 'rgb(' . implode(",", $rgb) . ')';
        }

        //Return rgb(a) color string
        return $output;
    }

    public static function rgb2hex( $rgba ) {
        if ( strpos( $rgba, '#' ) === 0 ) {
            return $rgba;
        }

        preg_match( '/^rgba?[\s+]?\([\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?,[\s+]?(\d+)[\s+]?/i', $rgba, $by_color );

        return sprintf( '#%02x%02x%02x', $by_color[1], $by_color[2], $by_color[3] );
    }

    public static function secondsToWords($seconds){
        $ret = "";

        /*** get the days ***/
        $days = intval(intval($seconds) / (3600 * 24));
        if ($days > 0) {
            $ret .= "$days " . __( 'days', SURVEY_MAKER_NAME ) . ' ';
        }

        /*** get the hours ***/
        $hours = intval(round($seconds / 3600) % 24);
        if ($hours > 0) {
            $ret .= "$hours " . __( 'hours', SURVEY_MAKER_NAME ) . ' ';
        }

        /*** get the minutes ***/
        $minutes = intval(round($seconds / 60) % 60);
        if ($minutes > 0) {
            $ret .= "$minutes " . __( 'minutes', SURVEY_MAKER_NAME ) . ' ';
        }

        /*** get the seconds ***/
        $seconds = intval($seconds) % 60;
        if ($seconds > 0) {
            $ret .= "$seconds " . __( 'seconds', SURVEY_MAKER_NAME );
        }

        return $ret;
    }

    public static function secondsToFormat($seconds){
        $ret = "";

        /*** get the days ***/
        $days = intval(intval($seconds) / (3600 * 24));
        if ($days > 0) {
            $ret .= "$days " . __( 'd', SURVEY_MAKER_NAME ) . ' ';
        }

        /*** get the hours ***/
        $hours = intval(round($seconds / 3600) % 24);
        if ($hours > 0) {
            $ret .= "$hours " . __( 'h', SURVEY_MAKER_NAME ) . ' ';
        }

        /*** get the minutes ***/
        $minutes = intval(round($seconds / 60) % 60);
        if ($minutes > 0) {
            $ret .= "$minutes " . __( 'm', SURVEY_MAKER_NAME ) . ' ';
        }

        /*** get the seconds ***/
        $seconds = intval($seconds) % 60;
        if ($seconds > 0) {
            $ret .= "$seconds " . __( 's', SURVEY_MAKER_NAME );
        }

        return $ret;
    }

    public static function get_limit_user_by_ip($id){
        global $wpdb;
        $table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions" );
        $user_ip = self::get_user_ip();
        $sql = "SELECT COUNT(*)
                FROM `{$table}`
                WHERE `user_ip` = '". esc_sql( $user_ip ) ."'
                  AND `survey_id` = ". absint( $id );
        $result = intval($wpdb->get_var($sql));
        return $result;
    }

    public static function get_limit_user_by_id($survey_id, $user_id){
        global $wpdb;
        $table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions" );
        $sql = "SELECT COUNT(*)
                FROM `{$table}`
                WHERE `user_id` = ". absint( $user_id ) ."
                  AND `survey_id` =  ". absint( $survey_id );
        $result = intval($wpdb->get_var($sql));
        return $result;
    }

    public static function get_user_ip(){
        $ipaddress = '';
        if (getenv('REMOTE_ADDR'))
            $ipaddress = getenv('REMOTE_ADDR');
        elseif (getenv('HTTP_CLIENT_IP'))
            $ipaddress = getenv('HTTP_CLIENT_IP');
        else if (getenv('HTTP_X_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
        else if (getenv('HTTP_X_FORWARDED'))
            $ipaddress = getenv('HTTP_X_FORWARDED');
        else if (getenv('HTTP_FORWARDED_FOR'))
            $ipaddress = getenv('HTTP_FORWARDED_FOR');
        else if (getenv('HTTP_FORWARDED'))
            $ipaddress = getenv('HTTP_FORWARDED');
        else
            $ipaddress = 'UNKNOWN';
        return $ipaddress;
    }

    public static function get_time_difference($strStart, $strEnd){
        $dteStart = new DateTime($strStart);
        $dteEnd = new DateTime($strEnd);
        $texts = array(
            'year' => __( "year", AYS_QUIZ_NAME ),
            'years' => __( "years", AYS_QUIZ_NAME ),
            'month' => __( "month", AYS_QUIZ_NAME ),
            'months' => __( "months", AYS_QUIZ_NAME ),
            'day' => __( "day", AYS_QUIZ_NAME ),
            'days' => __( "days", AYS_QUIZ_NAME ),
            'hour' => __( "hour", AYS_QUIZ_NAME ),
            'hours' => __( "hours", AYS_QUIZ_NAME ),
            'minute' => __( "minute", AYS_QUIZ_NAME ),
            'minutes' => __( "minutes", AYS_QUIZ_NAME ),
            'second' => __( "second", AYS_QUIZ_NAME ),
            'seconds' => __( "seconds", AYS_QUIZ_NAME ),
        );
        $interval = $dteStart->diff($dteEnd);
        $return = '';

        if ($v = $interval->y >= 1) $return .= $interval->y ." ". $texts[self::pluralize_new($interval->y, 'year')] . ' ';
        if ($v = $interval->m >= 1) $return .= $interval->m ." ". $texts[self::pluralize_new($interval->m, 'month')] . ' ';
        if ($v = $interval->d >= 1) $return .= $interval->d ." ". $texts[self::pluralize_new($interval->d, 'day')] . ' ';
        if ($v = $interval->h >= 1) $return .= $interval->h ." ". $texts[self::pluralize_new($interval->h, 'hour')] . ' ';
        if ($v = $interval->i >= 1) $return .= $interval->i ." ". $texts[self::pluralize_new($interval->i, 'minute')] . ' ';

        $return .= $interval->s ." ". $texts[self::pluralize_new($interval->s, 'second')];

        return $return;
    }

    public static function pluralize_new($count, $text){
        return ($count == 1) ? $text."" : $text."s";
    }

    public static function ays_autoembed( $content ) {
        global $wp_embed;
        $content = stripslashes( wpautop( $content ) );
        $content = $wp_embed->autoembed( $content );
        if ( strpos( $content, '[embed]' ) !== false ) {
            $content = $wp_embed->run_shortcode( $content );
        }
        $content = do_shortcode( $content );
        return $content;
    }

    public static function get_questions_categories($q_ids){
        global $wpdb;

        if($q_ids == ''){
            return array();
        }
        $sql = "SELECT DISTINCT c.id, c.title
                FROM {$wpdb->prefix}aysquiz_categories c
                JOIN {$wpdb->prefix}aysquiz_questions q
                ON c.id = q.category_id
                WHERE q.id IN (". esc_sql( $q_ids ) .")";

        $result = $wpdb->get_results($sql, 'ARRAY_A');
        $cats = array();

        foreach($result as $res){
            $cats[$res['id']] = $res['title'];
        }

        return $cats;
    }

    public static function get_suervey_sections_with_questions( $sections_ids, $question_ids ){
        
        $sections = self::get_sections_by_survey_id($sections_ids);
        $sections_count = count( $sections );
        
        foreach ($sections as $section_key => $section) {
            $sections[$section_key]['title'] = (isset($section['title']) && $section['title'] != '') ? stripslashes( htmlentities( $section['title'] ) ) : '';
            $sections[$section_key]['description'] = (isset($section['description']) && $section['description'] != '') ? stripslashes( htmlentities( $section['description'] ) ) : '';

            $section_questions = self::get_questions_by_section_id( intval( $section['id'] ), $question_ids, true );

            foreach ($section_questions as $question_key => $question) {
                $section_questions[$question_key]['question'] = (isset($question['question']) && $question['question'] != '') ? stripslashes( $question['question'] ) : '';
                $section_questions[$question_key]['question_description'] = (isset($question['question_description']) && $question['question_description'] != '') ? stripslashes( $question['question_description'] ) : '';
                $section_questions[$question_key]['image'] = (isset($question['image']) && $question['image'] != '') ? $question['image'] : '';
                $section_questions[$question_key]['type'] = (isset($question['type']) && $question['type'] != '') ? $question['type'] : 'radio';
                $section_questions[$question_key]['user_variant'] = (isset($question['user_variant']) && $question['user_variant'] == 'on') ? true : false;

                $opts = json_decode( $question['options'], true );
                $opts['required'] = (isset($opts['required']) && $opts['required'] == 'on') ? true : false;

                $q_answers = self::get_answers_by_question_id( intval( $question['id'] ) );

                foreach ($q_answers as $answer_key => $answer) {
                    $q_answers[$answer_key]['answer'] = (isset($answer['answer']) && $answer['answer'] != '') ? stripslashes( $answer['answer'] ) : '';
                    $q_answers[$answer_key]['image'] = (isset($answer['image']) && $answer['image'] != '') ? $answer['image'] : '';
                    $q_answers[$answer_key]['placeholder'] = (isset($answer['placeholder']) && $answer['placeholder'] != '') ? $answer['placeholder'] : '';
                }

                $section_questions[$question_key]['answers'] = $q_answers;

                $section_questions[$question_key]['options'] = $opts;
            }

            $sections[$section_key]['questions'] = $section_questions;
        }
        
        return $sections;
    }

    /**
     * Recursive sanitation for an array
     * 
     * @param $array
     *
     * @return mixed
     */
    public static function recursive_sanitize_text_field( $array, $textareas = array() ) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::recursive_sanitize_text_field( $value, $textareas );
            } else {
                if( in_array( $key, $textareas ) ){
                    if( function_exists( 'sanitize_textarea_field' ) ){
                        $value = sanitize_textarea_field( $value );
                    }else{
                        $value = sanitize_text_field( $value );
                    }
                }else{
                    $value = sanitize_text_field( $value );
                }
            }
        }

        return $array;
    }

    /**
     * Recursive sanitation of email for an array
     * 
     * @param $array
     *
     * @return mixed
     */
    public static function recursive_sanitize_email( $array ) {
        foreach ( $array as $key => &$value ) {
            if ( is_array( $value ) ) {
                $value = self::recursive_sanitize_email( $value );
            } else {
                $value = sanitize_email( $value );
            }
        }

        return $array;
    }

    public static function get_survey_takers_count( $id ){
        global $wpdb;
        $submission_table = $wpdb->prefix . "socialsurv_submissions";
        $sql = "SELECT COUNT(id) AS count FROM {$submission_table} WHERE survey_id=". absint( $id ) ;
        $result = absint( $wpdb->get_var( $sql ) );
        
        return $result;
    }

    public static function ays_survey_numbering_all( $numbering ){
        $keyword_arr = array();
        switch ($numbering) {
            case '1.':

                $char_min_val = '1';
                $char_max_val = '100';
                for($x = $char_min_val; $x <= $char_max_val; $x++){
                    $keyword_arr[] = $x .".";
                }

                break;
            case '1)':

                $char_min_val = '1';
                $char_max_val = '100';
                for($x = $char_min_val; $x <= $char_max_val; $x++){
                    $keyword_arr[] = $x .")";
                }

                break;
            case 'A.':

                $char_min_val = 'A';
                $char_max_val = 'Z';
                for($x = $char_min_val; $x <= $char_max_val; $x++){
                    $keyword_arr[] = $x .".";
                }

                break;
            case 'A)':

                $char_min_val = 'A';
                $char_max_val = 'Z';
                for($x = $char_min_val; $x <= $char_max_val; $x++){
                    $keyword_arr[] = $x .")";
                }

                break;
            case 'a.':
                $char_min_val = 'a';
                $char_max_val = 'z';
                for($x = $char_min_val; $x <= $char_max_val; $x++){
                    $keyword_arr[] = $x .".";
                }

                break;
            case 'a)':

                $char_min_val = 'a';
                $char_max_val = 'z';
                for($x = $char_min_val; $x <= $char_max_val; $x++){
                    $keyword_arr[] = $x .")";
                }

                break;

            default:

                break;
        }

        return $keyword_arr;
    }

    public static function get_setting_data( $meta_key = 'options' ){
        global $wpdb;
       
        $name_prefix = 'survey_';

        $settings_table = $wpdb->prefix . "socialsurv_settings";
        $sql = "SELECT meta_value FROM " . $settings_table . " WHERE meta_key = '". esc_sql( $meta_key ) ."'";
        $result = $wpdb->get_var($sql);

        $options = ($result == "") ? array() : json_decode($result, true);

        return $options;
    }

    public static function get_listtables_title_length( $listtable_name ) {
        global $wpdb;

        $options = self::get_setting_data( 'options' );

        $listtable_title_length = 5;
        if(! empty($options) ){
            switch ( $listtable_name ) {
                case 'surveys':
                    $listtable_title_length = (isset($options['survey_title_length']) && intval($options['survey_title_length']) != 0) ? absint(intval($options['survey_title_length'])) : 5;
                    break;
                case 'submissions':
                    $listtable_title_length = (isset($options['survey_submissions_title_length']) && intval($options['survey_submissions_title_length']) != 0) ? absint(intval($options['survey_submissions_title_length'])) : 5;
                    break;
                case 'survey_categories':
                    $listtable_title_length = (isset($options['survey_categories_title_length']) && intval($options['survey_categories_title_length']) != 0) ? absint(intval($options['survey_categories_title_length'])) : 5;
                    break;    
                default:
                    $listtable_title_length = 5;
                    break;
            }
            return $listtable_title_length;
        }
        return $listtable_title_length;
    }

    public static function ays_set_survey_texts( $plugin_name, $settings ){

        /*
         * Get survey buttons texts from database
         */
        global $wpdb;
        
        $name_prefix = 'survey_';

        $settings_table = $wpdb->prefix . "socialsurv_settings";
        $sql = "SELECT meta_value FROM ".$settings_table." WHERE meta_key = 'buttons_texts'";
        $result = $wpdb->get_var($sql);
        $settings_buttons_texts = ($result == "") ? array() : json_decode($result, true);

        $ays_next_button            = (isset($settings_buttons_texts['next_button']) && $settings_buttons_texts['next_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['next_button'] ) ) : 'Next';
        $ays_previous_button        = (isset($settings_buttons_texts['previous_button']) && $settings_buttons_texts['previous_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['previous_button'] ) ) : 'Prev';
        $ays_clear_button           = (isset($settings_buttons_texts['clear_button']) && $settings_buttons_texts['clear_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['clear_button'] ) ) : 'Clear selection';
        $ays_finish_button          = (isset($settings_buttons_texts['finish_button']) && $settings_buttons_texts['finish_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['finish_button'] ) ) : 'Finish';
        $ays_restart_survey_button  = (isset($settings_buttons_texts['restart_button']) && $settings_buttons_texts['restart_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['restart_button'] ) ) : 'Restart survey';
        $ays_exit_button            = (isset($settings_buttons_texts['exit_button']) && $settings_buttons_texts['exit_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['exit_button'] ) ) : 'Exit';
        $ays_login_button           = (isset($settings_buttons_texts['login_button']) && $settings_buttons_texts['login_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['login_button'] ) ) : 'Log In';
        $ays_check_button           = (isset($settings_buttons_texts['check_button']) && $settings_buttons_texts['check_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['check_button'] ) ) : 'Check';
        $ays_start_button           = (isset($settings_buttons_texts['start_button']) && $settings_buttons_texts['start_button'] != '') ? stripslashes( esc_attr( $settings_buttons_texts['start_button'] ) ) : 'Start';

        if ($ays_next_button === 'Next') {
            $ays_next_button_text = __('Next', $plugin_name);
        }else{
            $ays_next_button_text = $ays_next_button;
        }

        if ($ays_previous_button === 'Prev') {
            $ays_previous_button_text = __('Prev', $plugin_name);
        }else{
            $ays_previous_button_text = $ays_previous_button;
        }

        if ($ays_clear_button === 'Clear selection') {
            $ays_clear_button_text = __('Clear selection', $plugin_name);
        }else{
            $ays_clear_button_text = $ays_clear_button;
        }

        if ($ays_finish_button === 'Finish') {
            $ays_finish_button_text = __('Finish', $plugin_name);
        }else{
            $ays_finish_button_text = $ays_finish_button;
        }

        if ($ays_restart_survey_button === 'Restart survey') {
            $ays_restart_survey_button_text = __('Restart survey', $plugin_name);
        }else{
            $ays_restart_survey_button_text = $ays_restart_survey_button;
        }

        if ($ays_exit_button === 'Exit') {
            $ays_exit_button_text = __('Exit', $plugin_name);
        }else{
            $ays_exit_button_text = $ays_exit_button;
        }

        if ($ays_login_button === 'Log In') {
            $ays_login_button_text = __('Log In', $plugin_name);
        }else{
            $ays_login_button_text = $ays_login_button;
        }

        if ($ays_check_button === 'Check') {
            $ays_check_button_text = __('Check', $plugin_name);
        }else{
            $ays_check_button_text = $ays_check_button;
        }

        if ($ays_start_button === 'Start') {
            $ays_start_button_text = __('Start', $plugin_name);
        }else{
            $ays_start_button_text = $ays_start_button;
        }

        $texts = array(
            'nextButton'         => $ays_next_button_text,
            'previousButton'     => $ays_previous_button_text,
            'clearButton'        => $ays_clear_button_text,
            'finishButton'       => $ays_finish_button_text,
            'restartButton'      => $ays_restart_survey_button_text,
            'exitButton'         => $ays_exit_button_text,
            'loginButton'        => $ays_login_button_text,
            'checkButton'        => $ays_check_button_text,
            'startButton'        => $ays_start_button_text,
        );

        return $texts;
    }
    
    public static function get_submission_count_and_ids_for_summary( $survey_id){
        global $wpdb;

        if($survey_id === null){
            return false;
        }
        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
       
        $submission_ids = "SELECT COUNT(*) AS count_submission FROM " . $submitions_table . " WHERE survey_id=" . absint( $survey_id );

        $submission_count = $wpdb->get_var($submission_ids);

        $submission_count_and_ids = array(
            'submission_count' => $submission_count,
        );

        return $submission_count_and_ids;
    }

    public static function get_template_part( $slug, $name = null, $args = array(), $path = 'admin' ) {
		/**
		 * Fires before the specified template part file is loaded.
		 *
		 * The dynamic portion of the hook name, `$slug`, refers to the slug name
		 * for the generic template part.
		 *
		 * @since 1.0.0
		 * @since 1.0.0 The `$args` parameter was added.
		 *
		 * @param string      $slug The slug name for the generic template.
		 * @param string|null $name The name of the specialized template.
		 * @param array       $args Additional arguments passed to the template.
		 */

		$templates = array();
		$name      = (string) $name;
		if ( '' !== $name ) {
			$templates[] = "{$slug}-{$name}.php";
		}

		$templates[] = "{$slug}.php";

		/**
		 * Fires before an attempt is made to locate and load a template part.
		 *
		 * @since 1.0.0
		 * @since 1.0.0 The `$args` parameter was added.
		 *
		 * @param string   $slug      The slug name for the generic template.
		 * @param string   $name      The name of the specialized template.
		 * @param string[] $templates Array of template files to search for, in order.
		 * @param array    $args      Additional arguments passed to the template.
		 */
		do_action( 'ays_sm_get_template_part', $slug, $name, $templates, $path, $args );

		if ( ! self::locate_template( $templates, true, false, $path, $args ) ) {
			return false;
		}
	}

	public static function locate_template( $template_names, $load = false, $require_once = true, $path = 'admin', $args = array() ) {
		$located = '';
		foreach ( (array) $template_names as $template_name ) {
			if ( ! $template_name ) {
				continue;
			}

			$path = $path == 'public' ? SURVEY_MAKER_PUBLIC_PATH : SURVEY_MAKER_ADMIN_PATH;

			if ( file_exists( $path . '/' . $template_name ) ) {
				$located = $path . '/' . $template_name;
				break;
			} elseif ( file_exists( $path . '/' . $template_name ) ) {
				$located = $path . '/' . $template_name;
				break;
			}
		}

		if ( $load && '' !== $located ) {
			self::load_template( $located, $require_once, $args );
		}

		return $located;
	}

	public static function load_template( $_template_file, $require_once = true, $args = array() ) {
		if ( $require_once ) {
			require_once $_template_file;
		} else {
			require $_template_file;
		}
	}

    public static function survey_questions_count($id){
        global $wpdb;
        $survey_question_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "questions";

        $sql = "SELECT COUNT(id) AS question_count
                FROM {$survey_question_table}
                WHERE survey_id=" . esc_sql( absint( $id ) );

        $survey_question_count = $wpdb->get_row( $sql );

        return $survey_question_count;
    }

    public static function ays_survey_get_user_answered( $attr ){
        global $wpdb;
        $answers_table               = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";
        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";

        $submission_id = (isset($attr['submission_id']) && sanitize_text_field( $attr['submission_id'] ) != '') ? absint(intval( $attr['submission_id'] )) : null;
        $survey_id     = (isset($attr['survey_id']) && sanitize_text_field( $attr['survey_id'] ) != '') ? absint(intval( $attr['survey_id'] )) : null;
        $question_id   = (isset($attr['question_id']) && sanitize_text_field( $attr['question_id'] ) != '') ? absint(intval( $attr['question_id'] )) : null;

        $question_type_check = isset($attr['question_type']) && $attr['question_type'] != "" ? $attr['question_type'] : ""; 
        $question_export_check = isset($attr['all_results']) && $attr['all_results'] ? true : false;
        $question_options = isset($attr['question_options']) && !empty($attr['question_options']) ? $attr['question_options'] : array(); 
        $is_csv = isset($attr['is_csv']) && $attr['is_csv'] ? true : false; 
        $question_options_columns = array();
        if( isset( $question_options['matrix_columns'] ) ){
            if( is_array( $question_options['matrix_columns'] ) ){
                $question_options_columns = $question_options['matrix_columns'];
            }else{
                $question_options_columns = json_decode($question_options['matrix_columns'], true);
            }
        }

        if ( is_null($submission_id) || is_null($survey_id) || is_null($question_id) ) {
            return array(
                'message' => '',
                'status' => false
            );
        }

        $text_types     = array('text', 'number', 'phone', 'short_text', 'date', 'time' , 'date_time', 'hidden');
        $single_types   = array('radio', 'select', 'linear_scale');
        $multiple_types = array('checkbox');
        $matrix_types   = array('matrix_scale', 'star_list', 'slider_list' , 'matrix_scale_checkbox');

        $sql = "SELECT * FROM {$submissions_questions_table} WHERE submission_id={$submission_id} AND survey_id={$survey_id} AND question_id={$question_id}";
        if(in_array($question_type_check, $matrix_types)){
            $res = $wpdb->get_results( $sql, 'ARRAY_A' );
            $matrix_answer_sql = "SELECT id, answer FROM ".$answers_table." WHERE question_id=".$question_id;
            $matrix_answers = $wpdb->get_results($matrix_answer_sql , "ARRAY_A");
            $matrix_scale_answer_ids = array();
            foreach($matrix_answers as $a_key => $a_value){
                $matrix_scale_answer_ids[$a_value['id']] = $a_value['answer'];
            }
        }else{
            $res = $wpdb->get_row( $sql, 'ARRAY_A' );
        }

        $text = '';
        if (! is_null($res) ) {
            $answer_id     = (isset($res['answer_id']) && sanitize_text_field( $res['answer_id'] ) != '') ? sanitize_text_field( $res['answer_id'] ) : '';
            $question_type = (isset($res['type']) && sanitize_text_field( $res['type'] ) != '') ? sanitize_text_field( $res['type'] ) : '';
            $user_answer   = (isset($res['user_answer']) && sanitize_text_field( trim($res['user_answer']) ) != '') ? sanitize_text_field( trim($res['user_answer']) ) : '';
            $user_variant  = (isset($res['user_variant']) && sanitize_text_field( trim($res['user_variant']) ) != '') ? sanitize_text_field( trim($res['user_variant']) ) : '';

            if ( in_array($question_type, $multiple_types) ) {
                $text = array();
                $answer_id_arr = explode(',', $user_answer);
                foreach ($answer_id_arr as $key => $answer_id_value) {
                    if ($answer_id_value == '' || $answer_id_value == 0) {
                        continue;
                    }
                    $answer_id_value = absint(intval($answer_id_value));
                    $result = $wpdb->get_row("SELECT answer FROM {$answers_table} WHERE id={$answer_id_value}", 'ARRAY_A');
                    $text[] = $result['answer'];
                }
                $text = implode(', ', $text);
            }elseif( in_array($question_type, $single_types) ) {
                if ($answer_id != '' && $answer_id != 0) {
                    $answer_id = absint(intval($answer_id));
                    $sql    = "SELECT answer FROM {$answers_table} WHERE id={$answer_id}";
                    $result = $wpdb->get_row( $sql, 'ARRAY_A' );
                    $text   = ( isset( $result['answer'] ) && $result['answer'] != '' ) ? $result['answer'] : '';
                }else{
                    $text = $user_answer;
                }
            }elseif( in_array($question_type_check, $matrix_types) ) {
                if($question_type_check == 'star_list' || $question_type_check == 'slider_list'){
                    if(!$question_export_check){
                        $matrix_results = array();
                        foreach($res as $m_key => $m_value){
                            $matrix_results[$matrix_scale_answer_ids[$m_value['answer_id']]] = $m_value['user_answer'];
                        }
                        $text = $matrix_results;
                        
                    }else{
                        $matrix_results_all = array();
                        foreach($res as $m_key => $m_value){
                            if(isset($matrix_scale_answer_ids[ $m_value['answer_id'] ])){
                                $matrix_results_all[] = $matrix_scale_answer_ids[ $m_value['answer_id'] ] . ' : ' . $m_value['user_answer'];
                            }
                        }
                        $text = implode(",\n", $matrix_results_all);
                    }
                }
                else if($question_type_check == 'matrix_scale'){
                    if(!$question_export_check){
                        $matrix_results = array();
                        foreach($res as $m_key => $m_valaue){
                            $matrix_results[$matrix_scale_answer_ids[$m_valaue['answer_id']]] = $question_options_columns[$m_valaue['user_answer']];
                        }
                        $text = $matrix_results;
                    }else{
                        $matrix_results_all = array();
                        foreach($res as $m_key => $m_valaue){
                            $matrix_results_all[] = $matrix_scale_answer_ids[ $m_valaue['answer_id'] ] . ': ' . $question_options_columns[ $m_valaue['user_answer'] ];
                        }
                        $text = implode(",\n", $matrix_results_all);
                    }
                    
                    
                }
                else if($question_type_check == 'matrix_scale_checkbox'){
                    $devider = ",";
                    if(!$question_export_check){
                        $matrix_results = array();
                        if($is_csv){
                            $devider = ";";
                            
                        }
                        foreach($res as $m_key => $m_value){
                            $matrix_results_checkbox = array();
                            $matrix_checkbox_value = explode("," , $m_value['user_answer']);
                                foreach($matrix_checkbox_value as $each_answer_key => $each_answer_value){
                                    $matrix_results_checkbox[] = $question_options_columns[$each_answer_value];
                                }
                                
                            $matrix_results[$matrix_scale_answer_ids[$m_value['answer_id']]] = implode($devider , $matrix_results_checkbox);
                            
                        }
                        $text = $matrix_results;
                    }else{
                        $matrix_results_all = array();
                        foreach($res as $m_key => $m_valaue){
                            $matrix_checkbox_value = explode($devider , $m_valaue['user_answer']);
                            $matrix_results_all[] = $matrix_scale_answer_ids[ $m_valaue['answer_id'] ]. ': ';
                            $loop_iteration = 1;
                            $columns_count = count($matrix_checkbox_value);
                            foreach($matrix_checkbox_value as $each_answer_key => $each_answer_value){
                                $matrix_results_all[] = $question_options_columns[ $each_answer_value ];
                                if($loop_iteration != ($columns_count) && $columns_count != 1){
                                    $matrix_results_all[] = $devider;
                                }
                                $loop_iteration++;
                            }
                            $matrix_results_all[] = "\n";
                        }
                        $text = implode(" ", $matrix_results_all);
                    }
                }
            }elseif( in_array($question_type, $text_types) ) {
                $text = $user_answer;
            }else {
                $text = $user_answer;
            }

            if($user_variant != ''){
                if ($text != '') {
                    $text .= ', ' . $user_variant;
                }else{
                    $text = $user_variant;
                }
            }   
        }

        if($text == ''){
            $text = '';
        }

        return $text;
    }

    public static function ays_survey_get_user_display_name( $id ) {
        $user_id   = (isset($id) && sanitize_text_field($id) != '') ? absint( sanitize_text_field( $id ) ) : 0;
        if (!$user = get_userdata($user_id))
            return false;
        return $user->data->display_name;
    }

    public static function ays_survey_generate_keyword_array( $max_val ) {
        if (is_null($max_val) || $max_val == '') {
            $max_val = 6; //'F';
        }
        $max_val = absint(intval($max_val)) - 1;

        $keyword_arr = array();
        $letters = range('A', 'Z');

        if($max_val <= 25){
            $max_alpha_val = $letters[$max_val];
        }
        elseif($max_val > 25){
          $dividend = ($max_val + 1);
          $max_alpha_val = '';
          $modulo;
          while ($dividend > 0){
            $modulo = ($dividend - 1) % 26;
            $max_alpha_val = $letters[$modulo] . $max_alpha_val;
            $dividend = floor((($dividend - $modulo) / 26));
          }
        }

        $keyword_arr = self::ays_survey_create_columns_array( $max_alpha_val );

        return $keyword_arr;

    }

    public static function ays_survey_create_columns_array($end_column, $first_letters = '') {
        $columns = array();
        $letters = range('A', 'Z');
        $length = strlen($end_column);

        // Iterate over 26 letters.
        foreach ($letters as $letter) {
            // Paste the $first_letters before the next.
            $column = $first_letters . $letter;

            // Add the column to the final array.
            $columns[] = $column;

            // If it was the end column that was added, return the columns.
            if ($column == $end_column)
                return $columns;
        }

        // Add the column children.
        foreach ($columns as $column) {
            // Don't itterate if the $end_column was already set in a previous itteration.
            // Stop iterating if you've reached the maximum character length.
            if (!in_array($end_column, $columns) && strlen($column) < $length) {
              $new_columns = self::ays_survey_create_columns_array($end_column, $column);
              // Merge the new columns which were created with the final columns array.
              $columns = array_merge($columns, $new_columns);
            }
        }

        return $columns;
    }
    
    public static function aysSurveyMakerVersionCompare($version1, $operator, $version2) {
   
        $_fv = intval ( trim ( str_replace ( '.', '', $version1 ) ) );
        $_sv = intval ( trim ( str_replace ( '.', '', $version2 ) ) );
       
        if (strlen ( $_fv ) > strlen ( $_sv )) {
            $_sv = str_pad ( $_sv, strlen ( $_fv ), 0 );
        }
       
        if (strlen ( $_fv ) < strlen ( $_sv )) {
            $_fv = str_pad ( $_fv, strlen ( $_sv ), 0 );
        }
       
        return version_compare ( ( string ) $_fv, ( string ) $_sv, $operator );
    }

    public static function survey_maker_capabilities_for_editing(){
        global $wpdb;
        $options = self::get_setting_data();
        
        $capability = false;

        // User roles to change survey
        $ays_user_roles = (isset($options['user_roles_to_change_survey']) && !empty( $options['user_roles_to_change_survey'] ) ) ? $options['user_roles_to_change_survey'] : array('administrator');
        if(is_user_logged_in()){
            $current_user = wp_get_current_user();
            $current_user_roles = $current_user->roles;
            $ishmar = 0;
            foreach($current_user_roles as $r){
                if(in_array($r, $ays_user_roles)){
                    $ishmar++;
                }
            }
            if($ishmar > 0){
                $capability = true;
            }
        }
        
        return $capability;
    }
    
    public static function ays_survey_question_results( $survey_id, $submission_ids = null, $questions_ids = null, $user_id = null, $detect_data_type = null, $is_email = false, $filters = array() ){
        global $wpdb;
        // $survey_id = isset( $_GET['survey'] ) ? intval( $_GET['survey'] ) : null;

        $sql_for_post_filter = '';
        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = self::get_filters_where_condition($filters, true);
            if (!is_null($filters['filter_submission_ids']) && !empty($filters['filter_submission_ids'])) {
                $sql_for_post_filter = ' AND submission_id IN('.esc_sql( implode( ',', $filters['filter_submission_ids'] ) ).') ';
            }
        }

        if($survey_id === null){
            return array(
                'total_count' => 0,
                'questions' => array()
            );
        }
        // $sql_for_current_user = isset($user_id) && $user_id != "" ? "AND s_q.user_id = ".$user_id : "";
        // $sql_for_current_user_general = isset($user_id) && $user_id != "" ? "AND `user_id` = ".$user_id : "";

        $submitions_questiions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $answer_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";
        $question_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "questions";
        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $survey_section_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "sections";
        $surveys_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "surveys";

        $sql_for_current_user = "";
        $sql_for_current_user_general = "";
        $sql_for_recent_survey = "";
        $sql_for_recent_survey_general = "";
        
        if($detect_data_type == 'public'){
            if (isset($user_id) && $user_id == 0) {
                $recent_submittion_id = "SELECT `id` FROM `" . $submitions_table . "`".$filters_where_condition."ORDER BY `id` DESC LIMIT 1";
                $recent_submittion_id_result = $wpdb->get_var($recent_submittion_id);
                
                $sql_for_recent_survey = " AND s_q.submission_id = " . intval($recent_submittion_id_result);
                $sql_for_recent_survey_general = " AND submission_id = " . intval($recent_submittion_id_result);
            } 
            else{
                $sql_for_current_user = isset($user_id) && $user_id != "" ? "AND s_q.user_id = ".$user_id : "";
                $sql_for_current_user_general = isset($user_id) && $user_id != "" ? " AND `user_id` = ".$user_id : "";
            }
        }
        else{
            $sql_for_current_user = isset($user_id) && $user_id != "" ? "AND s_q.user_id = ".$user_id : "";
            $sql_for_current_user_general = isset($user_id) && $user_id != "" ? "AND `user_id` = ".$user_id : "";
        }

        $survey_options_sql = "SELECT options FROM {$surveys_table} WHERE id =". absint( $survey_id );
        $survey_options = $wpdb->get_var( $survey_options_sql );

        $survey_options = isset( $survey_options ) && $survey_options != '' ? json_decode( $survey_options, true ) : array();

        // Allow HTML in answers
        $survey_options[ 'survey_allow_html_in_answers' ] = isset($survey_options[ 'survey_allow_html_in_answers' ]) ? $survey_options[ 'survey_allow_html_in_answers' ] : 'off';
        $allow_html_in_answers = (isset($survey_options[ 'survey_allow_html_in_answers' ]) && $survey_options[ 'survey_allow_html_in_answers' ] == 'on') ? true : false;

        $question_ids = "SELECT question_ids FROM {$surveys_table} WHERE id =". absint( $survey_id );
        $question_ids_results = $wpdb->get_var( $question_ids );
        $ays_question_id = ($question_ids_results != '') ? $question_ids_results : null;

        if($ays_question_id == null){
            return array(
                'total_count' => 0,
                'questions' => array()
            );
        }

        $questions_ids_arr = explode(',',$ays_question_id);
        $answer_id = "SELECT a.id, a.answer, COUNT(s_q.answer_id) AS answer_count
                    FROM {$answer_table} AS a
                    LEFT JOIN {$submitions_questiions_table} AS s_q 
                    ON a.id = s_q.answer_id
                    WHERE s_q.survey_id=". absint( $survey_id ) ." ".$sql_for_post_filter . $sql_for_current_user . $sql_for_recent_survey."
                    GROUP BY a.id";

        $answer_id_result = $wpdb->get_results($answer_id,'ARRAY_A');

        $for_checkbox = "SELECT a.id, a.answer, COUNT(s_q.answer_id) AS answer_count
                    FROM {$answer_table} AS a
                    LEFT JOIN {$submitions_questiions_table} AS s_q 
                    ON a.id = s_q.answer_id OR FIND_IN_SET( a.id, s_q.user_answer )
                    WHERE s_q.type = 'checkbox'
                    AND s_q.survey_id=". absint( $survey_id ) ." ".$sql_for_post_filter . $sql_for_current_user . $sql_for_recent_survey."                    
                    GROUP BY a.id";

        $for_checkbox_result = $wpdb->get_results($for_checkbox,'ARRAY_A');

        // $for_text_type = "SELECT a.id, a.answer, COUNT(s_q.id) AS answer_count
        //             FROM {$answer_table} AS a
        //             LEFT JOIN {$submitions_questiions_table} AS s_q 
        //             ON a.id = s_q.answer_id OR FIND_IN_SET( a.id, s_q.user_answer )
        //             WHERE s_q.type IN ('name', 'email', 'text', 'short_text', 'number', 'date')
        //             AND s_q.survey_id={$survey_id}
        //             GROUP BY a.id";
        // echo $for_text_type;

        // $for_text_type_result = $wpdb->get_results($for_text_type,'ARRAY_A');

        $for_matrix_scale = "SELECT `question_id` , `answer_id`, `user_answer`, COUNT(`user_answer`) AS col_count
                                FROM {$submitions_questiions_table}
                                WHERE `type` = 'matrix_scale' AND `survey_id`=". absint( $survey_id ) ." ".$sql_for_post_filter . $sql_for_current_user_general . $sql_for_recent_survey_general."
                                GROUP BY `question_id` , `answer_id`, `user_answer`";
        $for_matrix_scale_result = $wpdb->get_results($for_matrix_scale,'ARRAY_A');
        $matrix_answers_columns = array();
        foreach($for_matrix_scale_result as $mat_key => $mat_val){
            $matrix_answers_columns[$mat_val['question_id']][] = array(
                'answer_id'   => $mat_val['answer_id'],
                'user_answer' => $mat_val['user_answer'],
                'col_count'   => $mat_val['col_count'],
            );
        }
        
        // for matrix scale checkbox 
        $for_matrix_scale_checkbox = "SELECT `question_id` , `answer_id`, `user_answer`
                                FROM {$submitions_questiions_table}
                                WHERE `type` = 'matrix_scale_checkbox' AND `survey_id`=". absint( $survey_id ) ." ".$sql_for_post_filter . $sql_for_current_user_general . $sql_for_recent_survey_general."
                                GROUP BY `question_id` , `user_answer` , `answer_id`, `submission_id` ";
        $for_matrix_scale_result_checkbox = $wpdb->get_results($for_matrix_scale_checkbox,'ARRAY_A');
        $matrix_checkbox_answers_columns = array();
        $matrix_checkbox_answers_columns_new = array();
        $matrix_checkbox_answers_columns_count = array();
        $count_matrix_column_values = array();
        foreach($for_matrix_scale_result_checkbox as $mat_key => $mat_val){
            $answered_columns = explode("," , $mat_val['user_answer']);
            foreach($answered_columns as $a_key => $b_key){
                $count_matrix_column_values[$mat_val['answer_id']][] = $b_key;
            }
            $matrix_checkbox_answers_columns[$mat_val['question_id']][$mat_val['answer_id']] = array_count_values($count_matrix_column_values[$mat_val['answer_id']]);
        }
        
        foreach($matrix_checkbox_answers_columns as $very_new_key => $very_new_value){
            foreach($very_new_value as $t => $z){
                $matrix_checkbox_answers_columns_count[$t] =  array_sum($z);
                foreach($z as $r => $y){
                    $matrix_checkbox_answers_columns_new[$very_new_key][] = array(
                        'answer_id'   => strval($t),
                        'user_answer' => strval($r),
                        'col_count'   => strval($y),
                    );

                }
            }
        }
        // Star list and Slider list question type
        $for_star_list = "SELECT `question_id` ,
                                 `answer_id`,
                                 `type`,
                                 COUNT(`user_answer`) AS answered_count,
                                 SUM(`user_answer`)   AS answered_sum
                                 FROM {$submitions_questiions_table}
                                 WHERE `type` = 'star_list' OR `type` = 'slider_list' 
                                 AND `survey_id`=". absint( $survey_id ) ." ".$sql_for_post_filter . $sql_for_current_user_general . $sql_for_recent_survey_general."
                                 GROUP BY `question_id` , `answer_id`";
        $for_listed_result = $wpdb->get_results($for_star_list,'ARRAY_A');
        
        $listed_answers = array();
        foreach($for_listed_result as $mat_key => $mat_val){
            $listed_answers[$mat_val['type']][$mat_val['question_id']][$mat_val['answer_id']] = array(
                'answered_count' => $mat_val['answered_count'],
                'answered_sum'   => $mat_val['answered_sum'],
                'answer_id'   => $mat_val['answer_id'],
            );
        }

        $answer_count = array();
        $question_type = '';
        foreach ($answer_id_result as $key => $answer_count_by_id) {
            $ays_survey_answer_count = (isset($answer_count_by_id['answer_count']) && $answer_count_by_id['answer_count'] !="") ? absint(intval($answer_count_by_id['answer_count'])) : '';
            $answer_count[$answer_count_by_id['id']] = $ays_survey_answer_count;
        }

        foreach ($for_checkbox_result as $key => $answer_count_by_id) {
            $ays_survey_answer_count = (isset($answer_count_by_id['answer_count']) && $answer_count_by_id['answer_count'] !="") ? absint(intval($answer_count_by_id['answer_count'])) : '';
            $answer_count[$answer_count_by_id['id']] = $ays_survey_answer_count;
        }

        if ( isset( $questions_ids ) && $questions_ids !== null ) {
            if ( is_array( $questions_ids ) ) {
                $questions_ids = explode( ',', $questions_ids );
            }
            $questions_ids_arr = (string) $questions_ids;
        }

        $question_by_ids = Survey_Maker_Data::get_question_by_ids( $questions_ids_arr, false, $is_email, false, true );
        $select_answer_q_type = "SELECT type, user_answer, id, question_id
            FROM {$submitions_questiions_table}
            WHERE user_answer != '' 
                AND type != 'checkbox'
                ".$sql_for_post_filter . $sql_for_current_user_general . $sql_for_recent_survey_general." 
                AND survey_id=". absint( $survey_id );

        $submission_answer_other = "SELECT question_id, answer_id, user_variant
            FROM {$submitions_questiions_table}
            WHERE user_variant != ''
                ".$sql_for_post_filter . $sql_for_current_user_general . $sql_for_recent_survey_general."
                AND survey_id=". absint( $survey_id );

        if( $submission_ids !== null ){
            if( is_array( $submission_ids ) ){
                $select_answer_q_type .= " AND submission_id IN (" . esc_sql( implode( ',', $submission_ids ) ) . ") ";
                $submission_answer_other .= " AND submission_id IN (" . esc_sql( implode( ',', $submission_ids ) ) . ") ";
            }
        }

        $result_answers_q_type = $wpdb->get_results($select_answer_q_type,'ARRAY_A');
        $result_answers_other = $wpdb->get_results($submission_answer_other,'ARRAY_A');
        $text_answer = array();
        foreach($result_answers_q_type as $key => $result_answer_q_type){
            $text_answer[$result_answer_q_type['type']][$result_answer_q_type['question_id']][] = $result_answer_q_type['user_answer'];
        }
        
        $other_answers = array();
        $other_answers_all = array();
        foreach($result_answers_other as $key => $result_answer_other){
            if( intval( $result_answer_other['answer_id'] ) == 0 ){
                $other_answers[$result_answer_other['question_id']][] = $result_answer_other['user_variant'];
            }
            $other_answers_all[$result_answer_other['question_id']][] = $result_answer_other['user_variant'];
        }

        $text_types = array(
            'text',
            'short_text',
            'number',
            'phone',
            'name',
            'email',
            'linear_scale',
            'star',
            'date',
            'time',
            'date_time',
            'range',
            'upload',
            'hidden',
        );

        //Question types different charts
        $ays_submissions_count  = array();
        $question_results = array();
        
        $total_count = 0;
        foreach ($question_by_ids as $key => $question) {

            // Matrix Scale
            $question_m_type = isset($question->type) && $question->type == "matrix_scale" ? true : false;
            $question_m_c_type = isset($question->type) && $question->type == "matrix_scale_checkbox" ? true : false;
            $matrix_answer_ids = array();
            $matrix_columns = array();
            $matrix_answers = array();
            $question_cloumns = array();

            $answers = $question->answers;
            $question_id = $question->id;
            $question_title = $question->question;
            $question_description = $question->question_description;

            $question_options = $question->options;
            $question_options = json_decode($question_options, true);
            if($question_m_type || $question_m_c_type){
                $question_cloumns = array();
                if( isset( $question_options['matrix_columns'] ) ){
                    foreach ($question_options['matrix_columns'] as $column_key => &$column_val) {
                        $column_val = stripslashes( esc_attr($column_val) );
                    }                    
                    if( is_array( $question_options['matrix_columns'] ) ){
                        $question_cloumns = $question_options['matrix_columns'];
                    }else{
                        $question_cloumns = json_decode($question_options['matrix_columns'], true);
                    }
                }
                if($question_m_c_type){
                    $collected_matrix_answers_columns = $matrix_checkbox_answers_columns_new;
                }
                else{
                    $collected_matrix_answers_columns = $matrix_answers_columns;

                }
                if(isset($collected_matrix_answers_columns[$question_id])){
                    foreach($collected_matrix_answers_columns[$question_id] as $mat_key => $mat_val){
                        if(isset($question_cloumns[$mat_val['user_answer']])){
                            $matrix_columns[$mat_val['user_answer']][$mat_val['answer_id']] = $mat_val['col_count'];
                        }
                    }
                }
            }

            //questions
            $question_results[$question_id]['question_id'] = $question_id;
            $question_results[$question_id]['question'] = $question_title;
            $question_results[$question_id]['question_description'] = $question_description;
            $ays_answer = array();
            $ays_all_answer = array();
            $question_answer_ids = array();
            foreach ($answers as $key => $answer) {
                $answer_id = $answer->id;
                $answer_title = $answer->answer;

                $matrix_answer_ids[] = $answer_id;
                
                $ays_answer[$answer_id] = isset( $answer_count[$answer_id] ) ? $answer_count[$answer_id] : 0;
                if($question_m_c_type){
                    $ays_all_answer[] = isset( $matrix_checkbox_answers_columns_count[$answer_id] ) ? $matrix_checkbox_answers_columns_count[$answer_id] : 0;
                }
                else{
                    $ays_all_answer[] = isset( $answer_count[$answer_id] ) ? $answer_count[$answer_id] : 0;

                }
                $question_answer_ids[$answer_id] = $allow_html_in_answers ? sanitize_text_field( $answer_title ) : $answer_title;
            }
            
            $matrix_header = array('');
            $matrix_body = array();
            foreach($matrix_answer_ids as $row_key => $row_val){
                $matrix_body[$row_val][] = $question_answer_ids[$row_val];
            }

            if(isset($question_cloumns)){
                foreach($question_cloumns as $col_key => $col_val){
                    if(!isset($matrix_columns[$col_key])){
                        $matrix_columns[$col_key] = array();
                    }

                    foreach($matrix_answer_ids as $row_key => $row_val){
                        if(!isset($matrix_columns[$col_key][$row_val])){
                            $matrix_columns[$col_key][$row_val] = 0;
                        }
                        $matrix_body[$row_val][] = intval( $matrix_columns[$col_key][$row_val] );
                    }

                    $matrix_header[] = $col_val;
                }
            }
            
            $matrix_result_all = array_merge( array( $matrix_header ), $matrix_body );
            
            //sum of submissions count per questions
            if($question->type == "checkbox"){
                $sub_checkbox_count = self::ays_survey_get_submission_count($question->id, $question->type, $survey_id, $sql_for_current_user_general, $sql_for_recent_survey_general, $filters);
                $sum_of_count = $sub_checkbox_count;
            }
            elseif( $question_m_type || $question_m_c_type){
                $sub_checkbox_count = self::ays_survey_get_submission_count($question->id, $question->type, $survey_id, $sql_for_current_user_general, $sql_for_recent_survey_general, $filters);
                $sum_of_count = $sub_checkbox_count;
            }
            elseif( $question->type == 'star_list' || $question->type == 'slider_list' ){
                $sum_of_count = self::ays_survey_get_submission_count($question->id, $question->type, $survey_id, $sql_for_current_user_general, $sql_for_recent_survey_general, $filters);
            }
            else{
                $sum_of_count = array_sum( array_values( $ays_answer ) );
            }

            $question_results[$question_id]['otherAnswers'] = isset( $other_answers[$question->id] ) ? $other_answers[$question->id] : array();

            if( in_array( $question->type, $text_types ) ){
                $question_ls_options = json_decode($question->options, true);
                if($question->type == 'linear_scale'){
                    $scale_from     = isset($question_ls_options['linear_scale_1']) && $question_ls_options['linear_scale_1'] != "" ? stripslashes($question_ls_options['linear_scale_1']) : "";
                    $scale_to       = isset($question_ls_options['linear_scale_2']) && $question_ls_options['linear_scale_2'] != "" ? stripslashes($question_ls_options['linear_scale_2']) : "";
                    $scale_length   = isset($question_ls_options['scale_length']) && $question_ls_options['scale_length'] != "" ? $question_ls_options['scale_length'] : "";
                    $question_results[$question_id]['labels'] = array(
                        'from'      => $scale_from,
                        'to'        => $scale_to,
                        'length'    => $scale_length
                    );
                }
                if($question->type == 'star'){
                    $scale_from     = isset($question_ls_options['star_1']) && $question_ls_options['star_1'] != "" ? stripslashes($question_ls_options['star_1']) : "";
                    $scale_to       = isset($question_ls_options['star_2']) && $question_ls_options['star_2'] != "" ? stripslashes($question_ls_options['star_2']) : "";
                    $scale_length   = isset($question_ls_options['star_scale_length']) && $question_ls_options['star_scale_length'] != "" ? $question_ls_options['star_scale_length'] : "";
                    $question_results[$question_id]['labels'] = array(
                        'from'      => $scale_from,
                        'to'        => $scale_to,
                        'length'    => $scale_length
                    );
                }

                                
                $question_results[$question_id]['answers'] = isset( $text_answer[$question->type] ) ? $text_answer[$question->type] : '';
                $question_results[$question_id]['answerTitles'] = isset( $text_answer[$question->type] ) ? $text_answer[$question->type] : '';
                $question_results[$question_id]['sum_of_answers_count'] = isset( $text_answer[$question->type][$question->id] ) ? count( $text_answer[$question->type][$question->id] ) : 0;
                $question_results[$question_id]['sum_of_same_answers']  = isset( $text_answer[$question->type][$question->id] ) ? array_count_values( $text_answer[$question->type][$question->id] ) : 0;

                if($question->type == "upload"){
                    if(isset($text_answer[$question->type])){
                        if(isset($text_answer[$question->type][$question->id])){
                            $upload_answers = $text_answer[$question->type][$question->id];
                            foreach($upload_answers as $u_key => $u_value){
                                $u_answer = "";
                                if($u_value != "0" && $u_value != ""){
                                    $u_answer = $u_value;
                                }
                                if($u_answer){
                                    $question_results[$question_id]['answers_name'][$question->id][] = wp_basename($u_answer);
                                }
                                else{
                                    $question_results[$question_id]['answers_name'][$question->id][] = "";
                                }
                            }
                        }
                    }
                    else{
                        $question_results[$question_id]['answers_name'][$question->id][] = "";
                    }
                }
            }else{
                $question_results[$question_id]['answers'] = $ays_answer;
                $question_results[$question_id]['answerTitles'] = $question_answer_ids;
                $question_results[$question_id]['sum_of_answers_count'] = $sum_of_count;
                $question_results[$question_id]['allAnswers'] = $ays_all_answer;
                if( $sum_of_count == 0 ){
                    $question_results[$question_id]['answers'] = array();
                }
            }

            // Answers for charts
            if( !empty( $question_results[$question_id]['otherAnswers'] ) ){
                $question_results[$question_id]['answers'][0] = count( $question_results[$question_id]['otherAnswers'] );
                $question_results[$question_id]['answerTitles'][0] = __( '"Other" answer(s)', SURVEY_MAKER_NAME );
                $question_results[$question_id]['same_other_count'] = array_count_values( $question_results[$question_id]['otherAnswers'] );

                if($question->type == "radio" || $question->type == "yesorno"){
                    $question_results[$question_id]['sum_of_answers_count'] += count( $question_results[$question_id]['otherAnswers'] );
                }
            }
            //

            $total_count += intval( $question_results[$question_id]['sum_of_answers_count'] );
            $question_results[$question_id]['question_type'] = $question->type;
            $question_results[$question_id]['matrix_data'] = $matrix_result_all;
            if($question->type == "star_list"){
                $question_results[$question_id]['star_list_data'] = isset( $listed_answers['star_list'] ) ? $listed_answers['star_list'] : "";
                $question_results[$question_id]['question_options'] = $question_options['star_list_stars_length'];
            }
            if($question->type == "slider_list"){
                $question_results[$question_id]['slider_list_data'] = isset( $listed_answers['slider_list'] ) ? $listed_answers['slider_list'] : array();
                $question_results[$question_id]['slider_list_range_length'] = $question_options['slider_list_range_length'];
                $question_results[$question_id]['slider_list_range_step_length'] = $question_options['slider_list_range_step_length'];
                $question_results[$question_id]['slider_list_range_min_value'] = $question_options['slider_list_range_min_value'];
                $question_results[$question_id]['slider_list_range_default_value'] = $question_options['slider_list_range_default_value'];
                $question_results[$question_id]['slider_list_range_calculation_type'] = $question_options['slider_list_range_calculation_type'];
            }
            if($question->type == "range"){
                $question_results[$question_id]['range_min_value'] = $question_options['range_min_value'];
                $question_results[$question_id]['range_length'] = $question_options['range_length'];
            }
        }

        $results_ready = array(
            'total_count' => $total_count,
            'questions' => $question_results,
        );
        
        return $results_ready;
    }
    
    public static function ays_survey_get_last_submission_id( $survey_id , $user_id = 0, $filters = array() ){
        global $wpdb;

        if($survey_id === null){
            return array();
        }

        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";

        $where = "";
        if( $user_id > 0 ){
            $where = " AND user_id = ".$user_id." ";
        }

        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = self::get_filters_where_condition($filters);
        }

        //submission of each result
        $submission = "SELECT id FROM {$submitions_table} WHERE survey_id = ". absint( $survey_id ) ." ".$where. $filters_where_condition . " ORDER BY id DESC LIMIT 1 ";
        $result = $wpdb->get_var( $submission );
        $last_submission = array(
			'id' => $result
	    );

        
        if( $last_submission == null ){
            return array();
        }
        return $last_submission;
    }

    public static function ays_survey_individual_results_for_one_submission( $submission, $survey, $get_submission_question_ids = false, $filters = array(), $hide_question_ids = null ){
        global $wpdb;
        $survey_id = isset( $survey['id'] ) ? absint( intval( $survey['id'] ) ) : null;
        
        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = self::get_filters_where_condition($filters);
        }

        if( is_null( $survey_id ) || empty( $submission )){
            return array(
                'sections' => array()
            );
        }

        $submitions_questiions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";

        $ays_individual_questions_for_one_submission = array();
        $question_answer_id = array();
        $subm_quest_ids_matrix_types = array();
        $submission_id = isset( $submission['id'] ) && $submission['id'] != '' ? $submission['id'] : null;

        if( is_null( $submission_id ) ){
            return array(
                'sections' => array()
            );
        }

        $checkbox_ids = '';
        
        $where_for_individual_questions = '';
        $individual_questions = "SELECT * FROM {$submitions_questiions_table} WHERE submission_id=" . absint( $submission_id ) ." AND type != 'html'";
        if(isset($hide_question_ids) && $hide_question_ids){
            $where_for_individual_questions .= ' AND question_id NOT IN ('.$hide_question_ids.')';
            $individual_questions .= $where_for_individual_questions;
        }
        $individual_questions_results = $wpdb->get_results($individual_questions,'ARRAY_A');

        // Get user info
        $which_needed = "id,user_ip,user_id,user_name,user_email,submission_date,password";
        $individual_users = "SELECT ".$which_needed." FROM ".$submitions_table." WHERE id=" . absint( $submission_id ) . $filters_where_condition;
        $individual_users_results = $wpdb->get_row($individual_users,'ARRAY_A');
        $user_id = isset($individual_users_results['user_id']) && $individual_users_results['user_id'] != "" ? $individual_users_results['user_id'] : 0;
        $user_real_name = __( "Guest" , SURVEY_MAKER_NAME ); 
        $user_real_email = ""; 
        if($user_id > 0){
            $user_data = get_userdata($user_id);
            if($user_data){
                $user_real_name = $user_data->data->display_name;
                $user_real_email = $user_data->data->user_email;
            }
        }
        if(!isset($individual_users_results['user_name']) || (isset($individual_users_results['user_name']) && $individual_users_results['user_name'] == "")){
            $individual_users_results['user_name'] = stripslashes( $user_real_name );
        }else{
            $individual_users_results['user_name'] = stripslashes( $individual_users_results['user_name'] );
        }

        if(!isset($individual_users_results['user_email']) || (isset($individual_users_results['user_email']) && $individual_users_results['user_email'] == "")){
            $individual_users_results['user_email'] = $user_real_email;
        }

        $individual_users_results['user_name'] =  stripslashes(nl2br( htmlentities($individual_users_results['user_name'])));

        // Survey questions IDs
        $question_ids = isset( $survey['question_ids'] ) && $survey['question_ids'] != '' ? $survey['question_ids'] : '';

        // Section Ids
        $sections_ids = (isset( $survey['section_ids' ] ) && $survey['section_ids'] != '') ? $survey['section_ids'] : '';

        $sections = Survey_Maker_Data::get_suervey_sections_with_questions( $sections_ids, $question_ids );

        $text_types = array(
            'text',
            'short_text',
            'number',
            'phone',
            'name',
            'email',
            'linear_scale',
            'range',
            'star',
            'date',
            'time',
            'date_time',
            'hidden'
        );

        foreach ($individual_questions_results as $key => $individual_questions_result) {
            $question_answer_id[ $individual_questions_result['question_id'] ]['user_explanation'] = isset( $individual_questions_result['user_explanation'] ) && $individual_questions_result['user_explanation'] != '' ? stripslashes( $individual_questions_result['user_explanation'] ) : '';
            
            if($individual_questions_result['type'] == 'checkbox'){
                $checkbox_ids = $individual_questions_result['user_answer'] != '' ? explode(',', $individual_questions_result['user_answer']) : array();
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = $checkbox_ids;
                $question_answer_id[ $individual_questions_result['question_id'] ]['otherAnswer'] = isset($individual_questions_result['user_variant']) && $individual_questions_result['user_variant'] != '' ? stripslashes( $individual_questions_result['user_variant'] ) : '';
            }elseif( in_array( $individual_questions_result['type'], $text_types ) ){
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = stripslashes( $individual_questions_result['user_answer'] );
                if( $individual_questions_result['type'] == 'date' ){
                    if( $individual_questions_result['user_answer'] != '' ){
                        $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = date( 'd . m . Y', strtotime(nl2br(htmlentities($individual_questions_result['user_answer']))) );
                    }else{
                        $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = '';
                    }
                }
                elseif( $individual_questions_result['type'] == 'time' ){
                    if( $individual_questions_result['user_answer'] != '' ){
                        $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = implode(" : ", explode( ":", $individual_questions_result['user_answer'] ));
                    }else{
                        $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = '';
                    }
                }
                elseif( $individual_questions_result['type'] == 'date_time' ){
                    if( $individual_questions_result['user_answer'] != '' ){
                        $user_date_time_answer = explode(" ", $individual_questions_result['user_answer'] );
                        if((isset($user_date_time_answer[0]) && $user_date_time_answer[0] != '-') && (isset($user_date_time_answer[1]) && $user_date_time_answer[1] != '-')){                            
                            $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = date( 'd . m . Y', strtotime(nl2br(htmlentities($user_date_time_answer[0]))) ) . " " . implode(" : ", explode( ":", $user_date_time_answer[1] ));
                        }
                    }else{
                        $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = '';
                    }
                }
            }elseif($individual_questions_result['type'] == 'matrix_scale'){
                $user_matrix_answer_ids = $individual_questions_result['user_answer'];
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer_ids'][$individual_questions_result['answer_id'] ] = $user_matrix_answer_ids;
                if($get_submission_question_ids){
                    $subm_quest_ids_matrix_types[ $individual_questions_result['question_id'] ]['submission_questions_ids'][] = $individual_questions_result['id'];
                }
            }elseif($individual_questions_result['type'] == 'matrix_scale_checkbox'){
                $user_matrix_answer_ids = explode("," , $individual_questions_result['user_answer']);
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer_ids'][$individual_questions_result['answer_id'] ] = $user_matrix_answer_ids;
                if($get_submission_question_ids){
                    $subm_quest_ids_matrix_types[ $individual_questions_result['question_id'] ]['submission_questions_ids'][] = $individual_questions_result['id'];
                }
            }elseif($individual_questions_result['type'] == 'star_list'){
                $user_star_list_answer_ids = $individual_questions_result['user_answer'];
                $question_answer_id[ $individual_questions_result['question_id'] ]['star_list_answer_ids'][$individual_questions_result['answer_id'] ] = $user_star_list_answer_ids;
                if($get_submission_question_ids){
                    $subm_quest_ids_matrix_types[ $individual_questions_result['question_id'] ]['submission_questions_ids'][] = $individual_questions_result['id'];
                }
            }
            elseif($individual_questions_result['type'] == 'slider_list'){
                $user_slider_list_answer_ids = $individual_questions_result['user_answer'];
                $question_answer_id[ $individual_questions_result['question_id'] ]['slider_list_answer_ids'][$individual_questions_result['answer_id'] ] = $user_slider_list_answer_ids;
                if($get_submission_question_ids){
                    $subm_quest_ids_matrix_types[ $individual_questions_result['question_id'] ]['submission_questions_ids'][] = $individual_questions_result['id'];
                }
            }elseif($individual_questions_result['type'] == 'upload'){
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = $individual_questions_result['user_answer'];
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer_name'] = wp_basename($individual_questions_result['user_answer']);
            }elseif($individual_questions_result['type'] == 'radio'){
                // $other_answer = isset($individual_questions_result['user_variant']) && $individual_questions_result['user_variant'] != '' ? stripslashes( $individual_questions_result['user_variant'] ) : '';
                $other_answer = isset($individual_questions_result['user_variant']) && $individual_questions_result['user_variant'] != '' ? stripslashes(nl2br( htmlentities($individual_questions_result['user_variant']))) : '';
                $question_answer_id[ $individual_questions_result['question_id'] ]['otherAnswer'] = $other_answer;
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = $individual_questions_result['answer_id'];
                if( intval( $individual_questions_result['answer_id'] ) === 0 && $other_answer !== '' ){
                    $question_answer_id[ $individual_questions_result['question_id'] ]['otherAnswer'] = $other_answer;
                    $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = $individual_questions_result['answer_id'];
                }elseif( intval( $individual_questions_result['answer_id'] ) === 0 && $other_answer === '' ){
                    $question_answer_id[ $individual_questions_result['question_id'] ]['otherAnswer'] = '';
                    $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = '-1';
                }else{
                    $question_answer_id[ $individual_questions_result['question_id'] ]['otherAnswer'] = '';
                    $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = $individual_questions_result['answer_id'];
                }
            }else{
                $question_answer_id[ $individual_questions_result['question_id'] ]['answer'] = $individual_questions_result['answer_id'];
            }
        }
        
        $ays_individual_questions_for_one_submission['submission_id'] = $submission['id'];
        $ays_individual_questions_for_one_submission['questions'] = $question_answer_id;
        $ays_individual_questions_for_one_submission['sections'] = $sections;

        $ays_individual_questions_for_one_submission['subm_quest_ids_matrix_types'] = $subm_quest_ids_matrix_types;

        $ays_individual_questions_for_one_submission['user_info'] = $individual_users_results;

        return $ays_individual_questions_for_one_submission;
    }

    public static function get_submission_count_and_ids( $survey_id, $filters = array() ){
        global $wpdb;

        if($survey_id === null){
            return false;
        }
        $submitions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";

        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = self::get_filters_where_condition($filters);
        }
       
        //submission of each result
        $submission_ids = "SELECT id
                           FROM {$submitions_table} j 
                           WHERE survey_id=". absint( $survey_id ) . $filters_where_condition . "
                           ORDER BY id";
        $submission_ids_result = $wpdb->get_results($submission_ids,'ARRAY_A');

	    $submission_count_sql = "SELECT COUNT(id) AS count_submission
								 FROM {$submitions_table} 
                            	 WHERE survey_id=". absint( $survey_id ) . $filters_where_condition ." ";
	    $submission_count_result = $wpdb->get_var($submission_count_sql);
        $submission_count = '';
        $submissions_id_arr = array();

        foreach ($submission_ids_result as $key => $submission_id_result) {
            $submission_count = intval($submission_count_result);
            $submissions_id_arr[] = $submission_id_result['id'];
        }
        $submissions_id_str = implode(',', $submissions_id_arr );
        
        $submission_count_and_ids = array(
            'submission_count' => $submission_count,
            'submission_ids' => $submissions_id_str,
            'submission_ids_arr' => $submissions_id_arr,
        );

        return $submission_count_and_ids;
    }
    
    // Get Submissions count for question
    public static function ays_survey_get_submission_count( $id, $type, $survey_id, $sql_for_current_user_general, $sql_for_recent_survey_general, $filters ){
        global $wpdb;
        $submitions_table   = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        $submitions_q_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";
        $results = array();

        $sql_for_post_filter = '';
        $sql_for_post_join = '';
        $filters_where_condition = "";
        if (isset($filters['is_filter']) && $filters['is_filter']) {
            $filters_where_condition = self::get_filters_where_condition($filters, false, $submitions_table);
            if (isset($filters['post_id']) && intval($filters['post_id']) > 0) {
                $sql_for_post_join = ' LEFT JOIN '.$submitions_table.' ON `'.$submitions_q_table.'`.`submission_id` = `'.$submitions_table.'`.`id` ';
            }
            if (!is_null($filters['filter_submission_ids']) && !empty($filters['filter_submission_ids'])) {
                $sql_for_post_filter = ' AND submission_id IN('.esc_sql( implode( ',', $filters['filter_submission_ids'] ) ).') ';
            }
        }
        $sql = "SELECT submission_id AS sub_count
                FROM {$submitions_q_table}
                " . $sql_for_post_join . "
                WHERE question_id = ". absint( $id ) ."
                AND `{$submitions_q_table}`.survey_id = ". absint( $survey_id ) ."";

        if( $type == 'checkbox' ){
            $sql .= " AND user_answer != '' ";
        }

        if($sql_for_current_user_general != ''){
            $sql .= $sql_for_current_user_general;
        }

        if($sql_for_recent_survey_general != ''){
            $sql .= $sql_for_recent_survey_general;
        }
        
        if($sql_for_post_filter != ''){
            $sql .= $sql_for_post_filter;
        }

        if($filters_where_condition != ''){
            $sql .= $filters_where_condition;
        }

        $sql .= " GROUP BY submission_id ";
        $results = $wpdb->get_results( $sql, 'ARRAY_A' );

        $submission_count = count( $results );
        return $submission_count;
    }

    public static function ays_survey_detected_device_chart() {
        $device = 'desktop';
        $isMobile = preg_match("/(iphone|ipod|android|blackberry|opera|mini|windows\sce|palm|smartphone|iemobile)/i", $_SERVER["HTTP_USER_AGENT"]);
        $isTablet = preg_match("/(ipad|android|android 3.0|xoom|sch-i800|playbook|tablet|kindle)/i", $_SERVER["HTTP_USER_AGENT"]);

        if($isMobile){
            $device = 'mobile';
        }else if($isTablet){
            $device = 'tablet';
        }else{
            $device = 'desktop';
        }
        return $device;
    }

    public static function ays_color_inverse( $color ){
        $color = str_replace( '#', '', $color );
        if ( strlen( $color ) != 6 ){
            return '#000000';
        }

        $rgb = '';
        for ( $x = 0; $x < 3; $x++ ){
            $c = 255 - hexdec( substr( $color, ( 2 * $x ), 2 ) );
            $c = ( $c < 0 ) ? 0 : dechex( $c );
            $rgb .= ( strlen( $c ) < 2 ) ? '0' . $c : $c;
        }
        
        return '#'.$rgb;
    }

    
    public static function ays_survey_is_elementor(){
        if( isset( $_GET['action'] ) && $_GET['action'] == 'elementor' ){
            $is_elementor = true;
        }elseif( isset( $_REQUEST['elementor-preview'] ) && $_REQUEST['elementor-preview'] != '' ){
            $is_elementor = true;
        }else{
            $is_elementor = false;
        }

        if ( ! $is_elementor ) {
            $is_elementor = ( isset( $_REQUEST['action'] ) && $_REQUEST['action'] == 'elementor_ajax' ) ? true : false;
        }

        return $is_elementor;
    }

    public static function get_survey_submission_results( $survey_id ){
        $checkbox_readio_yesorno = array(
           'checkbox' =>  'checkbox',
           'radio'    =>  'radio',
           'select'   =>  'select',
           'yesorno'  =>  'yesorno',
        );

        $range_scale_star = array(
            'range'       =>  'range',
            'star'        =>  'star',
            'linear_scale'=>  'linear_scale',
            'date'        =>  'date',
            'time'        =>  'time',
            'date_time'   =>  'date_time'
         );
 

        $submissions = self::ays_survey_question_results( $survey_id );
        $quest_results = ( isset( $submissions['questions'] ) && !empty($submissions['questions']) ) ? $submissions['questions'] : array();

        $summary_result = array();
        if (!empty($quest_results)) {
            foreach ($quest_results as $quest_result_key => $result) {
                //Question ID
                $question_id = ( isset( $result['question_id'] ) && $result['question_id'] != '') ? intval( $result['question_id'] ) : null;

                //Question
                $question = ( isset( $result['question'] ) && $result['question'] != '') ? stripslashes( $result['question'] ) : '';

                //Question description
                $question_description = ( isset( $result['question_description'] ) && $result['question_description'] != '') ? stripslashes( $result['question_description'] ) : '';

                //Question type
                $question_type = ( isset( $result['question_type'] ) && $result['question_type'] != '') ? stripslashes( $result['question_type'] ) : '';

                //Answered answer
                $answers = ( isset( $result['answers'] ) && !empty($result['answers']) ) ? $result['answers'] : array();
                
                //Answer Titles
                $answer_title = ( isset( $result['answerTitles'] ) && !empty($result['answerTitles']) ) ? $result['answerTitles'] : array();
                
                //Other Answer
                $other_answer = ( isset( $result['otherAnswers'] ) && !empty($result['otherAnswers']) ) ? $result['otherAnswers'] : array();
                
                //Answers Count
                $sum_of_answers_count = ( isset( $result['sum_of_answers_count'] ) && $result['sum_of_answers_count'] != '') ? intval( $result['sum_of_answers_count'] ) : 0;
                
                //Matrix Data 
                $matrix_data = ( isset( $result['matrix_data'] ) && !empty($result['matrix_data']) ) ? $result['matrix_data'] : array();

                
                //Checkbox Radio Select YesorNo
                $percent_of_each_answer = array();
                $sum_of_each_answer = array();
                if ( in_array( $question_type, $checkbox_readio_yesorno ) ) {
                    $summary_result[$question_id]['question'] = $question;
                    $summary_result[$question_id]['question_description'] = $question_description;
                    $summary_result[$question_id]['question_type'] = $question_type;
                    
                    //Answerd sum with other answer
                    if( $question_type != 'radio' && !empty($other_answer) ){
                        $sum_of_answers_count += count($other_answer);
                    }
                    
                    if($question_type == 'checkbox'){
                        $sum_of_answers_count = array_sum($answers);
                    }
                    $summary_result[$question_id]['sum_of_answers'] = $sum_of_answers_count;
                    
                    foreach ($answers as $answer_key => $answer_val) {
                        //Percent of answers
                        if( $sum_of_answers_count == 0 || $answer_val == 0 ){
                            $percent = 0;
                        }else{
                            $percent = ( $answer_val * 100) / $sum_of_answers_count;
                        }
                        
                        $percent_of_each_answer[$answer_title[$answer_key]] = $percent;
                        $sum_of_each_answer[$answer_title[$answer_key]] = $answer_val;
                       
                    }
                    $summary_result[$question_id]['sum_of_each_answer'] = $sum_of_each_answer;
                    $summary_result[$question_id]['percent'] = $percent_of_each_answer;
                }

                //Range Scale Star
                if ( in_array( $question_type, $range_scale_star ) ) {
                    $summary_result[$question_id]['question'] = $question;
                    $summary_result[$question_id]['question_description'] = $question_description;
                    $summary_result[$question_id]['question_type'] = $question_type;
                    $summary_result[$question_id]['sum_of_answers'] = $sum_of_answers_count;
                    foreach ($answers as $answer_key => $answer_val) {
                        //Sum of each answer
                        if($quest_result_key !== $answer_key){
                            continue;
                        }
                        $sum_of_each_answer = array_count_values($answer_val);
                        ksort($sum_of_each_answer);
                        foreach ($sum_of_each_answer as $key => $value) {
                            if( $sum_of_answers_count == 0 || $answer_val == 0 ){
                                $percent = 0;
                            }else{
                                $percent = ( $value * 100) / $sum_of_answers_count;
                            }
                            $percent_of_each_answer[$key] = $percent;
                            $sum_of_each_answer[$key] = $value;
                        }
                        
                    }
                    $summary_result[$question_id]['sum_of_each_answer'] = $sum_of_each_answer;
                    $summary_result[$question_id]['percent'] = $percent_of_each_answer;
                }

                //Matrix scale
                if($question_type == 'matrix_scale' || $question_type == 'matrix_scale_checkbox'){
                    $summary_result[$question_id]['question'] = $question;
                    $summary_result[$question_id]['question_description'] = $question_description;
                    $summary_result[$question_id]['question_type'] = $question_type;
                    array_shift($matrix_data[0]);
                    $columns = $matrix_data[0];
                    $rows = array_values($answer_title);

                    array_shift($matrix_data);
                    foreach ($matrix_data as $matrix_data_key => $matrix_data_val) {
                        array_shift($matrix_data_val);
                        $sum_of_each_row = array_sum($matrix_data_val);
                        $percents = array();
                        $col = array();
                        foreach ($matrix_data_val as $matrix_key => $matrix_val) {

                            if($sum_of_each_row == 0 || $matrix_val == 0){
                                $percent = 0;
                            }else{
                                $percent = ( $matrix_val * 100) / $sum_of_each_row;
                            }

                            $percents[$columns[$matrix_key]] = $percent;
                            $col[] = $columns[$matrix_key];
                        }
                        $percent_of_each_answer[$rows[$matrix_data_key]] = $percents;
                    }
                    
                    $summary_result[$question_id]['percent'] = $percent_of_each_answer;
                    $summary_result[$question_id]['columns'] = $col;
                }
            }
            return $summary_result;
        }       
    }

    public static function get_survey_sections_count($id){
        global $wpdb;
        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "surveys";

        $sql = "SELECT `sections_count`
                FROM {$surveys_table}
                WHERE id=" . esc_sql( absint( $id ) );

        $sections_str = $wpdb->get_var( $sql );
        $count = intval( $sections_str );

        return $count;
    }

    public static function ays_survey_copy_text_formater( $info_array ) {
        $return = "`\n";
        foreach ( $info_array as $section => $details ) {
                $return .= sprintf( "%s: %s", $section, $details );
            $return .= "\n";
        }
        $return .= '`';
        return $return;
    }

    public static function ays_survey_get_passed_users_count( $survey_id ) {
        global $wpdb;
        $submissions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions";
        // Get passed users
        $sql = "SELECT COUNT(id) AS users_count FROM ".$submissions_table." 
                WHERE survey_id = ".$survey_id." AND user_id != 0
                GROUP BY user_id";
        $result = $wpdb->get_results($sql);
        // Get passed guests count
        $sql2 = "SELECT COUNT(id) FROM ".$submissions_table." WHERE `user_id` = 0 AND `survey_id` = ".$survey_id;
        $result2 = $wpdb->get_var($sql2);
        $all_count = intval(count($result)) + intval($result2);
        return $all_count;
    }

    // Check users cookie
    public static function ays_survey_set_cookie($attr){
        $cookie_name = $attr['name'].$attr['id'];
        $cookie_value = $attr['title'];
        $cookie_value = isset( $attr['attempts_count'] ) ? $attr['attempts_count'] : 1;
        self::ays_survey_remove_cookie( $attr );
        $cookie_expiration =  current_time('timestamp') + (1 * 365 * 24 * 60 * 60);
        setcookie($cookie_name, $cookie_value, $cookie_expiration, '/');
    }    

    public static function ays_survey_remove_cookie($attr){
        $cookie_name = $attr['name'].$attr['id'];
        if(isset($_COOKIE[$cookie_name])){
            unset($_COOKIE[$cookie_name]);
            $cookie_expiration =  current_time('timestamp') - 1;
            setcookie($cookie_name, null, $cookie_expiration, '/');
        }
    }

    public static function ays_survey_check_cookie($attr){
        $cookie_name = $attr['name'].$attr['id'];
        if(isset($_COOKIE[$cookie_name])){
            if( isset( $attr['increase_count'] ) && $attr['increase_count'] == true ){
                $attr['attempts_count'] = intval( $_COOKIE[$cookie_name] ) + 1;
                self::ays_survey_set_cookie( $attr );
            }
            return true;
        }
        return false;
    }

    public static function get_limit_cookie_count($attr){
        $cookie_name = $attr['name'].$attr['id'];
        if(isset($_COOKIE[$cookie_name])){
            return intval( $_COOKIE[ $cookie_name ] );
        }
        return false;
    }

    public static function get_user_profile_data(){

        $user_first_name = '';
        $user_last_name  = '';
        $user_nickname   = '';

        $user_id = get_current_user_id();
        if($user_id != 0){
            $usermeta = get_user_meta( $user_id );
            if($usermeta !== null){
                $user_first_name = (isset($usermeta['first_name'][0]) && $usermeta['first_name'][0] != '' ) ? sanitize_text_field( $usermeta['first_name'][0] ) : '';
                $user_last_name  = (isset($usermeta['last_name'][0]) && $usermeta['last_name'][0] != '' ) ? sanitize_text_field( $usermeta['last_name'][0] ) : '';
                $user_nickname   = (isset($usermeta['nickname'][0]) &&  $usermeta['nickname'][0] != '' ) ? sanitize_text_field( $usermeta['nickname'][0] ) : '';
            }
        }

        $message_data = array(
            'user_first_name'   => $user_first_name,
            'user_last_name'    => $user_last_name,
            'user_nickname'     => $user_nickname,
        );

        $current_user_data = get_userdata( $user_id );
        $user_display_name = "";
        $user_wordpress_roles = '';
        $user_email = '';

        if ( ! is_null( $current_user_data ) && $current_user_data ) {
            $user_display_name = ( isset( $current_user_data->data->display_name ) && $current_user_data->data->display_name != '' ) ? sanitize_text_field( $current_user_data->data->display_name ) : "";
            $user_email = ( isset( $current_user_data->data->user_email ) && $current_user_data->data->user_email != '' ) ? sanitize_text_field( $current_user_data->data->user_email ) : "";
            $user_wordpress_roles = ( isset( $current_user_data->roles ) && ! empty( $current_user_data->roles ) ) ? $current_user_data->roles : "";
            if ( !empty( $user_wordpress_roles ) && $user_wordpress_roles != "" ) {
                if ( is_array( $user_wordpress_roles ) ) {
                    $user_wordpress_roles = implode(",", $user_wordpress_roles);
                }
            }

        }

        $message_data['user_display_name'] = $user_display_name;
        $message_data['user_wordpress_roles'] = $user_wordpress_roles;
        $message_data['user_wordpress_email'] = $user_email;
        $message_data['user_ip_address'] = self::get_user_ip();

        return $message_data;
    }    

    public static function get_survey_results_count_by_id($id){
        global $wpdb;

        $sql = "SELECT COUNT(*) AS res_count
                FROM {$wpdb->prefix}socialsurv_submissions
                WHERE survey_id=". $id ." ";

        $quiz = $wpdb->get_row($sql, 'ARRAY_A');

        return $quiz;
    }

    // Retrieves the attachment ID from the file URL
    public static function ays_survey_get_image_id_by_url( $image_url ) {
        global $wpdb;

        $image_alt_text = "";
        if ( !empty( $image_url ) ) {

            $re = '/-\d+[Xx]\d+\./';
            $subst = '.';

            $image_url = preg_replace($re, $subst, $image_url, 1);
            $attachment = $wpdb->get_col($wpdb->prepare("SELECT ID FROM $wpdb->posts WHERE guid='%s';", $image_url ));
            if ( !is_null( $attachment ) && !empty( $attachment ) ) {

                $image_id = (isset( $attachment[0] ) && $attachment[0] != "") ? absint(  $attachment[0] ) : "";
                if ( $image_id != "" ) {
                    $image_alt_text = self::ays_survey_get_image_alt_text_by_id( $image_id );
                }
            }
        }
        return $image_alt_text; 
    }

    public static function ays_survey_get_image_alt_text_by_id( $image_id ) {

        $image_data = "";
        if ( $image_id != "" ) {

            $result = get_post_meta($image_id, '_wp_attachment_image_alt', TRUE);
            if ( $result && $result != "" ) {
                $image_data = esc_attr( $result );
            }
        }

        return $image_data; 
    }

    public static function get_user_by_ip($id){
        global $wpdb;
        $user_ip = self::get_user_ip();
        $sql = "SELECT COUNT(*)
                FROM `{$wpdb->prefix}socialsurv_submissions`
                WHERE `user_ip` = '$user_ip'
                  AND `survey_id` = $id";
        $result = $wpdb->get_var($sql);
        return $result;
    }

    public static function exclude_sections_logic_jump( $survey_id ){
        global $wpdb;

        $surveys_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "surveys";
        $answers_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "answers";
        $questions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "questions";

        $sql = "SELECT * FROM " . $surveys_table . " WHERE id =" . absint( $survey_id );
        $survey_name = $wpdb->get_row( $sql, 'ARRAY_A' );

        $question_ids = ( isset( $survey_name['question_ids'] ) && $survey_name['question_ids'] != '' ) ? stripcslashes( $survey_name['question_ids'] ) : '';

        $answer_sql = "SELECT `id`, `question_id`, `options` FROM " . $answers_table . " WHERE `question_id` IN ({$question_ids})";
        $answers_results = $wpdb->get_results( $answer_sql, 'ARRAY_A' );
        
        $question_sql = "SELECT `id`, `section_id`, `options` FROM " . $questions_table . " WHERE `id` IN ({$question_ids})";
        $question_results = $wpdb->get_results( $question_sql, 'ARRAY_A' );

        if ( empty( $survey_name ) || is_null( $survey_name ) ){
            return null;
        }
        

        $last_submission = Survey_Maker_Data::ays_survey_get_last_submission_id( $survey_id );
        
        $ays_survey_individual_submissions = Survey_Maker_Data::ays_survey_individual_results_for_one_submission( $last_submission, $survey_name );

        //Questions
        $questions = ( isset( $ays_survey_individual_submissions['questions'] ) && !empty( $ays_survey_individual_submissions['questions'] ) ) ? $ays_survey_individual_submissions['questions'] : array();

        $is_logic_jump = array();
        foreach ($question_results as $key => $question_result) { 
            $q_id = ( isset( $question_result['id'] ) && $question_result['id'] != '' ) ? absint( $question_result['id'] ) : null;

            $q_section_id = ( isset( $question_result['section_id'] ) && $question_result['section_id'] != '' ) ? absint( $question_result['section_id'] ) : null;

            $q_options = ( isset( $question_result['options'] ) && $question_result['options'] != '' ) ? json_decode( $question_result['options'], true ) : '';
            
            //Logic Jump
            $q_options['is_logic_jump'] = ( isset( $q_options['is_logic_jump'] ) && $q_options['is_logic_jump'] == 'on' ) ? stripslashes( $q_options['is_logic_jump'] ) : 'off';
            $q_is_logic_jump = ( isset( $q_options['is_logic_jump'] ) && $q_options['is_logic_jump'] == 'on' ) ? true : false;
            
            if($q_id != null){
                $is_logic[$q_id] = $q_is_logic_jump;

                if($q_is_logic_jump){
                    $is_logic['sections'][$q_id] = $q_section_id;
                }
            }
        }

        $opened_sections = array();
        foreach ($answers_results as $key => $answers_result) {
            $ans_id = ( isset( $answers_result['id'] ) && $answers_result['id'] != '' ) ? absint( $answers_result['id'] ) : null;

            $ans_quest_id = ( isset( $answers_result['question_id'] ) && $answers_result['question_id'] != '' ) ? absint( $answers_result['question_id'] ) : null;

            $ans_options = ( isset( $answers_result['options'] ) && $answers_result['options'] != '' ) ? json_decode( $answers_result['options'], true ) : '';
            
            if($is_logic[$ans_quest_id] === true){
                $go_to_section = ( isset( $ans_options['go_to_section'] ) && $ans_options['go_to_section'] != '' ) ? $ans_options['go_to_section'] : -1;

                $answered_answers = ( isset( $questions[$ans_quest_id]['answer'] ) && $questions[$ans_quest_id]['answer'] != '') ? absint($questions[$ans_quest_id]['answer']) : '';
                
                if($ans_id == $answered_answers){
                    $opened_sections['from'] = $is_logic['sections'][$ans_quest_id];
                    $opened_sections['to'] = absint($go_to_section);
                }
            }
        }
        $from_section = ( isset( $opened_sections['from'] ) && $opened_sections['from'] != '' ) ? absint($opened_sections['from']) : '';
        $to_section = ( isset( $opened_sections['to'] ) && $opened_sections['to'] != '' ) ? absint($opened_sections['to']) : '';
        
        $excluded_sections = array();

        if($from_section != '' && $to_section != ''){

            for ($i = $from_section+1; $i < $to_section; $i++) { 
                $excluded_sections[] = $i;
            }
        }
        
        return $excluded_sections;  
    }

    public static function get_surveys_by_category( $id ){
        global $wpdb;

        $id = ( isset( $id ) && $id != '' ) ? absint( intval( $id ) ) : null;
        $surveys_table    = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "surveys";
        $categories_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "survey_categories";

        $results = '';

        if($id != null){
            $sql = "SELECT *, s.`title`, s.`id`, s.`options` FROM `{$surveys_table}` AS s LEFT JOIN `{$categories_table}` AS c ON FIND_IN_SET(c.`id`, s. `category_ids` ) AND c.`status` = 'published' AND s.`status` = 'published'  WHERE c.`id` = " . esc_sql( $id );
            $results = $wpdb->get_results( $sql, 'ARRAY_A');
        }
        
        return $results;
    }

    public static function survey_lazy_loading_for_images( $toggle ){
        if($toggle){
            return "loading='lazy'";
        }
        return '';
    }
    
    public static function ays_survey_get_user_explanation($attr){
        global $wpdb;

        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";

        $submission_id = (isset($attr['submission_id']) && sanitize_text_field( $attr['submission_id'] ) != '') ? absint(intval( $attr['submission_id'] )) : null;
        $survey_id     = (isset($attr['survey_id']) && sanitize_text_field( $attr['survey_id'] ) != '') ? absint(intval( $attr['survey_id'] )) : null;
        $question_id   = (isset($attr['question_id']) && sanitize_text_field( $attr['question_id'] ) != '') ? absint(intval( $attr['question_id'] )) : null;

        $user_explanation_sql  = "SELECT `user_explanation` FROM {$submissions_questions_table} WHERE `question_id` = {$question_id} AND `submission_id` = {$submission_id}";
        $user_explanation = $wpdb->get_var($user_explanation_sql);
        
        return $user_explanation;
    }

    public static function ays_survey_translate_content($content) {
        $in = str_replace("\n", "-ays-survey-break-line-", $content);
        $out = preg_replace_callback("/\[:(.*?)\[:]/", function($part){
            $language_slug = explode('-', get_bloginfo("language"))[0];
            preg_match("/\[\:".$language_slug."\](.*?)\[\:/is", $part[0], $out);
            if(empty($out)){
                $language_slug = "en";
                preg_match("/\[\:".$language_slug."\](.*?)\[\:/is", $part[0], $out);
            }
            return (is_array($out) && isset($out[1])) ? $out[1] : $part[0];
        }, $in);
        $out = str_replace("-ays-survey-break-line-", "\n", $out);
        return $out;
    }

    public static function apply_translation_for_arrays($array_data) {
        foreach($array_data as $key => &$each_array){
            if( isset($each_array['answerTitles']) && !empty($each_array['answerTitles']) ){
                $each_array['answerTitles'] = array_map('Survey_Maker_Data::ays_survey_translate_content' , $each_array['answerTitles']);
            }
            if( isset($each_array['matrix_data']) && !empty($each_array['matrix_data']) ){
                $each_array['matrix_data'] = array_map('Survey_Maker_Data::ays_survey_translate_content' , $each_array['matrix_data']);
            }
        }
        return $array_data;
    }

    public static function get_sum_of_changed_submissions($submission_id) {
        global $wpdb;

        $submissions_table = ( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions";
        
        $sql = "SELECT SUM(changed) FROM ".$submissions_table." WHERE id = ".$submission_id;
        $changed_count = intval( $wpdb->get_var( $sql ) );
        return $changed_count;
    }

    public static function survey_no_items_list_tables($subject) {
        if( isset( $_GET['status'] ) && ($_GET['status'] == 'deleted' || $_GET['status'] == 'restored')){
            $url = remove_query_arg( array('fstatus', 'status', '_wpnonce') );
            $url = esc_url_raw( $url );
            wp_redirect( $url );
        }
        else{
            echo sprintf(__( 'There are no %s yet.', "survey-maker" ) , $subject);
        }
    }

    public static function ays_survey_get_survey_posts($survey_id) {
        global $wpdb;
        $submissions_table = esc_sql( $wpdb->prefix . SURVEY_MAKER_DB_PREFIX ) . "submissions";

        $posts = [];

        $sql = "SELECT `post_id` FROM `{$submissions_table}` WHERE `survey_id`={$survey_id} GROUP BY `post_id`";
        $results = $wpdb->get_results( $sql, 'ARRAY_A');

        foreach ($results as $key => $val) {
            $post_id = intval($val['post_id']);
            $post = get_post($post_id);
            if (!is_null($post)) {
                $post_title = $post->post_title;
                $posts[$post_id] = $post_title;
            }
        }
        
        return $posts;
    }

    public static function get_filters_where_condition ($filters, $start = false, $table = '') {
        $where = array();
        $sql = $start ? " WHERE " : " AND ";

        $table = $table != '' ? '`'.$table.'`.' : '';
 
        $post_id = isset($filters['post_id']) ? intval( sanitize_text_field( $filters['post_id'] ) ) : null;
        if (!is_null($post_id)) {
            $where[] = ' '.$table.'`post_id` = '.$post_id.' ';
        }

        $start_date = isset($filters['start_date']) ? sanitize_text_field( $filters['start_date'] ) : null;
        $end_date = isset($filters['end_date']) ? sanitize_text_field( $filters['end_date'] ) : null;
        if (!is_null($start_date) && !is_null($end_date)) {
            $where[] = ' `submission_date` BETWEEN "'.$start_date.'" AND "'.$end_date.'" ';
        } else if (!is_null($start_date)) {
            $where[] = ' `submission_date` >= "'.$start_date.'" ';
        } else if (!is_null($end_date)) {
            $where[] = ' `submission_date` <= "'.$end_date.'" ';
        }

        if( ! empty($where) ){
            $sql .= implode( " AND ", $where );
        }

        return $sql;
    }

    public static function survey_get_loader($type , $loading = ''){
        switch( $type ){
            case 'default':
                $survey_loader_html = "<div data-class='lds-ellipsis' data-role='loader' class='lds-ellipsis ays-survey-wait-loading-loader'><div></div><div></div><div></div><div></div></div>";
                break;
            case 'circle':
                $survey_loader_html = "<div data-class='lds-circle' data-role='loader' class='lds-circle ays-survey-wait-loading-loader'></div>";
                break;
            case 'dual_ring':
                $survey_loader_html = "<div data-class='lds-dual-ring' data-role='loader' class='lds-dual-ring ays-survey-wait-loading-loader'></div>";
                break;
            case 'facebook':
                $survey_loader_html = "<div data-class='lds-facebook' data-role='loader' class='lds-facebook ays-survey-wait-loading-loader'><div></div><div></div><div></div></div>";
                break;
            case 'hourglass':
                $survey_loader_html = "<div data-class='lds-hourglass' data-role='loader' class='lds-hourglass ays-survey-wait-loading-loader'></div>";
                break;
            case 'ripple':
                $survey_loader_html = "<div data-class='lds-ripple' data-role='loader' class='lds-ripple ays-survey-wait-loading-loader'><div></div><div></div></div>";
                break;
            case 'snake':
                $survey_loader_html = '<div class="ays-survey-loader-snake ays-survey-wait-loading-loader" data-class="ays-survey-loader-snake" data-role="loader"><div></div><div></div><div></div><div></div><div></div><div></div></div>';
            break;
            // case 'text':
            //     $survey_loader_html = '<div class="ays-survey-loader ays-survey-loader-with-text '.$custom_class.'" data-class="ays-survey-loader-text" data-role="loader">'.$text.'</div>';
            // break;
            // case 'custom_gif':
            //     $survey_loader_html = '<div class="ays-survey-loader ays-survey-loader-with-custom-gif '.$custom_class.'" data-class="ays-survey-loader-cistom-gif" data-role="loader"><img src="'.$gif.'" '.$loading.' style="width: '.$gif_width.'px;object-fit:cover;"></div>';
            // break;
            default:
                $survey_loader_html = "<div data-class='lds-ellipsis' data-role='loader' class='lds-ellipsis ays-survey-wait-loading-loader'><div></div><div></div><div></div><div></div></div>";
            break;
        }
        return $survey_loader_html;
    }

    public static function ays_set_survey_message_variables_data( $id, $survey, $settings_options ){

        /*
         * Survey message variables for Start Page
         */

        $survey = (array)$survey;

        // Survey options 
        $options = ( json_decode($survey['options'], true) != null ) ? json_decode($survey['options'], true) : array();

        // General Setting's Options

        // Do not store IP adressess 
        $disable_user_ip = (isset($settings_options['disable_user_ip']) && $settings_options['disable_user_ip'] == 'on') ? true : false;

        // Survey title
        $survey_title = (isset( $survey['title'] ) && $survey['title'] != "") ? stripslashes( sanitize_text_field($survey['title']) ) : "";

        // Survey create date
        $survey_creation_date = (isset($options['date_created']) && $options['date_created'] != '') ? sanitize_text_field( $options['date_created'] ) : "";
        if( $survey_creation_date != "" ){
            $survey_creation_date = date_i18n( get_option( 'date_format' ), strtotime( $survey_creation_date ) );
        }

        // Current time
        $survey_current_time = explode( ' ', current_time( 'mysql' ) );
        $survey_current_time_only = ($survey_current_time[1]) ? $survey_current_time[1] : '';

        // Get survey author
        $current_survey_user_data = get_userdata( $survey['author_id'] );
        $current_survey_author = '';
        $current_survey_author_email = '';
        if ( isset( $current_survey_user_data ) && $current_survey_user_data ) {
            // Get survey author name
            $current_survey_author = ( isset( $current_survey_user_data->data->display_name ) && $current_survey_user_data->data->display_name != '' ) ? sanitize_text_field( $current_survey_user_data->data->display_name ) : "";
            // Get survey author email
            $current_survey_author_email = ( isset( $current_survey_user_data->data->user_email ) && $current_survey_user_data->data->user_email != '' ) ? sanitize_text_field( $current_survey_user_data->data->user_email ) : "";
        }

        $questions_count = (isset( $survey['questions_count'] ) && $survey['questions_count'] != "") ? stripslashes( sanitize_text_field($survey['questions_count']) ) : 0;

        $survey_question_count      = self::get_survey_questions_count($id);
        $survey_sections_count      = self::get_survey_sections_count($id);
        $survey_passed_users_count  = self::ays_survey_get_passed_users_count($id);

        // WP home page url
        $home_main_url = home_url();
        $wp_home_page_url = '<a href="'.$home_main_url.'" target="_blank">'.$home_main_url.'</a>';

        $survey_user_information = self::get_user_profile_data();
        // Get user first name
        $user_first_name = (isset( $survey_user_information['user_first_name'] ) && $survey_user_information['user_first_name']  != "") ? $survey_user_information['user_first_name'] : '';

        // Get user last name
        $user_last_name  = (isset( $survey_user_information['user_last_name'] ) && $survey_user_information['user_last_name']  != "") ? $survey_user_information['user_last_name'] : '';
        
        // Get user nick name
        $user_nick_name  = (isset( $survey_user_information['user_nickname'] ) && $survey_user_information['user_nickname']  != "") ? $survey_user_information['user_nickname'] : '';

        // Get display name
        $user_display_name  = (isset( $survey_user_information['user_display_name'] ) && $survey_user_information['user_display_name']  != "") ? $survey_user_information['user_display_name'] : '';

        // User Wordpress role
        $user_wordpress_roles = (isset( $survey_user_information['user_wordpress_roles'] ) && $survey_user_information['user_wordpress_roles']  != "") ? $survey_user_information['user_wordpress_roles'] : '';

        // User ip address
        $user_ip_address = "";
        if($disable_user_ip){
            $user_ip_address = '';
        }else{
            $user_ip_address = self::get_user_ip();
        }

        // User wordpress email
        $user_wordpress_email = (isset( $survey_user_information['user_wordpress_email'] ) && $survey_user_information['user_wordpress_email']  != "") ? esc_attr($survey_user_information['user_wordpress_email']) : '';
        
        $super_admin_email = get_option('admin_email');

        $message_data = array(
            'survey_title'                  => $survey_title,
            'survey_id'                     => $id,
            'questions_count'               => $questions_count,
            'current_time'                  => $survey_current_time_only,
            'sections_count'                => $survey_sections_count,
            'users_count'                   => $survey_passed_users_count,
            'users_first_name'              => $user_first_name,
            'users_last_name'               => $user_last_name,
            'users_nick_name'               => $user_nick_name,
            'users_display_name'            => $user_display_name,
            'users_ip_address'              => $user_ip_address,
            'user_wordpress_roles'          => $user_wordpress_roles,
            'creation_date'                 => $survey_creation_date,
            'current_survey_author'         => $current_survey_author,
            'current_survey_author_email'   => $current_survey_author_email,
            'current_survey_page_link'      => $current_survey_author_email,
            'admin_email'                   => $super_admin_email,
            'home_page_url'                 => $wp_home_page_url,
        );

        return $message_data;
    }
    
    public static function ays_survey_get_user_answered_for_submissions_export( $attr ){
        global $wpdb;
        $answers_table               = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "answers";
        $submissions_questions_table = $wpdb->prefix . SURVEY_MAKER_DB_PREFIX . "submissions_questions";

        $submission_id = (isset($attr['submission_id']) && sanitize_text_field( $attr['submission_id'] ) != '') ? absint(intval( $attr['submission_id'] )) : null;
        $survey_id     = (isset($attr['survey_id']) && sanitize_text_field( $attr['survey_id'] ) != '') ? absint(intval( $attr['survey_id'] )) : null;
        $question_id   = (isset($attr['question_id']) && sanitize_text_field( $attr['question_id'] ) != '') ? absint(intval( $attr['question_id'] )) : null;

        $question_type_check = isset($attr['question_type']) && $attr['question_type'] != "" ? $attr['question_type'] : ""; 
        $question_export_check = isset($attr['all_results']) && $attr['all_results'] ? true : false;
        $question_matrix_columns = isset($attr['matrix_columns']) && !empty($attr['matrix_columns']) ? $attr['matrix_columns'] : '';
        $submissions_questions = isset($attr['submission_question']) && !empty($attr['submission_question']) ? $attr['submission_question'] : array();
        $all_answers = isset($attr['all_answers']) && !empty($attr['all_answers']) ? $attr['all_answers'] : array();
        $is_csv = isset($attr['is_csv']) && $attr['is_csv'] ? true : false;
        $question_options_columns = array();
        if( $question_matrix_columns != '' ){
            $question_options_columns = json_decode($question_matrix_columns, true);            
        }

        if ( is_null($submission_id) || is_null($survey_id) || is_null($question_id) ) {
            return array(
                'message' => '',
                'status' => false
            );
        }

        $text_types     = array('text', 'number', 'phone', 'short_text', 'date', 'time' , 'date_time', 'hidden');
        $single_types   = array('radio', 'select', 'linear_scale');
        $multiple_types = array('checkbox');
        $matrix_types   = array('matrix_scale', 'star_list', 'slider_list' , 'matrix_scale_checkbox');

        $sql = "SELECT * FROM {$submissions_questions_table} WHERE submission_id={$submission_id} AND survey_id={$survey_id} AND question_id={$question_id}";
        if(in_array($question_type_check, $matrix_types)){
            $submissions_question = $submissions_questions;
            $matrix_answers = (isset($all_answers[$question_id]) && $all_answers[$question_id] != '') ? $all_answers[$question_id] : array();
            $matrix_scale_answer_ids = array();
            foreach($matrix_answers as $a_key => $a_value){
                $matrix_scale_answer_ids[$a_value['id']] = $a_value['answer'];
            }
        }else{
            $submissions_question = (isset($submissions_questions[0]) && !empty($submissions_questions[0])) ? $submissions_questions[0] : array() ;
            
        }

        $text = '';
        if (! is_null($submissions_question) ) {
            $answer_id     = (isset($submissions_question['answer_id']) && sanitize_text_field( $submissions_question['answer_id'] ) != '') ? sanitize_text_field( $submissions_question['answer_id'] ) : '';
            $question_type = (isset($submissions_question['type']) && sanitize_text_field( $submissions_question['type'] ) != '') ? sanitize_text_field( $submissions_question['type'] ) : '';
            $user_answer   = (isset($submissions_question['user_answer']) && sanitize_text_field( trim($submissions_question['user_answer']) ) != '') ? sanitize_text_field( trim($submissions_question['user_answer']) ) : '';
            $user_variant  = (isset($submissions_question['user_variant']) && sanitize_text_field( trim($submissions_question['user_variant']) ) != '') ? sanitize_text_field( trim($submissions_question['user_variant']) ) : '';
            
            if ( in_array($question_type, $multiple_types) ) {
                $text = array();
                $answer_id_arr = explode(',', $user_answer);
                foreach ($answer_id_arr as $key => $answer_id_value) {
                    if ($answer_id_value == '' || $answer_id_value == 0) {
                        continue;
                    }
                    $answer_id_value = absint(intval($answer_id_value));
                    $result = (isset($all_answers[$question_id][$answer_id_value]) && !empty($all_answers[$question_id][$answer_id_value])) ? $all_answers[$question_id][$answer_id_value] : array();
                    $text[] = ( isset( $result['answer'] ) && $result['answer'] != '' ) ? $result['answer'] : '';
                }
                $text = implode(', ', $text);
            }elseif( in_array($question_type, $single_types) ) {
                if ($answer_id != '' && $answer_id != 0) {
                    $answer_id = absint(intval($answer_id));
                    $result = (isset($all_answers[$question_id][$answer_id]) && !empty($all_answers[$question_id][$answer_id])) ? $all_answers[$question_id][$answer_id] : array();
                    $text   = ( isset( $result['answer'] ) && $result['answer'] != '' ) ? $result['answer'] : '';
                }else{
                    $text = $user_answer;
                }
            }elseif( in_array($question_type_check, $matrix_types) ) {
                if($question_type_check == 'star_list' || $question_type_check == 'slider_list'){
                    if(!$question_export_check){
                        $matrix_results = array();
                        foreach($submissions_question as $m_key => $m_value){
                            $matrix_results[$matrix_scale_answer_ids[$m_value['answer_id']]] = $m_value['user_answer'];
                        }
                        $text = $matrix_results;
                        
                    }else{
                        $matrix_results_all = array();
                        foreach($submissions_question as $m_key => $m_value){
                            if(isset($matrix_scale_answer_ids[ $m_value['answer_id'] ])){
                                $matrix_results_all[] = $matrix_scale_answer_ids[ $m_value['answer_id'] ] . ' : ' . $m_value['user_answer'];
                            }
                        }
                        $text = implode(",\n", $matrix_results_all);
                    }
                } else if($question_type_check == 'matrix_scale'){
                    if(!$question_export_check){
                        $matrix_results = array();
                        foreach($submissions_question as $m_key => $m_valaue){
                            $matrix_results[$matrix_scale_answer_ids[$m_valaue['answer_id']]] = $question_options_columns[$m_valaue['user_answer']];
                        }
                        $text = $matrix_results;
                    }else{
                        $matrix_results_all = array();
                        foreach($submissions_question as $m_key => $m_valaue){
                            $matrix_results_all[] = $matrix_scale_answer_ids[ $m_valaue['answer_id'] ] . ': ' . $question_options_columns[ $m_valaue['user_answer'] ];
                        }
                        $text = implode(",\n", $matrix_results_all);
                        }
                } else if($question_type_check == 'matrix_scale_checkbox'){
                    $devider = ",";
                    if(!$question_export_check){
                        $matrix_results = array();
                        if($is_csv){
                            $devider = ";";
                            
                        }
                        foreach($submissions_question as $m_key => $m_value){
                            $matrix_results_checkbox = array();
                            $matrix_checkbox_value = explode("," , $m_value['user_answer']);
                                foreach($matrix_checkbox_value as $each_answer_key => $each_answer_value){
                                    $matrix_results_checkbox[] = $question_options_columns[$each_answer_value];
                                }
                                
                            $matrix_results[$matrix_scale_answer_ids[$m_value['answer_id']]] = implode($devider , $matrix_results_checkbox);
                            
                        }
                        $text = $matrix_results;
                        }else{
                        $matrix_results_all = array();
                        foreach($submissions_question as $m_key => $m_valaue){
                            $matrix_checkbox_value = explode($devider , $m_valaue['user_answer']);
                            $matrix_results_all[] = $matrix_scale_answer_ids[ $m_valaue['answer_id'] ]. ': ';
                            $loop_iteration = 1;
                            $columns_count = (isset($matrix_checkbox_value) && !empty($matrix_checkbox_value)) ? count($matrix_checkbox_value) : 0;
                            foreach($matrix_checkbox_value as $each_answer_key => $each_answer_value){
                                $matrix_results_all[] = $question_options_columns[ $each_answer_value ];
                                if($loop_iteration != ($columns_count) && $columns_count != 1){
                                    $matrix_results_all[] = $devider;
                                }
                                $loop_iteration++;
                            }
                            $matrix_results_all[] = "\n";
                        }
                        $text = implode(" ", $matrix_results_all);
                    }
                }
            }elseif( in_array($question_type, $text_types) ) {
                $text = $user_answer;
            }else {
                $text = $user_answer;
            }

            if($user_variant != ''){
                if ($text != '') {
                    $text .= ', ' . $user_variant;
                }else{
                    $text = $user_variant;
                }
            }   
        }

        if($text == ''){
            $text = '';
        }

        return $text;
    }

    public static function get_submissions_count_by_survey_ids($ids){
        global $wpdb;
        $submissions_table = $wpdb->prefix . 'socialsurv_submissions';
        
        $survey_users_count_sql = "SELECT COUNT(id) AS submission_count,survey_id FROM {$submissions_table} WHERE survey_id IN ({$ids}) GROUP BY survey_id";
        $submission_count = $wpdb->get_results($survey_users_count_sql, "ARRAY_A");
        
        // Use array_reduce to sum submission_count for each survey_id
        $result_array = array_reduce($submission_count, function ($carry, $item) {
            $surveyId = $item['survey_id'];
            $submission_count = (int)$item['submission_count'];

            if (array_key_exists($surveyId, $carry)) {
                $carry[$surveyId] += $submission_count;
            } else {
                $carry[$surveyId] = $submission_count;
            }

            return $carry;
        }, []);
        
        return $result_array;

    }

}
