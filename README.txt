=== Bora Bora ===
Contributors: boraboraio
Author: Bora Bora
Donate link: https://bora-bora.io/
Tags: community, membership, subscription, paywall, user access
Stable tag: 1.3.2
Requires PHP: 8.2
Tested up to: 6.8
License: GPLv2
Requires at least: 6.0
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Bora Bora offers a complete solution for managing your community, from the subscription to the management of the users and their access to the content

== Description ==

Bora Bora offers a complete solution for managing your community, from the subscription to the management of the users
and their access to the content.

The Bora Bora plugin is a free companion plugin for the services of the third party [Bora-Bora.io](https://bora-bora.io)
. It allows you to protect your pages by user role defined by the subscribed community. Therefore, you can use the
backend offered by Bora Bora.
 It also allows you to redirect users to a specific page if they are not authenticated or if their subscription has
 ended.

 Terms of use and privacy policy of Bora Bora can be found [here](https://bora-bora.io/privacy-policy).

== Installation ==

To use this plugin, follow these steps, after you installed the "***[carbon-fields](https://carbonfields.net/zip/latest)***" plugin

1. Upload `bora_bora.zip` to the `/wp-content/plugins/` directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. On the settings page, add the API key provided by Bora Bora, add the application password for the "Bora_Bora" user
(created by the plugin) and save the settings.
4. configure the redirect URL for not authenticated users or users with ended subscription
5. Edit all the pages you want to protect by switching the dropdown to the wished user role (guest or any other role)

== Changelog ==

= 1.3.2 =

* Add Logo and Screenshots for the plugin

= 1.3.1 =

* Add tested up to version info to the plugin config

= 1.3.0 =

* bugfix: creation of Bora Bora user fix and error handling

= 1.2.3 =

* bugfix: editor is now allowed to access the dashboard

= 1.2.2 =

* Allow editor users to access the admin bar and dashboard

= 1.2.0 =

* Fix bug to hide admin bar for non admin users

= 1.2.0 =

* Add new API endpoint to reset the user session automatically after an up-/downgrade of the subscription from the Bora
Bora Server

= 1.1.4 =

* bugfixes
* Release for WordPress App Store

= 1.1.1 =

* fixed permanent login
* bugfixes

= 1.0.9 =

* Added a new shortcode to display the billing portal URL to the user
* Possibility to disable the permanent login feature

= 1.0.3 =
* Bugfixes

= 1.0.0 =
* Init version of the plugin

== Upgrade Notice ==

= 1.0.0 =
No action required
