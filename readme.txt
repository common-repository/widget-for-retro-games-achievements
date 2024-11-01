=== Widget for Retro Games Achievements ===
Contributors: hiscorebob
Stable tag: 1.1
Tags: Retro Achievements, Widget
Requires at least: 4.6
Tested up to: 4.7
License: GPLv2 or later

This plugin enables a widget to display achievements from retroachievements.org 

== Description ==

This plugin enables a widget to display achievements from retroachievements.org (RA). 

To get started :

1. Create an Account on http://www.retroachievements.org
1. Obtain an API-Key  
1. Configure the Widget with your RA Account and your API Key, and specify the number of latest entries to display. 

Detailled Instructions are available here : http://www.hiscorebob.lu/workshops/wordpress-plugin-retroachievements-widget/

You can customize parts of the appearance through your WP Theme's Custom CSS.

== Installation ==

Upload the plugin files to the "/wp-content/plugins/plugin-name" directory, or install the plugin through the WordPress plugins screen directly.

1. Activate the plugin through the "Plugins" screen in WordPress

1. Use the "Appearance" -> "Widgets" screen to place the widget in the desired area.

1. Configure the Widget in the "Appearance" -> "Widgets" Screen to match with your Retroachievements Username (case sensitive!) and API-Key (both can be obtained from www.retroachievements.org. The number of recent entries is limited to 50, so if you put 60, then only 50 will be displayed.

Optionally, you can customize parts of the appearance of the widget with extra CSS classes ( you must add them to you Theme's Custom CSS)

Detailled Instructions are available here : http://www.hiscorebob.lu/workshops/wordpress-plugin-retroachievements-widget/

== Frequently Asked Questions ==
= Nothing is displayed at all? =
This can be either a wrong username or an invalid/missing API-Key

= After activating and placing the Widget, only the Retroachievements Avatar Icon is displayed ? =
Your API-Key ist most likely empty or invalid

= The Game List ist empty? =
Make sure to use only integer numbers , and put at least 1 into the "Number of recent entries to display:" (default = 5, max = 50)

= Everything is displayed except the Avatar Icon =
The username is case sensitive ! Make sure that you have entered your username respecting upper/lower case

= I want to display 100 recent Entries, but only 50 are displayed =
That's because the retroachievement API only returns 50 games.
 
== Screenshots ==
1. Sample Configuration of the Widget
2. Widget Display on Front-End

== Changelog ==
1.1
Cleanup in CSS Code
You need to adjust your theme's custom CSS Entries, in case you use this.
Please see http://www.hiscorebob.lu/workshops/wordpress-plugin-retroachievements-widget/ for further details

1.0
Initial Version

== Upgrade Notice ==
Nothing here yet
