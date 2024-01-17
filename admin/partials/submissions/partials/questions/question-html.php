<?php
extract($args);

$text_types = array(
	'text',
	'short_text',
	'number',
	'phone',
	'name',
	'email',
	'date',
	'time',
	'date_time',
);

if( in_array( $question['question_type'], $text_types ) &&
    $question['question_type'] != 'date' &&
    $question['question_type'] != 'time' &&
    $question['question_type'] != 'date_time'):
    ?>
    <div class="ays-survey-submission-text-answers-div">
        <?php
        if( isset( $question['answers'] ) && !empty( $question['answers'] ) ):
            if( isset( $question['answers'][ $question['question_id'] ] ) && !empty( $question['answers'][ $question['question_id'] ] ) ):
                $filtered_text_answers = array_values(array_unique($question['answers'][ $question['question_id'] ]));
                foreach( $filtered_text_answers as $aid => $answer ):
                    $text_answer_count = isset($question['sum_of_same_answers'][$answer]) && $question['sum_of_same_answers'][$answer] != "" ? $question['sum_of_same_answers'][$answer] : "";
                    ?>
                    <div class="ays-survey-submission-text-answer">
                        <div><?php echo stripslashes(nl2br( $answer) ); ?></div>
                        <div><?php echo stripslashes(nl2br( $text_answer_count) ); ?></div>
                    </div>
                <?php
                endforeach;
            endif;
        endif;
        ?>
    </div>
<?php
elseif( $question['question_type'] == 'date' ):
    ?>
    <div class="ays-survey-question-date-summary-wrapper">
        <div class="ays-survey-question-date-summary-wrap">
            <?php
            if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                if( isset( $question['answers'][ $question['question_id'] ] ) && !empty( $question['answers'][ $question['question_id'] ] ) ){
                    $dates_array = array();
                    foreach( $question['answers'][ $question['question_id'] ] as $aid => $answer ){
                        $year_month = explode( '-', $answer );
                        $day = $year_month[2];
                        if( isset( $dates_array[ $year_month[0] ] ) ){
                            if( isset( $dates_array[ $year_month[0] ][ $year_month[1] ] ) ){
                                if( isset( $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] ) ){
                                    $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] += 1;
                                }else{
                                    $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                                }
                            }else{
                                $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                            }
                        }else{
                            $dates_array[ $year_month[0] ][ $year_month[1] ][ $year_month[2] ] = 1;
                        }
                    }

                    ksort( $dates_array, SORT_NATURAL );
                    foreach( $dates_array as $year => $months ){
                        ksort( $months, SORT_NATURAL );
                        foreach( $months as $month => $days ){
                            ksort( $days, SORT_NATURAL );
                        }
                    }

                    foreach( $dates_array as $year => $months ){
                        foreach( $months as $month => $days ){
                            ?>
                            <div class="ays-survey-question-date-summary-row">
                                <div class="ays-survey-question-date-summary-year-month"><?php echo date_i18n( 'F Y', strtotime( $year ."-". $month ) ); ?></div>
                                <div class="ays-survey-question-date-summary-days">
                                    <div class="ays-survey-question-date-summary-days-row">
                                        <?php
                                        foreach( $days as $day => $count ){
                                            if( $count == 1 ){
                                                ?>
                                                <div class="ays-survey-question-date-summary-days-row-day">
                                                    <span><?php echo esc_html( $day ); ?></span>
                                                </div>
                                                <?php
                                            }else{
                                                ?>
                                                <div class="ays-survey-question-date-summary-days-row-day ays-survey-question-date-summary-days-row-day-with-count">
                                                    <span><?php echo esc_html( $day ); ?></span>
                                                    <div class="ays-survey-question-date-summary-days-row-day-count"><?php echo esc_html( $count ); ?></div>
                                                </div>
                                                <?php
                                            }
                                        }
                                        ?>
                                    </div>
                                </div>
                            </div>
                            <?php
                        }
                    }
                }
            }
            ?>
        </div>
    </div>
<?php
elseif( $question['question_type'] == 'time' ):
    ?>
    <div class="ays-survey-question-time-summary-wrapper">
        <div class="ays-survey-question-time-summary-wrap">
            <?php
            if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                if( isset( $question['answers'][ $question['question_id'] ] ) && !empty( $question['answers'][ $question['question_id'] ] ) ){
                    $hours_array = array();
                    foreach( $question['answers'][ $question['question_id'] ] as $aid => $answer ){
                        $answer_hour_minutes = explode( ':', $answer );
                        $answer_hour = isset($answer_hour_minutes[0]) && $answer_hour_minutes[0] != "" ? esc_attr($answer_hour_minutes[0]) : "00";
                        $answer_minute = isset($answer_hour_minutes[1]) && $answer_hour_minutes[1] != "" ? esc_attr($answer_hour_minutes[1]) : "00";
                        if( isset( $hours_array[ $answer_hour ] ) ){
                            if( isset( $hours_array[ $answer_hour ][ $answer_minute ] ) ){
                                $hours_array[ $answer_hour ][ $answer_minute ] += 1;
                            }else{
                                $hours_array[ $answer_hour ][ $answer_minute ] = 1;
                            }
                        }else{
                            $hours_array[ $answer_hour ][ $answer_minute ] = 1;
                        }
                    }
                    ksort($hours_array);
                    foreach( $hours_array as $k_hours => $v_hours ){
                        ?>
                        <div class="ays-survey-question-time-summary-row">
                            <div class="ays-survey-question-time-summary-hour"><span class="ays-survey-question-time-summary-hour-all"><?php echo $k_hours . " :"; ?></span></div>
                            <div class="ays-survey-question-time-summary-hours">
                                <div class="ays-survey-question-time-summary-hours-row">
                                    <?php
                                    foreach( $v_hours as $k_hour => $count ){
                                        if( $count == 1 ){
                                            ?>
                                            <div class="ays-survey-question-time-summary-hours-row-hour">
                                                <span><?php echo esc_html( $k_hour ); ?></span>
                                            </div>
                                            <?php
                                        }else{
                                            ?>
                                            <div class="ays-survey-question-time-summary-hours-row-hour ays-survey-question-time-summary-hours-row-hour-with-count">
                                                <span><?php echo esc_html( $k_hour ); ?></span>
                                                <div class="ays-survey-question-time-summary-hours-row-hour-count"><?php echo esc_html( $count ); ?></div>
                                            </div>
                                            <?php
                                        }
                                    }
                                    ?>
                                </div>
                            </div>
                        </div>
                        <?php
                    }
                }
            }
            ?>
        </div>
    </div>
<?php
elseif( $question['question_type'] == 'date_time' ):
    ?>
    <div class="ays-survey-question-date-time-summary-wrapper">
        <div class="ays-survey-question-date-time-summary-wrap">
            <?php
            if( isset( $question['answers'] ) && !empty( $question['answers'] ) ){
                if( isset( $question['answers'][ $question['question_id'] ] ) && !empty( $question['answers'][ $question['question_id'] ] ) ){
                    $dates_array = array();
                    $time_array = array();
                    foreach( $question['answers'][ $question['question_id'] ] as $aid => $answer ){
                        $each_answer = explode(" " , trim($answer));
                        $date_answer = isset($each_answer[0]) && $each_answer[0] != '' ? $each_answer[0] : '';
                        $time_answer = isset($each_answer[1]) && $each_answer[1] != '' ? $each_answer[1] : '';
                        if($time_answer != '-' || $date_answer != '-'){
                            $year_month_day = $date_answer != '-' ? explode( '-', $date_answer ) : '-';

                            $dates_collected = '';
                            if($year_month_day != '-'){
                                $dates_collected = date_i18n( 'F Y d', strtotime( $year_month_day[0] ."-". $year_month_day[1] . "-". $year_month_day[2] ) );
                            }

                            $dates_array[] = $dates_collected;
                            $time_array[$dates_collected][] = $time_answer;
                            $dates_new_array = array_count_values($dates_array);
                        }
                    }
                    if(!empty($dates_array)){
                        foreach($dates_new_array as $new_date => $new_date_value){
                            ?>
                            <div class="ays-survey-question-date-time-summary-row">

                                <div class="ays-survey-question-date-time-summary-year-month-day">
                                    <div class="ays-survey-question-date-time-summary-year-month-day-row">
                                        <?php
                                        if( $new_date_value == 1 ){
                                            ?>
                                            <div class="ays-survey-question-time-summary-hours-row-hour" <?php echo !$new_date ? 'style="background-color: white;"' : '' ?>>
                                                <span><?php echo esc_html( $new_date ); ?></span>
                                            </div>
                                            <?php
                                        }elseif($new_date != ''){
                                            ?>
                                            <div class="ays-survey-question-time-summary-hours-row-hour ays-survey-question-time-summary-hours-row-hour-with-count">
                                                <span><?php echo esc_html( $new_date ); ?></span>
                                                <div class="ays-survey-question-time-summary-hours-row-hour-count"><?php echo esc_html( $new_date_value ); ?></div>
                                            </div>
                                            <?php
                                        }
                                        ?>
                                    </div>

                                </div>
                                <div class="ays-survey-question-date-summary-days">
                                    <?php
                                    $time_array_new = array();
                                    foreach( $time_array[$new_date] as $f => $r ){
	                                    if($r != '' && $r != '-') {
                                            if( isset( $time_array_new[ $r ] ) ){
		                                        $time_array_new[ $r ] += 1;
                                            }else{
	                                            $time_array_new[ $r ] = 1;
                                            }
	                                    }
                                    }

                                    foreach( $time_array_new as $f => $r ){
                                        ?><span class="ays-survey-question-date-time-summary-hour-all"><?= $f ?><span class="ays-survey-question-date-time-summary-hour-all-count"><?= $r ?></span></span><?php
                                    }
                                    ?>
                                </div>
                            </div>
                            <?php
                        }
                    }

                }
            }
            ?>
        </div>
    </div>
<?php
else:
    ?>
    <div id="survey_answer_chart_<?php echo $question['question_id']; ?>" style="width: 100%;" class="chart_div">
        <img src="<?php echo SURVEY_MAKER_ADMIN_URL ?>/images/loaders/tail-spin.svg" class="ays-survey-submission-summary-loader" style="cursor:pointer; margin: 80px auto">
    </div>
    <?php
    if( !empty( $question['otherAnswers'] ) ):
        ?>
        <h2 class="ays-survey-subtitle"><?php echo __( '"Other" answers', SURVEY_MAKER_NAME ); ?></h2>
        <div class="ays-survey-submission-text-answers-div">
            <?php
            if( isset( $question['otherAnswers'] ) && !empty( $question['otherAnswers'] ) ):
                $filtered_other_answers = array_values(array_unique($question['otherAnswers']));
                foreach( $filtered_other_answers as $aid => $answer ):
                    $other_answer_count = isset($question['same_other_count'][$answer]) && $question['same_other_count'][$answer] != "" ? $question['same_other_count'][$answer] : "";
                    ?>
                    <div class="ays-survey-submission-text-answer">
                        <div><?php echo stripslashes($answer); ?></div>
                        <div><?php echo stripslashes($other_answer_count); ?></div>
                    </div>

                <?php
                endforeach;
            endif;
            ?>
        </div>
    <?php
    endif;
endif;

