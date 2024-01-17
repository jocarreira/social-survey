<?php
extract($args);
?>
<!-- Questons start -->
<div class="ays-survey-question-answer-conteiner ays-survey-old-question" data-name="questions" data-id="<?php echo $question['id']; ?>">
	<input type="hidden" class="ays-survey-question-collapsed-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][collapsed]" value="<?php echo $question['options']['collapsed']; ?>">
	<input type="hidden" class="ays-survey-question-is-logic-jump" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][is_logic_jump]" value="<?php echo $question['options']['is_logic_jump'] ? 'on' : 'off'; ?>">
	<input type="hidden" class="ays-survey-question-user-explanation" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][user_explanation]" value="<?php echo $question['options']['user_explanation'] ? 'on' : 'off'; ?>">
	<input type="hidden" class="ays-survey-question-admin-note-saver" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][enable_admin_note]" value="<?php echo $question['options']['enable_admin_note'] ? 'on' : 'off'; ?>">
	<input type="hidden" class="ays-survey-question-url-parameter-saver" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][enable_url_parameter]" value="<?php echo $question['options']['enable_url_parameter'] ? 'on' : 'off'; ?>">
	<input type="hidden" class="ays-survey-question-hide-results-saver" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][enable_hide_results]" value="<?php echo $question['options']['enable_hide_results'] ? 'on' : 'off'; ?>">
	<div class="ays-survey-question-wrap-collapsed <?php echo $question['options']['collapsed'] == 'expanded' ? 'display_none' : ''; ?>">
		<div class="ays-survey-question-dlg-dragHandle">
			<div class="ays-survey-icons ays-survey-icons-hidden">
				<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-horizontal.svg">
			</div>
		</div>
		<div class="ays-survey-question-wrap-collapsed-contnet ays-survey-question-wrap-collapsed-contnet-box">
			<div class="ays-survey-question-wrap-collapsed-contnet-text">
				<?php echo $question['question']; ?>
			</div>
			<div>


			<div class="ays-survey-action-expand-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Expand question',SURVEY_MAKER_NAME)?>">
					<div class="ays-question-img-icon-content">
						<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/expand-section.svg">
					</div>
				</div>
				<div class="ays-survey-answer-icon-box ays-survey-question-more-actions droptop ">
					<div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
						<div class="ays-question-img-icon-content">
							<div class="ays-question-img-icon-content-div">
								<div class="ays-survey-icons">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
								</div>
							</div>
						</div>
					</div>
					<div class="dropdown-menu dropdown-menu-right">
						<button type="button" class="dropdown-item ays-survey-action-delete-question"><?php echo __( 'Delete question', SURVEY_MAKER_NAME ); ?></button>
						<button type="button" class="dropdown-item ays-survey-question-action ays-survey-question-id-copy-box" data-action="copy-question-id" onClick="selectElementContents(this)" class="ays_help" data-toggle="tooltip" title="<?php echo __('Click for copy',SURVEY_MAKER_NAME);?>">
							<?php echo __( 'Question ID', SURVEY_MAKER_NAME ); ?>
							<strong class="ays-survey-question-id-copy"  style="font-size:16px; font-style:normal;"  > <?php echo $question['id']; ?></strong>
						</button>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="ays-survey-question-wrap-expanded <?php echo $question['options']['collapsed'] == 'collapsed' ? 'display_none' : ''; ?>">
		<div class="ays-survey-question-conteiner">
			<div class="ays-survey-question-dlg-dragHandle">
				<div class="ays-survey-icons ays-survey-icons-hidden">
					<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-horizontal.svg">
					<input type="hidden" class="ays-survey-question-ordering" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][ordering]" value="<?php echo $question['ordering']; ?>">
				</div>
			</div>
			<div class="ays-survey-question-row-wrap">
				<div class="ays-survey-question-row">
					<div class="ays-survey-question-box">
						<div class="ays-survey-question-input-box <?php echo $question['options']['with_editor'] ? 'display_none' : ''; ?>">
							<textarea type="text" class="ays-survey-remove-default-border ays-survey-question-input-textarea ays-survey-question-input ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][title]" placeholder="<?php echo __( 'Question', SURVEY_MAKER_NAME ); ?>"style="height: 24px;"><?php echo $question['question']; ?></textarea>
							<input type="hidden" name="<?php echo $html_name_prefix; ?>question_ids[]" value="<?php echo $question['id']; ?>">
							<div class="ays-survey-input-underline"></div>
							<div class="ays-survey-input-underline-animation"></div>
						</div>
						<div class="ays-survey-description-box ays-survey-question-input-box <?php echo $question['options']['with_editor'] ? 'display_none' : ''; ?> <?php echo $question['type'] == 'html' ? 'display_none_not_important' : ''; ?>">
							<textarea type="text" class="ays-survey-remove-default-border ays-survey-question-description-input-textarea ays-survey-input ays-survey-description-input"
								name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][description]" 
								placeholder="<?php echo __( 'Question description', SURVEY_MAKER_NAME ); ?>"style="height: 24px;"><?php echo $question['question_description']; ?></textarea>
							<div class="ays-survey-input-underline"></div>
							<div class="ays-survey-input-underline-animation"></div>
						</div>
						<div class="ays-survey-question-preview-box <?php echo $question['options']['with_editor'] ? '' : 'display_none'; ?>"><?php echo Survey_Maker_Data::ays_autoembed( $question['question'] ); ?></div>
					</div>
					<div class="ays-survey-question-img-icon-box">
					<div class="ays-survey-open-question-editor appsMaterialWizButtonPapericonbuttonEl" data-question-id="<?php echo $question['id']; ?>" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Open editor',SURVEY_MAKER_NAME)?>">
							<div class="ays-question-img-icon-content">
								<div class="ays-question-img-icon-content-div">
									<div class="ays-survey-icons">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/edit-content.svg">
									</div>
								</div>
							</div>
							<input type="hidden" class="ays-survey-open-question-editor-flag" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][with_editor]" value="<?php echo $question['options']['with_editor'] ? 'on' : 'off'; ?>">
						</div>
					</div>
					<div class="ays-survey-question-img-icon-box">
						<div class="ays-survey-add-question-image appsMaterialWizButtonPapericonbuttonEl" data-type="questionImgButton" data-type="questionImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',SURVEY_MAKER_NAME)?>">
							<div class="ays-question-img-icon-content">
								<div class="ays-question-img-icon-content-div">
									<div class="ays-survey-icons">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="ays-survey-question-type-box">
						<select name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][type]" tabindex="-1" class="ays-survey-question-type" aria-hidden="true">
							<?php
								$select_question_type = (isset( $question['type'] ) && $question['type'] != '') ? $question['type'] :  $survey_default_type;
								foreach ($question_types as $type_slug => $type):
									$selected = '';
									if( $type_slug == $select_question_type ){
										$selected = ' selected ';
									}
									?>
									<option <?php echo $selected; ?> value="<?php echo $type_slug; ?>"><?php echo $type; ?></option>
									<?php
								endforeach;
							?>
							<option disabled>Net promoter score (Agency)</option>
							<option disabled>Ranking (Agency)</option>
						</select>
						<input type="hidden" class="ays-survey-check-type-before-change" value="<?php echo $select_question_type; ?>">
					</div>
				</div>
				<div>
					<div class="ays-survey-question-img-icon-box">
						<div class="ays-survey-action-collapse-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Collapse',SURVEY_MAKER_NAME)?>">
							<div class="ays-question-img-icon-content">
								<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/collapse-section.svg">
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="ays-survey-question-image-container" <?php echo $question['image'] == '' ? 'style="display: none;"' : ''; ?> >
			<div class="ays-survey-question-image-body">
				<div class="ays-survey-question-image-wrapper aysFormeditorViewMediaImageWrapper">
					<div class="ays-survey-question-image-pos aysFormeditorViewMediaImagePos">
						<div class="d-flex">
							<div class="dropdown mr-1">
								<div class="ays-survey-question-edit-menu-button dropdown-menu-actions" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
									<div class="ays-survey-icons">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
									</div>
								</div>
								<div class="dropdown-menu">
									<a class="dropdown-item ays-survey-question-img-action" data-action="edit-image" href="javascript:void(0);"><?php echo __( 'Edit', SURVEY_MAKER_NAME ); ?></a>
									<a class="dropdown-item ays-survey-question-img-action" data-action="delete-image" href="javascript:void(0);"><?php echo __( 'Delete', SURVEY_MAKER_NAME ); ?></a>
									<a class="dropdown-item ays-survey-question-img-action" data-action="<?php echo ($question['options']['image_caption_enable']) ? 'close-caption' : 'add-caption'; ?>" href="javascript:void(0);"><?php echo ($question['options']['image_caption_enable']) ? __( 'Close caption', SURVEY_MAKER_NAME ) : __( 'Add a caption', SURVEY_MAKER_NAME ); ?></a>
								</div>
							</div>
						</div>
						<img class="ays-survey-question-img" src="<?php echo $question['image']; ?>" tabindex="0" aria-label="Captionless image" />
						<input type="hidden" class="ays-survey-question-img-src" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][image]" value="<?php echo $question['image']; ?>">
						<input type="hidden" class="ays-survey-question-img-caption-enable" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][image_caption_enable]" value="<?php echo $question['options']['image_caption_enable'] ? 'on' : 'off'; ?>">
					</div>
					<div class="ays-survey-question-image-caption-text-row <?php echo ($question['options']['image_caption_enable']) ? '' : 'display_none'; ?>">
						<div class="ays-survey-question-image-caption-box-wrap">
							<!-- <div class="ays-survey-answer-box-wrap"> -->
								<!-- <div class="ays-survey-answer-box"> -->
									<!-- <div class="ays-survey-answer-box-input-wrap"> -->
										<input type="text" class="ays-survey-input ays-survey-question-image-caption" autocomplete="off" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][image_caption]" value="<?php echo $question['options']['image_caption']; ?>">
										<div class="ays-survey-input-underline ays-survey-question-image-caption-input-underline"></div>
										<div class="ays-survey-input-underline-animation"></div>
									<!-- </div> -->
								<!-- </div> -->
							<!-- </div> -->
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="ays-survey-answers-conteiner">
		<?php
			$selected_question_type = (isset($question['type']) && $question['type'] != '') ? $question['type'] : $survey_default_type;
			$question_type_Radio_Checkbox_Select = false;
			$question_type_Text_ShortText_Number = false;
			// if ($selected_question_type == 'radio' || $selected_question_type == 'select' || $selected_question_type == 'checkbox' ) {
			//     $question_type_Radio_Checkbox_Select = true;
			// }
			
			if ( in_array( $selected_question_type, $text_question_types ) ){// == 'text' || $selected_question_type == 'short_text' || $selected_question_type == 'number' ) {
				$question_type_Text_ShortText_Number = true;
			}else{
				$question_type_Radio_Checkbox_Select = true;
			}

			// $selected_anser_i_class = '';
			if ($question_type_Radio_Checkbox_Select && ($selected_question_type != "matrix_scale" || $selected_question_type == 'matrix_scale_checkbox' || $selected_question_type != "star_list" || $selected_question_type != "slider_list") ):

				switch ($selected_question_type) {
					case 'radio':
						$selected_anser_i_class = 'radio-button-unchecked';
						break;
					case 'select':
						$selected_anser_i_class = 'radio-button-unchecked';
						break;
					case 'checkbox':
						$selected_anser_i_class = 'checkbox-unchecked';
						break;    
					default:
						$selected_anser_i_class = 'radio-button-unchecked';
						break;
				}
		
			foreach ($question['answers'] as $answer_key => $answer):
			?>
			<!-- Answers start -->
			<div class="ays-survey-answer-row" data-id="<?php echo $answer['id']; ?>" <?php echo $selected_question_type == 'linear_scale' || $selected_question_type == 'date' || $selected_question_type == 'star' || $selected_question_type == 'matrix_scale' || $selected_question_type == 'matrix_scale_checkbox' || $selected_question_type == 'star_list' || $selected_question_type == 'slider_list' ? 'style="display:none;"' : '' ;?>>
				<div class="ays-survey-answer-wrap">
					<div class="ays-survey-answer-dlg-dragHandle">
						<div class="ays-survey-icons ays-survey-icons-hidden">
							<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
						</div>
						<input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $answer['id']; ?>][ordering]" value="<?php echo $answer['ordering']; ?>">
					</div>
					<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
						<div class="ays-survey-icons">
							<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/<?php echo $selected_anser_i_class; ?>.svg">
						</div>
					</div>
					<div class="ays-survey-answer-box-wrap">
						<div class="ays-survey-answer-box">
							<div class="ays-survey-answer-box-input-wrap">
								<input type="text" autocomplete="off" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $answer['id']; ?>][title]" placeholder="Option 1" value="<?php echo $answer['answer']; ?>">
								<div class="ays-survey-input-underline"></div>
								<div class="ays-survey-input-underline-animation"></div>
							</div>
						</div>
						<div class="ays-survey-answer-icon-box">
							<div class="ays-survey-add-answer-image appsMaterialWizButtonPapericonbuttonEl" data-type="answerImgButton" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add image',SURVEY_MAKER_NAME)?>">
								<div class="ays-question-img-icon-content">
									<div class="ays-question-img-icon-content-div">
										<div class="ays-survey-icons">
											<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/insert-photo.svg">
										</div>
									</div>
								</div>
							</div>
						</div>
						<div class="ays-survey-answer-icon-box">
							<span class="ays-survey-answer-icon ays-survey-answer-delete appsMaterialWizButtonPapericonbuttonEl" <?php echo count( $question['answers'] ) > 1 ? '' : 'style="visibility: hidden;"'; ?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
								<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
							</span>
						</div>
						<div class="ays-survey-answer-logic-jump-wrap <?php echo in_array( $selected_question_type, $logic_jump_question_types ) && $question['options']['is_logic_jump'] ? '' : 'display_none'; ?>">
							<div class="ays-survey-answer-logic-jump-cont">
								<select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $answer['id']; ?>][options][go_to_section]">
									<option <?php echo $answer['options']['go_to_section'] == -1 ? 'selected' : ''; ?> value="-1"><?php echo __( "Continue to next section", SURVEY_MAKER_NAME ); ?></option>
									<?php
									foreach ($sections as $sk => $sval):
										$selected = '';
										if( intval( $sval['id'] ) == intval( $answer['options']['go_to_section'] ) ){
											$selected = ' selected ';
										}
										?>
										<option <?php echo $selected; ?> value="<?php echo $sval['id']; ?>"><?php echo __( "Go to section", SURVEY_MAKER_NAME ) . " " . ( $sk + 1 ) . " (" . $sval['title'] . ")"; ?></option>
										<?php
									endforeach;
									?>
									<option <?php echo $answer['options']['go_to_section'] == -2 ? 'selected' : ''; ?> value="-2"><?php echo __( "Submit form", SURVEY_MAKER_NAME ); ?></option>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="ays-survey-answer-image-container" <?php echo $answer['image'] == '' ? 'style="display: none;"' : ''; ?> >
					<div class="ays-survey-answer-image-body">
						<div class="ays-survey-answer-image-wrapper">
							<div class="ays-survey-answer-image-wrapper-delete-wrap">
								<div role="button" class="ays-survey-answer-image-wrapper-delete-cont removeAnswerImage">
									<span class="exportIcon">
										<div class="ays-survey-answer-image-wrapper-delete-icon-cont">
											<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
										</div>
									</span>
								</div>
							</div>
							<img class="ays-survey-answer-img" src="<?php echo $answer['image']; ?>" tabindex="0" aria-label="Captionless image" />
							<input type="hidden" class="ays-survey-answer-img-src" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $answer['id']; ?>][image]" value="<?php echo $answer['image']; ?>">
						</div>
					</div>
				</div>
			</div>
			<!-- Answers end -->
			<?php
		endforeach;

		elseif ($question_type_Text_ShortText_Number):

			$selected_question_type_class = '';
			$selected_question_type_placeholder = '';
			switch ($selected_question_type) {
				case 'text':
					$selected_question_type_class = 'ays-survey-question-type-text-box ays-survey-question-type-all-text-types-box';
					$selected_question_type_placeholder = $question_types_placeholders['text'];
					break;
				case 'short_text':
					$selected_question_type_class = 'ays-survey-question-type-short-text-box ays-survey-question-type-all-text-types-box';
					$selected_question_type_placeholder = $question_types_placeholders['short_text'];
					break;
				case 'number':
					$selected_question_type_class = 'ays-survey-question-type-number-box ays-survey-question-type-all-text-types-box';
					$selected_question_type_placeholder = $question_types_placeholders['number'];
					break;
				case 'phone':
					$selected_question_type_class = 'ays-survey-question-type-number-box ays-survey-question-type-all-text-types-box';
					$selected_question_type_placeholder = $question_types_placeholders['phone'];
					break;
				case 'email':
					$selected_question_type_class = 'ays-survey-question-type-email-box ays-survey-question-type-all-text-types-box';
					$selected_question_type_placeholder = $question_types_placeholders['email'];
					break;
				case 'name':
					$selected_question_type_class = 'ays-survey-question-type-name-box ays-survey-question-type-all-text-types-box';
					$selected_question_type_placeholder = $question_types_placeholders['name'];
					break;
				default:
					$selected_question_type_class = 'ays-survey-question-type-text-box ays-survey-question-type-all-text-types-box';
					$selected_question_type_placeholder = $question_types_placeholders['text'];
					break;
			}
			?>
			<div class="ays-survey-question-types <?php if($select_question_type == "hidden") {echo "display_none";}?>">
				<div class="ays-survey-answer-row" data-id="1">
					<div class="ays-survey-question-types-conteiner">
						<div class="ays-survey-question-types-box isDisabled <?php echo $selected_question_type_class; ?>">
							<div class="ays-survey-question-types-box-body">
								<div class="ays-survey-question-types-input-box">
									<input type="text" class="ays-survey-remove-default-border ays-survey-question-types-input ays-survey-question-types-input-with-placeholder" autocomplete="off" tabindex="0" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][placeholder]" placeholder="<?php echo $selected_question_type_placeholder; ?>" style="font-size: 14px;" value="<?php echo $question['options']['placeholder']; ?>">
								</div>
								<div class="ays-survey-question-types-input-underline"></div>
								<div class="ays-survey-question-types-input-focus-underline"></div>
							</div>
						</div>
						<div class="ays-survey-question-text-types-note-text"><span>* <?php echo __('You can insert your custom placeholder for input. Note your custom text will not be translated', SURVEY_MAKER_NAME); ?></span></div>
						<?php if($selected_question_type == "phone"): ?>
						<div class="ays-survey-question-types-box-phone-type-note">
							<?php
								echo "<span>" . __( "Note: Phone question type can contain only numbers and the following signs + ( ) -", SURVEY_MAKER_NAME ) . "</span>";
							?>
						</div>
						<?php endif; ?>
					</div>
				</div>
			</div>
		<?php
		endif;
		?>
		</div>
		<?php
		//Question type Linear Scale
		if($question['type'] == 'linear_scale'):
		?>
		<div class="ays-survey-question-types_linear_scale" >
			<div class="ays-survey-answer-row" data-id="1">
				<div class="ays-survey-question-types-conteiner">
					<div class="ays-survey-question-types-box<?php echo $survey_default_type; ?>">
						<div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght">
							<div class="ays-survey-question-types_linear_scale_span">
								<span style="font-size: 25px;" class="ays-survey_linear_scale_span">1 to</span>
							</div>
							<div class="ays-survey-question-types-for-select-lenght">
								<select class="ays-survey-choose-for-select-lenght" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][scale_length]">
									<?php
										$scale_options = "" ;
										$scale_length  = isset($question['options']['scale_length']) && $question['options']['scale_length'] != "" ? $question['options']['scale_length'] : "5";
										for($l_i = 3; $l_i <= 10; $l_i++){
											$scale_option_selected = "";
											$scale_option_selected = ($scale_length == $l_i) ? "selected" : "";
											$scale_options .= "<option value=".$l_i." ".$scale_option_selected.">".$l_i."</option>";
										}
										echo $scale_options;
									?>
								</select>
							</div>
						</div>
						<div class="ays-survey-answer-box ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
							<span class="ays_survey_linear_scale_span">1</span>
							<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-linear-scale-1 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][linear_scale_1]" value="<?php echo esc_attr(stripslashes($question['options']['linear_scale_1'])); ?>">
							<div class="ays-survey-question-types-input-underline-linear-scale"></div> 
							<div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
						</div>
						<div class="ays-survey-answer-box ays-survey-not-adding-enter-box">
							<span class="ays_survey_linear_scale_span ays_survey_linear_scale_span_changeable"><?php echo $scale_length;?></span>
							<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-linear-scale-2 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][linear_scale_2]" value="<?php echo esc_attr(stripslashes($question['options']['linear_scale_2'])); ?>">
							<div class="ays-survey-question-types-input-underline-linear-scale"></div> 
							<div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		//Question Type Date
		elseif($question['type'] == 'date'):
		?>
		<div class="ays-survey-question-types_date">
			<div class="ays-survey-answer-row" data-id="1">
				<div class="ays-survey-question-types-conteiner">
					<div class="ays-survey-question-types-box isDisabled">
						<div class="ays-survey-question-types-box-body">
							<div class="ays-survey-answer-box ays_survey_date">
								<input type="text" autocomplete="off" tabindex="0" value="<?php echo __("Month, day, year", SURVEY_MAKER_NAME); ?>" disabled="" dir="auto">
								<i class="fa fa-calendar-check-o" aria-hidden="true"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		//Question Type time
		elseif($question['type'] == 'time'):
		?>
		<div class="ays-survey-question-types_time">
			<div class="ays-survey-answer-row" data-id="1">
				<div class="ays-survey-question-types-conteiner">
					<div class="ays-survey-question-types-box isDisabled">
						<div class="ays-survey-question-types-box-body">
							<div class="ays-survey-answer-box ays_survey_time">
								<input type="text" autocomplete="off" tabindex="0" value="<?php echo __("Time", SURVEY_MAKER_NAME); ?>" disabled="" dir="auto">
								<i class="fa fa-clock-o" aria-hidden="true"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		//Question Type date & time
		elseif($question['type'] == 'date_time'):
		?>
		<div class="ays-survey-question-types_date_time">
			<div class="ays-survey-answer-row" data-id="1">
				<div class="ays-survey-question-types-conteiner">
					<div class="ays-survey-question-types-box isDisabled">
						<div class="ays-survey-question-types-box-body">
							<div class="ays-survey-answer-box ays_survey_time">
								<input type="text" autocomplete="off" tabindex="0" value="Month, day, year, hour, minute" disabled="" dir="auto">
								<i class="fa fa-calendar" aria-hidden="true"></i>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		//Question Type Star
		elseif($question['type'] == 'star'):
		?>
		<div class="ays-survey-question-types_star">
			<div class="ays-survey-answer-row" data-id="1">
				<div class="ays-survey-question-types-conteiner">
					<div class="ays-survey-question-types-box<?php echo $survey_default_type; ?>">
						<div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght">
							<div class="ays-survey-question-types_star_span">
								<span style="font-size: 25px;" class="ays-survey_star_span">1 to</span>
							</div>
							<div class="ays-survey-question-types-for-select-lenght">
								<select class="ays-survey-choose-for-start-select-lenght" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][star_scale_length]">
									<?php
										$scale_options = "" ;
										$star_scale_length  = isset($question['options']['star_scale_length']) && $question['options']['star_scale_length'] != "" ? $question['options']['star_scale_length'] : "5";
										for($s_i = 3; $s_i <= 10; $s_i++){
											$scale_option_selected = "";
											$scale_option_selected = ($star_scale_length == $s_i) ? "selected" : "";
											$scale_options .= "<option value=".$s_i." ".$scale_option_selected.">".$s_i."</option>";
										}
										echo $scale_options;
									?>
								</select>
							</div>
						</div>
						<div class="ays-survey-answer-box ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
							<span class="ays_survey_star_span">1</span>
							<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-star-1 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][star_1]" value="<?php echo $question['options']['star_1']; ?>">
							<div class="ays-survey-question-types-input-underline-linear-scale">
							</div> 
							<div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
						</div>
						<div class="ays-survey-answer-box ays-survey-not-adding-enter-box">
							<span class="ays_survey_star_span ays_survey_linear_scale_span_changeable"><?php echo $star_scale_length;?></span>
							<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-star-2 notAdding ays-survey-without-enter" autocomplete="off" tabindex="0" placeholder="<?php echo __( "Label (Optional)", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][star_2]" value="<?php echo $question['options']['star_2']; ?>">
							<div class="ays-survey-question-types-input-underline-linear-scale"></div> 
							<div class="ays-survey-input-underline-animation ays-survey-input-underline-animation-linear-scale"></div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php
		//Question Type Ramge
		elseif($question['type'] == 'range'):
		?>
		<div class="ays-survey-question-types_range">
			<div class="ays-survey-answer-row" data-id="1">
				<div class="ays-survey-question-types-conteiner">
					<div class="ays-survey-question-types-box ays-survey-range-box">
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider length" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-types-range-options-input">
									<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" placeholder="<?php echo __( "100 (Optional)", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][range_length]" value="<?php echo (isset($question['options']['range_length'])) ? esc_attr($question['options']['range_length']) : ''; ?>" >
									<div class="ays-survey-input-underline-animation"></div>
								</div>
							</label>
						</div>
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider step length" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-types-range-options-input">
									<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-step-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" placeholder="<?php echo __( "0 (Optional)", SURVEY_MAKER_NAME ); ?>" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][range_step_length]" value="<?php echo (isset($question['options']['range_step_length'])) ? esc_attr($question['options']['range_step_length']) : ''; ?>">
									<div class="ays-survey-input-underline-animation"></div>
								</div>
							</label>
						</div>
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider minimum value" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-types-range-options-input">
									<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-min-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" placeholder="<?php echo __( "0 (Optional)", SURVEY_MAKER_NAME ); ?>" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][range_min_value]" value="<?php echo (isset($question['options']['range_min_value'])) ? esc_attr($question['options']['range_min_value']) : ''; ?>">
									<div class="ays-survey-input-underline-animation"></div>
								</div>
							</label>
						</div>
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider default value" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-types-range-options-input">
									<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-default-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers" autocomplete="off" tabindex="0" style="font-size: 14px;" placeholder="<?php echo __( "0 (Optional)", SURVEY_MAKER_NAME ); ?>" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][range_default_value]" value="<?php echo (isset($question['options']['range_default_value'])) ? esc_attr($question['options']['range_default_value']) : ''; ?>">
									<div class="ays-survey-input-underline-animation"></div>
								</div>
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php    
		//Question type Upload
		elseif($question['type'] == 'upload'):
		?>
		<div class="ays-survey-question-types_range">
			<div class="ays-survey-answer-row" data-id="1">
				<div class="ays-survey-question-types-conteiner">
					<div class="ays-survey-question-types-box">
						<div class="ays-survey-question-type-upload-main-box ays_toggle_parent">
							<div class="ays-survey-question-type-upload-allow-type-box">
								<div class="ays-survey-question-type-upload-allow-type-box-title">
									<span><?php echo __("Allow only specific file types" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-question-type-upload-allow-type-box-checkbox">
									<label>
										<input type="checkbox" class="display_none ays-survey-upload-tpypes-on-off ays-switch-checkbox" <?php echo (isset($question['options']['file_upload_toggle']) && $question['options']['file_upload_toggle'] ) ? "checked" : ''; ?> value="on" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][toggle_types]" >
										<div class="switch-checkbox-wrap" aria-label="Required" tabindex="0" role="checkbox">
											<div class="switch-checkbox-track"></div>
											<div class="switch-checkbox-ink"></div>
											<div class="switch-checkbox-circles">
												<div class="switch-checkbox-thumb"></div>
											</div>
										</div>
									</label>
								</div>
							</div>
							<div class="ays-survey-question-type-upload-allowed-types ays_toggle_target <?php echo (isset($question['options']['file_upload_toggle']) && $question['options']['file_upload_toggle'] ) ? "" : "display_none_not_important"; ?>" >
								<div>
									<label class="ays-survey-answer-label ays-survey-answer-label-grid ">
										<input class="ays-survey-current-upload-type-pdf ays-survey-current-upload-type-file-types" type="checkbox" value="on" <?php echo (isset($question['options']['file_upload_types_pdf']) && $question['options']['file_upload_types_pdf'] ) ? "checked" : ''; ?> name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][upload_pdf]">
										<div class="ays-survey-answer-label-content">
											<div class="ays-survey-answer-icon-content">
												<div class="ays-survey-answer-icon-ink"></div>
												<div class="ays-survey-answer-icon-content-1">
													<div class="ays-survey-answer-icon-content-2">
														<div class="ays-survey-answer-icon-content-3"></div>
													</div>      
												</div>
											</div>
										<span class="">PDF</span>
										</div>
									</label>
								</div>
								<div>
									<label class="ays-survey-answer-label ays-survey-answer-label-grid ">
										<input class="ays-survey-current-upload-type-doc ays-survey-current-upload-type-file-types" type="checkbox" value="on" <?php echo (isset($question['options']['file_upload_types_doc']) && $question['options']['file_upload_types_doc']) ? "checked" : ''; ?> name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][upload_doc]">
										<div class="ays-survey-answer-label-content">
											<div class="ays-survey-answer-icon-content">
												<div class="ays-survey-answer-icon-ink"></div>
												<div class="ays-survey-answer-icon-content-1">
													<div class="ays-survey-answer-icon-content-2">
														<div class="ays-survey-answer-icon-content-3"></div>
													</div>      
												</div>
											</div>
										<span class="">DOC,DOCX</span>
										</div>
									</label>
								</div>
								<div>
									<label class="ays-survey-answer-label ays-survey-answer-label-grid ">
										<input class="ays-survey-current-upload-type-png ays-survey-current-upload-type-file-types" type="checkbox" value="on" <?php echo (isset($question['options']['file_upload_types_png']) && $question['options']['file_upload_types_png']) ? "checked" : ''; ?> name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][upload_png]">
										<div class="ays-survey-answer-label-content">
											<div class="ays-survey-answer-icon-content">
												<div class="ays-survey-answer-icon-ink"></div>
												<div class="ays-survey-answer-icon-content-1">
													<div class="ays-survey-answer-icon-content-2">
														<div class="ays-survey-answer-icon-content-3"></div>
													</div>      
												</div>
											</div>
										<span class="">PNG</span>
										</div>
									</label>
								</div>
								<div>
									<label class="ays-survey-answer-label ays-survey-answer-label-grid ">
										<input class="ays-survey-current-upload-type-jpg ays-survey-current-upload-type-file-types" type="checkbox" value="on" <?php echo (isset($question['options']['file_upload_types_jpg']) && $question['options']['file_upload_types_jpg']) ? "checked" : ''; ?> name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][upload_jpg]">
										<div class="ays-survey-answer-label-content">
											<div class="ays-survey-answer-icon-content">
												<div class="ays-survey-answer-icon-ink"></div>
												<div class="ays-survey-answer-icon-content-1">
													<div class="ays-survey-answer-icon-content-2">
														<div class="ays-survey-answer-icon-content-3"></div>
													</div>      
												</div>
											</div>
										<span class="">JPG, JPEG</span>
										</div>
									</label>
								</div>
								<div>
									<label class="ays-survey-answer-label ays-survey-answer-label-grid ">
										<input class="ays-survey-current-upload-type-gif ays-survey-current-upload-type-file-types" type="checkbox" value="on" <?php echo (isset($question['options']['file_upload_types_gif']) && $question['options']['file_upload_types_gif']) ? "checked" : ''; ?> name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][upload_gif]">
										<div class="ays-survey-answer-label-content">
											<div class="ays-survey-answer-icon-content">
												<div class="ays-survey-answer-icon-ink"></div>
												<div class="ays-survey-answer-icon-content-1">
													<div class="ays-survey-answer-icon-content-2">
														<div class="ays-survey-answer-icon-content-3"></div>
													</div>      
												</div>
											</div>
										<span class="">GIF</span>
										</div>
									</label>
								</div>
							</div>
						</div>
						<div class="ays-survey-question-type-upload-max-size-main-box">
							<div class="ays-survey-question-type-upload-max-size-text-box">
								<span class="ays-survey-question-type-upload-max-size-text">
									<?php echo __("Maximum file size" , SURVEY_MAKER_NAME); ?>
								</span>
							</div>
							<div class="ays-survey-question-type-upload-max-size-select-box">
								<select class="ays-survey-question-type-upload-max-size-select" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][upload_size]">
									<option value="1"   <?php echo (isset($question['options']['file_upload_types_size']) && $question['options']['file_upload_types_size'] == "1") ? "selected" : ''; ?>>1 MB</option>
									<option value="5"   <?php echo (isset($question['options']['file_upload_types_size']) && $question['options']['file_upload_types_size'] == "5") ? "selected" : ''; ?>>5 MB</option>
									<option value="10"  <?php echo (isset($question['options']['file_upload_types_size']) && $question['options']['file_upload_types_size'] == "10") ? "selected" : ''; ?>>10 MB</option>
									<option value="100" <?php echo (isset($question['options']['file_upload_types_size']) && $question['options']['file_upload_types_size'] == "100") ? "selected" : ''; ?>>100 MB</option>
								</select>
							</div>
						</div>
					</div>
					<div class="ays-survey-question-types-box-upload-size">
						<?php
							echo "<span>" . __( "Maximum upload file size of your website: ", SURVEY_MAKER_NAME ) . " " . wp_max_upload_size() / 1024 / 1024 . " MB.</span>";
						?>
						<a class="ays_help" data-toggle="tooltip" title="<?php echo __('The chosen value must be equal or higher than the value set on your Server. For example, if the Server value is 64MB, in case of choosing 100MB, the users will not be able to upload the file. Please, note that in the note text, the value set in the Server will be displayed.',SURVEY_MAKER_NAME); ?>">
							<i class="ays_fa ays_fa_info_circle"></i>
						</a>
					</div>
				</div>
			</div>
		</div>
		<?php
		//Question Type HTML
		elseif ($question['type'] == 'html'):
			?>
		<div class="ays-survey-question-types_html">
			<div class="ays-survey-answer-row" data-id="1">
				<div class="ays-survey-question-types-conteiner">
					<div class="ays-survey-question-types-box">
						<div class="ays-survey-question-types-box-body">
							<?php
								$content = (isset($question['options']['html_types_content']) && $question['options']['html_types_content'] != "") ? stripslashes( wpautop($question['options']['html_types_content'])) : '';
								$editor_id = $html_name_prefix.'html-type-editor-section-'.$section['id'].'-edit-'.$question['id'];
							?>
							<textarea name="<?php echo $html_name_prefix.'sections['.$section['id'].'][questions]['.$question['id'].'][options][html_type_editor]'; ?>" id="<?php echo $editor_id; ?>" class="wp-editor-area ays-survey-html-question-type-for-js"><?php echo $content; ?></textarea>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php    
		//Question Type Matrix Scale
		elseif($question['type'] == 'matrix_scale'):
		?>
		<div class="ays-survey-question-matrix_scale ays-survey-question-all-matrix-types">
			<div class="ays-survey-question-matrix_scale_row">
				<div class="ays-survey-answers-conteiner-matrix-row">
					<div class="ays-survey-question-matrix_scale_row_title"><?php echo __("Rows" , SURVEY_MAKER_NAME); ?></div>
					<!-- Add rows start -->
					<div class="ays-survey-answers-conteiner-row">
					<?php 
					$counter = 0;
					foreach ($question['answers'] as $matrix_answer_key => $matrix_answer):
						$counter++;
					?>                       
						<div class="ays-survey-answer-row ays-survey-old-answer" data-id="<?php echo $matrix_answer['id']?>" <?php echo $selected_question_type == 'linear_scale' || $selected_question_type == 'date' || $selected_question_type == 'star' ? 'style="display:none;"' : '' ;?>>
							<div class="ays-survey-answer-wrap">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons ays-survey-icons-hidden">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
									<input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $matrix_answer['id']; ?>][ordering]" value="<?php echo $matrix_answer['ordering']; ?>">
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box-wrap">
									<div class="ays-survey-answer-box">
										<div class="ays-survey-answer-box-input-wrap">
											<input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $matrix_answer['id']; ?>][title]" placeholder="<?php echo $matrix_answer['answer']; ?>" value="<?php echo $matrix_answer['answer']; ?>">
											<div class="ays-survey-input-underline"></div>
											<div class="ays-survey-input-underline-animation"></div>
										</div>
									</div>
									<div class="ays-survey-answer-icon-box">
										<span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" <?php echo count( $question['answers'] ) > 1 ? '' : 'style="visibility: hidden;"'; ?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
											<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
										</span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach;?>
					</div>
					<!-- Add rows end -->
				</div>

				<!-- Add "Add button for rows" start -->
				<div class="ays-survey-answers-conteiner-matrix-button">
					<div class="ays-survey-matrix-scale-row-add-button">
						<div class="ays-survey-answer-row">
							<div class="ays-survey-answer-wrap" style="justify-content: initial;">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons invisible">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box d-flex">
									<div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',SURVEY_MAKER_NAME)?>" data-dir="row">
										<div class="ays-question-img-icon-content">
											<div class="ays-question-img-icon-content-div">
												<div class="ays-survey-icons">
													<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Add "Add button for rows" end -->
			</div>
			<div class="ays-survey-question-matrix_scale_column">
				<div class="ays-survey-question-matrix_scale_column_title"><?php echo __("Columns" , SURVEY_MAKER_NAME)?></div>
				<!-- Add column start -->
				<div class="ays-survey-answers-conteiner-column" data-flag="true">
					<?php $col_counter = 0;
							$get_all_columns = array();
							if(isset($matrix_columns)){
								$get_all_columns = isset($matrix_columns[$question['id']]) ? $matrix_columns[$question['id']] : array();

							
							}
						foreach ($get_all_columns as $matrix_col_key => $matrix_col_value):
						$col_counter++;
					?>
					<div class="ays-survey-answer-row ays-survey-new-answer" data-id="<?php echo $col_counter; ?>" data-name="answers_add">
						<div class="ays-survey-answer-wrap">
							<div class="ays-survey-answer-dlg-dragHandle">
								<div class="ays-survey-icons ays-survey-icons-hidden">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
								</div>
								<input type="hidden" class="ays-survey-answer-ordering" value="1">
							</div>
							<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
								<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
							</div>                                                                        
							<div class="ays-survey-answer-box-wrap">
								<div class="ays-survey-answer-box">
									<div class="ays-survey-answer-box-input-wrap">
										<input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][columns][<?php echo $matrix_col_key;?>]" value="<?php echo stripslashes(esc_attr($matrix_col_value)); ?>">
										<div class="ays-survey-input-underline"></div>
										<div class="ays-survey-input-underline-animation"></div>
									</div>
								</div>
								<div class="ays-survey-answer-icon-box">
									<span class="ays-survey-answer-icon ays-survey-answer-delete-column appsMaterialWizButtonPapericonbuttonEl" <?php echo count( (array) $get_all_columns ) > 1 ? '' : 'style="visibility: hidden;"'; ?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
									</span>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach;?>
				</div>
				<!-- Add column end -->
				<!-- Add "Add button for columns" start -->
				<div class="ays-survey-matrix-scale-column-add-button">
					<div class="ays-survey-other-answer-and-actions-column">
						<div class="ays-survey-answer-row">                                                                
							<div class="ays-survey-answer-wrap" style="justify-content: initial;">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons invisible">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box d-flex">
									<div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',SURVEY_MAKER_NAME)?>" data-dir="col">
										<div class="ays-question-img-icon-content">
											<div class="ays-question-img-icon-content-div">
												<div class="ays-survey-icons">
													<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Add "Add button for columns" end -->
			</div>
		</div>
		<?php
		//Question Type Matrix Scale Checkbox
		elseif($question['type'] == 'matrix_scale_checkbox'):
		?>
		<div class="ays-survey-question-matrix_scale_checkbox ays-survey-question-all-matrix-types">
			<div class="ays-survey-question-matrix_scale_checkbox_row">
				<div class="ays-survey-answers-conteiner-matrix-row">
					<div class="ays-survey-question-matrix_scale_row_title"><?php echo __("Rows" , SURVEY_MAKER_NAME); ?></div>
					<!-- Add rows start -->
					<div class="ays-survey-answers-conteiner-row">
					<?php 
					$counter = 0;
					foreach ($question['answers'] as $matrix_checkbox_answer_key => $matrix_checkbox_answer):
						$counter++;
					?>                       
						<div class="ays-survey-answer-row ays-survey-old-answer" data-id="<?php echo $matrix_checkbox_answer['id']?>" <?php echo $selected_question_type == 'linear_scale' || $selected_question_type == 'date' || $selected_question_type == 'star' ? 'style="display:none;"' : '' ;?>>
							<div class="ays-survey-answer-wrap">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons ays-survey-icons-hidden">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
									<input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $matrix_checkbox_answer['id']; ?>][ordering]" value="<?php echo $matrix_checkbox_answer['ordering']; ?>">
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box-wrap">
									<div class="ays-survey-answer-box">
										<div class="ays-survey-answer-box-input-wrap">
											<input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $matrix_checkbox_answer['id']; ?>][title]" placeholder="<?php echo $matrix_checkbox_answer['answer']; ?>" value="<?php echo $matrix_checkbox_answer['answer']; ?>">
											<div class="ays-survey-input-underline"></div>
											<div class="ays-survey-input-underline-animation"></div>
										</div>
									</div>
									<div class="ays-survey-answer-icon-box">
										<span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" <?php echo count( $question['answers'] ) > 1 ? '' : 'style="visibility: hidden;"'; ?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
											<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
										</span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach;?>
					</div>
					<!-- Add rows end -->
				</div>

				<!-- Add "Add button for rows" start -->
				<div class="ays-survey-answers-conteiner-matrix-button">
					<div class="ays-survey-matrix-scale-row-add-button">
						<div class="ays-survey-answer-row">
							<div class="ays-survey-answer-wrap" style="justify-content: initial;">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons invisible">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box d-flex">
									<div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',SURVEY_MAKER_NAME)?>" data-dir="row">
										<div class="ays-question-img-icon-content">
											<div class="ays-question-img-icon-content-div">
												<div class="ays-survey-icons">
													<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Add "Add button for rows" end -->
			</div>
			<div class="ays-survey-question-matrix_scale_checkbox_column">
				<div class="ays-survey-question-matrix_scale_column_title"><?php echo __("Columns" , SURVEY_MAKER_NAME)?></div>
				<!-- Add column start -->
				<div class="ays-survey-answers-conteiner-column" data-flag="true">
					<?php $col_counter = 0;
							$get_all_columns = array();
							if(isset($matrix_columns)){
								$get_all_columns = isset($matrix_columns[$question['id']]) ? $matrix_columns[$question['id']] : array();

							}
						foreach ($get_all_columns as $matrix_col_key => $matrix_col_value):
						$col_counter++;
					?>
					<div class="ays-survey-answer-row ays-survey-new-answer" data-id="<?php echo $col_counter; ?>" data-name="answers_add">
						<div class="ays-survey-answer-wrap">
							<div class="ays-survey-answer-dlg-dragHandle">
								<div class="ays-survey-icons ays-survey-icons-hidden">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
								</div>
								<input type="hidden" class="ays-survey-answer-ordering" value="1">
							</div>
							<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
								<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
							</div>                                                                        
							<div class="ays-survey-answer-box-wrap">
								<div class="ays-survey-answer-box">
									<div class="ays-survey-answer-box-input-wrap">
										<input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][columns][<?php echo $matrix_col_key;?>]" value="<?php echo stripslashes(esc_attr($matrix_col_value)); ?>">
										<div class="ays-survey-input-underline"></div>
										<div class="ays-survey-input-underline-animation"></div>
									</div>
								</div>
								<div class="ays-survey-answer-icon-box">
									<span class="ays-survey-answer-icon ays-survey-answer-delete-column appsMaterialWizButtonPapericonbuttonEl" <?php echo count( (array) $get_all_columns ) > 1 ? '' : 'style="visibility: hidden;"'; ?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
									</span>
								</div>
							</div>
						</div>
					</div>
					<?php endforeach;?>
				</div>
				<!-- Add column end -->
				<!-- Add "Add button for columns" start -->
				<div class="ays-survey-matrix-scale-column-add-button">
					<div class="ays-survey-other-answer-and-actions-column">
						<div class="ays-survey-answer-row">                                                                
							<div class="ays-survey-answer-wrap" style="justify-content: initial;">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons invisible">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/checkbox-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box d-flex">
									<div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',SURVEY_MAKER_NAME)?>" data-dir="col">
										<div class="ays-question-img-icon-content">
											<div class="ays-question-img-icon-content-div">
												<div class="ays-survey-icons">
													<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Add "Add button for columns" end -->
			</div>
		</div>
		<?php
		//Question Type Star List
		elseif($question['type'] == 'star_list') :
		?>
		<div class="ays-survey-question-star_list ays-survey-question-all-matrix-types">
			<div class="ays-survey-question-star_list_row">
				<div class="ays-survey-answers-conteiner-star_list-row">
					<div class="ays-survey-question-star_list_row_title"><?php echo __("Rows" , SURVEY_MAKER_NAME); ?></div>
					<!-- Add rows start -->
					<div class="ays-survey-answers-conteiner-row">
					<?php 
					$counter = 0;
					foreach ($question['answers'] as $star_list_answer_key => $star_list_answer):
						$counter++;
					?>                       
						<div class="ays-survey-answer-row ays-survey-old-answer" data-id="<?php echo $star_list_answer['id']?>" <?php echo $selected_question_type == 'linear_scale' || $selected_question_type == 'date' || $selected_question_type == 'star' ? 'style="display:none;"' : '' ;?>>
							<div class="ays-survey-answer-wrap">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons ays-survey-icons-hidden">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
									<input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $star_list_answer['id']; ?>][ordering]" value="<?php echo $star_list_answer['ordering']; ?>">
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box-wrap">
									<div class="ays-survey-answer-box">
										<div class="ays-survey-answer-box-input-wrap">
											<input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $star_list_answer['id']; ?>][title]" placeholder="<?php echo $star_list_answer['answer']; ?>" value="<?php echo $star_list_answer['answer']; ?>">
											<div class="ays-survey-input-underline"></div>
											<div class="ays-survey-input-underline-animation"></div>
										</div>
									</div>
									<div class="ays-survey-answer-icon-box">
										<span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" <?php echo count( $question['answers'] ) > 1 ? '' : 'style="visibility: hidden;"'; ?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
											<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
										</span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach;?>
					</div>
					<!-- Add rows end -->
				</div>

				<!-- Add "Add button for rows" start -->
				<div class="ays-survey-answers-conteiner-star-list-button">
					<div class="ays-survey-star-list-row-add-button">
						<div class="ays-survey-answer-row">
							<div class="ays-survey-answer-wrap" style="justify-content: initial;">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons invisible">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box d-flex">
									<div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',SURVEY_MAKER_NAME)?>" data-dir="row">
										<div class="ays-question-img-icon-content">
											<div class="ays-question-img-icon-content-div">
												<div class="ays-survey-icons">
													<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Add "Add button for rows" end -->
			</div>
			<div class="ays-survey-question-star_list_options">
				<div class="ays-survey-question-star_list_column_title"><?php echo __("Stars length" , SURVEY_MAKER_NAME)?></div>
				<div class="ays-survey-question-types-box-body ays-survey-body-for-select-lenght ays-survey-question-star-list-length-box">
					<div class="ays-survey-question-types_star_list_span">
						<span style="font-size: 25px;" class="ays-survey_star_list_span">1 to</span>
					</div>
					<div class="ays-survey-question-types-for-select-lenght">
						<select class="ays-survey-choose-for-select-lenght-star-list" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][star_list_stars_length]">
							<?php
								$scale_options = "" ;
								$scale_length  = isset($question['options']['star_list_stars_length']) && $question['options']['star_list_stars_length'] != "" ? $question['options']['star_list_stars_length'] : "5";
								for($l_i = 3; $l_i <= 10; $l_i++){
									$scale_option_selected = "";
									$scale_option_selected = ($scale_length == $l_i) ? "selected" : "";
									$scale_options .= "<option value=".$l_i." ".$scale_option_selected.">".$l_i."</option>";
								}
								echo $scale_options;
							?>
						</select>
					</div>
				</div>
				<!-- Add "Add button for columns" end -->
			</div>
		</div>
		<?php
		// Question type slider list
		elseif($question['type'] == 'slider_list') :
		?> 
		<div class="ays-survey-question-slider_list ays-survey-question-all-matrix-types">
			<div class="ays-survey-question-slider_list_row">
				<div class="ays-survey-answers-conteiner-slider_list-row">
					<div class="ays-survey-question-slider_list_row_title"><?php echo __("Rows" , SURVEY_MAKER_NAME); ?></div>
					<!-- Add rows start -->
					<div class="ays-survey-answers-conteiner-row">
					<?php 
					$counter = 0;
					foreach ($question['answers'] as $slider_list_answer_key => $slider_list_answer):
						$counter++;
					?>                       
						<div class="ays-survey-answer-row ays-survey-old-answer" data-id="<?php echo $slider_list_answer['id']?>" <?php echo $selected_question_type == 'linear_scale' || $selected_question_type == 'date' || $selected_question_type == 'star' ? 'style="display:none;"' : '' ;?>>
							<div class="ays-survey-answer-wrap">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons ays-survey-icons-hidden">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
									<input type="hidden" class="ays-survey-answer-ordering" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $slider_list_answer['id']; ?>][ordering]" value="<?php echo $slider_list_answer['ordering']; ?>">
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box-wrap">
									<div class="ays-survey-answer-box">
										<div class="ays-survey-answer-box-input-wrap">
											<input type="text" class="ays-survey-input" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][answers][<?php echo $slider_list_answer['id']; ?>][title]" placeholder="<?php echo $slider_list_answer['answer']; ?>" value="<?php echo $slider_list_answer['answer']; ?>">
											<div class="ays-survey-input-underline"></div>
											<div class="ays-survey-input-underline-animation"></div>
										</div>
									</div>
									<div class="ays-survey-answer-icon-box">
										<span class="ays-survey-answer-icon ays-survey-answer-delete-row appsMaterialWizButtonPapericonbuttonEl" <?php echo count( $question['answers'] ) > 1 ? '' : 'style="visibility: hidden;"'; ?> data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
											<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
										</span>
									</div>
								</div>
							</div>
						</div>
					<?php endforeach;?>
					</div>
					<!-- Add rows end -->
				</div>

				<!-- Add "Add button for rows" start -->
				<div class="ays-survey-answers-conteiner-star-list-button">
					<div class="ays-survey-star-list-row-add-button">
						<div class="ays-survey-answer-row">
							<div class="ays-survey-answer-wrap" style="justify-content: initial;">
								<div class="ays-survey-answer-dlg-dragHandle">
									<div class="ays-survey-icons invisible">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
									</div>
								</div>
								<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/radio-button-unchecked.svg">
								</div>
								<div class="ays-survey-answer-box d-flex">
									<div class="ays-survey-action-add-answer-row-and-column appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add answer',SURVEY_MAKER_NAME)?>" data-dir="row">
										<div class="ays-question-img-icon-content">
											<div class="ays-question-img-icon-content-div">
												<div class="ays-survey-icons">
													<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<!-- Add "Add button for rows" end -->
			</div>
			<div class="ays-survey-question-slider_list_options">
				<div class="ays-survey-question-slider_list_column_title"><?php echo __("Slider options" , SURVEY_MAKER_NAME)?></div>
				<div class="ays-survey-question-types-conteiner ays-survey-question-slider-list-length-box">
					<div class="ays-survey-question-types-box ays-survey-range-box">
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box" style="margin: 20px 0px;">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider length" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-types-range-options-input">
									<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-range-length" autocomplete="off" tabindex="0" placeholder="<?php echo __( "100 (Optional)", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][slider_list_range_length]" value="<?php echo (isset($question['options']['slider_list_range_length'])) ? esc_attr($question['options']['slider_list_range_length']) : ''; ?>" >
									<div class="ays-survey-input-underline-animation"></div>
								</div>
							</label>
						</div>
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider step length" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-types-range-options-input">
									<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-step-length notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-range-step-length" autocomplete="off" tabindex="0" style="font-size: 14px;" placeholder="<?php echo __( "0 (Optional)", SURVEY_MAKER_NAME ); ?>" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][slider_list_range_step_length]" value="<?php echo (isset($question['options']['slider_list_range_step_length'])) ? esc_attr($question['options']['slider_list_range_step_length']) : ''; ?>">
									<div class="ays-survey-input-underline-animation"></div>
								</div>
							</label>
						</div>
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider minimum value" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-types-range-options-input">
									<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-min-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-min-value" autocomplete="off" tabindex="0" style="font-size: 14px;" placeholder="<?php echo __( "0 (Optional)", SURVEY_MAKER_NAME ); ?>" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][slider_list_range_min_value]" value="<?php echo (isset($question['options']['slider_list_range_min_value'])) ? esc_attr($question['options']['slider_list_range_min_value']) : ''; ?>">
									<div class="ays-survey-input-underline-animation"></div>
								</div>
							</label>
						</div>
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider default value" , SURVEY_MAKER_NAME); ?></span>
								</div>
								<div class="ays-survey-types-range-options-input">
									<input type="text" autocomplete="off" class="ays-survey-input ays-survey-input-range-default-val notAdding ays-survey-without-enter ays-survey-slider-only-numbers ays-survey-slider-list-input-default-value" autocomplete="off" tabindex="0" style="font-size: 14px;" placeholder="<?php echo __( "0 (Optional)", SURVEY_MAKER_NAME ); ?>" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][slider_list_range_default_value]" value="<?php echo (isset($question['options']['slider_list_range_default_value'])) ? esc_attr($question['options']['slider_list_range_default_value']) : ''; ?>">
									<div class="ays-survey-input-underline-animation"></div>
								</div>
							</label>
						</div>
						<div class="ays-survey-answer-box ays-survey-answer-box-for-range ays-survey-not-adding-enter-box">
							<label class="ays-survey-question-range-length-label">
								<div class="ays-survey-types-range-options-span">
									<span class="ays_survey_range_span"><?php echo __("Slider calulation type" , SURVEY_MAKER_NAME); ?></span>
								</div>                                                                        
							</label>
							<div class="my-3 ml-2 ays-survey-question-range-length-label">
								<label for="ays-survey-slider-list-calculation-seperatly-type-<?php echo $question['id']?>" style="margin-bottom: 0.3rem; <?php echo ( isset($question['options']['slider_list_range_calculation_type']) && $question['options']['slider_list_range_calculation_type'] == 'seperatly' ) ? 'font-weight: 600;' : '' ?>">
									<span><?php echo __( 'Seperatly', SURVEY_MAKER_NAME ); ?></span>
								</label>
								<input type="radio" id="ays-survey-slider-list-calculation-seperatly-type-<?php echo $question['id']?>" class="display_none ays-survey-slider-list-calculation-type ays-switch-checkbox" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][slider_list_range_calculation_type]" value="seperatly" <?php echo (isset($question['options']['slider_list_range_calculation_type']) && $question['options']['slider_list_range_calculation_type'] == 'seperatly') ? 'checked' : '' ?>>
								<input type="radio" id="ays-survey-slider-list-calculation-combined-type-<?php echo $question['id']?>" class="display_none ays-survey-slider-list-calculation-type ays-switch-checkbox" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][slider_list_range_calculation_type]" value="combined" <?php echo (isset($question['options']['slider_list_range_calculation_type']) && $question['options']['slider_list_range_calculation_type'] == 'combined') ? 'checked' : '' ?>>
								<div class="switch-checkbox-wrap mx-2 ays-survey-slider-list-center-toggle" aria-label="Required" tabindex="0" role="checkbox" data-toggle-type="<?php echo (isset($question['options']['slider_list_range_calculation_type']) && $question['options']['slider_list_range_calculation_type'] == 'combined') ? 'combined' : 'seperatly' ?>">
									<div class="switch-checkbox-track"></div>
									<div class="switch-checkbox-ink"></div>
									<div class="switch-checkbox-circles">
										<div class="switch-checkbox-thumb"></div>
									</div>
								</div>
								<label for="ays-survey-slider-list-calculation-combined-type-<?php echo $question['id']?>" style="margin-bottom: 0.3rem; <?php echo (isset($question['options']['slider_list_range_calculation_type']) && $question['options']['slider_list_range_calculation_type'] == 'combined') ? 'font-weight: 600;' : '' ?>">
									<span><?php echo __( 'Combined', SURVEY_MAKER_NAME ); ?></span>
								</label>                                                                        
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<?php                                              
		endif;
		?>

		<div class="ays-survey-other-answer-and-actions-row">
		<?php
			if($question_type_Radio_Checkbox_Select && ($selected_question_type != "matrix_scale" || $selected_question_type != "star_list" || $selected_question_type != "slider_list")):
				// $selected_other_anser_i_class = '';
				switch ($selected_question_type) {
					case 'radio':
						$selected_other_anser_i_class = 'ays_fa_circle_thin';
						break;
					case 'select':
						$selected_other_anser_i_class = 'ays_fa_circle_thin';
						break;
					case 'checkbox':
						$selected_other_anser_i_class = 'ays_fa_square_o';
						break;    
					default:
						$selected_other_anser_i_class = 'ays_fa_circle_thin';
						break;
				}
			?>
			<div class="ays-survey-answer-row ays-survey-other-answer-row" <?php echo $question['user_variant'] ? '' : 'style="display: none;"'; ?>>
				<div class="ays-survey-answer-wrap">
					<div class="ays-survey-answer-dlg-dragHandle">
						<div class="ays-survey-icons invisible">
							<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
						</div>
					</div>
					<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
						<div class="ays-survey-icons">
							<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/<?php echo $selected_anser_i_class; ?>.svg">
						</div>
					</div>
					<div class="ays-survey-answer-box-wrap">
						<div class="ays-survey-answer-box">
							<div class="ays-survey-answer-box-input-wrap">
								<input type="text" autocomplete="off" disabled class="ays-survey-input ays-survey-input-other-answer" placeholder="<?php echo __( 'Other...', SURVEY_MAKER_NAME ); ?>" value="<?php echo __( 'Other...', SURVEY_MAKER_NAME ); ?>">
								<div class="ays-survey-input-underline"></div>
								<div class="ays-survey-input-underline-animation"></div>
							</div>
						</div>
						<div class="ays-survey-answer-icon-box">
							<span class="ays-survey-answer-icon ays-survey-other-answer-delete appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
								<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/close.svg">
							</span>
						</div>
						<div class="ays-survey-answer-logic-jump-wrap <?php echo in_array( $selected_question_type, $logic_jump_question_types ) && $question['options']['is_logic_jump'] ? '' : 'display_none'; ?>">
							<div class="ays-survey-answer-logic-jump-cont">
								<select tabindex="-1" class="ays-survey-answer-logic-jump-select ays-survey-answer-logic-jump-select-other" aria-hidden="true" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][go_to_section]">
									<option <?php echo $question['options']['other_answer_logic_jump'] == -1 ? 'selected' : ''; ?> value="-1"><?php echo __( "Continue to next section", SURVEY_MAKER_NAME ); ?></option>
									<?php
									foreach ($sections as $sk => $sval):
										$selected = '';
										if( intval( $sval['id'] ) == intval( $question['options']['other_answer_logic_jump'] ) ){
											$selected = ' selected ';
										}
										?>
										<option <?php echo $selected; ?> value="<?php echo $sval['id']; ?>"><?php echo __( "Go to section", SURVEY_MAKER_NAME ) . " " . ( $sk + 1 ) . " (" . $sval['title'] . ")"; ?></option>
										<?php
									endforeach;
									?>
									<option <?php echo $question['options']['other_answer_logic_jump'] == -2 ? 'selected' : ''; ?> value="-2"><?php echo __( "Submit form", SURVEY_MAKER_NAME ); ?></option>
								</select>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ays-survey-answer-row" <?php echo in_array($question['type'] , $other_question_types) ? 'style="display:none;"' : '' ;?>>
				<div class="ays-survey-answer-wrap">
					<div class="ays-survey-answer-dlg-dragHandle">
						<div class="ays-survey-icons invisible">
							<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/dragndrop-vertical.svg">
						</div>
					</div>
					<div class="ays-survey-answer-icon-box ays-survey-answer-icon-just">
						<div class="ays-question-img-icon-content">
							<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/<?php echo $selected_anser_i_class; ?>.svg">
						</div>
					</div>
					<div class="ays-survey-answer-box-wrap">
						<div class="ays-survey-answer-box">
							<div class="ays-survey-action-add-answer appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="top" data-content="<?php echo __('Add option',"survey-maker")?>">
								<div class="ays-question-img-icon-content">
									<div class="ays-question-img-icon-content-div">
										<div class="ays-survey-icons">
											<img src="<?php echo esc_attr(SURVEY_MAKER_ADMIN_URL); ?>/images/icons/add-circle-outline.svg">
										</div>
										<div class="ays-survey-action-add-answer-text">
											<?php  echo __('Add option' , "survey-maker"); ?>
										</div>
									</div>
								</div>
							</div>
							<?php
								$show_add_other_answer_button = '';
								if( $selected_question_type == 'select' ){
									$show_add_other_answer_button = 'style="display: none;"';
								}elseif( $question['user_variant'] ){
									$show_add_other_answer_button = 'style="display: none;"';
								}
							?>
							<div class="ays-survey-other-answer-add-wrap" <?php echo $show_add_other_answer_button; ?>>
								<span class=""><?php echo __( 'or', SURVEY_MAKER_NAME ) ?></span>
								<div class="ays-survey-other-answer-container ays-survey-other-answer-add">
									<div class="ays-survey-other-answer-container-overlay"></div>
									<span class="ays-survey-other-answer-content">
										<span class="appsMaterialWizButtonPaperbuttonLabel quantumWizButtonPaperbuttonLabel"><?php echo __( 'add "Other"', SURVEY_MAKER_NAME ) ?></span>
										<input type="checkbox" <?php echo $question['user_variant'] ? 'checked' : ''; ?> class="display_none ays-survey-other-answer-checkbox" value="on" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][user_variant]">
									</span>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<?php
			endif;
			?>
		</div>

		<div class="ays-survey-answer-other-logic-jump-wrapper <?php echo in_array( $selected_question_type, $other_logic_jump_question_types ) && $question['options']['is_logic_jump'] ? '' : 'display_none'; ?>">
			<div class="ays-survey-row-divider"><div></div></div>
			<div class="ays-survey-answer-other-logic-jump-add-condition d-flex align-items-center justify-content-between flex-wrap">
				<div class="ays-survey-action-add-condition appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Add Condition',SURVEY_MAKER_NAME)?>">
					<div class="ays-question-img-icon-content ays-question-img-icon-content-conditions">
						<div class="ays-question-img-icon-content-div">
							<div class="ays-survey-icons">
								<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/add-circle-outline.svg">
							</div>
						</div>
					</div>
					<div>
						<span style="padding: 10px;"><?php echo __("Add Condition" , SURVEY_MAKER_NAME); ?></span>
					</div>
				</div>
				<div>
					<button type="button" class="button ays-survey-condition-refresh-data"><?php echo __( "Refresh question data", SURVEY_MAKER_NAME ); ?></button>
				</div>
			</div>
			<div class="ays-survey-answer-other-logic-jump-wrap">
				<div class="ays-survey-answer-checkbox-logic-jump-conditions">
					<?php if( !empty( $question['options']['other_logic_jump'] ) ): ?>
						<?php foreach ($question['options']['other_logic_jump'] as $olj_key => $olj_item): ?>
						<div class="ays-survey-answer-checkbox-logic-jump-condition" data-question-id="<?php echo $question['id'] ?>" data-condition-id="<?= intval($olj_key) ?>">
							<div class="ays-survey-answer-checkbox-condition-selects">
								<div class="ays-survey-checkbox-condition-selects-if"><span><?php echo __("If", SURVEY_MAKER_NAME); ?></span></div>
								<div class="ays-survey-answer-checkbox-condition-selects-row">
									<div class="ays-survey-checkbox-condition-selects">
										<select class="ays-survey-checkbox-condition-select" multiple
											name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][other_logic_jump][<?php echo $olj_key; ?>][selected_options][]" >
											<?php
											$checkbox_lj_select_options = "";
											// $checkbox_lj_select_options .= '<option value="">'. __("Select" , SURVEY_MAKER_NAME).'</option>';
											$selected = "";
											foreach($question['answers'] as $answer_key => $answer){
												$selected = isset($olj_item['selected_options']) && in_array( $answer['id'], $olj_item['selected_options'] ) ? "selected" : "";
												$checkbox_lj_select_options .= "<option value='".$answer['id']."' ".$selected.">".$answer['answer']."</option>";
											}

											if( isset($olj_item['selected_options']) && in_array( 'other', $olj_item['selected_options'] ) ){
												$checkbox_lj_select_options .= '<option value="other" selected>'. __("Other" , SURVEY_MAKER_NAME).'</option>';
											}

											echo $checkbox_lj_select_options;
											?>
										</select>
									</div>
									<div class="ays-survey-answer-logic-jump-cont">
										<div class="ays-survey-checkbox-condition-selects-then"><span><?php echo __("are selected then", SURVEY_MAKER_NAME); ?></span></div>
										<select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true"
											name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][other_logic_jump][<?php echo $olj_key; ?>][go_to_section]">
											<option <?php echo $olj_item['go_to_section'] == -1 ? 'selected' : ''; ?> value="-1"><?php echo __( "Continue to next section" ); ?></option>
											<?php
											foreach ($sections as $sk => $sval):
												$selected = '';
												if( intval( $sval['id'] ) == intval( $olj_item['go_to_section'] ) ){
													$selected = ' selected ';
												}
												?>
												<option <?php echo $selected; ?> value="<?php echo $sval['id']; ?>"><?php echo __( "Go to section", SURVEY_MAKER_NAME ) . " " . ( $sk + 1 ) . " (" . $sval['title'] . ")"; ?></option>
											<?php
											endforeach;
											?>
											<option <?php echo $olj_item['go_to_section'] == -2 ? 'selected' : ''; ?> value="-2"><?php echo __( "Submit form" ); ?></option>
										</select>
									</div>
								</div>
								<div class="ays-survey-condition-delete-currnet">
									<div class="ays-survey-delete-question-condition appsMaterialWizButtonPapericonbuttonEl appsMaterialWizButtonPapericonbuttonEl ays-survey-delete-button" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __("Delete", SURVEY_MAKER_NAME ); ?>">
										<div class="ays-question-img-icon-content">
											<div class="ays-question-img-icon-content-div">
												<div class="ays-survey-icons ays-survey-icons">
													<img src="<?php echo SURVEY_MAKER_ADMIN_URL?>/images/icons/trash.svg">
												</div>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
						<?php endforeach; ?>
					<?php else: ?>
						<div class="ays-surevy-checkbox-logic-jump-empty-condition">
							<span><?php echo __( 'Press "Add condition" button to add a new condition', SURVEY_MAKER_NAME ); ?></span>
						</div>
					<?php endif; ?>
				</div>
			</div>
			<div class="ays-survey-answer-other-logic-jump-wrap ays-survey-answer-other-logic-jump-else-wrap <?php echo !empty( $question['options']['other_logic_jump'] ) ? '' : 'display_none'; ?>">
				<div class="ays-survey-answer-checkbox-condition-selects">
					<div class="ays-survey-checkbox-condition-selects-if"><span><?php echo __("Otherwise", SURVEY_MAKER_NAME); ?></span></div>
					<div class="ays-survey-answer-checkbox-condition-selects-row">
						<div class="ays-survey-answer-logic-jump-cont">
							<?php
							$other_logic_jump_otherwise = isset( $question['options']['other_logic_jump_otherwise'] ) && $question['options']['other_logic_jump_otherwise'] !== '' ? intval( $question['options']['other_logic_jump_otherwise'] ) : -1;
							?>
							<select tabindex="-1" class="ays-survey-answer-logic-jump-select" aria-hidden="true"
									name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][other_logic_jump_otherwise]">
								<option <?php echo $other_logic_jump_otherwise == -1 ? 'selected' : ''; ?> value="-1"><?php echo __( "Continue to next section" ); ?></option>
								<?php
								foreach ($sections as $sk => $sval):
									$selected = '';
									if( intval( $sval['id'] ) == $other_logic_jump_otherwise ){
										$selected = ' selected ';
									}
									?>
									<option <?php echo $selected; ?> value="<?php echo $sval['id']; ?>"><?php echo __( "Go to section", SURVEY_MAKER_NAME ) . " " . ( $sk + 1 ) . " (" . $sval['title'] . ")"; ?></option>
								<?php
								endforeach;
								?>
								<option <?php echo $other_logic_jump_otherwise == -2 ? 'selected' : ''; ?> value="-2"><?php echo __( "Submit form" ); ?></option>
							</select>
						</div>
					</div>
				</div>
			</div>
		</div>

		<div class="ays-survey-row-divider"><div></div></div>
		<div class="ays-survey-question-more-options-wrap">
			<!-- Min -->
			<div class="ays-survey-question-more-option-wrap ays-survey-question-min-selection-count <?php echo $selected_question_type == "checkbox" && $question['options']['enable_max_selection_count'] ? "" : "display_none"; ?>">
				<div class="ays-survey-answer-box" style="margin: 20px 0px;">
					<label class="ays-survey-question-min-selection-count-label">
						<span><?php echo __( "Minimum selection count", SURVEY_MAKER_NAME ); ?></span>
						<input type="number" class="ays-survey-input ays-survey-min-votes-field" autocomplete="off" tabindex="0" 
							placeholder="<?php echo __( "Minimum selection count", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;"
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][min_selection_count]"
							value="<?php echo $question['options']['min_selection_count']; ?>" min="0">
						<div class="ays-survey-input-underline"></div> 
						<div class="ays-survey-input-underline-animation"></div>
					</label>
				</div>
			</div>
			<!-- Max -->
			<div class="ays-survey-question-more-option-wrap ays-survey-question-max-selection-count <?php echo $selected_question_type == "checkbox" && $question['options']['enable_max_selection_count'] ? "" : "display_none"; ?>">
				<input type="checkbox" class="display_none ays-survey-question-max-selection-count-checkbox" <?php echo $question['options']['enable_max_selection_count'] ? 'checked' : ''; ?> name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][enable_max_selection_count]" value="on">
				<div class="ays-survey-answer-box" style="margin: 20px 0px;">
					<label class="ays-survey-question-max-selection-count-label">
						<span><?php echo __( "Maximum selection count", SURVEY_MAKER_NAME ); ?></span>
						<input type="number" class="ays-survey-input ays-survey-max-votes-field" autocomplete="off" tabindex="0" 
							placeholder="<?php echo __( "Maximum selection count", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" 
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][max_selection_count]" 
							value="<?php echo $question['options']['max_selection_count']; ?>" min="0">
						<div class="ays-survey-input-underline"></div> 
						<div class="ays-survey-input-underline-animation"></div>
					</label>
				</div>
			</div>
			<!-- Text limitations -->
			<div class="ays-survey-question-word-limitations <?php echo (($selected_question_type == "short_text" || $selected_question_type == "text") && $question['options']['enable_word_limitation']) ? "" : "display_none"; ?>">
				<input type="checkbox"
						class="display_none ays-survey-question-word-limitations-checkbox" 
						<?php echo $question['options']['enable_word_limitation'] ? 'checked' : ''; ?> 
						name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][enable_word_limitation]" 
						value="on">

				<div class="ays-survey-question-more-option-wrap-limitations ays-survey-question-word-limit-by ">
					<div class="ays-survey-question-word-limit-by-text">
						<span><?php echo __("Limit by", SURVEY_MAKER_NAME); ?></span>
					</div>
					<div class="ays-survey-question-word-limit-by-select">
						<select name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][limit_by]" class="ays-text-input ays-text-input-short">
							<option value="char" <?php echo ($question['options']['limit_by'] == "char") ? "selected": ""; ?>> <?php echo __("Characters")?> </option>
							<option value="word" <?php echo ($question['options']['limit_by'] == "word") ? "selected": ""; ?>> <?php echo __("Word")?> </option>
						</select>
					</div>
				</div>
				<div class="ays-survey-row-divider"><div></div></div>
				<div class="ays-survey-question-more-option-wrap-limitations ">
					<div class="ays-survey-answer-box">
						<label class="ays-survey-question-limitations-label">
							<span><?php echo __( "Length", SURVEY_MAKER_NAME ); ?></span>
							<input type="number" 
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][limit_length]"        
								class="ays-survey-input ays-survey-limit-length-input" autocomplete="off" tabindex="0" 
								placeholder="<?php echo __( "Length", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;"
								value="<?php echo $question['options']['limit_length']; ?>" min="0">
							<div class="ays-survey-input-underline"></div> 
							<div class="ays-survey-input-underline-animation"></div>
						</label>
					</div>
				</div>
				<div class="ays-survey-row-divider"><div></div></div>
				<div class="ays-survey-question-more-option-wrap-limitations ays-survey-question-word-show-word ">
					<label class="ays-survey-question-limitations-counter-label">
						<span><?php echo __( "Show word/character counter", SURVEY_MAKER_NAME ); ?></span>
						<input type="checkbox"
								name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][limit_counter]" 
								<?php echo $question['options']['limit_counter'] ? "checked" : ""; ?>
								autocomplete="off" 
								value="on" 
								class="ays-survey-text-limitations-counter-input">
					</label>
				</div>
			</div>
				<!-- Number limitations start -->
				<div class="ays-survey-question-number-limitations <?php echo ( ($selected_question_type == "number" || $selected_question_type == "phone") && $question['options']['enable_number_limitation'] ) ? "" : "display_none"; ?>">
				<input type="checkbox"
						class="display_none ays-survey-question-number-limitations-checkbox" 
						<?php echo $question['options']['enable_number_limitation'] ? 'checked' : ''; ?> 
						name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][enable_number_limitation]" 
						value="on">
				<!-- Min Number -->
				<?php if($selected_question_type != "phone"): ?>
				<div class="ays-survey-question-number-min-box ays-survey-question-number-votes-count-box <?php echo $selected_question_type == "phone" ? "display_none" : ""; ?>" style="margin: 20px 0px;">
					<label class="ays-survey-question-number-min-selection-label">
						<span><?php echo __( "Minimum value", SURVEY_MAKER_NAME ); ?></span>
						<input type="number" class="ays-survey-input ays-survey-number-min-votes ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
							placeholder="<?php echo __( "Minimum value", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;"
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][number_min_selection]"
							value="<?php echo $question['options']['number_min_selection']; ?>"
							>
						<div class="ays-survey-input-underline"></div> 
						<div class="ays-survey-input-underline-animation"></div>
					</label>
				</div>
				<!-- Max Number -->
				<div class="ays-survey-question-number-max-box ays-survey-question-number-votes-count-box <?php echo $selected_question_type == "phone" ? "display_none" : ""; ?>" style="margin: 20px 0px;">
					<label class="ays-survey-question-number-max-selection-label">
						<span><?php echo __( "Maximum value", SURVEY_MAKER_NAME ); ?></span>
						<input type="number" class="ays-survey-input ays-survey-number-max-votes ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
							placeholder="<?php echo __( "Maximum value", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;"
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][number_max_selection]"
							value="<?php echo $question['options']['number_max_selection']; ?>"
								>
						<div class="ays-survey-input-underline"></div> 
						<div class="ays-survey-input-underline-animation"></div>
					</label>
				</div>
				<?php endif; ?>
				<!-- Error message -->
				<div class="ays-survey-question-number-votes-count-box" style="margin: 20px 0px;">                                                        
					<label class="ays-survey-question-number-min-selection-label">
						<span><?php echo __( "Error message", SURVEY_MAKER_NAME ); ?></span>
						<input type="text"
							class="ays-survey-input ays-survey-number-error-message ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
							placeholder="<?php echo __( "Error Message", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;"
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][number_error_message]"
							value="<?php echo $question['options']['number_error_message']; ?>"
							>
							<div class="ays-survey-input-underline"></div> 
						<div class="ays-survey-input-underline-animation"></div>
					</label>
				</div>
				<!-- Show error message -->
				<div class="ays-survey-question-number-votes-count-box" style="margin: 20px 0px;">                                                        
					<label class="ays-survey-question-number-min-selection-label ays-survey-question-number-message-label">
						<span><?php echo __( "Show error message", SURVEY_MAKER_NAME ); ?></span>
						<input type="checkbox"
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][enable_number_error_message]" 
							<?php echo $question['options']['enable_number_error_message'] ? "checked" : ""; ?>
							autocomplete="off" 
							value="on" 
							class="ays-survey-number-enable-error-message">
					</label>
				</div>
				<div class="ays-survey-question-number-votes-count-box ">
					<div class="ays-survey-answer-box">
						<label class="ays-survey-question-number-min-selection-label">
							<span><?php echo __( "Length", SURVEY_MAKER_NAME ); ?></span>
							<input type="number" 
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][number_limit_length]"        
								class="ays-survey-input ays-survey-number-limit-length ays-survey-number-votes-inputs" autocomplete="off" tabindex="0" 
								placeholder="<?php echo __( "Length", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;"
								value="<?php echo $question['options']['number_limit_length']; ?>" min="0">
							<div class="ays-survey-input-underline"></div> 
							<div class="ays-survey-input-underline-animation"></div>
						</label>
					</div>
				</div>
				<!-- Show Char length -->
				<div class="ays-survey-question-number-votes-count-box ">
					<label class="ays-survey-question-number-min-selection-label ays-survey-question-number-message-label">
						<span><?php echo __( "Show character counter", SURVEY_MAKER_NAME ); ?></span>
						<input type="checkbox"
								name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][enable_number_limit_counter]" 
								<?php echo $question['options']['enable_number_limit_counter'] ? "checked" : ""; ?>
								autocomplete="off" 
								value="on" 
								class="ays-survey-number-number-limit-length">
					</label>
				</div>
				<hr>
			</div>
			<!-- Number limitations end -->
			<!-- User explanation -->
			<div class="ays-survey-question-user-explanation-wrap <?php echo $question['options']['user_explanation'] ? "" : "display_none";?>">
				<div class="ays-survey-answer-box" style="margin: 20px 0px;">
					<label class="ays-survey-question-user-explanation-label">
						<input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" disabled
							placeholder="<?php echo __( "User Explanation", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;"  
							value="<?php echo __( "User Explanation", SURVEY_MAKER_NAME ); ?>" >
						<div class="ays-survey-input-underline"></div> 
						<div class="ays-survey-input-underline-animation"></div>
					</label>
				</div>
			</div>
			<!-- Admin note -->
			<div class="ays-survey-question-admin-note <?php echo $question['options']['enable_admin_note'] ? "" : "display_none";?>">
				<div class="ays-survey-answer-box" style="margin: 20px 0px;">
					<label class="ays-survey-question-admin-note-label">
						<span><?php echo __( "Admin note", SURVEY_MAKER_NAME ); ?></span>
						<input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" 
							placeholder="<?php echo __( "Note", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" 
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][admin_note]" 
							value="<?php echo $question['options']['admin_note']; ?>" min="0">
						<div class="ays-survey-input-underline"></div> 
						<div class="ays-survey-input-underline-animation"></div>
					</label>
				</div>
			</div>
				<!-- URL Parameter -->
				<div class="ays-survey-question-url-parameter <?php echo $question['options']['enable_url_parameter'] ? "" : "display_none";?>">
				<div class="ays-survey-answer-box" style="margin: 20px 0px;">
					<label class="ays-survey-question-url-parameter-label">
						<span><?php echo __( "Parameter Name", SURVEY_MAKER_NAME ); ?></span>
						<input type="text" class="ays-survey-input" autocomplete="off" tabindex="0" 
							placeholder="<?php echo __( "Parameter", SURVEY_MAKER_NAME ); ?>" style="font-size: 14px;" 
							name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][url_parameter]"
							value="<?php echo $question['options']['url_parameter']; ?>" min="0" >
						<div class="ays-survey-input-underline"></div> 
						<div class="ays-survey-input-underline-animation"></div>
					</label>
				</div>
			</div>
		</div>
		<div class="ays-survey-actions-row">
			<div class="ays-survey-actions-left">
				<div class="ays-survey-actions-answers-bulk-add <?php echo ($selected_question_type == 'radio' || $selected_question_type == 'checkbox' || $selected_question_type == 'select') ? '' : 'display_none' ;?>">
					<div class="ays-survey-answer-icon-box">
						<div class="ays-survey-action-bulk-add-answer">
							<div class="ays-survey-action-bulk-add-answer-content">
								<div class="ays-survey-action-bulk-add-answer-content-div">
									<div class="ays-survey-icons ays-survey-action-bulk-add-answer-icon">
										<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/download.svg" class="ays-survey-action-bulk-add-answer-icon-svg">
									</div>
								</div>
							</div>
							<div class="ays-survey-action-bulk-add-answer-text">
								<span><?php echo __('Bulk add',SURVEY_MAKER_NAME)?></span>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="ays-survey-actions">
				<div class="ays-survey-answer-icon-box">
					<div class="ays-survey-action-duplicate-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Duplicate',SURVEY_MAKER_NAME)?>">
						<div class="ays-question-img-icon-content">
							<div class="ays-question-img-icon-content-div">
								<div class="ays-survey-icons">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/duplicate.svg">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ays-survey-answer-icon-box">
					<div class="ays-survey-action-delete-question appsMaterialWizButtonPapericonbuttonEl" data-container="body" data-toggle="popover" data-trigger="hover" data-placement="auto" data-content="<?php echo __('Delete',SURVEY_MAKER_NAME)?>">
						<div class="ays-question-img-icon-content">
							<div class="ays-question-img-icon-content-div">
								<div class="ays-survey-icons">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/trash.svg">
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="ays-survey-vertical-divider"><div></div></div>
				<div class="ays-survey-answer-elem-box <?php echo $question['type'] == 'html' ? 'display_none_not_important' : ''; ?>" >
					<label>
						<span>
							<span><?php echo __( 'Required', SURVEY_MAKER_NAME ); ?></span>
						</span>
						<input type="checkbox" <?php echo $question['options']['required'] ? 'checked' : ''; ?> class="display_none ays-survey-input-required-question ays-switch-checkbox" name="<?php echo $html_name_prefix; ?>sections[<?php echo $section['id']; ?>][questions][<?php echo $question['id']; ?>][options][required]" value="on">
						<div class="switch-checkbox-wrap" aria-label="Required" tabindex="0" role="checkbox">
							<div class="switch-checkbox-track"></div>
							<div class="switch-checkbox-ink"></div>
							<div class="switch-checkbox-circles">
								<div class="switch-checkbox-thumb"></div>
							</div>
						</div>
					</label>
				</div>
				<div class="ays-survey-answer-icon-box ays-survey-question-more-actions droptop">
					<div class="ays-survey-action-more appsMaterialWizButtonPapericonbuttonEl" data-toggle="dropdown">
						<div class="ays-question-img-icon-content">
							<div class="ays-question-img-icon-content-div">
								<div class="ays-survey-icons">
									<img src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/more-vertical.svg">
								</div>
							</div>
						</div>
					</div>
					<div class="dropdown-menu dropdown-menu-right">
						<button type="button" class="dropdown-item ays-survey-question-action" data-action="move-to-section">
							<?php echo __( 'Move to section', SURVEY_MAKER_NAME ); ?>
						</button>
						<button type="button" class="dropdown-item ays-survey-question-action <?php echo $survey_default_type == 'checkbox' ? '' : 'display_none'; ?>" data-action="<?php echo $question['options']['enable_max_selection_count'] ? "max-selection-count-disable" : "max-selection-count-enable"; ?>">
							<img class="ays-survey-question-action-icon display_none" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
							<?php echo __( 'Enable selection count', SURVEY_MAKER_NAME ); ?>
						</button>
						<button type="button" class="dropdown-item ays-survey-question-action <?php echo in_array( $selected_question_type, $logic_jump_question_types ) ? '' : 'display_none'; ?>" data-action="<?php echo $question['options']['is_logic_jump'] ? 'go-to-section-based-on-answers-disable' : 'go-to-section-based-on-answers-enable'; ?>">
							<img class="ays-survey-question-action-icon <?php echo $question['options']['is_logic_jump'] ? '' : 'display_none'; ?>" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
							<?php echo __( 'Logic jump', SURVEY_MAKER_NAME ); ?>
						</button>
						<button type="button" class="dropdown-item ays-survey-question-action" data-action="<?php echo $question['options']['user_explanation'] ? "disable-user-explanation" : "enable-user-explanation"; ?>">
							<img class="ays-survey-question-action-icon <?php echo $question['options']['user_explanation'] ? '' : 'display_none'; ?>" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
							<?php echo __( 'User explanation', SURVEY_MAKER_NAME ); ?>
						</button>
						<button type="button" class="dropdown-item ays-survey-question-action" data-action="<?php echo $question['options']['enable_admin_note'] ? "disable-admin-note" : "enable-admin-note"; ?>">
							<img class="ays-survey-question-action-icon <?php echo $question['options']['enable_admin_note'] ? '' : 'display_none'; ?>" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
							<?php echo __( 'Admin Note', SURVEY_MAKER_NAME ); ?>
						</button>
						<button type="button" class="dropdown-item ays-survey-question-action" data-action="<?php echo $question['options']['enable_url_parameter'] ? "disable-url-parameter" : "enable-url-parameter"; ?>">
							<img class="ays-survey-question-action-icon <?php echo $question['options']['enable_url_parameter'] ? '' : 'display_none'; ?>" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
							<?php echo __( 'URL Parameter', SURVEY_MAKER_NAME ); ?>
						</button>
						<button type="button" class="dropdown-item ays-survey-question-action" data-action="<?php echo $question['options']['enable_hide_results'] ? "disable-hide-results" : "enable-hide-results"; ?>">
							<img class="ays-survey-question-action-icon <?php echo $question['options']['enable_hide_results'] ? '' : 'display_none'; ?>" src="<?php echo SURVEY_MAKER_ADMIN_URL; ?>/images/icons/done.svg">
							<?php echo __( 'Hide results', SURVEY_MAKER_NAME ); ?>
						</button>
						<?php if(isset($question['type']) && ($question['type'] == 'matrix_scale' || $question['type'] == 'linear_scale')): ?>
						<button type="button" class="dropdown-item ays-survey-question-action">
							<a href="https://ays-pro.com/wordpress/survey-maker" style="color: gray;font-style: italic;" target="_blank" ><?php echo __( 'Likert scale', "survey-maker" ); ?> (Agency) </a>
						</button>
						<?php endif;?>
						<button type="button" class="dropdown-item ays-survey-question-action ays-survey-question-id-copy-box" class="ays_help" data-toggle="tooltip" data-action="copy-question-id" onClick="selectElementContents(this)" title="<?php echo __('Click for copy',SURVEY_MAKER_NAME);?>" >
							<i><?php echo __( 'Question ID', SURVEY_MAKER_NAME ) . ": "; ?></i>
							<strong class="ays-survey-question-id-copy"  style="font-size:16px; font-style:normal;"  > <?php echo $question['id']; ?></strong>
						</button>
						<button type="button" class="dropdown-item ays-survey-question-action <?php echo $survey_default_type == 'text' || $survey_default_type == 'short_text' ? '' : 'display_none'; ?>" data-action="<?php echo $question['options']['enable_word_limitation'] ? "word-limitation-disable" : "word-limitation-enable"; ?>"><?php echo __( 'Enable word limitation', SURVEY_MAKER_NAME ); ?></button>
						<button type="button" class="dropdown-item ays-survey-question-action <?php echo $survey_default_type == 'number' || $survey_default_type == 'phone' ? '' : 'display_none'; ?>" data-action="<?php echo $question['options']['enable_word_limitation'] ? "number-word-limitation-disable" : "number-word-limitation-enable"; ?>"><?php echo __( 'Enable limitation', SURVEY_MAKER_NAME ); ?></button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
<!-- Questons end -->
