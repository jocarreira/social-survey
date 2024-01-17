<?php
    if ( ! function_exists( 'mycred_survey_maker_load_hook' ) ) :
    function mycred_survey_maker_load_hook() {
        
        if ( !class_exists( 'myCRED_Hook' ) ) return;
        if ( class_exists( 'myCRED_Hook_Survey_Maker' ) ) return;

        class myCRED_Hook_Survey_Maker extends myCRED_Hook {

            /**
             * Construct
             */
            function __construct( $hook_prefs, $type = MYCRED_DEFAULT_TYPE_KEY ) {

                parent::__construct( array(
                    'id'       => 'surveymaker',
                    'defaults' => array(
                        'creds'    => 1,
                        'log'      => '%plural% for finishing',
                        'limit'    => '0/x'
                    )
                ), $hook_prefs, $type );
            }

            /**
             * Run
             * This class method is fired of by myCRED when it's time to load all hooks.
             * It should be used to "hook" into the plugin we want to add support for or the
             * appropriate WordPress instances. Anything that must be loaded for this hook to work
             * needs to be called here.
             * @since 1.0
             * @version 1.0
             */
            public function run() {
                add_action( 'ays_survey_maker_mycred', array( $this, 'finish_survey' )); 
            }

            /**
             * Poll Voting
             * @since 1.0
             * @version 1.0
             */
            public function finish_survey() {
                $id = (isset($survey['id'])) ? absint(intval($survey['id'])) : null;
                $user_id = get_current_user_id();
                if ( $user_id == 0 ) return;

                if ( $this->core->exclude_user( $user_id ) ) return;
                
                if ( ! $this->over_hook_limit( '', 'survey_maker_finishing', $user_id ) )                
                    $this->core->add_creds(
                        'survey_maker_finishing',
                        $user_id,
                        $this->prefs['creds'],
                        $this->prefs['log'],
                        $id,
                        array( 'ref_type' => 'post' ),
                        $this->mycred_type
                    );
            }

            /**
             * Preferences
             * If the hook has settings, it has to be added in using this class method.
             * @since 1.0
             * @version 1.0
             */
            public function preferences() {

                $prefs = $this->prefs;
            ?>
            <label class="subheader"><?php _e( 'Earn:', 'mycred_surveymaker' ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( 'creds' ); ?>" id="<?php echo $this->field_id( 'creds' ); ?>" value="<?php echo $this->core->number( $prefs['creds'] ); ?>" size="8" /></div>
                </li>
                <li>
                    <label for="<?php echo $this->field_id( array( 'giving' => 'limit' ) ); ?>"><?php _e( 'Limit:', 'mycred' ); ?></label>
                    <?php echo $this->hook_limit_setting( $this->field_name( 'limit' ), $this->field_id( 'limit' ), $prefs['limit'] ); ?>
                </li>
            </ol>
            <label class="subheader"><?php _e( 'Log Template:', 'mycred_surveymaker' ); ?></label>
            <ol>
                <li>
                    <div class="h2"><input type="text" name="<?php echo $this->field_name( 'log' ); ?>" id="<?php echo $this->field_id( 'log' ); ?>" value="<?php echo esc_attr( $prefs['log'] ); ?>" class="long" /></div>
                </li>
            </ol>
            <?php

            }

            /**
             * Sanitise Preferences
             * While myCRED does some basic sanitization of the data you submit in the settings,
             * we do need to handle our hook limits since 1.6. If your settings contain a checkbox (or multiple)
             * then you should also use this method to handle the submission making sure the checkbox values are
             * taken care of.
             * @since 1.0
             * @version 1.0
             */
            function sanitise_preferences( $data ) {

                if ( isset( $data['limit'] ) && isset( $data['limit_by'] ) ) {
                    $limit = sanitize_text_field( $data['limit'] );
                    if ( $limit == '' ) $limit = 0;
                    $data['limit'] = $limit . '/' . $data['limit_by'];
                    unset( $data['limit_by'] );
                }

                return $data;

            }
        }
    }
endif;