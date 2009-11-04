=== Wordpress CSS Drop-down Menu ===
Contributors: zackdesign
http://www.zackdesign.biz/wp-plugins/42
Tags: css, dropdown, menu, wordpress, plugin, page, drop, down, browser, friendly, child, theme, exclude
Requires at least: 2.3
Tested up to: 2.8.4
Stable tag: 2.3.7

Creates a DYNAMIC MULTI-level navigation menu of pages with dropdown menus for child pages. Uses ONLY cross-browser friendly CSS.

== Description ==

Features at a glance:

* Multi-level dropdown (that's right! you're only limited by your imagination and your CSS skills)
* Exclude pages
* Exclude page URLs (for parent pages without content)
* Set a root node for your menu
* Simply tick a box to get a 'Home' button added in automatically!
* Dynamic menu item widths! Simply say how wide you want the menu to be and all the menu items will be automaticall resized whenever you add or delete them!
* Verified to work with IE8
* Fixed multi-lang filter support
* Remove URLs from top-level parent pages with a click
* Alphabetical or menu order sorting
* Set a different menu for logged-in or authenticated users

If you want me to modify the CSS for you simply [contact me](http://www.zackdesign.biz "Zack Design") and I will do it easily and quickly for you for a moderate sum.

It uses [Stu Nicholl's final drop-down code](http://www.cssplay.co.uk/menus/final_drop.html "Stu Nicholl") which is a complete CSS solution - no Javascript required!! With the plugin I've included his CSS but not imported it directly as it's best that you modify it to suit your theme, and you also need to read Stu's copyright notice on the page I'e linked to (a donation to his fund would also be good). Other Stu Nicholl menu CSS should technically work with this plugin also! Just try it and see!

== Installation ==

1. Upload the 'wordpress-css-drop-down-menu' folder to the `/wp-content/plugins/` directory
2. Copy the CSS from `theme_css` to your theme CSS file
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place `<?php wp_css_dropdownmenu(); ?>` in your header.php file (or anywhere you want the drop-down menu to render)
5. You may now begin modifying the CSS to suit.
6. Optionally you can visit the Wordpress Options -> CSS Drop Down Admin menu to exclude certain pages from showing.
7. Now comes with the ability to set before and after HTML to wrap around the menu like so: `<?php wp_css_dropdownmenu('<div class="menu"><ul>', '</ul></div>'); ?>`
   Or you can have no wrapping whatsoever: `<?php wp_css_dropdownmenu('',''); ?>`

Just to be clear, <?php wp_css_dropdownmenu(); ?> will give you default wrapping which is what I gave in the first example. If you want a custom wrapping just use the first example I gave, but be sure to include the menu class.

== Frequently Asked Questions ==

= I Need HELP!!! =

That's what I'm here for. I do Wordpress sites for many people in a professional capacity and
can do the same for you. Check out www.zackdesign.biz

== Changelog ==

2.3.7

- Authenticated menus
- Ability to set further fine-grained controls from the code

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