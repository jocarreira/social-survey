<?php 

/**
 * 
 */
class Survey_Maker_Chat_Survey extends Survey_Maker_Public
{

	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	private $html_class_prefix = 'ays-survey-';
	private $html_name_prefix = 'ays-survey-';
	private $name_prefix = 'survey_';
	private $options;
	private $unique_id;
	private $settings;
    protected $buttons_texts;

    private $chat_loader = '<svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" style="" width="30px" height="30px" viewBox="25 25 50 50" preserveAspectRatio="xMidYMid">
        <circle cx="35" cy="57.5" r="5" fill="#D6DAF8">
            <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="57.5;42.5;57.5;57.5" keyTimes="0;0.3;0.6;1" dur="0.8s" begin="-0.4799999999999999s"></animate>
        </circle>
        <circle cx="50" cy="57.5" r="5" fill="#D6DAF8">
            <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="57.5;42.5;57.5;57.5" keyTimes="0;0.3;0.6;1" dur="0.8s" begin="-0.32s"></animate>
        </circle>
        <circle cx="65" cy="57.5" r="5" fill="#D6DAF8">
            <animate attributeName="cy" calcMode="spline" keySplines="0 0.5 0.5 1;0.5 0 1 0.5;0.5 0.5 0.5 0.5" repeatCount="indefinite" values="57.5;42.5;57.5;57.5" keyTimes="0;0.3;0.6;1" dur="0.8s" begin="-0.16s"></animate>
        </circle>
    </svg>';
	
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
        $this->settings = new Survey_Maker_Settings_Actions($this->plugin_name);

	}

	public function enqueue_styles() {

        /**
         * This function is provided for demonstration purposes only.
         *
         * An instance of this class should be passed to the run() function
         * defined in Survey_Maker_Loader as all of the hooks are defined
         * in that particular class.
         *
         * The Survey_Maker_Loader will then create the relationship
         * between the defined hooks and the functions defined in this
         * class.
         */

        wp_enqueue_style( $this->plugin_name . "-public-chat-survey", SURVEY_MAKER_PUBLIC_URL . '/css/survey-maker-public-chat-survey.css', array(), $this->version, 'all' );

    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts(){

        wp_enqueue_script( $this->plugin_name . 'public-chat-survey', SURVEY_MAKER_PUBLIC_URL . '/js/survey-maker-public-chat-survey.js', array('jquery'), $this->version, true);

         wp_localize_script( $this->plugin_name . 'public-chat-survey', 'aysSurveyChat', array(
            'chatLoader' => $this->chat_loader,
            'questionPrIconURL' => SURVEY_MAKER_PUBLIC_URL .'/images/group.webp',
        ) );
    }


    public function create_chat( $sections , $unique_id , $options){
        $this->enqueue_styles();
        $this->enqueue_scripts();

        $this->unique_id = $unique_id;
        $this->options = $options;
        $this->buttons_texts = Survey_Maker_Data::ays_set_survey_texts( $this->plugin_name, $this->options );
        $questions = array();
    	foreach ($sections as $key => $section) {
    		foreach ($section['questions'] as $key => $question) {
    			$questions[] = $question;

    		}
    	}

		if( $this->options[ $this->name_prefix . 'enable_randomize_questions' ] ){
            shuffle( $questions );
        }

    	$content = array();
    	$content[] = '<div class="' . $this->html_class_prefix . 'chat-container">';

            $content[] = '<div class="' . $this->html_class_prefix . 'chat-item">';

			$survey_chat_password = false;
			if(isset($this->options[ $this->name_prefix .'enable_password']) && $this->options[ $this->name_prefix .'enable_password'] ){
				$survey_chat_password = true;
				// $content[] = '<div class="' . $this->html_class_prefix . 'section">';
	
					$content[] = '<div class="' . $this->html_class_prefix . 'section-password-content">';
						
						$content[] = '<div style="margin-bottom:10px;">';
							$content[] = '<div>';
								$content[] = $this->options[ $this->name_prefix .'password_message'];
							$content[] = '</div>';
						$content[] = '</div>';
						$content[] = '<div style="margin-bottom:10px;">';
							$content[] = '<div class="' . $this->html_class_prefix . 'question-input-box">';
								$content[] = '<input class="' . $this->html_class_prefix . 'remove-default-border ' . $this->html_class_prefix . 'password ' . $this->html_class_prefix . 'question-input ' . 
												$this->html_class_prefix . 'input" type="password" autocomplete="off" tabindex="0" placeholder="'. __( "Please enter password", $this->plugin_name) .'" />';
								$content[] = '<div class="' . $this->html_class_prefix . 'input-underline" style="margin:0;"></div>';
								$content[] = '<div class="' . $this->html_class_prefix . 'input-underline-animation" style="margin:0;"></div>';
							$content[] = '</div>';
						$content[] = '</div>';
	
						$content[] = '<div class="' . $this->html_class_prefix . 'check-password-block">';
							
							$content[] = '<div class="' . $this->html_class_prefix . 'section-buttons">';
								$content[] = '<div class="' . $this->html_class_prefix . 'section-button-container">';
									$content[] = '<div class="' . $this->html_class_prefix . 'section-button-content">';
	
										$content[] = '<input type="button" class="' . $this->html_class_prefix . 'section-button ays-check-survey-password ays-check-survey-password-chat-mode" value="'. $this->buttons_texts[ 'checkButton' ] .'" />';
	
									$content[] = '</div>';
								$content[] = '</div>';
							$content[] = '</div>';
							
						$content[] = '</div>';
					$content[] = '</div>';
	
				// $content[] = '</div>';
			}
			$display = ($survey_chat_password) ? "style='display: none;'" : "";
                $content[] = '<div class="' . $this->html_class_prefix . 'chat-content" '.$survey_chat_password.' '.$display.'>';
                	$content[] = '<div class="' . $this->html_class_prefix . 'chat-header" >';
                		$content[] = '<div class="' . $this->html_class_prefix . 'chat-header-main" >';
	                		foreach ($questions as $key => $q) {
	                			$content[] = $this->create_question( $q, null, 0 );
	                		}
	                	$content[] = '</div>';		
					$content[] = '</div>';
					$content[] = '<div class="' . $this->html_class_prefix . 'chat-footer" >';
						foreach ($questions as $key => $q) {
                			$content[] = $this->create_answer( $q );
                		}
					$content[] = '</div>';
                	

					$content[] = '<div class="' . $this->html_class_prefix . 'chat-button-container">';
                            $content[] = '<input type="button" class="' . $this->html_class_prefix . 'chat-button ' . $this->html_class_prefix . 'finish-button" value="'. $this->buttons_texts[ 'finishButton' ] .'" />';

                    $content[] = '</div>';
                $content[] = '</div>';

            $content[] = '</div>';

    	$content[] = '</div>';
		if(Survey_Maker_Data::ays_survey_is_elementor()){
			$content[] = '<style>';
			$content[] = '.ays-survey-chat-header-main .ays-survey-chat-question-box:first-child .ays-survey-chat-question-item{
								display: block !important;
							}
							
							
							.ays-survey-chat-item .ays-survey-chat-footer .ays-survey-chat-answer-box:first-child{
								display: block !important;
							}';
			$content[] = '</style>';
		}
    	$content = implode( '', $content );

    	return $content;
    }

	public function create_question( $question, $numbering, $loop ){

    	$question_type = $question['type'];
    	$answers = $question['answers'];
    	$answers_html = array();

    	 $question_types = array(
            "radio",
            "short_text",
            "name",
            "email",
            "yesorno",
        );

		$content = array();
	    if( in_array( $question_type, $question_types ) ){
	   		if($question_type == "yesorno"){
            	$question_type = "radio";
	   		}

			$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-box" data-id="'.$question['id'].'" >';
				$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-animation-dots"   >';
	            		// $content[] = '<img class="ays-survey-chat-animation-icon" src="'. SURVEY_MAKER_PUBLIC_URL .'/images/dots.svg">';
						$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-pre-icon"> <img src="'. SURVEY_MAKER_PUBLIC_URL .'/images/group.webp"> </div>';
						$content[] = '<div>';
							$content[] = $this->chat_loader;
						$content[] = '</div>';
				$content[] = '</div>';
				$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-item" >';
					
		    		$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-header" >';

		    
		            	$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-pre-icon"> <img src="'. SURVEY_MAKER_PUBLIC_URL .'/images/group.webp"> </div>';
		            	$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-title ' . $this->html_class_prefix . 'chat-question-title-request" > <span class="' . $this->html_class_prefix . 'chat-question-title-content"> ' . $question['question'] . '</div>';

		            $content[] = '</div>';

		    		$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-reply"  >';
		            	$content[] = '<div class="' . $this->html_class_prefix . 'chat-question-reply-title"   ></div>';
		           
		            $content[] = '</div>';
	            $content[] = '</div>';
	        $content[] = '</div>';
	                	
    	}
    	$content = implode( '', $content );

    	return $content;
    }

    public function create_answer( $question ){

    	$question_type = $question['type'];
    	$answers = $question['answers'];

        if( $this->options[ $this->name_prefix . 'enable_randomize_answers' ] ){
            shuffle( $question['answers'] );
            shuffle( $answers );
        }

    	$answers_html = array();

    	 $question_types = array(
            "radio",
            "short_text",
			"name",
            "email",
            "yesorno",
        );
		$content = array();
	    if( in_array( $question_type, $question_types ) ){
	   		if($question_type == "yesorno"){
            	$question_type = "radio";
	   		}

	        // $question_types_getting_answers_array = array(
	        //     "radio",
	        // );

	        $question_type_function = 'ays_chat_survey_question_type_' . strtoupper( $question_type ) . '_html';
	        // $transmitting_array = in_array( $question_type, $question_types_getting_answers_array ) ? $answers : $question;
	        $transmitting_array = $question;

	        $answers_html[] = $this->$question_type_function($transmitting_array);

	        $answers_html = implode( '', $answers_html );

			$content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-box" data-questionId="'.$question['id'].'">';
	    		$content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-header" >';

	                    $content[] = '<input type="hidden" name="' . $this->html_name_prefix . 'questions-' . $this->unique_id . '[' . $question['id'] . '][section]" value="' . $question['section_id'] . '">';
	                    $content[] = '<input type="hidden" name="' . $this->html_name_prefix . 'questions-' . $this->unique_id . '[' . $question['id'] . '][questionType]" value="' . $question_type . '">';
	                    $content[] = '<input type="hidden" class="' . $this->html_class_prefix . 'question-id" name="' . $this->html_name_prefix . 'questions-' . $this->unique_id . '[' . $question['id'] . '][questionId]" value="' . $question['id'] . '">';
	            	
	            	 	$content[] = $answers_html;
	            $content[] = '</div>';

	        $content[] = '</div>';
	                	
		}
    	$content = implode( '', $content );

    	return $content;
    }

     public function ays_chat_survey_question_type_RADIO_html( $question ){
		global $wpdb;

		$answers = $question['answers'];

        $content = array();
        foreach ($answers as $key => $answer) {
            
			$question_to_go = '';
			if ($question['options']['is_logic_jump']) {
				if ($answer['options']['go_to_section'] !== -1) {
					$questions_table = $wpdb->prefix . 'socialsurv_questions';
					$section_to_go = $answer['options']['go_to_section'];
					if($section_to_go == -2){
						$question_to_go = 'submit_form';	
					} 
					else{
						$sql = "SELECT id FROM " . $questions_table . " WHERE section_id =" . $section_to_go . " ORDER BY id ASC LIMIT 1";
						$question_to_go = $wpdb->get_var( $sql );
					}
				}
			}
            $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer ">';
            
                $content[] = '<label class="' . $this->html_class_prefix . 'chat-answer-label">';
                    $content[] = '<input class="" type="radio" name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $answer['question_id'] . '][answer]" value="' . $answer['id'] . '">';

                    $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-label-content" data-go-to-question="' . $question_to_go . '">';

                        $content[] = '<span class="">' . $answer['answer'] . '</span>';

                    $content[] = '</div>';
                $content[] = '</label>';

            $content[] = '</div>';
        }
        
    	$content = implode( '', $content );

    	return $content;
    }

     public function ays_chat_survey_question_type_SHORT_TEXT_html( $question ){
        $content = array();

        $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-text">';

        	$content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-text-type">';

	            $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-input-box">';

	                $content[] = '<input class="' . 
	                                $this->html_class_prefix . 'remove-default-border ' . 
	                                $this->html_class_prefix . 'question-input ' . 
	                                $this->html_class_prefix . 'chat-input ' . 
	                                $this->html_class_prefix . 'chat-short-text-input" type="text" style="min-height: 24px;"
	                                placeholder="'. __( "Your answer", $this->plugin_name ) .'"
	                                name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]">';

	            $content[] = '</div>';

	            $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-input-btn">';
	          		$content[] = '<label class="' . 
	                                $this->html_class_prefix . 'chat-answer-input-content ' . 
	                                $this->html_class_prefix . 'chat-answer-short-text-button">
	                                ';
                    	$content[] = '<img  src="'. SURVEY_MAKER_PUBLIC_URL .'/images/send-icon.svg">';   
					$content[] = '</label>';
	                                                     
				$content[] = '</div>';
			$content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }
 
	public function ays_chat_survey_question_type_NAME_html( $question ){
        $content = array();

        $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-text">';

        	$content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-text-type">';

	            $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-input-box">';

	                $content[] = '<input class="' . 
	                                $this->html_class_prefix . 'remove-default-border ' . 
	                                $this->html_class_prefix . 'question-input ' . 
	                                $this->html_class_prefix . 'chat-input ' . 
	                                $this->html_class_prefix . 'chat-short-text-input" type="text" style="min-height: 24px;"
	                                placeholder="'. __( "Your name", $this->plugin_name ) .'"
	                                name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]">';

	            $content[] = '</div>';

	            $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-input-btn">';
	          		$content[] = '<label class="' . 
	                                $this->html_class_prefix . 'chat-answer-input-content ' . 
	                                $this->html_class_prefix . 'chat-answer-short-text-button">
	                                ';
                    	$content[] = '<img  src="'. SURVEY_MAKER_PUBLIC_URL .'/images/send-icon.svg">';   
					$content[] = '</label>';
	                                                     
				$content[] = '</div>';
			$content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }
	
	public function ays_chat_survey_question_type_EMAIL_html( $question ){
        $content = array();

        $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-text">';

        	$content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-text-type">';

	            $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-input-box">';

	                $content[] = '<input class="' . 
	                                $this->html_class_prefix . 'remove-default-border ' . 
	                                $this->html_class_prefix . 'question-input ' . 
	                                $this->html_class_prefix . 'chat-input ' . 
	                                $this->html_class_prefix . 'chat-short-text-input" type="email" style="min-height: 24px;"
	                                placeholder="'. __( "Your email", $this->plugin_name ) .'"
	                                name="' . $this->html_name_prefix . 'answers-' . $this->unique_id . '[' . $question['id'] . '][answer]">';

	            $content[] = '</div>';

	            $content[] = '<div class="' . $this->html_class_prefix . 'chat-answer-input-btn">';
	          		$content[] = '<label class="' . 
	                                $this->html_class_prefix . 'chat-answer-input-content ' . 
	                                $this->html_class_prefix . 'chat-answer-short-text-button">
	                                ';
                    	$content[] = '<img  src="'. SURVEY_MAKER_PUBLIC_URL .'/images/send-icon.svg">';   
					$content[] = '</label>';
	                                                     
				$content[] = '</div>';
			$content[] = '</div>';
        $content[] = '</div>';

        $content = implode( '', $content );

        return $content;
    }



}