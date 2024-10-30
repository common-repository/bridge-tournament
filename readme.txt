=== Bridge Tournament ===
Contributors: Frederic Hantrais
Tags: bridge tournament, bridge, cards, game, carte, club
Requires at least: 3.0.1
Tested up to: 3.8
Stable tag: trunk
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

Bridge Tournament or games organizer. Allow players to register, find a partner and watch the results of tournaments.

== Description ==
This plugin helps Bridge club to organize and manage their tournaments. It allows: 
- Players to register or to find a partner
- Organizers to prepare tournaments (assign pairs to starting positions)
- Enter and publish the results of tournaments

This plugin *does not* work out the scores from each deal results. This part will be done by another plugin of the "Bridge Club Suite" published soon.

== Installation ==
1. Upload \"bridge-tournament\" to the \"/wp-content/plugins/\" directory.
2. Activate the plugin through the \"Plugins\" menu in WordPress
3. Use shortcodes or place the page "bridge tournament" in your menu 
4. Change the Permalink option to something different that the defaults (in settings->Permalinks)

== Frequently Asked Questions ==
= What can I expect from this plugin? =
If you manage the tournaments of your bridge club, this plugin may help you. It will allow the members of your club to register before the tournament, and to check the results online. It will also help you to organize the tournaments - assigning and printing players starting positions before the beginning of the game.

= What can I *not* expect from this plugin? =
The plugin doesn\'t make the score computation. It will be done by another plugin that is not released yet.

= Can I get an idea of what the plugin does?=
Yes, check the test implementation at the following address : http://pmbeforepm.org/bridge. Contact me if you want an administration test account.

= Can I expect support if I use this plugin? =
Yes. Submit your questions and suggestions, and I'll be happy to improve this plugin. Please consider that it is the first release, and that you may encounter some bugs or unexpected behaviors.

= Is this plugin available in different languages ?=
The plugin works in English and French. Other languages may be available soon. If you want to contribute to it's translation in other languages, feel free to contact me.

= I just installed the plugin and nothing happened = 
You probably still have to add the page "bridge-tournament" to one of your menus. This page is the dashboard of the plugin, and depending on your theme, it may not appear automatically in your menu. Another possible problem is that you haven't changed the permalink configuration (in settings->permalinks). Choose any permalink option other than the default one.

= What is the availability list? =
If a player is looking for a partner, he/she may try to contact a player that is not already registered, or put his/her name on the availability list. It means that this person is looking for a partner for this specific tournament.  

= Will the players need an account in Wordpress to register to the tournament? =
It's up to you ! The default behavior is that a player has to be added to a plugin-user list without WP account. This prevent an unknown user from registering, or someone from registering twice. By default, anyone can also watch the result (including search engines). But If you prefer to keep your result "private", you can restrict the access to the site to logged-in users (this is a plugin option). In this case, users will need to remember another password. 

= What kind of personal information may be published if I use the plugin? =
Let's assume you have *not* chosen to create Wordpress account for your players (default behavior). In this situation, your results will be public. The members list is also public and search engine browsable (but the email address of players is protected). If a player want to contact another one, she/he can do it through a form, but doesn\'t know the address of the player he\'s contacting. If you want an absolute confidentiality of the information, the best solution is to create "reader" account for your members.

= Is there an anti-spam system embedded =
Not for now. Some anti-spam plugins are quite effecient to avoid these inconvenience, but if a specific need appears, this could be fixed in a next release.

= Can I change the organization of the plugin? =
Every function of this plugin has been created using shortcode (the list is available on the reference website). So, if you prefer having you menus organized in a different manner, it's doable. 

= What kind of tournament can I organize = 
Basically, you can organized Mitchell-style tournaments (two sets of scores for north-south and East-West) or Howell-style (only one list).

= Can I improve the layout of the plugin's pages? =
Sure ! I concede that the actual layout is not great and I intend to improve it. The results especially depends on the theme you're using. 
If you want to improve the layout, please refer to the documentation (if you're familiar with css) or contact me.

== Screenshots ==
1. Plugin welcome page 
2. Tournament creation page
3. Result display

== Changelog ==
= 1.0 =
* Initial release.