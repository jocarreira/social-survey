<?php
    $request_id = isset($_GET['survey']) ? intval($_GET['survey']) : null;

    if ($request_id === null) {
        wp_redirect( admin_url('admin.php') . '?page=' . $this->plugin_name . '-admin' );
    }else {
        $this->survey_maker_update_request_read_status($request_id);
    }

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

    $data = $this->ays_survey_get_request_data_by_id( $request_id );
    
    $title = ( isset( $data['survey_title'] ) && $data['survey_title'] != '' ) ? stripslashes( $data['survey_title'] ) : '';

    $category_id = ( isset( $data['category_id'] ) && $data['category_id'] != '' ) ? absint( $data['category_id'] ) : 1;

    $category_title = $this->ays_survey_get_survey_category_by_id( $category_id );
    $category_title = ( isset( $category_title ) && $category_title != '' ) ? stripslashes( $category_title ) : 'Uncategorized';

    $user_id = ( isset( $data['user_id'] ) && $data['user_id'] != '' ) ? absint( $data['user_id'] ) : 1;

    $request_data = ( isset( $data['request_data'] ) && $data['request_data'] != '' ) ? json_decode( $data['request_data'], true ) : '';
?>

<!-- This file should primarily consist of HTML with a little bit of PHP. -->
<div class="wrap">
    <div class="ays-survey-frontend-request-content">
        <div class="form-group row">
            <div class="col-sm-3">
                <p class="ays-frontend-request-data-text ays-frontend-request-data"><?php echo __('Survey Title', $this->plugin_name); ?></p>
            </div>
            <div class="col-sm-9">
                <p class="ays-frontend-request-data-text"><?php echo $title; ?></p>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <div class="col-sm-3">
                <p class="ays-frontend-request-data-text ays-frontend-request-data"><?php echo __('Survey category', $this->plugin_name); ?></p>
            </div>
            <div class="col-sm-9">
                <p class="ays-frontend-request-data-text"><?php echo $category_title; ?></p>
            </div>
        </div>
        <hr>
        <div class="form-group row">
            <div class="col-sm-3">
                <p class="ays-frontend-request-data-text ays-frontend-request-data"><?php echo __('Question', $this->plugin_name); ?></p>
            </div>
        </div>
        <hr>
        <?php
            foreach ( $request_data as $question_id => $question ):
                $type = ( isset( $question['type'] ) && $question['type'] != '' ) ? stripslashes( $question['type'] ) : '';
        ?>
                <div class="form-group row">
                    <div class="col-sm-3">
                        <p class="ays-frontend-request-data-text">
                            <strong><?php echo ( isset($question['question'] ) && $question['question'] != '' ) ? stripslashes( esc_html( $question['question'] ) ) : ''; ?></strong>
                        </p>
                        <p class="ays-frontend-request-data-text" style="font-size:15px; color:#717171;">
                            Question Type: <?php echo $survey_question_types[$type]; ?>
                        </p>
                    </div>
                </div>
                <?php if( $type == 'text' || $type == 'number' || $type == 'short_text' || $type == 'name' || $type == 'email' ): ?>
                <?php else: 
                    $answers = ( isset( $question['answers'] ) && !empty( $question['answers'] ) ) ? $question['answers'] : array();
                    foreach ( $answers as $ans_key => $answer):
                        $ans_title = ( isset( $answer['answer'] ) && $answer['answer'] != '' ) ? stripslashes( esc_html( $answer['answer'] ) ) : '';
                        switch ( $type ) {
							case 'checkbox':
								$answer_title = $ans_title;
								$icon = '<img src="' . SURVEY_MAKER_ADMIN_URL . '/images/icons/checkbox-unchecked.svg">';
								break;
                            case 'radio':
                            case 'dropdown':
                            default:
                                $answer_title = $ans_title;
								$icon = '<img src="' . SURVEY_MAKER_ADMIN_URL . '/images/icons/radio-button-unchecked.svg">';
                                break;
                        }
                    ?>
                        <div class="form-group row">
                            <div class="col-sm-9">
                                <div class="row">
                                    <div class="ays-frontend-request-data-text-input-type">
                                        <?php echo $icon; ?>
                                    </div>
                                    <div>
                                        <p class="ays-frontend-request-data-text-answer">
                                            <?php echo $answer_title; ?>
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php 
                        endforeach;
                    ?> 
                <?php endif; ?>
            <hr>
        <?php
            endforeach;
        ?>
    </div>
</div>