=== Wordpress CSS Drop-down Menu ===
Contributors: zackdesign
http://www.zackdesign.biz/wp-plugins/42
Tags: css, dropdown, menu, wordpress, plugin, page, drop, down, browser, friendly, child, theme, exclude
Requires at least: 2.3
Tested up to: 2.5
Stable tag: 2.2.2

Creates a DYNAMIC three-level navigation menu of pages with dropdown menus for child pages. Uses ONLY cross-browser friendly CSS.

== Description ==

I have created a THREE-level drop-down menu for Wordpress. NOTE: This is for people who know CSS and are willing to modify it to suit their theme!!! If you want me to modify the CSS for you simply [contact me](http://www.zackdesign.biz "Zack Design") and I will do it easily and quickly for you for a moderate sum.

Now comes with an admin menu for excluding pages of your choice from displaying, and allows you to set a start page for the menu. Also includes an optional 'Home' button, and allows you to remove URLs by ID.

It uses [Stu Nicholl’s final drop-down code](http://www.cssplay.co.uk/menus/final_drop.html "Stu Nicholl") which is a complete CSS solution - no Javascript required!! With the plugin I’ve included his CSS but not imported it directly as it’s best that you modify it to suit your theme, and you also need to read Stu’s copyright notice on the page I’ve linked to (a donation to his fund would also be good).

Future releases may include theme packs... I have already created several different styles. The biggest barrier to introducing gradient or 'pretty' graphics to the CSS is that IE6 no longer drops down the menu, so you need to use several browser hacks to get around that. So, note of caution - if you're not very good at CSS, stick to single colours ;)

One other minor niggle I noticed - you need to set the menu close to the width you want it - in multiples of however big each menu item is wide. Otherwise if you get it wrong you may notice the rightmost border sitting at the far right. This is no problem if your menu goes right to the edge, or if the border is the same colour as the background. However, if you want the menu to be very dynamic in terms of changing what is in it, you may have to consider a way to either fix this or just live with it. It's not a huge problem, but one that may cause you some head-scratching!

I certainly think that this solution is worth it because when it works it is extremely browser-friendly AND fast.

== Installation ==

1. Upload the 'wordpress-css-drop-down-menu' folder to the `/wp-content/plugins/` directory
2. Copy the CSS from `theme_css` to your theme CSS file
3. Activate the plugin through the 'Plugins' menu in WordPress
4. Place `<?php wp_css_dropdownmenu(); ?>` in your header.php file (or anywhere you want the drop-down menu to render)
5. You may now begin modifying the CSS to suit.
6. Optionally you can visit the Wordpress Options -> CSS Drop Down Admin menu to exclude certain pages from showing.
7. Now comes with the ability to set before and after HTML to wrap around the menu like so: `<?php wp_css_dropdownmenu(0, '<div class="menu"><ul>', '</ul></div>'); ?>`
   Or you can have no wrapping whatsoever: `<?php wp_css_dropdownmenu(1); ?>`

Just to be clear, <?php wp_css_dropdownmenu(); ?> will give you default wrapping which is what I gave in the first example. If you want a custom wrapping just use the first example I gave, but be sure to include the menu class.

== Frequently Asked Questions ==

= I Need HELP!!! =

That's what I'm here for. I do Wordpress sites for many people in a professional capacity and
can do the same for you. Check out www.zackdesign.biz

