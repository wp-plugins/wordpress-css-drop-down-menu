<?php

   /*

   Plugin Name: WP CSS Dropdown Menu

   Plugin URI: http://zackdesign.biz

   Description: Creates a navigation menu of pages with dropdown menus for child pages. Uses ONLY cross-browser friendly CSS, no Javascript.

   Version: 0.3

   Author: Isaac Rowntree

   Author URI: http://www.zackdesign.biz

   

Changelog:

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





   function wp_css_dropdownmenu()

   {

     $pages = get_option('excluded_css_dropdown_pages');

     

     $remove = '';

     

     if ($pages)

     {

         $pages = explode(',',$pages);

         

         foreach ($pages as $page)

             $remove .= ' AND ID != ' . $page;

     }




     $before = '<ul>';

	   $after = '</ul>';



      global $wpdb; // Global wordpress variables

		

      $postSQL =  "SELECT 

			$wpdb->posts.ID, 

			$wpdb->posts.post_title,

			$wpdb->posts.post_parent";

		

		$postSQL	.=	" FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'page'" . $remove;



		$postSQL	.=	" ORDER BY $wpdb->posts.menu_order";		



		  //	Get the results

		  $articles	=	$wpdb->get_results($postSQL);

		

		  //	Show the results

		  $result  =  "";



		





		$parents = array();

		$children = array();



  		if ( $articles ) {

	  		//	Display the post titles with permalinks

			  foreach ( $articles as $display ) {

				  

				  if ($display->post_parent == 0)

				  {



					array_push($parents, $display);



				  }

				  else

				  {

					array_push($children, $display);

				  }



			}

      

      foreach ( $parents as $parent ) {

				

				$children_result='';

				$child_exists ='';



				  //check if children exists

				foreach ( $children as $child ) {



					if($parent->ID == $child->post_parent)

					{



						$childListTitle	=	stripslashes(str_replace('"', '', $child->post_title));

						

						if((strcmp  ( curPageURL()  , post_permalink($child->ID) ) == 0) )

					   {	



							$children_result .= '

              <li class="current_page_item">';

					   }

					   else

						{

						   $children_result .= '<li>';

						}





						$children_result .= '

            <a href="' . post_permalink($child->ID) . '" rel="bookmark" title="Permanent link &quot;' . $childListTitle . '&quot;">' . $childListTitle . '</a></li>';

					}

				}



				$children_result2 ='';



				if ( $children_result )

				{

					$children_result2 = '

          <!--[if lte IE 6]><table><tr><td><![endif]-->

	<ul>'.$children_result.'</ul><!--[if lte IE 6]></td></tr></table></a><![endif]-->

</li>';

				}

				else

				    $children_result2 = '<!--[if lte IE 6]><table><tr><td><![endif]--><ul></ul><!--[if lte IE 6]></td></tr></table></a><![endif]--></li>';



				  //	Format the title

				  $listTitle	=	stripslashes(str_replace('"', '', $parent->post_title));

				

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

		

		echo '<div class="menu"><ul>'.$result.'</ul></div>';	



   }





	function css_dropdownmenu_css() {



		print "<link media='screen' type='text/css' href='".get_bloginfo('url')."/wp-content/plugins/wp_css_dropdownmenu/styles.css' rel='stylesheet'></link>";



			

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

     if ($_REQUEST['pages'] || !$_REQUEST['pages'] || ($_REQUEST['pages'] == '') ) {

          update_option('excluded_css_dropdown_pages', $_REQUEST['pages']);

          $updated = true;

     }



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

?>



	<form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">

		

    <h3>Exclude Pages by ID</h3>

    

		<p><b>Pages: </b><input type="text" name="pages" value="<?php echo $pages; ?>"></p>

    <p>Seperate by commas if putting in multiple pages, e.g. 12,23,43.</p>

    



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



	add_action('admin_head', 'css_dropdownmenu_css', 1);

	

	add_action('admin_menu', 'setupCSSDropDownMenuAdminPanel');



?>
