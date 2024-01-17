<?php
extract($args);
$submission_text = $submission_count_and_ids['submission_count'] == 1 ? 'submission' : 'submissions';
?>
<div id="statistics_of_answer" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'statistics_of_answer') ? 'ays-survey-tab-content-active' : ''; ?>"  data-survey-id="<?= $survey_id ?>">
    <div class="wrap">
        <div class="ays-survey-submission-summary-question-container ays-survey-submission-summary-header-container">
            <div class="ays-survey-submission-summary-question-container-title">
                <div class="ays-survey-submission-summary-header-container-survey-title display_none_not_important" style="font-size: 36px;"><?php echo $survey_name['title']; ?></div>
                <h2 style="margin: 0;"><?php echo sprintf( __( 'In total %s %s', SURVEY_MAKER_NAME ), intval( $submission_count_and_ids['submission_count'] ), $submission_text ); ?></h2>
            </div>
            <div class="ays-survey-submission-summary-question-container-buttons ays-survey-submission-summary-question-container-buttons-with-loader">
                <button type="button" class="button button-primary ays-survey-submission-summary-print" <?php echo $export_disabled; ?>><?php echo __( 'Print', SURVEY_MAKER_NAME); ?></button>
            </div>
        </div>
		<?php
		if( is_array( $ays_survey_individual_questions['sections'] ) ):
			foreach ($ays_survey_individual_questions['sections'] as $section_key => $section) {
				?>
                <div class="ays-survey-submission-section ays-survey-submission-summary-section" data-section-id="<?= $section['id'] ?>" data-survey-id="<?= $survey_id ?>">
					<?php if($section['title'] != "" || $section['description'] != ""):?>
                        <div class="ays_survey_name ays-survey-submission-summary-section-header" style="border-top-color: <?php echo $survey_for_charts; ?>;">
                            <h3><?php echo $section['title']; ?></h3>
                            <p><?php echo ($survey_allow_html_in_section_description) ? strip_tags(htmlspecialchars_decode($section['description'] )) : nl2br( $section['description'] ) ?></p>
                        </div>
					<?php else:?>
                        <div class="ays_survey_name ays-survey-submission-summary-section-header" style="border-top-color: <?php echo $survey_for_charts; ?>;">
                            <h3><?php echo __( 'Untitled section' , SURVEY_MAKER_NAME );; ?></h3>
                        </div>
					<?php endif; ?>
					<?php
					foreach ( $section['questions'] as $q_key => $question ) {
						?>
                        <div class="ays-survey-submission-summary-question-container" data-question-id="<?= $question['id'] ?>">
                            <div class="ays-survey-submission-summary-question-header">
                                <div class="ays-survey-submission-summary-question-header-content">
                                    <div class="ays-survey-submission-summary-question-title-item" style="text-align:center;"><?php echo Survey_Maker_Data::ays_autoembed( nl2br( $question['question'] ) ); ?></div>
                                    <p style="text-align:center;">
                                        <span class="ays-survey-submission-summary-question-submissions-count"></span>
                                        <?php echo __(' submissions',SURVEY_MAKER_NAME); ?>
                                    </p>
                                </div>
                                <div class="ays-survey-submission-summary-question-container-buttons">
                                    <!-- <button type="button" class="button button-primary ays-survey-submission-summary-export-chart-img"></button> -->
                                    <img src="<?php echo SURVEY_MAKER_ADMIN_URL ?>/images/icons/download-file.svg" class="ays-survey-submission-summary-export-chart-img" style="cursor:pointer">
                                </div>
                            </div>
                            <div class="ays-survey-submission-summary-question-content">
                                <img src="<?php echo SURVEY_MAKER_ADMIN_URL ?>/images/loaders/tail-spin.svg" class="ays-survey-submission-summary-loader" style="cursor:pointer; margin: 80px auto">
                            </div>
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


