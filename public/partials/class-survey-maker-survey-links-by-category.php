<?php
    class Ays_Survey_Maker_Links_By_Category_Shortcodes_Public{
        protected $plugin_name;    
        private $version;
        protected $html_class_prefix = 'ays-survey-links-by-category-';
        protected $html_name_prefix = 'ays-survey-';
        protected $name_prefix = 'ays_survey_';
        private $unique_id;
        private $unique_id_in_class;
    
        public function __construct($plugin_name, $version){
    
            $this->plugin_name = $plugin_name;
            $this->version = $version;            
    
            add_shortcode('ays_survey_links_by_category', array($this, 'ays_survey_links_by_category_method'));
        }
    
        /*
        ==========================================
            Survey Links by Category | Start
        ==========================================
        */

        public function ays_survey_links_by_category_method( $attr ){

            $unique_id = uniqid();
            $this->unique_id = $unique_id;
            $this->unique_id_in_class = "-" . $unique_id;

            $survey_links_by_category_html = $this->ays_survey_links_by_category_html( $attr );
            return str_replace(array("\r\n", "\n", "\r"), "\n", $survey_links_by_category_html);
        }

        public function ays_survey_links_by_category_html( $attr ){

            $cat_id = ( isset( $attr['id'] ) && $attr['id'] != '' ) ? absint( intval( $attr['id'] ) ) : null;

            $content = array();
            if( $cat_id != null ){
                $get_surveys_by_category = Survey_Maker_Data::get_surveys_by_category($cat_id);

                $content[] = '<div class="'.$this->html_class_prefix.'container">';
                    $content[] = '<div class="'.$this->html_class_prefix.'content">';
                        $content[] = '<table class="'.$this->html_class_prefix.'table">';
                            $content[] = '<thead class="'.$this->html_class_prefix.'head">';
                                $content[] = '<tr>';
                                    $content[] = '<th>';
                                        $content[] = __('Survey Title', $this->plugin_name);
                                    $content[] = '</th>';
                                    $content[] = '<th>';
                                        $content[] = __('Survey link', $this->plugin_name);
                                    $content[] = '</th>';
                                $content[] = '</tr>';
                            $content[] = '</thead>';
                            $content[] = '<tbody class="'.$this->html_class_prefix.'body">';
                                foreach ($get_surveys_by_category as $key => $survey) {
                                    $title = ( isset( $survey['title'] ) && $survey['title'] != '' ) ? sanitize_text_field( stripslashes( $survey['title'] )) : '';

                                    $options = ( isset( $survey['options'] ) && $survey['options'] != '' ) ? json_decode( $survey['options'], true ) : '';

                                    // Survey main url
                                    $survey_main_url = (isset($options[ 'survey_main_url' ]) &&  $options[ 'survey_main_url' ] != '') ? stripslashes( esc_url( $options[ 'survey_main_url' ] ) ) : '';
                                    $href = 'javascript:void(0)';
                                    $target = '';
                                    $disabled = 'disabled';
                                    if($survey_main_url != ''){
                                        $href = $survey_main_url;
                                        $target = '_blank';
                                        $disabled = '';
                                    }
                                    $content[] = '<tr>';
                                        $content[] = '<td>';
                                            $content[] = $title;
                                        $content[] = '</td>';
                                        $content[] = '<td style="text-align:center;">';
                                            $content[] = '<button '.$disabled.' class="'.$this->html_class_prefix.'link-button">';
                                                $content[] = '<a href="'.$href.'" target="'.$target.'" class="'.$this->html_class_prefix.'link">'. __('Open', $this->plugin_name) .'</a>';
                                            $content[] = '</button>';
                                        $content[] = '</td>';
                                    $content[] = '</tr>';
                                }
                            $content[] = '</tbody>';
                        $content[] = '</table>';
                    $content[] = '</div>';
                $content[] = '</div>';
            }
            $content = implode('', Survey_Maker_Data::ays_survey_translate_content($content));

            return $content;
        }

        /*
        ==========================================
            Survey Links by Category | End
        ==========================================
        */
    }
?>