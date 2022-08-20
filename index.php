<?php

/*
Plugin Name: Export Woocommerce Product Schedule
Version: 1.0
Author: Pratik
Author URI: https://www.udemy.com/user/bradschiff/
*/

class ExportSchedulePlugin
{
    function __construct()
    {

        add_action('init', array($this, 'export_schedule_post_type'));
        add_shortcode('es_table', array($this, 'export_schedule_table'));
        add_action('wp_enqueue_scripts', array($this, 'frontend_styles'));
        add_action('admin_enqueue_scripts', array($this, 'backend_styles'));
        add_action('add_meta_boxes', array($this, 'es_meta_box'));
        add_action('save_post', array($this, 'es_save_meta_box_data'));
    }
    function frontend_styles()
    {
        wp_register_style('frontend-css', plugin_dir_url(__FILE__) . "/css/frontend.css");

        wp_register_script('bootstrap-bundle', "https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js");

        wp_enqueue_style('frontend-css');
        wp_enqueue_script('bootstrap-bundle');
    }
    function backend_styles()
    {
        wp_register_style('backend-css', plugin_dir_url(__FILE__) . "/css/backend.css");

        wp_enqueue_style('backend-css');
    }
    function export_schedule_post_type()

    {
        register_post_type('export-schedule', array(
            'capability_type' => 'export-schedule',
            'map_meta_cap' => true,
            'show_in_rest' => true,
            'supports' => array("title"),
            'rewrite' => array('slug' => 'export-schedule'),
            'has_archive' => true,
            'public' => true,
            'labels' => array(
                'name' => 'Export-Schedules',
                'add_new_item' => 'Add New Schedule',
                'edit_item' => 'Edit Schedule',
                'all_items' => 'All Schedules',
                'singular_name' => 'Schedule'
            ),
            'menu_icon' => 'dashicons-calendar'
        ));
    }
    function export_schedule_table()
    {
        ob_start();
        require("template.php");
        load_template("template.php");
        return ob_get_clean();
    }
    function es_meta_box()
    {
        //this will add the metabox for the member post type
        $screens = array('export-schedule');

        foreach ($screens as $screen) {

            add_meta_box(
                'es_tableid',
                __('ES Table Details', 'es_textdomain'),
                array($this, 'es_table_meta_box_callback'),
                $screen
            );
        }
    }

    /**
     * Prints the box content.
     *
     * @param WP_Post $post The object for the current post/page.
     */
    function es_table_meta_box_callback($post)
    {

        // Add a nonce field so we can check for it later.
        wp_nonce_field('es_save_meta_box_data', 'es_meta_box_nonce');

        /*
     * Use get_post_meta() to retrieve an existing value
     * from the database and use the value for the form.
     */
        $value = get_post_meta($post->ID, '_my_meta_value_key', true);
?>

        <div class="date-fields">
            <label for="order_period_date_first">Order Period (First Date)</label>
            <input type="date" name="order_period_date_first" id="order_period_date_first" value="<?php echo esc_attr($value[0]) ?>">
        </div>

        <div class="date-fields">
            <label for="order_period_date_second">Order Period (Second Date)</label>
            <input type="date" name="order_period_date_second" id="order_period_date_second" value="<?php echo esc_attr($value[1]) ?>">
        </div>

        <div class="date-fields">
            <label for="export_date">Export Date</label>
            <input type="date" name="export_date" id="export_date" value="<?php echo esc_attr($value[2]) ?>">
        </div>

        <div class="date-fields">
            <label for="receive_date_first">Receive Date (First)</label>
            <input type="date" name="receive_date_first" id="receive_date_first" value="<?php echo esc_attr($value[3]) ?>">
        </div>

        <div class="date-fields">
            <label for="receive_date_second">Receive Date (Second)</label>
            <input type="date" name="receive_date_second" id="receive_date_second" value="<?php echo esc_attr($value[4]) ?>">
        </div>


<?php
    }

    /**
     * When the post is saved, saves our custom data.
     *
     * @param int $post_id The ID of the post being saved.
     */
    function es_save_meta_box_data($post_id)
    {


        if (!isset($_POST['es_meta_box_nonce'])) {
            return;
        }

        if (!wp_verify_nonce($_POST['es_meta_box_nonce'], 'es_save_meta_box_data')) {
            return;
        }

        if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
            return;
        }

        // Check the user's permissions.
        if (isset($_POST['post_type']) && 'page' == $_POST['post_type']) {

            if (!current_user_can('edit_page', $post_id)) {
                return;
            }
        } else {

            if (!current_user_can('edit_post', $post_id)) {
                return;
            }
        }

        $my_data = array($_POST['order_period_date_first'], $_POST['order_period_date_second'], $_POST['export_date'], $_POST['receive_date_first'], $_POST['receive_date_second']);

        if (!isset($my_data)) {
            return;
        }
        
        $my_data = array_map('sanitize_text_field', $my_data);

        update_post_meta($post_id, '_my_meta_value_key', $my_data);
    }
}

$exportSchedulePlugin = new ExportSchedulePlugin();
