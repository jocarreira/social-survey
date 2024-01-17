<?php
	$html_name_prefix = 'ays_';
	$name_prefix = 'survey_';

	$gen_options = ($this->settings_obj->ays_get_setting('options') === false) ? array() : json_decode($this->settings_obj->ays_get_setting('options'), true);


	$survey_default_type = (isset($gen_options[$name_prefix . 'default_type']) && $gen_options[$name_prefix . 'default_type'] != '') ? stripslashes($gen_options[$name_prefix . 'default_type']) : null;
	$survey_answer_default_count = (isset($gen_options[$name_prefix . 'answer_default_count']) && $gen_options[$name_prefix . 'answer_default_count'] != '') ? intval($gen_options[$name_prefix . 'answer_default_count']) : null;

    // WP Editor height
    $survey_wp_editor_height = (isset($gen_options[$name_prefix . 'wp_editor_height']) && $gen_options[$name_prefix . 'wp_editor_height'] != '' && $gen_options[$name_prefix . 'wp_editor_height'] != 0) ? absint( esc_attr($gen_options[$name_prefix . 'wp_editor_height']) ) : 100;

	if($survey_default_type === null){
		$survey_default_type = 'radio';
	}

	if($survey_answer_default_count === null){
		$survey_answer_default_count = '1';
	}

	$question_types = array(
		"radio" => __("Radio", $this->plugin_name),
		"checkbox" => __("Checkbox (Multi)", $this->plugin_name),
		"select" => __("Dropdown", $this->plugin_name),
		"linear_scale" => __("Linear Scale", $this->plugin_name),
		"star" => __("Star Rating", $this->plugin_name),
		"text" => __("Paragraph", $this->plugin_name),
		"short_text" => __("Short Text", $this->plugin_name),
		"number" => __("Number", $this->plugin_name),
		"phone" => __("Phone", $this->plugin_name),
		"date" => __("Date", $this->plugin_name),
		"time" => __("Time", $this->plugin_name),
		"date_time" => __("Date and Time", $this->plugin_name),
		"matrix_scale" => __("Matrix Scale", $this->plugin_name),
		"matrix_scale_checkbox" => __("Matrix Scale Checkbox", $this->plugin_name),
		"star_list" => __("Star List", $this->plugin_name),
		"slider_list" => __("Slider List", $this->plugin_name),
		"yesorno" => __("Yes or No", $this->plugin_name),
		"range" => __("Slider", $this->plugin_name),
		"upload" => __("File upload", $this->plugin_name),		
        "html" => __("HTML", $this->plugin_name),
        "hidden" => __("Hidden", $this->plugin_name),
		"email" => __("Email", $this->plugin_name),
		"name" => __("Name", $this->plugin_name),
	);

	$question_types_placeholders = array(
		"radio" => '',
		"checkbox" => '',
		"select" => '',
		"linear_scale" => '',
		"star" => '',
		"text" => __("Your answer", $this->plugin_name),
		"short_text" => __("Your answer", $this->plugin_name),
		"number" => __("Your answer", $this->plugin_name),

		"phone" => __("Phone", $this->plugin_name),
		"matrix_scale" => '',
		"matrix_scale_checkbox" => '',
		"star_list" => '',
		"slider_list" => '',
		"date" => '',
		"time" => '',
		"date_time" => '',
		"yesorno" => '',
		"range" => __("Slider", $this->plugin_name),
		"upload" => __("File upload", $this->plugin_name),		
        "html" => __("Html", $this->plugin_name),
        "hidden" => __("", $this->plugin_name),
		"email" => __("Your email", $this->plugin_name),
		"name" => __("Your name", $this->plugin_name),

	);

	$text_question_types = array(
		"text",
		"short_text",
		"number",
		"phone",
		"email",
		"name",
        "hidden",
	);

	$other_question_types = array(
		"linear_scale",
		"matrix_scale",
		"matrix_scale_checkbox",
		"star_list",
		"slider_list",
		"star",
		"date",
		"time",
		"date_time",
		"range",
		"upload",		
        "html",
	);

	$logic_jump_question_types = array(
		"radio",
		"select",
		"yesorno",
	);

	$other_logic_jump_question_types = array(
		"checkbox",
	);
    
    $have_more_options = array(
        "radio",
        "checkbox",
        "select",
        "yesorno",
    );

	$survey_linear_scale_1 = '';
	$survey_linear_scale_2 = '';
	$survey_star_1 = '';
	$survey_star_2 = '';
	$matrix_columns = array();
	$sections_ids = (isset( $object['section_ids' ] ) && $object['section_ids'] != '') ? $object['section_ids'] : '';
	$sections = Survey_Maker_Data::get_sections_by_survey_id($sections_ids);

	// ----------------------------
		$question['question'] = (isset($question['question']) && $question['question'] != '') ? stripslashes( $question['question'] ) : '';
		$question['question_description'] = (isset($question['question_description']) && $question['question_description'] != '') ? stripslashes( $question['question_description'] ) : '';
		$question['image'] = (isset($question['image']) && $question['image'] != '') ? $question['image'] : '';
		$question['type'] = (isset($question['type']) && $question['type'] != '') ? $question['type'] : $survey_default_type;
		$question['user_variant'] = (isset($question['user_variant']) && $question['user_variant'] == 'on') ? true : false;

		$question_type_for_conditions = (isset($question['type']) && $question['type'] != '') ? $question['type'] : $survey_default_type;

		$opts = json_decode( $question['options'], true );
		$opts['required'] = (isset($opts['required']) && $opts['required'] == 'on') ? true : false;
		// $opts['collapsed'] = 'collapsed'; //(isset($opts['collapsed']) && $opts['collapsed'] != '') ? $opts['collapsed'] : 'collapsed';
		$opts['collapsed'] = ( isset($opts['collapsed']) && $opts['collapsed'] != '' ) ? $opts['collapsed'] : 'expanded';
		$opts['linear_scale_1'] = (isset($opts['linear_scale_1']) && $opts['linear_scale_1'] != '') ? $opts['linear_scale_1'] : '';
		$opts['linear_scale_2'] = (isset($opts['linear_scale_2']) && $opts['linear_scale_2'] != '') ? $opts['linear_scale_2'] : '';

		$survey_linear_scale_1 = (isset($opts['linear_scale_1']) && $opts['linear_scale_1'] != '') ? $opts['linear_scale_1'] : '';
		$survey_linear_scale_2 = (isset($opts['linear_scale_2']) && $opts['linear_scale_2'] != '') ? $opts['linear_scale_2'] : '';

		// Matrix Scale
		$opts['matrix_columns'] = isset($opts['matrix_columns']) ? $opts['matrix_columns'] : array();
		if( ! is_array( $opts['matrix_columns'] ) ){
			$opts['matrix_columns'] = json_decode( $opts['matrix_columns'], true );
		}
		$matrix_columns[$question['id']] = $opts['matrix_columns'];

		// Star list options
		$opts['star_list_stars_length'] = (isset( $opts['star_list_stars_length'] )) ? $opts['star_list_stars_length'] : '';

		// Star list options
		$opts['slider_list_range_length'] = (isset( $opts['slider_list_range_length'] )) ? $opts['slider_list_range_length'] : '';
		$opts['slider_list_range_step_length'] = (isset( $opts['slider_list_range_step_length'] )) ? $opts['slider_list_range_step_length'] : '';
		$opts['slider_list_range_min_value'] = (isset( $opts['slider_list_range_min_value'] )) ? $opts['slider_list_range_min_value'] : '';
		$opts['slider_list_range_default_value'] = (isset( $opts['slider_list_range_default_value'] )) ? $opts['slider_list_range_default_value'] : '';
		$opts['slider_list_range_calculation_type'] = (isset( $opts['slider_list_range_calculation_type'] )) ? $opts['slider_list_range_calculation_type'] : 'seperatly';

		$opts['star_1'] = (isset($opts['star_1']) && $opts['star_1'] != '') ? $opts['star_1'] : '';
		$opts['star_2'] = (isset($opts['star_2']) && $opts['star_2'] != '') ? $opts['star_2'] : '';

		$survey_star_1 = (isset($opts['star_1']) && $opts['star_1'] != '') ? $opts['star_1'] : '';
		$survey_star_2 = (isset($opts['star_2']) && $opts['star_2'] != '') ? $opts['star_2'] : '';

		$opts['enable_max_selection_count'] = (isset($opts['enable_max_selection_count']) && $opts['enable_max_selection_count'] == 'on') ? true : false;
		$opts['max_selection_count'] = (isset($opts['max_selection_count']) && $opts['max_selection_count'] != '') ? intval( $opts['max_selection_count'] ) : '';
		$opts['min_selection_count'] = (isset($opts['min_selection_count']) && $opts['min_selection_count'] != '') ? intval( $opts['min_selection_count'] ) : '';
		// Text Limitations
		$opts['enable_word_limitation'] = (isset($opts['enable_word_limitation']) && $opts['enable_word_limitation'] == 'on') ? true : false;
		$opts['limit_by']      = (isset($opts['limit_by']) && $opts['limit_by'] != '') ? sanitize_text_field($opts['limit_by'])  : '';
		$opts['limit_length']  = (isset($opts['limit_length']) && $opts['limit_length'] != '') ? intval( $opts['limit_length'] ) : '';
		$opts['limit_counter'] = (isset($opts['limit_counter']) && $opts['limit_counter'] == 'on') ? true : false;
		// Number Limitations
		$opts['enable_number_limitation']    = (isset($opts['enable_number_limitation']) && $opts['enable_number_limitation'] == 'on') ? true : false;
		$opts['number_min_selection']        = (isset($opts['number_min_selection']) && $opts['number_min_selection'] != '') ? intval( $opts['number_min_selection'] )  : '';
		$opts['number_max_selection']        = (isset($opts['number_max_selection']) && $opts['number_max_selection'] != '') ? intval( $opts['number_max_selection'] ) : '';
		$opts['number_error_message']        = (isset($opts['number_error_message']) && $opts['number_error_message'] != '') ? stripslashes( esc_attr($opts['number_error_message'])) : '';
		$opts['enable_number_error_message'] = (isset($opts['enable_number_error_message']) && $opts['enable_number_error_message'] == 'on') ? true : false;
		$opts['number_limit_length']         = (isset($opts['number_limit_length']) && $opts['number_limit_length'] != '') ? stripslashes( esc_attr($opts['number_limit_length'])) : '';
		$opts['enable_number_limit_counter'] = (isset($opts['enable_number_limit_counter']) && $opts['enable_number_limit_counter'] == 'on') ? true : false;

		// Input types placeholders
		$opts['placeholder'] = (isset($opts['survey_input_type_placeholder']) && $opts['survey_input_type_placeholder'] != '') ? stripslashes(esc_attr($opts['survey_input_type_placeholder'])) : $question_types_placeholders[$question['type']];

		// Question Caption
		$opts['image_caption'] = (isset($opts['image_caption']) && $opts['image_caption'] != '') ? stripslashes(esc_attr($opts['image_caption'])) : '';
		$opts['image_caption_enable'] = (isset($opts['image_caption_enable']) && $opts['image_caption_enable'] == 'on') ? true : false;

		$opts['with_editor'] = ! isset( $opts['with_editor'] ) ? 'off' : $opts['with_editor'];
		$opts['with_editor'] = $opts['with_editor'] == 'on' ? true : false;

		// Is logic jump
		$opts['is_logic_jump'] = isset( $opts['is_logic_jump'] ) && $opts['is_logic_jump'] == 'on' ? true : false;

		// Other answer logic jump
		$opts['other_answer_logic_jump'] = (isset($opts['other_answer_logic_jump']) && $opts['other_answer_logic_jump'] != '') ? $opts['other_answer_logic_jump'] : '-1';

		// User explanation
		$opts['user_explanation'] = isset( $opts['user_explanation'] ) && $opts['user_explanation'] == 'on' ? true : false;

		// Admin note
		$opts['enable_admin_note'] = (isset( $opts['enable_admin_note'] ) && $opts['enable_admin_note'] == 'on') ? true : false;
		$opts['admin_note'] = (isset( $opts['admin_note'] ) && $opts['admin_note'] != '') ? stripslashes( esc_attr( $opts['admin_note'] ) ) : "";

		// URL Parameter
		$opts['enable_url_parameter'] = (isset( $opts['enable_url_parameter'] ) && $opts['enable_url_parameter'] == 'on') ? true : false;
		$opts['url_parameter'] = (isset( $opts['url_parameter'] ) && $opts['url_parameter'] != '') ? stripslashes( esc_attr( $opts['url_parameter'] ) ) : "";    

		// Question Hide Results
		$opts['enable_hide_results'] = (isset( $opts['enable_hide_results'] ) && $opts['enable_hide_results'] == 'on') ? true : false;

		// Range type
		$opts['range_length']        = (isset( $opts['range_length'] ) && $opts['range_length'] != '') ? esc_attr($opts['range_length']) : '';
		$opts['range_step_length']   = (isset( $opts['range_step_length'] ) && $opts['range_step_length'] != '') ? esc_attr($opts['range_step_length']) : '';
		$opts['range_min_value']     = (isset( $opts['range_min_value'] ) && $opts['range_min_value'] != '') ? esc_attr($opts['range_min_value']) : '';
		$opts['range_default_value'] = (isset( $opts['range_default_value'] ) && $opts['range_default_value'] != '') ? esc_attr($opts['range_default_value']) : '';

		// Upload type
		$opts['file_upload_toggle'] = (isset( $opts['file_upload_toggle'] ) && $opts['file_upload_toggle'] == 'on') ? true : false;
		$opts['file_upload_types_pdf'] = (isset( $opts['file_upload_types_pdf'] ) && $opts['file_upload_types_pdf'] == 'on') ?  true : false;
		$opts['file_upload_types_doc'] = (isset( $opts['file_upload_types_doc'] ) && $opts['file_upload_types_doc'] == 'on') ?  true : false;
		$opts['file_upload_types_png'] = (isset( $opts['file_upload_types_png'] ) && $opts['file_upload_types_png'] == 'on') ?  true : false;
		$opts['file_upload_types_jpg'] = (isset( $opts['file_upload_types_jpg'] ) && $opts['file_upload_types_jpg'] == 'on') ?  true : false;
		$opts['file_upload_types_gif'] = (isset( $opts['file_upload_types_gif'] ) && $opts['file_upload_types_gif'] == 'on') ?  true : false;
		$opts['file_upload_types_size'] = (isset( $opts['file_upload_types_size'] ) && $opts['file_upload_types_size'] != '') ? esc_attr($opts['file_upload_types_size']) : "5";
		
		$q_answers = Survey_Maker_Data::get_answers_by_question_id( $question_id );

		// Checkbox type logic jump
		$opts['other_logic_jump'] = isset($opts['other_logic_jump']) && !empty($opts['other_logic_jump']) ? $opts['other_logic_jump'] : array();
		$opts['other_logic_jump_otherwise'] = isset( $opts['other_logic_jump_otherwise'] ) && $opts['other_logic_jump_otherwise'] !== '' ? intval( $opts['other_logic_jump_otherwise'] ) : -1;

		foreach ($q_answers as $answer_key => $answer) {
			$q_answers[$answer_key]['answer'] = (isset($answer['answer']) && $answer['answer'] != '') ? stripslashes( htmlentities( $answer['answer'] ) ) : '';
			$q_answers[$answer_key]['image'] = (isset($answer['image']) && $answer['image'] != '') ? $answer['image'] : '';
			$q_answers[$answer_key]['placeholder'] = (isset($answer['placeholder']) && $answer['placeholder'] != '') ? $answer['placeholder'] : '';

			$answer['options'] = !isset( $answer['options'] ) ? json_encode( array() ) : $answer['options'];
			if( $answer['options'] == '' || $answer['options'] == null ){
				$answer['options'] = json_encode( array() );
			}

			$ansopts = json_decode( $answer['options'], true );
			$ansopts['go_to_section'] = (isset($ansopts['go_to_section']) && $ansopts['go_to_section'] != '') ? $ansopts['go_to_section'] : '-1';

			$q_answers[$answer_key]['options'] = $ansopts;
		}

		$question['answers'] = $q_answers;

		$question['options'] = $opts;

	// -------------------------

