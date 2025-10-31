=== Bora Bora ===
Contributors: boraboraio
Author: Bora Bora
Donate link: https://bora-bora.io/
Tags: community, membership, subscription, paywall, user access, monetization, discord
Stable tag: 1.3.5
Requires PHP: 8.2
Tested up to: 6.8
Requires at least: 6.0
License: GPLv2
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Bora Bora helps you manage and monetize your online community. Protect content, manage memberships and connect your WordPress site to your Bora-Bora.io account — all in one simple plugin.

== Description ==

**Bora Bora** is the easiest way to connect your WordPress website with your community on [Bora-Bora.io](https://bora-bora.io) — a complete platform for community growth, monetization and member management.

With this plugin you can:
- Protect pages and posts based on user roles and subscriptions
- Restrict access to members with active plans
- Automatically redirect users when their subscription expires
- Manage all member data securely through the Bora Bora backend
- Seamlessly integrate your Bora Bora account with your WordPress site

This plugin acts as a **bridge between WordPress and Bora-Bora.io**.
It does not handle payments or user registration directly, but connects to your Bora Bora community where subscriptions, roles and payments are managed.
Once connected, you can control access to your WordPress content based on each member’s role or subscription level.

If users are not authenticated or their subscription has expired, you can redirect them automatically to a custom page (e.g. login, sales, or signup).

### Why use Bora Bora?

- 🧩 **Easy setup:** Connect your Bora Bora API key in just a few minutes.
- 🔒 **Secure content protection:** Restrict access by role or membership level.
- 🔁 **Automatic session sync:** User access is updated automatically after upgrades or downgrades.
- ⚙️ **Full flexibility:** Works with any theme or custom post type.
- 💬 **Community-ready:** Ideal for Discord-based or membership communities.

== Installation ==

Before installing, make sure the ***[Carbon Fields](https://carbonfields.net/zip/latest)*** plugin is active.

1. Upload `bora_bora.zip` to the `/wp-content/plugins/` directory, or install it directly from the WordPress Plugins screen.
2. Activate the plugin through the "Plugins" menu in WordPress.
3. Go to **Settings → Bora Bora**, and enter your API key from Bora-Bora.io.
4. Configure the redirect URL for users who are not authenticated or whose subscription has ended.
5. Edit any page or post you want to protect, and select the desired user role (e.g. guest, member, VIP) from the dropdown.

== Frequently Asked Questions ==

= Do I need a Bora-Bora.io account? =
Yes. The plugin connects your WordPress site to your Bora Bora community backend. You can create a free account at [bora-bora.io](https://bora-bora.io).

= Can I use it without Carbon Fields? =
No. The plugin requires Carbon Fields for managing custom fields and role settings.

= Does it handle payments directly? =
No, payments and subscriptions are managed securely through Bora-Bora.io. The plugin only controls access to WordPress content based on user roles and subscription status.

= Can I protect custom post types? =
Yes. Any post type that supports custom fields can be protected by role.

== Changelog ==

= 1.3.5 =
* Updated readme file for WordPress.org

= 1.3.2 =
* Added logo and screenshots for the plugin

= 1.3.1 =
* Added tested up to version info

= 1.3.0 =
* Bugfix: user creation and error handling improvements

= 1.2.3 =
* Editor access to dashboard restored

= 1.2.2 =
* Allow editor users to access admin bar and dashboard

= 1.2.0 =
* Improved admin bar handling for non-admin users
* Added API endpoint to automatically reset user sessions after plan changes

= 1.1.4 =
* Bugfixes and release for WordPress Plugin Directory

= 1.1.1 =
* Fixed permanent login issue
* General bugfixes

= 1.0.9 =
* Added shortcode for displaying the billing portal URL
* Option to disable permanent login

= 1.0.3 =
* Bugfixes

= 1.0.0 =
* Initial release

== Upgrade Notice ==

= 1.0.0 =
No action required.
