<?php

/**
 * Provide a public-facing view for the plugin
 *
 * This file is used to markup the public-facing aspects of the plugin.
 *
 * @link       https://ays-pro.com/
 * @since      1.0.0
 *
 * @package    Survey_Maker
 * @subpackage Survey_Maker/public/partials
 */

class Survey_Maker_Survey_Category
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $plugin_name The ID of this plugin.
     */
    protected $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string $version The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string $plugin_name The name of the plugin.
     * @param      string $version The version of this plugin.
     */
    public function __construct($plugin_name, $version){

        $this->plugin_name = $plugin_name;
        $this->version = $version;

        add_shortcode('ays_survey_cat', array($this, 'ays_generate_survey_categories_method'));
    }

    // Categories shortcode
    public function ays_generate_survey_categories_method($attr){
        global $wpdb;

        $ids = (isset($attr['ids'])) ? sanitize_text_field($attr['ids']) : null;
        $cat_ids = array_map('intval', explode(',', $ids));

        if (is_null($ids)) {
            $content = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
            return str_replace(array("\r\n", "\n", "\r"), '', $content);
        }

        $display = ( isset($attr['display']) && $attr['display'] != '' ) ? sanitize_text_field($attr['display']) : 'all';
        $count   = ( isset($attr['count']) && $attr['count'] != '' ) ? absint(sanitize_text_field($attr['count'])) : 5;
        $layout  = ( isset($attr['layout']) && $attr['layout'] != '' ) ? sanitize_text_field($attr['layout']) : 'list';

        $final_content = '';
        foreach ($cat_ids as $id) {
            $category = Survey_Maker_Data::get_survey_category_by_id($id);

            if (isset($category->published) && $category->published == 0) {
                $content = "<p class='wrong_shortcode_text' style='color:red;'>" . __('Wrong shortcode initialized', $this->plugin_name) . "</p>";
                return str_replace(array("\r\n", "\n", "\r"), '', $content);
            }


            $sql = "SELECT id FROM {$wpdb->prefix}socialsurv_surveys WHERE category_ids LIKE '%". $id ."%' ";

            $content = "";
            $random_survey_id = array();
            if ($display === 'random') {
                $sql .= "ORDER BY RAND() LIMIT ".$count;
                $result = $wpdb->get_results($sql, 'ARRAY_A');
                $all_survey_count = count($result);
                foreach ($result as $val) {
                    $val = absint(intval($val['id']));
                    $random_survey_id[] = $val;
                }
            }else{
                $result = $wpdb->get_results($sql, 'ARRAY_A');
                $all_survey_count = count($result);
                foreach ($result as $val) {
                    $val = absint(intval($val['id']));
                    $random_survey_id[] = $val;
                }
            }
            
            $conteiner_flex_class = '';
            if ($layout == 'grid') {
                $conteiner_flex_class = 'ays-survey-category-container-flex';
            }

            $category_title = (isset($category->title) && $category->title != '') ? stripslashes($category->title) : "";

            $content .= "<h2 class='ays-survey-category-title' style='text-align:center;'>
                            <span style='font-size:3rem;'>". __( "Category", $this->plugin_name) .":</span>
                            <em>". $category_title ."</em>
                        </h2>";

            if(isset($category->description) && $category->description != ''){
                $content .= "<div class='ays-survey-category-description'>". do_shortcode(stripslashes(wpautop($category->description))) ."</div>";
            }

            $content .= "<div class='ays-survey-category-container ". $conteiner_flex_class ."'>";
            foreach ($random_survey_id as $survey_id) {
                $content .= "<div class='ays-survey-category-item'>";
                $shortcode = "[ays_survey id='".$survey_id."']";
                $content .= do_shortcode($shortcode);
                $content .= "</div>";
            }
            $content .= "</div>";
            
            $final_content .= str_replace(array("\r\n", "\n", "\r"), "\n", $content);
        }

        return $final_content;

    }

}
