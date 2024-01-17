<?php
extract($args);
?>
<div id="poststuff" class="ays-survey-tab-content <?php echo ($ays_survey_tab == 'poststuff') ? 'ays-survey-tab-content-active' : ''; ?>">
    <div id="post-body" class="metabox-holder">
        <div id="post-body-content">
            <div class="meta-box-sortables ui-sortable">
				<?php
				$each_submission_obj->views();
				?>
                <form method="post">
					<?php
					$each_submission_obj->prepare_items();
					$each_submission_obj->search_box('Search', SURVEY_MAKER_NAME);
					$each_submission_obj->display();
//                    $each_submission_obj->mark_as_read_all_results( $survey_id );
					?>
                </form>
            </div>
        </div>
    </div>
    <br class="clear">
</div>