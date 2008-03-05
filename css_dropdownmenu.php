<?php
   /*
   Plugin Name: WP CSS Dropdown Menu
   Plugin URI: http://zackdesign.biz
   Description: Creates a navigation menu of pages with dropdown menus for child pages. Uses ONLY cross-browser friendly CSS, no Javascript.
   Version: 0.1
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


   function wp_css_dropdownmenu()
   {

	   $before = '<ul>';
	   $after = '</ul>';

      global $wpdb; // Global wordpress variables
		
      $postSQL =  "SELECT 
			$wpdb->posts.ID, 
			$wpdb->posts.post_title,
			$wpdb->posts.post_parent";
		
		$postSQL	.=	" FROM $wpdb->posts WHERE $wpdb->posts.post_status = 'publish' AND $wpdb->posts.post_type = 'page'";

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

	add_action('wp_head', 'css_dropdownmenu_css', 1);

	add_action('admin_head', 'css_dropdownmenu_css', 1);


?>
