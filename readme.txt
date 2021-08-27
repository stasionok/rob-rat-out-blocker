=== ROB (rat out blocker) ===
Contributors: stasionok
Donate link: https://web-marshal.ru/rob-rat-out-blocker/
Tags: block, request, external request, security, safety, speed up
Requires at least: 5.0
Tested up to: 5.7
Stable tag: 1.0
Requires PHP: 7.0
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

Restrict execution of certain external requests with partial url or even regex to prevent personal data leakage (as example)

== Description ==

With this simple plugin you can restrict extra external requests made with internal wordpress WP_Http class.
Just put in filter rules field what you wish to restrict. One url part or url regex per line.
By default plugin return WP_Error answer for restricted requests. But you can put in response your custom response data.

== Installation ==

This section describes how to install the plugin and get it working.

1. Upload the plugin files to the `/wp-content/plugins/rob-rat-out-blocker` directory, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Go to plugin settings page and put your restrictions

== Frequently Asked Questions ==

= How to find or detect outgoing requests =

You can use for that *Log HTTP Requests* plugin

= Which format for custom answer accepted =

You should put (if you need it) JSON encoded string. Then you say to plugin how to represent response format and it convert your string in required format.

= Which answer format accepted =

Plugin can convert your answer to array, object or leave string as is

== Screenshots ==

1. Main plugin page with rules field and placeholder examples

== Changelog ==

= 1.0 =
* Basic functionality released.
