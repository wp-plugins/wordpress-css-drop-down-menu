<?php

   /*

   Plugin Name: WP CSS Dropdown Menu
   Plugin URI: http://zackdesign.biz
   Description: Creates a navigation menu of pages with dropdown menus for child pages. Uses ONLY cross-browser friendly CSS, no Javascript.
   Version: 2.3.3
   Author: Isaac Rowntree
   Author URI: http://www.zackdesign.biz

   */





	function curPageURL() {
	   $pageURL = 'http';
	   if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
	   $pageURL .= "://";
	   if ($_SERVER["SERVER_PORT"] != "80") {
	    $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
	   } else {
	   $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
	   }
	  return $pageURL;
	}

   function wp_css_dropdownmenu($no_html = false, $before_plugin = '<div class="menu"><ul>', $after_plugin = '</ul></div>')
   {
     $before = '<ul>';
	   $after = '</ul>';		
		 $articles = get_pages_from_DB();
		 $no_urls = get_option('wp_css_menu_urls');
		 $urls = explode(',',$no_urls);
     
    $start_parent = get_option('wp_css_start_page');
    if (empty($start_parent))
        $start_parent = 0;
        
    if (is_home() || is_page('home'))
        $class="current_page";
     
     if (get_option('wp_css_menu_home'))
         $result = '<li class="menu_item '.$class.'"><a href="'.get_bloginfo('url').'" rel="bookmark" title="'.get_bloginfo('name').'">Home</a></li>';
     else
         $result = '';
     
     $result = build_CSSDropDown_menu($articles, $start_parent, $urls, $result);
     
     if (empty($result))
         $result = '<li>No page posts to display.</li>';
     
		  //	Show the results
		 echo $before_plugin.$result.$after_plugin;	      
  }


function get_pages_from_DB ( $parent = -1 )
{
    global $wpdb; // Global wordpress variables
    
    if ($parent > -1)
        $parent = " AND $wpdb->posts.post_parent = '$parent'";
    else
        $parent = '';

     $pages = get_option('excluded_css_dropdown_pages');

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
			$wpdb->posts.post_parent";
			
		$postSQL	.=	" FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' $parent AND $wpdb->posts.post_type = 'page'" . $remove;

		$postSQL	.=	" ORDER BY $wpdb->posts.menu_order";

		  //	Get the results
		  return $wpdb->get_results($postSQL);
}

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
            * html .'.$class.' a, * html .'.$class.' a:visited {width:'.$a.'px; w\idth:'.$a.'px;}
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
     update_option('wp_css_start_page', $_REQUEST['start_page']);
     update_option('wp_css_menu_width', $_REQUEST['menu_width']);
     update_option('wp_css_extra', $_REQUEST['extra_pages']);
     update_option('wp_css_menu_urls', $_REQUEST['urls']);
     update_option('wp_css_menu_home', $_REQUEST['home']);

          update_option('excluded_css_dropdown_pages', $_REQUEST['pages']);
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

	    $pages = get_option('excluded_css_dropdown_pages');
	    $class = get_option('wp_css_menu_class');
	    $width = get_option('wp_css_menu_width');
	    $page = get_option('wp_css_start_page');
	    $extra = get_option('wp_css_extra');
	    $urls = get_option('wp_css_menu_urls');
	    if (get_option('wp_css_menu_dynamic'))
        $checked = 'checked="checked"';
      else
        $checked = '';
      if (get_option('wp_css_menu_home'))
        $hchecked = 'checked="checked"';
      else
        $hchecked = '';

?>



	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

		<h3>Home Page Button? <input type="checkbox" name="home" value="1" <?php echo $hchecked; ?>></h3>

    <h3>Exclude Pages by ID</h3>

		<p><b>Pages: </b><input type="text" name="pages" value="<?php echo $pages; ?>"></p>
		
		<p>Seperate by commas if putting in multiple pages, e.g. 12,23,43.</p>
		
		<h3>Remove URLs by ID</h3>

		<p><b>Pages: </b><input type="text" name="urls" value="<?php echo $urls; ?>"></p>

    <p>Seperate by commas if putting in multiple pages, e.g. 12,23,43.</p>
    
    <h3>Starting Root Page ID</h3>
    
    <p><b>Page: </b><input type="text" name="start_page" value="<?php echo $page; ?>"></p>
    
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

function build_CSSDropDown_menu($pages, $cur_level, $no_urls, $result = '')
{    
    foreach ($pages as $page)
    {      
        if ($page->post_parent == $cur_level)
        {
            $listTitle	=	stripslashes(str_replace('"', '', $page->post_title));
            if (is_array($no_urls))
            {
                foreach ($no_urls as $u)
                {
                    if ($page->ID == $u)
                    {
                        $url = '#';
                        break;
                    }
                    else
                        $url = post_permalink($page->ID);
                }
            }
            // Get children
            $children = build_CSSDropDown_menu($pages,$page->ID,$no_urls);
            
            if (strstr($children, 'current_page'))
                $parent = 'current_parent';
            else if (!empty($children))
                $parent = 'parent';
            
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
            
	    $title = apply_filters( 'the_title', htmlspecialchars($listTitle) );
	    
            $result .= '<li '.$class.'><a href="' . $url . '" '.$aclass.' rel="bookmark" title="' . $title . '">' . $title;
            
            if (!empty($children))
                $result .= '<!--[if IE 7]><!--></a><!--<![endif]--><!--[if lte IE 6]><table><tr><td><![endif]--><ul>'.$children.'</ul><!--[if lte IE 6]></td></tr></table></a><![endif]--></li>';
            else
                $result .= '</a></li>';
        }
    }
    
    return $result;
}
	

	function setupCSSDropDownMenuAdminPanel()

	{

			add_options_page('CSS Drop-down Menu', 'CSS Drop-down Menu', 9, basename(__FILE__), 'CSSDropDownMenu_options');

	}



	add_action('wp_head', 'css_dropdownmenu_css', 1);

	add_action('admin_menu', 'setupCSSDropDownMenuAdminPanel');


?>