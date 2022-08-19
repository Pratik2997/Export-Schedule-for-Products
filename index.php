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
        add_action('wp_enqueue_scripts', array($this,'additional_styles'));
    }
    function additional_styles(){
        wp_register_style('custom-css', plugin_dir_url(__FILE__) . "/style.css");
        wp_register_script( 'bootstrap-bundle',"https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js");

        wp_enqueue_style('custom-css');
        wp_enqueue_script( 'bootstrap-bundle' );
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
}

$exportSchedulePlugin = new ExportSchedulePlugin();
