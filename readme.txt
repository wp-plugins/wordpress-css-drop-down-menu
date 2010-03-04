=== Wordpress CSS Drop-down Menu ===
Contributors: zackdesign
http://www.zackdesign.biz/category/wp-plugins/css-dropdown-menu
Tags: css, dropdown, menu, wordpress, plugin, page, drop, down, browser, friendly, child, theme, exclude, superfish, flyout, widget
Donate link: http://zackdesign.biz
Requires at least: 2.8
Tested up to: 2.9.2
Stable tag: 3.0.9

Turn your WP pages, links, and post categories into a menu system; includes some starting CSS and JS

== Description ==

_Note_: This plugin requires PHP 5

_Fatal Error_?

This is due to pre version 3.x code in your theme. You needed to update it to continue using version 3.x of this plugin. Your options:

* Click on Installation in the menu above to make your theme compatible
* Downgrade to a version that works: [2.3.7](http://downloads.wordpress.org/plugin/wordpress-css-drop-down-menu.2.3.7.zip "Version 2.3.7")
* Contact [Zack Design](http://zackdesign.biz "Zack Design") for help


Features at a glance:

* Submenu support (shows the parent page, and the current page when viewing that current page)
* Now widgetised to make it really easy to add multiple dropdowns anywhere on the page you have sidebars
* Ability to create the menu wherever you want with PHP Classes
* Pages, post categories, and links are all selectable and highly configurable to display however you want on whatever widget or manually-set sidebar you wish
* Leverages the new Wordpress 2.8 widget settings to allow quick and easy multiple widget instances

Theming options:

* Multi-level dropdown (CSS included)
* Multi-level left/right flyouts (CSS included)
* Support for Superfish Javascript (CSS included)
* Works with other CSS (within reason)

If you want me to modify the CSS for you simply [contact me](http://www.zackdesign.biz "Zack Design") and I will do it easily and quickly for you for a moderate sum.

It uses [Stu Nicholl's final drop-down code](http://www.cssplay.co.uk/menus/final_drop.html "Stu Nicholl") which is a complete CSS solution - no Javascript required!!

You can look on [Stu's site for other drop-down code](http://www.cssplay.co.uk/menus/ "Stu Nicholl's Menus") as I got the flyout left and right code from there. Don't forget to donate if you're using his styles.

Most of his CSS should work just fine with the menu. I've made it so that the plugin automatically finds menu.css in the plugin's folder and loads it in your Wordpress site so you can instantly see how it will look. If you want to create your own CSS simply create menu.css in your theme's root directory and the plugin will load that automatically for you.

Please note that if you're upgrading you will need to change your theme files to suit the new approach. See Installation for further information.

== Installation ==

* Upload the 'wordpress-css-drop-down-menu' folder to the `/wp-content/plugins/` directory or install it from Wordpress.org's Plugin directory inside your Wordpress installation.
* Activate the plugin through the 'Plugins' menu in WordPress
* Add a Dropdown widget to one of your sidebars. If you want to shown the widget in the header of your theme, [add a sidebar to your header file and update your functions.php file to add a new sidebar there](http://codex.wordpress.org/Customizing_Your_Sidebar "Customizing your Sidebar"). 

PHP for your header.php:  

`<?php if ( function_exists ( dynamic_sidebar('menu') ) ) : dynamic_sidebar ('menu'); endif; ?>`


PHP for your functions file: 

`<?php
if ( function_exists ('register_sidebar')) { 
    register_sidebar( array(
		'name' => __('Menu', 'menu'),
		'id' => 'menu',
		'description' => __('Shows a dropdown menu in the header.', 'menu'),
		'before_widget' => '',
		'after_widget' => '',
		'before_title' => '',
		'after_title' => ''
	) );
}
?>`

Note: The reason I recommend you add a sidebar to your header is simply because it's easier to edit than going in and editing the PHP every time you want to change something. Remember, a 'sidebar' is a misnomer, really it should just be called a 'widget area'.

Alternatively just manually reference the class as shown below:

`<?php
if (class_exists('CSSDropDownMenu'))
 {
     $myMenu = new CSSDropDownMenu(); 
     /* Extra options here, like so: $myMenu->exclude_purl="1"; */ 
     $myMenu->show(); 
 }
 ?>`

These are the options you have when manually placing your menu code in the header:

`$myMenu->before_menu - Defaults to <div class="menu"><ul>, can be anything you like, and you can add wrapping HTML for manually defined links here. The plugin will actually change the CSS classes in this variable only if it is in its default state so if you're wondering why the CSS isn't working check this.
$myMenu->after_menu - Defaults to </ul></div>, can be anything you like, and you can add wrapping HTML for manually defined links here. 
$myMenu->orientation - Values are 'top', 'right', 'left' - default is 'top'
$myMenu->home - Home page text, default is 'Home'
$myMenu->exclude_pid - Page IDs to exclude, comma seperated
$myMenu->exclude_purl - Page URLs removed by ID, comma seperated
$myMenu->show_pages - Show pages, default is '1', expected values 0,1
$myMenu->parent_urls - Remove all parent URLs, default is '0', expected values 0,1
$myMenu->subpages - Show subpages only (also shows parent page), default is '0', expected values 0,1
$myMenu->auth_id - Starting root ID of authenticated user, default is empty
$myMenu->non_auth_id - Starting root ID of non-authenticated user or just starting ID of menu, default is empty
$myMenu->exclude_lid - Remove link categories based on ID, comma seperated
$myMenu->show_links - Show links, default is '0', expected values 0,1
$myMenu->exclude_cid - Exclude post categories by ID, comma seperated
$myMenu->show_cats - Show post categories, default is '0', comma seperated
$myMenu->start_cid - Starting root ID of post categories, default is empty
$myMenu->cat_order - Values are 'name', 'description' - default is none
$myMenu->selective_pages - Shows only children of currently selected menu item, default is '0', expected values 0,1`

* The plugin defines its own menu.css in your theme's header. If you have your own menu.css file in your theme folder the plugin will check for that and load that for you automatically. It may be easier to simply copy across menu.css from the plugin folder and use that as the basis for your own. Or, browse the internet for unordered CSS list menu styles. [Stu's site is a good start](http://www.cssplay.co.uk/menus/ "Stu Nicholl's Menus").

* You can also activate the JS addon plugin to use Superfish javascript. This plugin uses its own Superfish CSS which you can find in the plugin js/superfish directory. Place superfish.css into your own theme folder to avoid having your CSS overwritten on a plugin update!

* For page ordering I suggest you use the excellent plugin called [PageMash](http://wordpress.org/extend/plugins/pagemash/ "PageMash").

* If you get 'broken image links' in IE it's because the background dropdown images in your menu.css file are set to Stu's original ones. You will need to change these.

== Upgrade Notice ==

Please make sure to update your theme's header.php file if coming from versions older than 3.x, removing `wp_css_dropdownmenu()` and replacing it with:

`
if (class_exists('CSSDropDownMenu'))
 {
     $myMenu = new CSSDropDownMenu(); 
     /* Extra options here, like so: $myMenu->exclude_purl="1"; */ 
     $myMenu->show(); 
 }
 `

== Screenshots ==

[Zack Design Plugin Showcase](http://wordpress.zackdesign.biz "Plugin Showcase")

== Frequently Asked Questions ==

= I Need HELP!!! =

That's what I'm here for. I do Wordpress sites for many people in a professional capacity and
can do the same for you. Check out www.zackdesign.biz

= I'm getting strange class errors =

You need PHP 5 to run this

== Changelog ==

3.0.9

- Added category ordering by description/name

3.0.8

- Reintroduction of deprecated function in an attempt to get people back on track.

3.07.1

- Minor fix - valid XHTML 1.0 Transitional (target="" removed)
- Changed CSS slightly (current_page_item > current_page and deleted drop.gif)

3.0.7

- Added URL target for WP links
- Added drop class for parent menu links
- Fixed dodgy css classes for WP links
- Added current_page for post categories

3.0.6

- Fixed 'division by zero' warning which occurs when there are no links for the dynamic menu to use

3.0.5

- Found that IE6 didn't play nice when excluding parent URLs, added href="#" parent A tags to fix issue

3.0.4

- Added widget title
- Also created ability to do a menu drilldown style in the sidebar, visible here: http://www.brakes.no/v2/produkter/

3.0.3

- Gives users ability to override superfish.css with their own superfish.css in their current theme folder (this is for the JS sub plugin)
- Also added PHP enclosing tags to README

3.0.2

- Allows users to create static non-flyout left/right menus with drilldown functionality (only shows children of currently selected menu item)

3.0.1

- Found there was a problem with dynamic menu widths due to using the classes. Assumes that the last top oriented menu defined is the one to set dynamic CSS for. This will need to be addressed in later versions.
- Menu titles for QTranslate broken when using htmlspecialchars. Removed.

3.0

- Uses all the Wordpress heirarchical elements - links, post categories, pages
- Attempts to make the installation process as simple as possible for non-savvy users (Auto CSS inclusion, allows user to define their own, user-friendly improvements)
- Includes sub-plugin - the superfish menu dropdown script
- Additional links in the plugin menu so that the user can quickly find the plugin setup area
- Updated README file to reflect new changes and all links to Zack Design are now for this plugin category
- Rewritten for PHP Classes
- Widgetised!!!

2.3.7

- Authenticated menus
- Ability to set further fine-grained controls from the code
- Added some extra code to try and make IE6 dynamic widths work properly

2.3.6

- Now allows you to rename the additional 'Home' button
- Added an extra 'else' to try and clear any unnecessary parent CSS

2.3.5

- Alphabetical sorting

2.3.4

- Top-level parents optionally have no URL

2.3.3

- HTML special chars for the title (noticed some XML errors)
- Multi-lang plugins supported with apply_filter on post title...

2.3.2

- Further classes. New link ones, a new parent class, and the return of the current_page class.

2.3.1

- Added classes back into the <li> HTML elements. Sorry! Forgot to add them back in when changing over the code...

2.3

- Minor bugfixes
  - list of IDs for which to not make an URL fixed up so that you can put in more than one
  - found some bugs in menu children URL removal
  - IE8 testing - Now works in IE8!!!
- Completely re-written recursive menu generator. You can have as many levels as you can write CSS for! :D

2.2.2

- Can now add a home-page by ticking it in settings
- List of IDs for which to not make an URL

2.2.1

- Added a way to make wrapped pages auto-dynamically factored in
- Fixed some minor bugs

2.2

- Added a start parent
- Moved dynamic width to settings only. No real point in forcing it to be stuck in functions.php.

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