<?php

   /*

   Plugin Name: WP CSS Dropdown Menu

   Plugin URI: http://zackdesign.biz

   Description: Creates a navigation menu of pages with dropdown menus for child pages. Uses ONLY cross-browser friendly CSS, no Javascript.

   Version: 2.1.1

   Author: Isaac Rowntree

   Author URI: http://www.zackdesign.biz
   
Changelog:

2.1.1

- Added filter to titles so that it works with plugins which filter the titles like QTranslate, etc.

2.1 

- Makes it so that if the width of top elements are wider than a certain amount the bottom level grows with it

2.0

- Proper dynamic width using CSS
- Added an admin option to set which class we use when generating dynamic width css, defaults to .menu

1.2

- Noticed some things weren't working quite right (e.g. extra <ul></ul> tags were apearing when no children were present)

1.1

- Able to pass certain directives to tell the menu what things to output

1.0

- Added third level flyouts

0.3

- Fixed some bugs with the code. Sorry, I accidentally left some test code in!

0.2

- Added admin page

- Can now stop certain pages showing... at the moment just doesn't fetch the pages (some parentless child pages

  are fetched but now shown)



0.1

- First build

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

		  //	Show the results

		  $result  =  "";


		$parents = array();

		$children = array();
		
		$sub_children = array();



  		if ( $articles ) {

	  		//	Display the post titles with permalinks

			  foreach ( $articles as $display ) {

				  

				  if ($display->post_parent == 0)

				  {
					    array_push($parents, $display);
				  }
			}
			
			if ( $articles ) {
			   foreach ( $articles as $display ) {
			   
			   
			   $sub_child = true;
			       foreach ( $parents as $parent ) {
			
			        
              
              foreach ($parents as $p)
				      {
                  if ($display->post_parent == $p->ID)
                  {
                      $sub_child = false;
                  }    
              }
              

           }              
              if (!$sub_child)
                  array_push($children, $display);
              else
                  array_push($sub_children, $display);
         }
      }

      foreach ( $parents as $parent ) {

				

				$children_result='';

				$child_exists ='';
				
				  //check if children exists

				foreach ( $children as $child ) {



					if($parent->ID == $child->post_parent)

					{

				      $schildren_result='';

				      $schild_exists ='';
              // Do third level
              
              foreach ( $sub_children as $schild ) {
                    
                    if ($child->ID == $schild->post_parent)
                    {
                
                        $schildListTitle	=	apply_filters('the_title', stripslashes(str_replace('"', '', $schild->post_title)));
                        
                        if((strcmp  ( curPageURL()  , post_permalink($schild->ID) ) == 0) )
                        {
                            $schildren_result .= '
                            <li class="current_page_item">';
                        }
                        else
                        {
                            $schildren_result .= '<li>';
                        }
                        
                        $schildren_result .= '
                        <a href="' . post_permalink($schild->ID) . '" rel="bookmark" title="Permanent link &quot;' . $schildListTitle . '&quot;">' . $schildListTitle . '</a></li>';
                
                    }
              }
              
              $schildren_result2 ='';
              
              if ( ($schildren_result != '') && $schildren_result)
              {
              
              					$schildren_result2 = '<!--[if IE 7]><!--></a><!--<![endif]-->
<!--[if lte IE 6]><table><tr><td><![endif]-->

	<ul>'.$schildren_result.'</ul><!--[if lte IE 6]></td></tr></table></a><![endif]-->
	</li>';
              
              }
              else
                  $schildren_result2 = '0';
              
              
              

						$childListTitle	=	apply_filters('the_title', stripslashes(str_replace('"', '', $child->post_title)));

						

						if((strcmp  ( curPageURL()  , post_permalink($child->ID) ) == 0) )

					   {	

							$children_result .= '

              <li class="current_page_item">';

					   }

					   else

						{

						   $children_result .= '<li>';

						}
						
						if ($schildren_result2)
						    $children_result .= '

            <a href="' . post_permalink($child->ID) . '" rel="bookmark" title="Permanent link &quot;' . $childListTitle . '&quot;">' . $childListTitle . $schildren_result2;
						else						
						    $children_result .= '

            <a href="' . post_permalink($child->ID) . '" rel="bookmark" title="Permanent link &quot;' . $childListTitle . '&quot;">' . $childListTitle . '</a></li>';

					}

				}



				$children_result2 ='';



				if ( ($children_result != '') && $children_result)

				{

					$children_result2 = '

          <!--[if lte IE 6]><table><tr><td><![endif]-->

	<ul>'.$children_result.'</ul><!--[if lte IE 6]></td></tr></table></a><![endif]-->

</li>';

				}

				else

				    $children_result2 = '<!--[if lte IE 6]></a><![endif]--></li>';



				  //	Format the title

				  $listTitle	=	apply_filters('the_title', stripslashes(str_replace('"', '', $parent->post_title)));

				 //	Set up the return string

			

					//if post_parent is not 0 then add that to the array with that id

					if((strcmp  ( curPageURL()  , post_permalink($parent->ID) ) == 0) )

					{	

						$result .= '

            <li class="current_page_item">';

					}

					else

					{

						   $result .= '<li>';

					}					



				  $result  .=	'

          <a href="' . post_permalink($parent->ID) . '" rel="bookmark" title="Permanent link &quot;' . $listTitle . '&quot;">' . $listTitle .'<!--[if IE 7]><!--></a><!--<![endif]-->'.$children_result2 ;

			}

		 }else {

			//	There were no posts in the category

			$result	.=	"<p>No page posts</p>\n";

		}

		
    if ($no_html)
        echo $result;
    else
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
    if ($dynamic)
    {
        $pages = sizeof(get_pages_from_DB(0));
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
	    if (get_option('wp_css_menu_dynamic'))
        $checked = 'checked="checked"';
      else
        $checked = '';

?>



	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

		

    <h3>Exclude Pages by ID</h3>

    






		<p><b>Pages: </b><input type="text" name="pages" value="<?php echo $pages; ?>"></p>

    <p>Seperate by commas if putting in multiple pages, e.g. 12,23,43.</p>
    
    <h3>Dynamic Menu Width</h3>


		<p><b>Dynamic? </b><input type="checkbox" name="dynamic" value="1" <?php echo $checked; ?>></p>
		<p><b>Dynamic class (to be used if you set something other than .menu): </b>
     <b>.</b><input type="text" name="cssclass" value="<?php echo $class; ?>"></p>

    <p>Place the following code somewhere in functions.php in your theme, 
    replacing 900 with the value of your header width:</p>
    
    <code>
// Menu Width for WP CSS Dropdown Menu<br /><br />

update_option('wp_css_menu_width', 900);<br />
<br />
/*------------------------------------*/
</code>


		<div class="submit">

			<input type="submit" name="submit_css_content" value="<?php _e('Save', 'CSSDropDownMenu') ?>" />

		</div></form>



<?php

	}

	

	function setupCSSDropDownMenuAdminPanel()

	{

			add_options_page('CSS Drop-down Menu', 'CSS Drop-down Menu', 9, basename(__FILE__), 'CSSDropDownMenu_options');

	}



	add_action('wp_head', 'css_dropdownmenu_css', 1);

	add_action('admin_menu', 'setupCSSDropDownMenuAdminPanel');


?>