<?php

class Survey_Maker_Frontend_Requests_Public {

	private $plugin_name;
	private $version;
    private $html_class_prefix = 'ays-survey-front-request-';
    private $html_name_prefix = 'ays_survey_front_request_';

	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		
		add_shortcode('ays_survey_request_form', array($this, 'ays_survey_front_request_generate_shortcode'));
	}

	public function enqueue_styles() {
		wp_enqueue_style( $this->plugin_name.'-front-request', SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-front-request.css', array(), $this->version, 'all' );
		wp_enqueue_style( $this->plugin_name . "-font-awesome", SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-font-awesome.min.css', array(), $this->version, 'all' );
	}

	public function enqueue_scripts() {
		wp_enqueue_script( $this->plugin_name.'-front-request', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-front-request.js', array( 'jquery' ), $this->version, false );
		wp_enqueue_script( $this->plugin_name . '-sweetalert-js', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-sweetalert2.all.min.js', array('jquery'), $this->version, true );
		wp_localize_script($this->plugin_name.'-front-request',  'aysSurveyFrontRequestPublic', array(
			'ajax_url' => admin_url('admin-ajax.php'),
			'icons' => array(
				'radio'  => SURVEY_MAKER_ADMIN_URL . '/images/icons/radio-button-unchecked.svg',
				'checkbox'     => SURVEY_MAKER_ADMIN_URL . '/images/icons/checkbox-unchecked.svg',
			)
		));
	}

	public function ays_survey_get_front_request_content_data(){
		global $wpdb;
		
		$survey_catergories_table = $wpdb->prefix . "socialsurv_survey_categories";

		$sql = "SELECT id,title FROM {$survey_catergories_table} WHERE `status`='published'";
		$survey_categories = $wpdb->get_results($sql,'ARRAY_A');

		$survey_question_types = array(
			'radio'	   	=> 'Radio',
			'checkbox' 	=> 'Checkbox (Multi)',
			'select' 	=> 'Dropdown',
			'text' 		=> 'Paragraph',
			'number'	=> 'Number',
			'short_text'=> 'Short Text',
			'name' 		=> 'Name',
			'email'		=> 'Email',
		);

		$content_array = array(
			'categories' 	   	  => $survey_categories,
			'survey_question_types' => $survey_question_types,
		);

		return $content_array;
	}

	public function ays_survey_generate_front_request_html(){

		if (! is_user_logged_in()) {
        	return false;
        }

		$content_data = $this->ays_survey_get_front_request_content_data();
		$categories = (isset($content_data['categories']) && !empty($content_data['categories'])) ? $content_data['categories'] : array();
		$survey_question_types = (isset($content_data['survey_question_types']) && !empty($content_data['survey_question_types'])) ? $content_data['survey_question_types'] : array();
		
		$content =  '';

		if (!empty($categories) && !empty($survey_question_types)) {
			$content .= '<div class="'.$this->html_class_prefix.'container">';
				$content .= '<div class="'.$this->html_class_prefix.'content">';
					$content .= '<div class="'.$this->html_class_prefix.'header">';
						$content .= '<span>'.__('Build your Survey in a few minutes',$this->plugin_name).'</span>';
					$content .= '</div>';
					$content .= '<div class="'.$this->html_class_prefix.'body">';
						$content .= '<div class="'.$this->html_class_prefix.'preloader">';
							$content .= '<img src="'.SURVEY_MAKER_PUBLIC_URL.'/images/cogs.svg" alt="loader">';
						$content .= '</div>';
						$content .= '<form method="post" id="'.$this->html_name_prefix.'form">';
							$content .= '<div class="'.$this->html_class_prefix.'body-content">';
								//Survey Title
								$content .= '<div class="'.$this->html_class_prefix.'row">';
									$content .= '<div class="'.$this->html_class_prefix.'col-3">';
										$content .= '<div class="'.$this->html_class_prefix.'survey-title-content">';
											$content .= '<label for="'.$this->html_name_prefix.'survey_title"><span>'.__('Survey Title',$this->plugin_name).'</span></label>';
										$content .= '</div>';
									$content .= '</div>';
									$content .= '<div class="'.$this->html_class_prefix.'col-9">';
										$content .= '<input type="text" name="'.$this->html_name_prefix.'survey_title" id="'.$this->html_name_prefix.'survey_title">';
									$content .= '</div>';
								$content .= '</div>'; 
								$content .= '<hr>';

								//Survey Category
								$content .= '<div class="'.$this->html_class_prefix.'row">';
									$content .= '<div class="'.$this->html_class_prefix.'col-3">';
										$content .= '<div class="'.$this->html_class_prefix.'survey-category-content">';
											$content .= '<label for="'.$this->html_name_prefix.'survey_category"><span>'. __('Survey Category',$this->plugin_name).'</span></label>';
										$content .= '</div>';
									$content .= '</div>';
									$content .= '<div class="'.$this->html_class_prefix.'col-9">';
										$content .= '<select type="text" name="'.$this->html_name_prefix.'survey_category" id="'.$this->html_name_prefix.'survey_category">';
											foreach ($categories as $key => $category) {
												$category_title = (isset($category['title']) && $category['title'] != '') ? sanitize_text_field($category['title']) : '';
												$category_id = (isset($category['id']) && $category['id'] != '') ? absint($category['id']) : 1;
												$content .= '<option value="'.$category_id.'">'.$category_title.'</option>';
											}
										$content .= '</select>';
									$content .= '</div>';
								$content .= '</div>';
								$content .= '<hr>';
	
								//Add question
								$content .= '<div class="'.$this->html_class_prefix.'row">';
									$content .= '<div class="'.$this->html_class_prefix.'col-12">';
										$content .= '<div class="'.$this->html_name_prefix.'question-container-title">';
											$content .= '<span class="'.$this->html_name_prefix.'question_container-title">'. __( 'Questions', $this->plugin_name ) .'</span>';
										$content .= '</div>';
	
										$content .= '<div class="'.$this->html_class_prefix.'survey-add-question-container">';
											$content .= '<a href="javascript:void(0)"  class="'.$this->html_class_prefix.'add-question">';	
												$content .= '<i class="ays_fa_front_req_survey ays_fa_plus_square"></i>';
												$content .= '<span>'. __( 'Add question', $this->plugin_name ) .'</span>';	
											$content .= '</a>';	
										$content .= '</div>';
	
									$content .= '</div>';
								$content .= '</div>';
								$content .= '<hr>';
	
								//Survey Question Container
								$content .= '<div class="'.$this->html_class_prefix.'survey-question-container" data-id="1">';
									$content .= '<div class="'.$this->html_class_prefix.'survey-question-content" data-id="1">';
										$content .= '<div class="'.$this->html_class_prefix.'row">';
											$content .= '<div class="'.$this->html_class_prefix.'col-8">';
												//Survey Question Name
												$content .= '<div class="'.$this->html_class_prefix.'quest-title">';
													$content .= '<input type="text" id="'.$this->html_name_prefix.'quest_title_1" class="'.$this->html_class_prefix.'question" name="'.$this->html_name_prefix.'question[1][question]" placeholder="Question title">';
												$content .= '</div>';
											$content .= '</div>';
	
											$content .= '<div class="'.$this->html_class_prefix.'col-4">';
												//Survey Question Type
												$content .= '<div class="'.$this->html_class_prefix.'question-type">';
													$content .= '<select id="'.$this->html_name_prefix.'quest_type_1" class="'.$this->html_class_prefix.'quest-type" name="'.$this->html_name_prefix.'question[1][type]" data-type="radio">';
														foreach ($survey_question_types as $key => $survey_question_type) {
															$content .= '<option value="'.$key.'">'.$survey_question_type.'</option>';
														}
													$content .= '</select >';
												$content .= '</div>';
											$content .='</div>';
										$content .='</div>';
	
										//Survey Question Answer
										$content .='<div class="'.$this->html_class_prefix.'answer">';
	
											$content .= '<span class="'.$this->html_name_prefix.'answers_container-title">'.__('Answers',$this->plugin_name).'</span>';
	
											$content .= '<div class="'.$this->html_class_prefix.'row">';
												$content .= '<div class="'.$this->html_class_prefix.'answer-content '.$this->html_class_prefix.'col-8">';
													$content .= '<div class="'.$this->html_class_prefix.'radio-answer">';
														$content .= '<div class="'.$this->html_class_prefix.'answer-row">';
															$content .= '<img src="' . SURVEY_MAKER_ADMIN_URL . '/images/icons/radio-button-unchecked.svg">';
															$content .= '<input type="text" class="'.$this->html_class_prefix.'answer-input" placeholder="Answer text" name="'.$this->html_name_prefix.'question[1][answers][1][answer]">';
															$content .= '<a href="javascript:void(0)" class="'.$this->html_class_prefix.'delete-answer" title="' . __( "Delete", $this->plugin_name ) . '">';
																$content .= '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
															$content .= '</a>';
														$content .= '</div>';
														$content .= '<div class="'.$this->html_class_prefix.'answer-row">';
															$content .= '<img src="' . SURVEY_MAKER_ADMIN_URL . '/images/icons/radio-button-unchecked.svg">';
															$content .= '<input type="text" class="'.$this->html_class_prefix.'answer-input" placeholder="Answer text" name="'.$this->html_name_prefix.'question[1][answers][2][answer]">';
															$content .= '<a href="javascript:void(0)" class="'.$this->html_class_prefix.'delete-answer" title="' . __( "Delete", $this->plugin_name ) . '">';
																$content .= '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
															$content .= '</a>';
														$content .= '</div>';
														$content .= '<div class="'.$this->html_class_prefix.'answer-row">';
															$content .= '<img src="' . SURVEY_MAKER_ADMIN_URL . '/images/icons/radio-button-unchecked.svg">';
															$content .= '<input type="text" class="'.$this->html_class_prefix.'answer-input" placeholder="Answer text" name="'.$this->html_name_prefix.'question[1][answers][3][answer]">';
															$content .= '<a href="javascript:void(0)" class="'.$this->html_class_prefix.'delete-answer" title="' . __( "Delete", $this->plugin_name ) . '">';
																$content .= '<i class="ays_fa_front_req_survey ays_fa_times"></i>';
															$content .= '</a>';
														$content .= '</div>';
													$content .= '</div>';
												$content .= '</div>';
											$content .= '</div>';
	
											$content .= '<hr>';
											
											$content .= '<div class="'.$this->html_class_prefix.'questions-actions-container">';
												$content .= '<div class="'.$this->html_class_prefix.'survey-add-answer-container">';
													$content .= '<a href="javascript:void(0)" class="'.$this->html_class_prefix.'add-answer">';
														$content .= '<i class="ays_fa_front_req_survey ays_fa_plus_square"></i>';
														$content .= '<span>'.__('Add answer',$this->plugin_name).'</span>';	
													$content .= '</a>';	
												$content .= '</div>';
	
												$content .= '<div class="'.$this->html_class_prefix.'answer-duplicate-delete-content">';
													$content .= '<a href="javascript:void(0)" class="'.$this->html_class_prefix.'clone-question" title="' . __( "Duplicate", $this->plugin_name ) . '">';
														$content .= '<i class="ays_fa_front_req_survey ays_fa_clone"></i>';
													$content .= '</a>';
													$content .= '<a href="javascript:void(0)" class="'.$this->html_class_prefix.'delete-question" title="' . __( "Delete", $this->plugin_name ) . '">';
														$content .= '<i class="ays_fa_front_req_survey ays_fa_trash_o"></i>';
													$content .= '</a>';
												$content .= '</div>';
											$content .= '</div>';
										$content .='</div>';
									$content .= '</div>';
								$content .= '</div>'; 
								$content .= '<hr>';
	
								$content .= '<div class="'.$this->html_class_prefix.'survey-add-question-container">';
									$content .= '<a href="javascript:void(0)"  class="'.$this->html_class_prefix.'add-question">';	
										$content .= '<i class="ays_fa_front_req_survey ays_fa_plus_square"></i>';
										$content .= '<span>'. __( 'Add question', $this->plugin_name ) .'</span>';	
									$content .= '</a>';	
								$content .= '</div>';
								$content .= '<hr>';
	
								$content .= '<div class="'.$this->html_class_prefix.'survey-submit-content">';
									$content .= '<input type="button" name="'.$this->html_name_prefix.'survey_submit" id="'.$this->html_name_prefix.'survey_submit" class="'.$this->html_class_prefix.'survey-submit" value="Submit"/>';
								$content .= '</div>'; 
							$content .= '</div>'; 
						$content .= '</form>';
					$content .= '</div>';
				$content .= '</div>';
			$content .= '</div>';
		}

		return $content;
	}

	public function ays_survey_front_request_generate_shortcode(){
		
		$this->enqueue_styles();
        $this->enqueue_scripts();
		
		$survey_content_front_request = $this->ays_survey_generate_front_request_html();
				
		return str_replace(array("\r\n", "\n", "\r"), "\n", $survey_content_front_request);
	}
}
