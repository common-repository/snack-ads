=== Snack Ads ===
Contributors: bouk
Donate link: 
Tags: advertising
Requires at least: 5.3
Tested up to: 6.6
Requires PHP: 7.0
Stable tag: 2.1.1
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html


Handles automatic update of ad units for publishers who advertise with Snack Media.
 
== Description ==
 
This plugin is a solution for the remote management of ad units for websites in the Snack Media advertising network.

Snack Media are a Google-certified partner, based in the UK, who specialise in optimising and monetising websites, working with a network of over 400 websites. We offer an advanced advertising set-up - header and exchange bidding across programmatic, video, native rich media etc.

We have specialised teams across ad-operations, tech, editorial and sales to help publishers grow their digital revenues and provide IAB approved solutions for GDPR & CCPA.

We also offer a range of additional products, tools and services to help publishers drive traffic, maximise engagement and optimise user experience.

To find out more, head to [Snack Media](https://www.snack-media.com/publishers/?utm_source=wp-plugin-repository) or contact [jamesm@snack-media.com](mailto:jamesm@snack-media.com)

== Installation ==
 
1. Upload the contents of this .zip file into '/wp-content/plugins/snack-ads' on your WordPress installation, or via the 'Plugins->Add New' option in the Wordpress dashboard.
1. Activate the plugin via the 'Plugins' option in the WordPress dashboard.
1. Open your wp-config.php and define SNACK_ADS_CONFIG_NAME constant - e.g. `define( 'SNACK_ADS_CONFIG_NAME', 'example.com' );`
1. Enable different ad units on your site by executing PHP code such as `<?php \Snack\Ads\Helpers\snackAdverts::getInstance()->printCode( 'desktop', 'leaderboard' ); ?>`
1. In case you are unsure about the setup, please contact support@snack-media.com.
 
== Frequently Asked Questions ==
 
== Screenshots ==

== Changelog ==

= 2.1.1 =
* Updating tested up to version

= 2.1.0 =
* Sorting PHP error thrown by register_rest_route

= 2.0.0 =
* Correctly handle ads for multisite instances

= 1.0.0 =
* First stable release of the plugin