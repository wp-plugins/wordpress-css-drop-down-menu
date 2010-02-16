<?php

   /*

   Plugin Name: WP CSS Dropdown Menu JS add-on
   Plugin URI: http://www.zackdesign.biz/category/wp-plugins/css-dropdown-menu
   Description: A complementary javascript plugin to the CSS dropdown menu. This uses a different stylesheet found in <plugin folder>/js/superfish/css/superfish.css
   Version: 3.0.7.1
   Author: Isaac Rowntree
   Author URI: http://www.zackdesign.biz

   */
   
   add_action('init', 'includeSuperfish');
   
   function includeSuperfish()
   {
        wp_deregister_style('wp-css-dropdown-menu-style');
	wp_enqueue_script('superfish','/wp-content/plugins/wordpress-css-drop-down-menu/js/superfish/js/superfish.js', array('jquery'));
	wp_enqueue_script('superfish-hover','/wp-content/plugins/wordpress-css-drop-down-menu/js/superfish/js/hoverIntent.js', array('jquery'));
	
	// Show superfish.css depending on what's available
        if (file_exists(TEMPLATEPATH.'/superfish.css'))
            wp_enqueue_style('superfish', get_bloginfo('template_directory').'/superfish.css');
        else
            wp_enqueue_style('superfish','/wp-content/plugins/wordpress-css-drop-down-menu/js/superfish/css/superfish.css');
	
	update_option('wp_css_menu_jclass', 'sf-menu');
	
	wp_enqueue_script('start-superfish','/wp-content/plugins/wordpress-css-drop-down-menu/js/start-superfish.js');
   }
   
   ?>