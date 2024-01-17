<?php
extract($args);
?>
<div id="questions" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'questions') ? 'ays-survey-tab-content-active' : ''; ?>">
    <div class="wrap">
        <div class="ays_survey_container_each_result">
            <div class="ays_survey_response_count">
                <div class="form-group row">
                    <div class="col-sm-6" style="font-size: 13px;"><?php echo __('Responses cannot be edited',SURVEY_MAKER_NAME); ?></div>
                    <div class="col-sm-6 ays-survey-question-action-butons" style="align-items: center;">
                        <span style="min-width: 70px;"><?php echo __("Export to", SURVEY_MAKER_NAME); ?></span>
                        <a download="" id="downloadFile" hidden href=""></a>
                        <button type="button" class="button button-primary ays-survey-export-button ays-survey-single-submission-pdf-export" data-result="<?php echo $submission_first_id; ?>" <?php echo $export_disabled; ?> ><?php echo __("PDF", SURVEY_MAKER_NAME); ?></button>
                        <button type="button" class="button button-primary ays-survey-export-button ays-survey-single-submission-results-export" data-result="<?php echo $submission_first_id; ?>" <?php echo $export_disabled; ?> data-type="xlsx" survey-id="<?php echo $survey_id; ?>"><?php echo __("XLSX", SURVEY_MAKER_NAME); ?></button>
                        <button type="button" class="button button-primary ays-survey-export-button ays-survey-single-submission-results-csv-export" data-result="<?php echo $submission_first_id; ?>" <?php echo $export_disabled; ?> data-type="csv" survey-id="<?php echo $survey_id; ?>"><?php echo __("CSV", SURVEY_MAKER_NAME); ?></button>
                    </div>
                </div>
				<?php
				if( $submissions_count > 0):
					?>
                    <div class="form-group row">
                        <div class="col-sm-6">
                            <h1><?php
								echo $submission_count_and_ids['submission_count'];
								echo __(" Responses",SURVEY_MAKER_NAME);
								?></h1>
                        </div>
                        <div class="col-sm-6 ays-survey-question-action-butons">
                            <button type="button" class="button button-primary ays-survey-submission-questions-print"><?php echo __( 'Print', SURVEY_MAKER_NAME); ?></button>
                        </div>
                    </div>
                    <div class="ays_survey_previous_next_conteiner">
                        <div class="ays_survey_previous_next ays_survey_previous" data-name="ays_survey_previous">
                            <div class="appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Previous response',SURVEY_MAKER_NAME); ?>">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/arrow-left.svg">
                            </div>
                        </div>
                        <div class="ays_submissions_input_box">
                            <div class="" style="position: relative;margin-right: 10px;">
                                <input type="number" class="ays_number_of_result ays-survey-question-input ays-survey-input" value="<?php echo $submission_count_and_ids['submission_count']; ?>" min="1" max="<?php echo $submission_count_and_ids['submission_count']; ?>" badinput="false" autocomplete="off" data-id="<?php echo $survey_id; ?>">
                                <div class="ays-survey-input-underline" style="margin:0;"></div>
                                <div class="ays-survey-input-underline-animation" style="margin:0;"></div>
                            </div>
                            <input type="hidden" class="ays_submissions_id_str" value="<?php echo $submission_count_and_ids['submission_ids']; ?>">
                            <span><?php echo __("of", SURVEY_MAKER_NAME); ?> <?php echo $submission_count_and_ids['submission_count']; ?></span>
                        </div>
                        <div class="ays_survey_previous_next ays_survey_next" data-name="ays_survey_next">
                            <div class="appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Next response',SURVEY_MAKER_NAME); ?>">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/arrow-right.svg">
                            </div>
                        </div>
                    </div>
				<?php
				else:?>
                    <h1><?php
						echo __("There are no responses yet.",SURVEY_MAKER_NAME);
						?></h1>
				<?php
				endif;
				?>
            </div>
			<?php if( intval($submission_count_and_ids['submission_count']) > 0 ):?>
                <div class="ays_survey_each_sub_user_info">
                    <div class="ays_survey_each_sub_user_info_header">
                        <div class="ays_survey_each_sub_user_info_header_text">
                            <span><?php echo __("User Information" , SURVEY_MAKER_NAME); ?></span>
                        </div>
                        <div class="ays_survey_each_sub_user_info_header_button">
                            <button type="button" class="button ays_help" data-toggle="tooltip" title="<?php echo __('Click for copy',SURVEY_MAKER_NAME);?>" data-clipboard-text="<?php echo $survey_data_formated_for_clipboard; ?>"><?php echo __("Copy user info to clipboard" , SURVEY_MAKER_NAME); ?></button>
                        </div>
                    </div>
                    <div class="ays_survey_each_sub_user_info_body ays_survey_copyable_box">
                        <div class="ays_survey_each_sub_user_info_columns">
                            <div ><?php echo __("User Name" , SURVEY_MAKER_NAME); ?></div>
                            <div class="ays_survey_each_sub_user_info_name"><?php echo $individual_user_name; ?></div>
                        </div>
                        <div class="ays_survey_each_sub_user_info_columns">
                            <div ><?php echo __("User Email" , SURVEY_MAKER_NAME); ?></div>
                            <div class="ays_survey_each_sub_user_info_email"><?php echo $individual_user_email; ?></div>
                        </div>
                        <div class="ays_survey_each_sub_user_info_columns">
                            <div ><?php echo __("User IP" , SURVEY_MAKER_NAME);   ?></div>
                            <div class="ays_survey_each_sub_user_info_user_ip"><?php echo $individual_user_ip; ?></div>
                        </div>
                        <div class="ays_survey_each_sub_user_info_columns">
                            <div ><?php echo __("Submission Date" , SURVEY_MAKER_NAME); ?></div>
                            <div class="ays_survey_each_sub_user_info_sub_date"><?php echo $individual_user_date; ?></div>
                        </div>
                        <div class="ays_survey_each_sub_user_info_columns">
                            <div ><?php echo __("Submission ID" , SURVEY_MAKER_NAME); ?></div>
                            <div class="ays_survey_each_sub_user_info_sub_id"><?php echo $individual_user_sub_id; ?></div>
                        </div>
                        <div class="ays_survey_each_sub_user_info_columns <?php echo ($individual_user_password == "") ? "display_none" : "";  ?>">
                            <div ><?php echo __("User password" , SURVEY_MAKER_NAME); ?></div>
                            <div class="ays_survey_each_sub_user_info_password"><?php echo $individual_user_password; ?></div>
                        </div>
                    </div>
                </div>
			<?php endif;?>
            <div class="question_result_container">
                <div class="ays_question_answer" style="position:relative;">
                    <div class="ays-survey-submission-sections">
						<?php
						$checked = '';
						$disabled = '';
						$selected = '';
						$color = '';
						$user_matrix_answer = array();
						$user_star_list_answer = array();
						$user_slider_list_answer = array();
						if( is_array( $ays_survey_individual_questions['sections'] ) ):
							foreach ($ays_survey_individual_questions['sections'] as $section_key => $section) {
								?>
                                <div class="ays-survey-submission-section">
                                    <div class="ays_survey_name" style="border-top-color: <?php echo $survey_for_charts; ?>;">
                                        <h3><?php echo stripslashes( $section['title'] ); ?></h3>
                                        <p><?php echo ($survey_allow_html_in_section_description) ? strip_tags(htmlspecialchars_decode($section['description'] )) : nl2br( $section['description'] ) ?></p>
                                    </div>
									<?php
									foreach ( $section['questions'] as $q_key => $question ) {
										?>
                                        <div class="ays_questions_answers" data-id="<?php echo $question['id']; ?>"  data-type="<?php echo $question['type']; ?>" style="border-left-color: <?php echo $survey_for_charts; ?>;">
                                            <div style="font-size: 23px;"><?php echo stripslashes( nl2br( $question['question'] ) ); ?></div>
											<?php
											$question_type_content = '';
											$user_answer = isset( $ays_survey_individual_questions['questions'][ $question['id'] ] ) ? $ays_survey_individual_questions['questions'][ $question['id'] ] : '';

											$user_explanation = isset( $ays_survey_individual_questions['questions'][ $question['id'] ]['user_explanation'] ) ? $ays_survey_individual_questions['questions'][ $question['id'] ]['user_explanation'] : '';
											$user_explanation = stripslashes( $user_explanation );
											$enable_user_explanation = false;
											if(isset( $question['options'] )){
												$enable_user_explanation = isset( $question['options']['user_explanation'] ) && $question['options']['user_explanation'] == "on" ? true : false;
											}

											if($question['type'] == 'matrix_scale' || $question['type'] == 'matrix_scale_checkbox'){
												$user_matrix_answer = isset( $ays_survey_individual_questions['questions'][ $question['id'] ]['answer_ids'] ) ? $ays_survey_individual_questions['questions'][ $question['id'] ]['answer_ids'] : array();
												$matrix_column_ids = array();
												if( isset( $question['options'] ) ){
													if( is_array( $question['options']['matrix_columns'] ) ){
														$matrix_column_ids = $question['options']['matrix_columns'];
													}else{
														$matrix_column_ids = json_decode($question['options']['matrix_columns'] , true);
													}
												}
											}

											if($question['type'] == 'star_list'){
												$user_star_list_answer = isset( $ays_survey_individual_questions['questions'][ $question['id'] ]['star_list_answer_ids'] ) ? $ays_survey_individual_questions['questions'][ $question['id'] ]['star_list_answer_ids'] : array();
											}

											if($question['type'] == 'slider_list'){
												$user_slider_list_answer = isset( $ays_survey_individual_questions['questions'][ $question['id'] ]['slider_list_answer_ids'] ) ? $ays_survey_individual_questions['questions'][ $question['id'] ]['slider_list_answer_ids'] : array();
											}

											$other_answer = '';
											if( isset( $user_answer['otherAnswer'] ) ){
												$other_answer = $user_answer['otherAnswer'];
											}
											if( isset( $user_answer['answer'] ) ){
												$user_answer = $user_answer['answer'];
											}
											$question_type_content = '';
											if( $question['type'] == 'select' ){
												$question_type_content .= '<div class="ays_each_question_answer">
                                            <select class="ays-survey-submission-select" disabled>
                                                <option value="">' . __( "Choose", SURVEY_MAKER_NAME ) . '</option>';
											}

											if( in_array( $question['type'], $text_types ) ){
												if( !is_array($user_answer) ){
													$user_answer = $user_answer;
												}
												else{
													$user_answer = '';
												}
												$question_type_content .= '<div class="ays_each_question_answer">
                                            <p class="ays_text_answer">' . $user_answer . '</p>
                                        </div>';
											}

											if( $question['type'] == 'linear_scale' ){

												$linear_scale_label_1 = isset( $question['options']['linear_scale_1'] ) && $question['options']['linear_scale_1'] != '' ? $question['options']['linear_scale_1'] : '';
												$linear_scale_label_2 = isset( $question['options']['linear_scale_2'] ) && $question['options']['linear_scale_2'] != '' ? $question['options']['linear_scale_2'] : '';
												$linear_scale_length = isset( $question['options']['scale_length'] ) && $question['options']['scale_length'] != '' ? absint( $question['options']['scale_length'] ) : 5;

												$question_type_content .= '<div class="ays_each_question_answer">';

												$question_type_content .= '<div class="ays-survey-answer-linear-scale">
                                                <label class="ays-survey-answer-linear-scale-label">
                                                    <div class="ays-survey-answer-linear-scale-radio-label" dir="auto"></div>
                                                    <div class="ays-survey-answer-linear-scale-radio">' . stripslashes( $linear_scale_label_1 ) . '</div>
                                                </label>';

												for ($i=1; $i <= $linear_scale_length; $i++) {
													$checked = '';
													if( intval( $user_answer ) == $i ){
														$checked = 'checked';
													}

													$question_type_content .= '<label class="ays-survey-answer-label">
                                                        <div class="ays-survey-answer-linear-scale-radio-label" dir="auto">' . $i . '</div>
                                                        <div class="ays-survey-answer-linear-scale-radio">
                                                            <input type="radio" name="ays-survey-question-linear-scale-' . $question['id'] . '" disabled ' . $checked . ' value="'.$i.'" data-id="' . $i . '" >
                                                            <div class="ays-survey-answer-label-content">
                                                                <div class="ays-survey-answer-icon-content">
                                                                    <div class="ays-survey-answer-icon-ink"></div>
                                                                    <div class="ays-survey-answer-icon-content-1">
                                                                        <div class="ays-survey-answer-icon-content-2" style="border-color:'.$survey_for_charts.' !important;">
                                                                            <div class="ays-survey-answer-icon-content-3" style="border-color:'.$survey_for_charts.' !important;"></div>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </label>';
												}

												$question_type_content .= '<label class="ays-survey-answer-linear-scale-label">
                                                    <div class="ays-survey-answer-linear-scale-radio-label" dir="auto"></div>
                                                    <div class="ays-survey-answer-linear-scale-radio">' . stripslashes( $linear_scale_label_2 ) . '</div>
                                                </label>
                                            </div>
                                        </div>';
											}

											if( $question['type'] == 'star' ){

												$star_label_1 = isset( $question['options']['star_1'] ) && $question['options']['star_1'] != '' ? $question['options']['star_1'] : '';
												$star_label_2 = isset( $question['options']['star_2'] ) && $question['options']['star_2'] != '' ? $question['options']['star_2'] : '';
												$star_scale_length = isset( $question['options']['star_scale_length'] ) && $question['options']['star_scale_length'] != '' ? absint( $question['options']['star_scale_length'] ) : 5;

												$question_type_content .= '<div class="ays_each_question_answer">';

												$question_type_content .= '<div class="ays-survey-answer-star">
                                                <label class="ays-survey-answer-star-label">
                                                    <div class="ays-survey-answer-star-radio-label" dir="auto"></div>
                                                    <div class="ays-survey-answer-star-radio">' . stripslashes( $star_label_1 ) . '</div>
                                                </label>';

												for ($i=1; $i <= $star_scale_length; $i++) {
													$checked = '';
													$icon_class = 'ays_fa_star_o';
													if( intval( $user_answer ) >= $i ){
														$checked = 'checked';
														$icon_class = 'ays_fa_star';
													}

													$question_type_content .= '<label class="ays-survey-answer-label">
                                                        <div class="ays-survey-answer-star-radio-label" dir="auto">' . $i . '</div>
                                                        <div class="ays-survey-answer-star-radio">
                                                            <input type="radio" name="ays-survey-question-star-' . $question['id'] . '" disabled ' . $checked . ' value="'.$i.'" data-id="' . $i . '" >
                                                            <i class="ays_fa ' . $icon_class . ' ays-survey-star-icon"></i>
                                                        </div>
                                                    </label>';
												}

												$question_type_content .= '<label class="ays-survey-answer-star-label">
                                                    <div class="ays-survey-answer-star-radio-label" dir="auto"></div>
                                                    <div class="ays-survey-answer-star-radio">' . stripslashes( $star_label_2 ) . '</div>
                                                </label>
                                            </div>
                                        </div>';
											}
											if( $question['type'] == 'matrix_scale' || $question['type'] == 'matrix_scale_checkbox'){
												$question_type_content .= '<div class="ays-survey-answer-matrix-scale-main">
                                            <div class="ays-survey-answer-matrix-scale-container">';
											}
											if( $question['type'] == 'star_list' ){
												$star_list_stars_length = isset( $question['options']['star_list_stars_length'] ) && $question['options']['star_list_stars_length'] != '' ? absint( $question['options']['star_list_stars_length'] ) : 5;
												$question_type_content .= '<div class="ays-survey-answer-star-list-main">
                                            <div class="ays-survey-answer-star-list-container">';
											}
											if( $question['type'] == 'slider_list' ){


												$question_type_content .= '<div class="ays-survey-answer-slider-list-main">
                                            <div class="ays-survey-answer-slider-list-container">';
											}

											if( $question['type'] == 'range' ){

												$range_type_length = isset( $question['options']['range_length'] ) && $question['options']['range_length'] != '' ? esc_attr(intval($question['options']['range_length'])) : 100;
												$range_type_step_length = isset( $question['options']['range_step_length'] ) && $question['options']['range_step_length'] != '' ? esc_attr(intval($question['options']['range_step_length'])) : 1;
												$range_type_min_value     = (isset( $question['options']['range_min_value'] ) && $question['options']['range_min_value'] != '') ? esc_attr(intval($question['options']['range_min_value'])) : 0;
												$range_type_default_value = (isset( $question['options']['range_default_value'] ) && $question['options']['range_default_value'] != '') ? esc_attr(intval($question['options']['range_default_value'])) : 0;
												if($range_type_length == 0){
													$range_type_length = 100;
												}
												if($range_type_step_length == 0){
													$range_type_step_length = 1;
												}
												if($user_answer == ""){
													$user_answer = 0;
												}
												$user_range_answer = absint( $user_answer );
												$left = 0;

												$left = ( $user_range_answer - $range_type_min_value ) * 100 / ( $range_type_length - $range_type_min_value );
												// if(intval($user_range_answer) < ($range_type_length - $range_type_min_value) / 2){
												//     $left += 5;
												// }else{
												//     $left -= 5;
												// }
												// if($left < 0){
												//     $left = 0;
												// }

												$leftOffset = 'calc( ' .  $left . '% + ' . ( 9 - $left * 0.18 ) . 'px )';
												$question_type_content .= '<div class="ays_each_question_answer">';
												$question_type_content .= '<div class="ays-survey-answer-range-type-main">';
												$question_type_content .= '<div class="ays-survey-answer-range-type-min-max-val">' . __( 'Min', SURVEY_MAKER_NAME ) . ' ' . $range_type_min_value . '</div>';

												$question_type_content .= '<div class="ays-survey-answer-range-type-range">';
												$question_type_content .= '<span class="ays-survey-answer-range-type-info-text" style="left: '. $leftOffset .';">'.$user_range_answer.'</span>';
												$question_type_content .= '<input type="range" class="ays-survey-range-type-input" min="' . $range_type_min_value . '" max="'.$range_type_length.'" value="'.$user_range_answer.'" disabled>';
												$question_type_content .= '</div>';

												$question_type_content .= '<div class="ays-survey-answer-range-type-min-max-val">' . __( 'Max', SURVEY_MAKER_NAME ) . ' ' . $range_type_length . '</div>';
												$question_type_content .= '</div>';
												$question_type_content .= '</div>';
											}

											if( $question['type'] == 'upload' ){
												$file_name = "";
												$enable_upload_container = false;
												if($user_answer != '0'){
													$file_name = wp_basename($user_answer);
													$enable_upload_container = true;
												}
												$upload_container = $enable_upload_container ? "" : "display_none_not_important";
												$dont_show_box = $file_name ? "" : "display_none";
												$question_type_content .= '<div class="ays-survey-answer-upload-ready '.$upload_container.'" >';
												// if($file_name){
												$question_type_content .= '<a href="'.$user_answer.'" class="ays-survey-answer-upload-ready-link '.$dont_show_box.'" download="'.$file_name.'">'.$file_name.'</a>';
												// }
												$question_type_content .= '</div>';
											}

											$loop_iteration = 0;
											$width = 0;

											foreach ($question['answers'] as $key => $answer) {
												$checked = '';
												$selected = '';
												$disabled = 'disabled';
												$color = '#777';
												// $color = 'black';

												$answer_content = $allow_html_in_answers ? $answer['answer'] : htmlentities( $answer['answer'] );
												switch( $question['type'] ){
													case 'radio':
													case 'yesorno':
														if( intval( $user_answer ) == intval( $answer['id'] ) ){
															$checked = 'checked';
														}
														$question_type_content .= '<div class="ays_each_question_answer">
                                                    <label style="color:' . $color . '">
                                                        <input type="radio" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '"/>
                                                        <div class="ays-survey-answer-label-content">
                                                            <div class="ays-survey-answer-icon-content">
                                                                <div class="ays-survey-answer-icon-ink"></div>
                                                                <div class="ays-survey-answer-icon-content-1">
                                                                    <div class="ays-survey-answer-icon-content-2" style="border-color:'.$survey_for_charts.' !important;">
                                                                        <div class="ays-survey-answer-icon-content-3" style="border-color:'.$survey_for_charts.' !important;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span style="font-size: 17px;">' . stripslashes( $answer_content ) . '</span> 
                                                        </div>
                                                    </label>
                                                </div>';
														break;
													case 'checkbox':
														if( is_array( $user_answer ) && !empty( $user_answer ) && in_array( $answer['id'], $user_answer ) ){
															$checked = 'checked';
														}elseif( intval( $user_answer ) == intval( $answer['id'] ) ){
															$checked = 'checked';
														}
														$question_type_content .= '<div class="ays_each_question_answer">
                                                    <label style="color:' . $color . '">
                                                        <input type="checkbox" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '"/>
                                                        <div class="ays-survey-answer-label-content">
                                                            <div class="ays-survey-answer-icon-content">
                                                                <div class="ays-survey-answer-icon-ink"></div>
                                                                <div class="ays-survey-answer-icon-content-1">
                                                                    <div class="ays-survey-answer-icon-content-2" style="border-color:'.$survey_for_charts.' !important;">
                                                                        <div class="ays-survey-answer-icon-content-3" style="border-color:'.$survey_for_charts.' !important;"></div>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <span style="font-size: 17px;">' . stripslashes( $answer_content ) . '</span> 
                                                        </div>
                                                    </label>
                                                </div>';
														break;
													case 'select':
														if( intval( $user_answer ) == intval( $answer['id'] ) ){
															$selected = 'selected';
														}
														$question_type_content .= '<option value=' . $answer['id'] . ' ' . $selected . '>' . stripslashes( $answer_content ) . '</option>';
														break;
													case 'matrix_scale':
													case 'matrix_scale_checkbox':
														$content = array();
														$row_spacer = '<div class="ays-survey-answer-matrix-scale-row-spacer"></div>';
														if($loop_iteration == 0){
															$content[] = '<div class="ays-survey-answer-matrix-scale-row">';
															$content[] = '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-row-header"></div>';
															foreach($matrix_column_ids as $q_key => $q_value){
																$content[] = '<div class="ays-survey-answer-matrix-scale-column">' . $q_value . '</div>';
															}
															$content[] = "</div>";
															$content[] = $row_spacer;
														}

														$rows_content = array();
														$rows_content[] = '<div class="ays-survey-answer-matrix-scale-row">';
														$rows_content[] = '<div class="ays-survey-answer-matrix-scale-row-content">';
														$rows_content[] = '<div class="ays-survey-answer-matrix-scale-column ays-survey-answer-matrix-scale-column-row-header">' . stripslashes( $answer_content ) . '</div>';
														foreach($matrix_column_ids as $a_key => $a_value){
															$checked = '';
															$question_matrix_type = "radio";
															if($question['type'] == 'matrix_scale_checkbox'){
																$question_matrix_type = "checkbox";
																if(isset($user_matrix_answer[$answer['id']]) && in_array($a_key , $user_matrix_answer[$answer['id']])){
																	$checked = 'checked';
																}
															}
															else{
																if(isset($user_matrix_answer[$answer['id']]) && $user_matrix_answer[$answer['id']] == $a_key){
																	$checked = 'checked';
																}
															}
															$rows_content[] = '<div class="ays-survey-answer-matrix-scale-column ays_each_question_answer">';
															$rows_content[] = '<div class="ays-survey-answer-matrix-scale-column-content-wrap">';
															$rows_content[] = '<div class="ays-survey-answer-matrix-scale-column-content">';

															$rows_content[] = '<label class="ays-survey-answer-label ays-survey-answer-label-matrix-row">';
															$rows_content[] = '<input class="" type="'.$question_matrix_type.'" ' . $checked . ' ' . $disabled . ' data-id="' . $answer['id'] . '" data-col-id="'.$a_key.'">';
															$rows_content[] = '<div class="ays-survey-answer-label-content">';
															$rows_content[] = '<div class="ays-survey-answer-icon-content">';
															$rows_content[] = '<div class="ays-survey-answer-icon-ink"></div>';
															$rows_content[] = '<div class="ays-survey-answer-icon-content-1">';
															$rows_content[] = '<div class="ays-survey-answer-icon-content-2" style="border-color:'.$survey_for_charts.' !important;">';
															$rows_content[] = '<div class="ays-survey-answer-icon-content-3" style="border-color:'.$survey_for_charts.' !important;"></div>';
															$rows_content[] = '</div>';
															$rows_content[] = '</div>';
															$rows_content[] = '</div>';
															$rows_content[] = '</div>';
															$rows_content[] = '</label>';

															$rows_content[] = '</div>';
															$rows_content[] = '</div>';
															$rows_content[] = '</div>';
														}
														$rows_content[] = '</div>';
														$rows_content[] = '</div>';
														$rows_content[] = $row_spacer;

														$content[] = implode( '', $rows_content );

														$question_type_content .= implode( '', $content );
														break;
													case 'star_list':
														$content = array();
														$star_list_content = array();

														$row_spacer = '<div class="ays-survey-answer-star-list-row-spacer"></div>';
														if($loop_iteration == 0){
															$star_list_content[] = '<div class="ays-survey-answer-star-list-row">';
															$star_list_content[] = '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-row-header"></div>';
															$star_list_content[] = '<div class="ays-survey-answer-star ays-survey-answer-stars-star-list">';
															for($i=1; $i <= $star_list_stars_length; $i++){
																$checked = '';
																$icon_class = 'ays_fa_star_o';
																if( isset( $user_star_list_answer[$answer['id']] ) && intval( $user_star_list_answer[$answer['id']] ) >= $i ){
																	$checked = 'checked';
																	$icon_class = 'ays_fa_star';
																}
																$star_list_content[] = '<label class="ays-survey-answer-label" style="margin:0">
                                                                        <div class="ays-survey-answer-star-radio-label" dir="auto">' . $i . '</div>
                                                                            
                                                                        </label>';
															}
															$star_list_content[] = "</div>";
															$star_list_content[] = "</div>";
															$star_list_content[] = $row_spacer;
														}
														$star_list_content[] = '<div class="ays-survey-answer-star-list-row">';
														$star_list_content[] = '<div class="ays-survey-answer-star-list-row-content">';

														$star_list_content[] = '<div class="ays-survey-answer-star-list-column ays-survey-answer-star-list-column-row-header" data-answer-id="'.$answer['id'].'">' . stripslashes( $answer_content ) . '</div>';
														$star_list_content[] = '<div class="ays-survey-answer-star ays-survey-answer-stars-star-list">';
														for ($i=1; $i <= $star_list_stars_length; $i++) {
															$checked = '';
															$icon_class = 'ays_fa_star_o';
															if( isset( $user_star_list_answer[$answer['id']] ) && intval( $user_star_list_answer[$answer['id']] ) >= $i ){
																$checked = 'checked';
																$icon_class = 'ays_fa_star';
															}
															$star_list_content[] = '<label class="ays-survey-answer-label" style="margin:0">
                                                                                            <div class="ays-survey-answer-star-radio">
                                                                                                <input type="radio" name="ays-survey-question-star-' . $question['id'] . '" disabled ' . $checked . ' value="'.$i.'" data-id="' . $i . '" class="ays-survey-star-list-radios">
                                                                                                <i class="ays_fa ' . $icon_class . ' ays-survey-star-icon"></i>
                                                                                            </div>
                                                                                        </label>';
														}
														$star_list_content[] = '</div>';
														$star_list_content[] = '</div>';
														$star_list_content[] = '</div>';
														$star_list_content[] = $row_spacer;

														$content[] = implode( '', $star_list_content );

														$question_type_content .= implode( '', $content );
														break;
													case 'slider_list':

														$range_type_length        = (isset( $question['options']['slider_list_range_length'] ) && $question['options']['slider_list_range_length'] != '') ? esc_attr(intval($question['options']['slider_list_range_length'])) : 100;
														$range_type_step_length   = (isset( $question['options']['slider_list_range_step_length'] ) && $question['options']['slider_list_range_step_length'] != '') ? esc_attr(intval($question['options']['slider_list_range_step_length'])) : 1;
														$range_type_min_value     = (isset( $question['options']['slider_list_range_min_value'] ) && $question['options']['slider_list_range_min_value'] != '') ? esc_attr(intval($question['options']['slider_list_range_min_value'])) : 0;
														$range_type_default_value = (isset( $question['options']['slider_list_range_default_value'] ) && $question['options']['slider_list_range_default_value'] != '') ? esc_attr(intval($question['options']['slider_list_range_default_value'])) : 0;
														if($range_type_length == 0){
															$range_type_length = 100;
														}
														if($range_type_step_length == 0){
															$range_type_step_length = 1;
														}
														if($user_answer == ""){
															$user_answer = 0;
														}
														$user_range_answer = isset($user_slider_list_answer[$answer['id']]) ? $user_slider_list_answer[$answer['id']] : 0;

														$left = 0;

														$left = ( $user_range_answer - $range_type_min_value ) * 100 / ( $range_type_length - $range_type_min_value );


														$leftOffset = 'calc( ' .  $left . '% + ' . ( 9 - $left * 0.18 ) . 'px )';


														$content = array();
														$slider_list_content = array();

														$row_spacer = '<div class="ays-survey-answer-slider-list-row-spacer"></div>';
														if($loop_iteration == 0){
															$slider_list_content[] = '<div class="ays-survey-answer-slider-list-row">';
															$slider_list_content[] = '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-row-header"></div>';
															$slider_list_content[] = '<div class="ays-survey-answer-slider-list-column">';
															$slider_list_content[] = '<div class="ays-survey-answer-range-type-min-max-val">';
															$slider_list_content[] = __("Min ".$range_type_min_value , SURVEY_MAKER_NAME);
															$slider_list_content[] = " / ";
															$slider_list_content[] = __("Max ".$range_type_length , SURVEY_MAKER_NAME);
															$slider_list_content[] = "</div>";
															$slider_list_content[] = "</div>";
															$slider_list_content[] = "</div>";
															$slider_list_content[] = $row_spacer;
														}
														$slider_list_content[] = '<div class="ays-survey-answer-slider-list-row">';
														$slider_list_content[] = '<div class="ays-survey-answer-slider-list-row-content">';

														$slider_list_content[] = '<div class="ays-survey-answer-slider-list-column ays-survey-answer-slider-list-column-row-header ays-survey-answer-slider-list-column-row-header-only-slider" data-answer-id="'.$answer['id'].'" >' . stripslashes( $answer_content ) . '</div>';
														$slider_list_content[] = '<div class="ays-survey-answer-range-type-main">';
														$slider_list_content[] = '<div class="ays-survey-answer-range-type-range">';
														$slider_list_content[] = '<span class="ays-survey-answer-range-type-info-text" style="left: '. $leftOffset .';">'.$user_range_answer.'</span>';
														$slider_list_content[] = '<input type="range" class="ays-survey-range-type-input" min="' . $range_type_min_value . '" max="'.$range_type_length.'" value="'.$user_range_answer.'" disabled>';
														$slider_list_content[] = '</div>';
														$slider_list_content[] = '</div>';
														$slider_list_content[] = '</div>';
														$slider_list_content[] = '</div>';
														$slider_list_content[] = $row_spacer;

														$content[] = implode( '', $slider_list_content );

														$question_type_content .= implode( '', $content );
														break;
												}
												$loop_iteration++;
											}

											if( ( $question['type'] == 'radio' || $question['type'] == 'checkbox' || $question['type'] == 'yesorno' ) && $question['user_variant'] == 'on' ){
												$checked = '';
												if( $question['type'] == 'radio' && intval( $user_answer ) == 0 && $other_answer != "" ){
													$checked = 'checked';
												}

												if( $question['type'] == 'checkbox' && !empty( $user_answer ) && in_array( '0', $user_answer ) ){
													$checked = 'checked';
												}

												if( $question['type'] == 'yesorno' && intval( $user_answer ) == 0 && $other_answer != "" ){
													$checked = 'checked';
												}

												$input_type = $question['type'];
												if( $question['type'] == 'yesorno' ){
													$input_type = 'radio';
												}

												$question_type_content .= '<div class="ays_each_question_answer ays-survey-answer-label-other">
                                            <label style="color:' . $color . '">
                                                <input type="'. $input_type .'" ' . $checked . ' ' . $disabled . ' data-id="0"/>
                                                <div class="ays-survey-answer-label-content">
                                                    <div class="ays-survey-answer-icon-content">
                                                        <div class="ays-survey-answer-icon-ink"></div>
                                                        <div class="ays-survey-answer-icon-content-1">
                                                            <div class="ays-survey-answer-icon-content-2" style="border-color:'.$survey_for_charts.' !important;">
                                                                <div class="ays-survey-answer-icon-content-3" style="border-color:'.$survey_for_charts.' !important;"></div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <span style="font-size: 17px;">' . __( 'Other', SURVEY_MAKER_NAME ) . ':</span>
                                                </div>
                                            </label>
                                            <div class="ays-survey-answer-other-text">
                                                <input class="ays-survey-answer-other-input ays-survey-question-input ays-survey-input" disabled type="text" value="' . stripslashes( esc_attr( $other_answer ) ) . '" autocomplete="off" tabindex="0">
                                                <div class="ays-survey-input-underline" style="margin:0;"></div>
                                                <div class="ays-survey-input-underline-animation" style="margin:0;background-color: '.$survey_for_charts.';" ></div>
                                            </div>
                                        </div>';
											}

											if( $question['type'] == 'select' && $key == count( $question['answers'] ) - 1 ){
												$question_type_content .= '</select></div>';
											}

											if( $question['type'] == 'matrix_scale' || $question['type'] == 'slider_list' || $question['type'] == 'star_list' || $question['type'] == 'matrix_scale_checkbox'){
												$question_type_content .= '</div>
                                                            </div>';
											}

											if( $user_explanation != '' ){
												$question_type_content .= "<div class='ays-survey-individual-user-explanation'>
                                                                        <div>
                                                                            <span>" . __('User explanation', SURVEY_MAKER_NAME) . ":</span>
                                                                        </div>
                                                                        <div class='ays-survey-individual-user-explanation-text'>
                                                                            <span>
                                                                                ".$user_explanation."
                                                                            </span>
                                                                        </div>
                                                                   </div>";
											}

											echo $question_type_content;
											?>
                                        </div>
										<?php
									}
									?>
                                </div>
								<?php
							}
						endif;
						?>
                    </div>
                </div>
                <div class="ays_survey_preloader" style="display:none;">
                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL ; ?>/images/loaders/tail-spin-result.svg" alt="" width="100">
                </div>
            </div>
			<?php
			if( $submissions_count > 0):
				?>
                <div class="ays_survey_response_count">
                    <div class="form-group row">
                        <div class="col-sm-6" style="font-size: 13px;"><?php echo __('Responses cannot be edited',SURVEY_MAKER_NAME); ?></div>
                        <div class="col-sm-6 ays-survey-question-action-butons" style="align-items: center;">
                            <span style="min-width: 70px;"><?php echo __("Export to", SURVEY_MAKER_NAME); ?></span>
                            <a download="" id="downloadFile" hidden href=""></a>
                            <button type="button" class="button button-primary ays-survey-export-button ays-survey-single-submission-pdf-export" data-result="<?php echo $submission_first_id; ?>" <?php echo $export_disabled; ?> ><?php echo __("PDF", SURVEY_MAKER_NAME); ?></button>
                            <button type="button" class="button button-primary ays-survey-export-button ays-survey-single-submission-results-export" data-result="<?php echo $submission_first_id; ?>" <?php echo $export_disabled; ?> data-type="xlsx" survey-id="<?php echo $survey_id; ?>"><?php echo __("XLSX", SURVEY_MAKER_NAME); ?></button>
                            <button type="button" class="button button-primary ays-survey-export-button ays-survey-single-submission-results-csv-export" data-result="<?php echo $submission_first_id; ?>" <?php echo $export_disabled; ?> data-type="csv" survey-id="<?php echo $survey_id; ?>"><?php echo __("CSV", SURVEY_MAKER_NAME); ?></button>
                        </div>
                    </div>
					<?php
					if( $submissions_count > 0):
						?>
                        <div class="form-group row">
                            <div class="col-sm-6">
                                <h1><?php
									echo $submission_count_and_ids['submission_count'];
									echo __(" Responses",SURVEY_MAKER_NAME);
									?></h1>
                            </div>
                            <div class="col-sm-6 ays-survey-question-action-butons">
                                <button type="button" class="button button-primary ays-survey-submission-questions-print"><?php echo __( 'Print', SURVEY_MAKER_NAME); ?></button>
                            </div>
                        </div>
                        <div class="ays_survey_previous_next_conteiner">
                            <div class="ays_survey_previous_next ays_survey_previous" data-name="ays_survey_previous">
                                <div class="appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Previous response',SURVEY_MAKER_NAME); ?>">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/arrow-left.svg">
                                </div>
                            </div>
                            <div class="ays_submissions_input_box">
                                <div class="" style="position: relative;margin-right: 10px;">
                                    <input type="number" class="ays_number_of_result ays-survey-question-input ays-survey-input" value="<?php echo $submission_count_and_ids['submission_count']; ?>" min="1" max="<?php echo $submission_count_and_ids['submission_count']; ?>" badinput="false" autocomplete="off" data-id="<?php echo $survey_id; ?>">
                                    <div class="ays-survey-input-underline" style="margin:0;"></div>
                                    <div class="ays-survey-input-underline-animation" style="margin:0;"></div>
                                </div>
                                <input type="hidden" class="ays_submissions_id_str" value="<?php echo $submission_count_and_ids['submission_ids']; ?>">
                                <span><?php echo __("of", SURVEY_MAKER_NAME); ?> <?php echo $submission_count_and_ids['submission_count']; ?></span>
                            </div>
                            <div class="ays_survey_previous_next ays_survey_next" data-name="ays_survey_next">
                                <div class="appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Next response',SURVEY_MAKER_NAME); ?>">
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/arrow-right.svg">
                                </div>
                            </div>
                        </div>
					<?php
					else:?>
                        <h1><?php
							echo __("There are no responses yet.",SURVEY_MAKER_NAME);
							?></h1>
					<?php
					endif;
					?>
                </div>
			<?php
			endif;
			?>
        </div>
    </div>
</div>
