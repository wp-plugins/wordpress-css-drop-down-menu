<?php

   /*

   Plugin Name: WP CSS Dropdown Menu
   Plugin URI: http://www.zackdesign.biz/category/wp-plugins/css-dropdown-menu
   Description: The ultimate wordpress dropdown menu builder. <a href="http://www.zackdesign.biz">Donate</a> | <a href="http://www.cssplay.co.uk/menus/">Other Menu Styles</a>
   Version: 3.0
   Author: Isaac Rowntree
   Author URI: http://www.zackdesign.biz

   */

if (!class_exists("CSSDropDownMenu")) {
    class CSSDropDownMenu {
        
        var $before_menu = '<div class="menu"><ul>';
        var $after_menu = '</ul></div>';
        var $orientation = 'top';
        var $home = 'Home';
        var $exclude_pid;
        var $exclude_purl;
        var $show_pages = 1;
        var $parent_urls = 0;
        var $subpages = 0;
        var $auth_id;
        var $non_auth_id;
        var $show_links = 0;
        var $exclude_lid;
        var $show_cats = 0;
        var $exclude_cid;
        var $start_cid;
        
        function CSSDropDownMenu() { //constructor
            
        }
        
        function show() 
        {
            if (is_home() || is_page('home'))
                $class="current_page";
     
            if (!empty($this->home))
                $result = '<li class="menu_item '.$class.'"><a href="'.get_bloginfo('url').'" rel="bookmark" title="'.get_bloginfo('name').'">'.$this->home.'</a></li>';
            else
                $result = '';
             
            if ($this->show_pages)
            {
                if ($this->subpages)   
                {
                    global $post; 
                    $result .= $this->build($this->return_pages(), $this->get_parent($post->post_parent));
                }
                else 
                {
                    if ( is_user_logged_in() && $this->auth_id )
                        $id = $this->auth_id;
                    else if (  $this->non_auth_id )
                        $id = $this->non_auth_id;
                    else
                        $id = 0;
                    
                    $result .= $this->build($this->return_pages(), $id);
                }
            }
            if ($this->show_links)
                $result .= $this->build($this->return_links());
            if ($this->show_cats)
            {
                if ( $this->start_cid)
                    $id = $this->start_cid;
                else
                    $id = 0;
                        
                $result .= $this->build($this->return_cats(), $id);
            }
     
            if (empty($result))
                $result = '<!--No page posts to display.-->';
     
            //	Show the results - checks to make sure the user hasn't changed the defaults
            if (($this->orientation != 'top') && ($this->before_menu == '<div class="menu"><ul>'))
                $this->before_menu = '<div class="menu_'.$this->orientation.'"><ul>';
            else if ($this->before_menu == '<div class="menu"><ul>')
                $this->before_menu = '<div class="menu '.get_option('wp_css_menu_class').'"><ul class="'.get_option('wp_css_menu_jclass').'">';
            echo $this->before_menu.$result.$this->after_menu;
        }
        
        function build($pages, $cur_level = 0, $result = '', $one_level = false)
        {  
            foreach ($pages as $page)
            {      
                if ($page->post_parent == $cur_level)
                {
                    $listTitle	= stripslashes(str_replace('"', '', $page->post_title));
            
                    // Go through list of 'no urls' and check this one
                    $no_urls = explode(',',$this->exclude_purl);
                    
                    foreach ($no_urls as $u)
                    {
                        if ($page->ID == $u)
                        {
                            $url = false;
                            break;
                        } 
                        else if ($page->type == 'page')
                            $url = post_permalink($page->ID);
                       
                    }
            
                    if ($page->type == 'link')
                        $url = $page->url;
                    else if (($page->type == 'category') && (get_option('permalink') != ''))
                        $url = get_bloginfo('url').'/'.$page->slug;
                    else if ($page->type == 'category') 
                        $url = get_bloginfo('url').'/?cat='.$page->ID;

            
                    // Get children
                    if (!$one_level)
                        $children = $this->build($pages,$page->ID);
            
                    // If menu parents can't be clicked also check to see if there are children present
                    if ($this->parent_urls && !empty($children))
                    {
                        $url = false;
                    }
            
                    global $post;
            
                    if ($post->post_parent == $page->ID)
                      $parent = 'current_parent';
                   else if (!empty($children))
                      $parent = 'parent';
                   else
                        $parent = '';
            
                    // Need to find the current page the user is visiting and add the class accordingly
                    global $post;
                    if ($post->ID == $page->ID)
                    {
                        $class="class='menu_item menu_item_$page->ID current_page $parent'";
                        $aclass="class='menu_item_link menu_item_link_$page->ID current_page_link $parent'";
                    }
                    else
                    {
                        $class="class='menu_item menu_item_$page->ID $parent'";
                        $aclass="class='menu_item_link menu_item_link_$page->ID $parent'";
                    }
            
                    $title = apply_filters( 'the_title', $listTitle);
            
                    if (!$url)
                        $result .= '
                        <li '.$class.'><a '.$aclass.' rel="anchor" title="' . $title . '">' . $title;
                    else
                        $result .= '
                        <li '.$class.'><a href="' . $url . '" '.$aclass.' rel="bookmark" title="' . $title . '">' . $title;
            
                    if (!empty($children))
                        $result .= '<!--[if IE 7]><!--></a><!--<![endif]--><!--[if lte IE 6]><table><tr><td><![endif]-->
                        <ul>'.$children.'</ul><!--[if lte IE 6]></td></tr></table></a><![endif]--></li>';
                    else
                        $result .= '</a></li>';
                }
            }
    
            return $result;
        }
        
        function return_pages ( )
        {
            global $wpdb; // Global wordpress variables
            
            global $post;
            if ($this->subpages && ($post->post_type == 'page'))
                $parent = " AND $wpdb->posts.post_parent = '$post->ID' OR $wpdb->posts.ID = '$post->ID' OR $wpdb->posts.ID = '$post->post_parent'";
            else if ($this->subpages)
                $parent = "";
            
             $pages = $this->exclude_pid;

             $remove = '';
             if ($pages)
             {
                 $pages = explode(',',$pages);
                 foreach ($pages as $page)
                     $remove .= ' AND ID != ' . $page;
             }
     
              $postSQL =  "SELECT 
                                $wpdb->posts.ID, 
                                $wpdb->posts.post_title,
                                $wpdb->posts.post_parent,
                                $wpdb->posts.post_type as type
                                FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' $parent AND $wpdb->posts.post_type = 'page'  $remove ORDER BY $wpdb->posts.menu_order";

             //	Get the results
             return $wpdb->get_results($postSQL);
        }

        function return_links()
        {
            global $wpdb;
            
            $links = $this->exclude_lid;

            $remove = '';
            if ($links)
            {
                $links = explode(',',$links);
                foreach ($links as $link)
                    $remove .= " AND $wpdb->terms.term_id !=  $link";
            }
    
            $sql = "SELECT $wpdb->terms.term_id as ID, $wpdb->terms.name as post_title, $wpdb->term_taxonomy.parent as post_parent, 
                                 $wpdb->term_taxonomy.taxonomy as type 
                      FROM $wpdb->term_taxonomy, $wpdb->terms 
                      WHERE $wpdb->term_taxonomy.taxonomy = 'link_category' AND $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id $remove";
    
            $cats = $wpdb->get_results($sql);
   
           $links = array();
            foreach ($cats as $c)
            {
                $sql = "SELECT $wpdb->links.link_name as ID, $wpdb->links.link_name as post_title, $wpdb->links.link_url as url, '$c->ID' as post_parent, 'link' as type
                          FROM $wpdb->links, $wpdb->term_relationships
                          WHERE $wpdb->term_relationships.term_taxonomy_id = $c->ID AND $wpdb->term_relationships.object_id = $wpdb->links.link_id";
                  
                $links = array_merge($links, $wpdb->get_results($sql));
            }  
    
            return array_merge($cats, $links);
        }

        function return_cats()
        {
            global $wpdb;
            
            $cats = $this->exclude_cid;
            $remove = '';
            if ($cats)
            {
                $cats = explode(',',$cats);
                foreach ($cats as $cat)
                    $remove .= " AND $wpdb->terms.term_id !=  $cat";
            }
    
            $sql = "SELECT $wpdb->terms.term_id as ID, $wpdb->terms.name as post_title, $wpdb->terms.slug as slug, $wpdb->term_taxonomy.parent as post_parent, 
                      $wpdb->term_taxonomy.taxonomy as type 
                      FROM $wpdb->term_taxonomy, $wpdb->terms 
                      WHERE $wpdb->term_taxonomy.taxonomy = 'category' AND $wpdb->terms.term_id = $wpdb->term_taxonomy.term_id $remove";
    
            return $wpdb->get_results($sql);
        }
        
        function get_parent($id)
        {
            if ($id > 0)
            {             
                global $wpdb; 
                $sql = "SELECT $wpdb->posts.post_parent FRom $wpdb->posts WHERE $wpdb->posts.ID = '$id'";
                return $wpdb->get_var($wpdb->prepare($sql));
            }
            else
                return 0;
        }
   
    }

} //End Class CSSDropDownMenu

// Widget Class 
class CSS_DDMenu extends WP_Widget 
{	    
    function CSS_DDMenu()
    {
        parent::WP_Widget(false, $name = 'Dropdown Menu');
    }
    
    function widget($args, $instance) {
       extract($args);
       
       echo $before_widget;
      
      if (class_exists("CSSDropDownMenu")) {
          $cssMenu = new CSSDropDownMenu();
          $cssMenu->orientation = strtolower(esc_attr($instance['orientation']));
          $cssMenu->home = esc_attr($instance['home_button']);
          $cssMenu->exclude_pid = esc_attr($instance['exclude_pid']);
          $cssMenu->exclude_purl = esc_attr($instance['exclude_purl']);
          $cssMenu->show_pages = esc_attr($instance['show_pages']);
          $cssMenu->parent_urls = esc_attr($instance['parent_urls']);
          $cssMenu->subpages = esc_attr($instance['subpages']);
          $cssMenu->auth_id = esc_attr($instance['auth_id']);
          $cssMenu->non_auth_id = esc_attr($instance['non_auth_id']);
          $cssMenu->exclude_lid = esc_attr($instance['exclude_lid']);
          $cssMenu->show_links = esc_attr($instance['show_links']);
          $cssMenu->exclude_cid = esc_attr($instance['exclude_cid']);
          $cssMenu->show_cats = esc_attr($instance['show_cats']);
          $cssMenu->start_cid = esc_attr($instance['start_cid']); 
          $cssMenu->show();
      }

       echo $after_widget;
    }
    
    function update($new_instance, $old_instance)
    {
        return $new_instance;
    }
    
    function form($instance)
    {
        $orientation = esc_attr($instance['orientation']);
        
        $right = '';
        $left = '';
        $top = '';
        
        if ($orientation == 'Right')
            $right = 'selected="selected"';
        else if ($orientation == 'Left')
            $left = 'selected="selected"';
        else if ($orientation == 'Top')
            $top = 'selected="selected"';
        
        $home = esc_attr($instance['home_button']);
        $ex_pid = esc_attr($instance['exclude_pid']);
        $purls = esc_attr($instance['exclude_purl']);
        $show_pages = esc_attr($instance['show_pages']);
            if ($show_pages): $show_pages = 'checked="checked"'; endif;
        $parent_urls = esc_attr($instance['parent_urls']);
            if ($parent_urls): $parent_urls = 'checked="checked"'; endif;
        $subpages = esc_attr($instance['subpages']);
            if ($subpages): $subpages = 'checked="checked"'; endif;
        $auth_id = esc_attr($instance['auth_id']);
        $non_auth_id = esc_attr($instance['non_auth_id']);
        $ex_lid = esc_attr($instance['exclude_lid']);
        $show_links = esc_attr($instance['show_links']);
            if ($show_links): $show_links = 'checked="checked"'; endif;
        $ex_cid = esc_attr($instance['exclude_cid']);
        $show_cats = esc_attr($instance['show_cats']);
            if ($show_cats): $show_cats = 'checked="checked"'; endif;
        $start_cid = esc_attr($instance['start_cid']);
        
        ?>
            <p><label for="<?php echo $this->get_field_id('orientation'); ?>"> <?php _e('Orientation:'); ?> <select class="widefat" id="<?php echo $this->get_field_id('orientation'); ?>" name="<?php echo $this->get_field_name('orientation'); ?>"><option <?php echo $right; ?>>Right</option><option  <?php echo $left; ?>>Left</option><option  <?php echo $top; ?>>Top</option></select></label></p>
            
            <p><label for="<?php echo $this->get_field_id('home_button'); ?>"> <?php _e('Home Button (no text no button):'); ?> <input class="widefat" id="<?php echo $this->get_field_id('home_button'); ?>" name="<?php echo $this->get_field_name('home_button'); ?>" value="<?php echo $home ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('show_pages'); ?>"> <?php _e('Show pages?'); ?> <input id="<?php echo $this->get_field_id('show_pages'); ?>" name="<?php echo $this->get_field_name('show_pages'); ?>" <?php echo $show_pages ?> type="checkbox" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('exclude_pid'); ?>"> <?php _e('Exclude page IDs (comma seperated):'); ?> <input type="text" class="widefat" id="<?php echo $this->get_field_id('exclude_pid'); ?>" name="<?php echo $this->get_field_name('exclude_pid'); ?>" value="<?php echo $ex_pid ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('exclude_purl'); ?>"> <?php _e('Exclude page URLs by ID (comma seperated):'); ?> <input type="text" class="widefat" id="<?php echo $this->get_field_id('exclude_purl'); ?>" name="<?php echo $this->get_field_name('exclude_purl'); ?>" value="<?php echo $purls ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('parent_urls'); ?>"> <?php _e('Remove all parent URLs:'); ?> <input id="<?php echo $this->get_field_id('parent_urls'); ?>" name="<?php echo $this->get_field_name('parent_urls'); ?>" <?php echo $parent_urls ?> type="checkbox" /></label></p>
    
            <p><label for="<?php echo $this->get_field_id('subpages'); ?>"> <?php _e('Show only subpages of the currently viewed page:'); ?> <input id="<?php echo $this->get_field_id('subpages'); ?>" name="<?php echo $this->get_field_name('subpages'); ?>" <?php echo $subpages ?> type="checkbox" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('non_auth_id'); ?>"> <?php _e('Non-authorised user starting root page ID (or just simply a different starting point for the menu):'); ?> <input type="text" size="3" id="<?php echo $this->get_field_id('non_auth_id'); ?>" name="<?php echo $this->get_field_name('non_auth_id'); ?>" value="<?php echo $non_auth_id ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('auth_id'); ?>"> <?php _e('Authorised user starting root page ID (different menu structure for logged-in users):'); ?> <input type="text" size="3" id="<?php echo $this->get_field_id('auth_id'); ?>" name="<?php echo $this->get_field_name('auth_id'); ?>" value="<?php echo $auth_id ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('show_links'); ?>"> <?php _e('Show links?'); ?> <input id="<?php echo $this->get_field_id('show_links'); ?>" name="<?php echo $this->get_field_name('show_links'); ?>" <?php echo $show_links ?> type="checkbox" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('exclude_lid'); ?>"> <?php _e('Exclude link category IDs (comma seperated):'); ?> <input type="text" class="widefat" id="<?php echo $this->get_field_id('exclude_lid'); ?>" name="<?php echo $this->get_field_name('exclude_lid'); ?>" value="<?php echo $ex_lid ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('show_cats'); ?>"> <?php _e('Show post categories?'); ?> <input id="<?php echo $this->get_field_id('show_cats'); ?>" name="<?php echo $this->get_field_name('show_cats'); ?>" <?php echo $show_cats ?> type="checkbox" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('exclude_cid'); ?>"> <?php _e('Exclude category IDs (comma seperated):'); ?> <input type="text" class="widefat" id="<?php echo $this->get_field_id('exclude_cid'); ?>" name="<?php echo $this->get_field_name('exclude_cid'); ?>" value="<?php echo $ex_cid ?>" /></label></p>
            
            <p><label for="<?php echo $this->get_field_id('start_cid'); ?>"> <?php _e('Starting root post category ID:'); ?> <input type="text" size="3" id="<?php echo $this->get_field_id('start_cid'); ?>" name="<?php echo $this->get_field_name('start_cid'); ?>" value="<?php echo $start_cid ?>" /></label></p>
        <?php
    }	    
}

add_action('widgets_init', create_function('','return register_widget("CSS_DDMenu");'));


function css_dropdownmenu_css() {

    $dynamic = get_option('wp_css_menu_dynamic');
    $start_parent = get_option('wp_css_start_page');
    if (empty($start_parent))
        $start_parent = 0;
    $extra_pages = get_option('wp_css_extra');
    if (empty($extra_pages))
        $extra_pages = 0;
        
    if (get_option('wp_css_menu_home'))
        $extra_pages++;
        
    if ($dynamic)
    {
        $pages = sizeof(get_pages_from_DB($start_parent)) + $extra_pages;
        $width = get_option('wp_css_menu_width');
        $class = get_option('wp_css_menu_class');
        if (!$class || ($class == ''))
            $class = 'menu';
        
        // Li is the full width divided by the number of pages - the a width is li less a seemingly arbitrary number??
        $li = ($width - 1) / $pages ;
        $a  = $li - 10;
        
        $lili = 128;
        // Now the second level widths...
        $lili = 128;
        if ($li > $lili)
            $lili = $li - 21;
        $aa = $lili + 22;
            
        
        echo '
        <!-- wp_css_menu_dropdown dynamic menu widths -->
        <style type=\'text/css\' media=\'screen\'>
            .'.$class.' {width: '.$width.'px}
            * html .'.$class.' {width:'.$width.'px; w\idth:'.$width.'px;}
            .'.$class.' li {width:'.$li.'px; }
            .'.$class.' a, .'.$class.' a:visited {width:'.$a.'px; }
            * html .'.$class.' a, * html .'.$class.' a:visited {width:'.$a.'px; w\idth:'.($a-2).'px;}
            .'.$class.' ul ul a, .'.$class.' ul ul a:visited {width:'.$lili.'px;}
            * html .'.$class.' ul ul a, * html .'.$class.' ul ul a:visited {width:'.$aa.'px;w\idth:'.$lili.'px;}       
            .'.$class.' ul ul ul {width:'.$li.'px; left:'.$li.'px}
            .'.$class.' ul ul {width:'.$li.'px;}                     
        </style>
        <!-- /dynamic menu widths -->
        
        ';
    }
}


function CSSDropDownMenu_options () {

     echo '<div class="wrap"><h2>Wordpress CSS Drop-Down Menu</h2>';

     if ($_REQUEST['submit_css_content']) {
          update_CSSDropDownMenu_options();
     }

     displayCSSDropDownMenuAdminPage();

     echo '</div>';

}



  function update_CSSDropDownMenu_options()

  {

     $updated = false;
     
     if (!$_REQUEST['dynamic'])
         $_REQUEST['dynamic'] = 0;
         
     update_option('wp_css_menu_dynamic', $_REQUEST['dynamic']);
     update_option('wp_css_menu_class', $_REQUEST['cssclass']);
     update_option('wp_css_menu_width', $_REQUEST['menu_width']);
     update_option('wp_css_extra', $_REQUEST['extra_pages']);
     $updated = true;


     if ($updated) {

           echo '<div id="message" class="updated fade">';

           echo '<p>Options Updated</p>';

           echo '</div>';

      } else {

           echo '<div id="message" class="error fade">';

           echo '<p>Unable to update options</p>';

           echo '</div>';

      }

  }

        

        function displayCSSDropDownMenuAdminPage()

        {

            $class = get_option('wp_css_menu_class');
            $width = get_option('wp_css_menu_width');
            $extra = get_option('wp_css_extra');
            
            if (get_option('wp_css_menu_dynamic'))
        $checked = 'checked="checked"';
      else
        $checked = '';

?>



        <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

    <h3>Dynamic Menu Width</h3>


                <p><b>Dynamic? </b><input type="checkbox" name="dynamic" value="1" <?php echo $checked; ?>></p>
                <p><b>Dynamic class (to be used if you set something other than .menu): </b>
     <b>.</b><input type="text" name="cssclass" value="<?php echo $class; ?>"></p>
    <p><b>Width: </b><input type="text" name="menu_width" value="<?php echo $width; ?>"></p>
    <p><b>Extra Pages: </b><input type="text" name="extra_pages" value="<?php echo $extra; ?>"></p>
    <p>Extra pages are the pages you have added in when creating your own 'wrapping' HTML. Expected is the number of extra pages. This will be used to 
    decide how wide to make each list element.</p>
  


                <div class="submit">

                        <input type="submit" name="submit_css_content" value="<?php _e('Save', 'CSSDropDownMenu') ?>" />

                </div></form>



<?php

        }



        function setupCSSDropDownMenuAdminPanel()

        {

            add_options_page('CSS Drop-down Menu', 'CSS Drop-down Menu', 9, basename(__FILE__), 'CSSDropDownMenu_options');

        }
        
        function include_CSSMenu_file()
        {	    	    
            // Show menu.css depending on what's available
            if (file_exists(TEMPLATEPATH.'/menu.css'))
                wp_enqueue_style('wp-css-dropdown-menu-style', get_bloginfo('template_directory').'/menu.css');
            else
                wp_enqueue_style('wp-css-dropdown-menu-style', '/wp-content/plugins/wordpress-css-drop-down-menu/theme_css/menu.css');
        }



        add_action('wp_head', 'css_dropdownmenu_css', 1);

        add_action('admin_menu', 'setupCSSDropDownMenuAdminPanel');
        
        add_action('init', 'include_CSSMenu_file');


?>