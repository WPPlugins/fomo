=== Fomo for WooCommerce ===

Contributors: fomo
Donate link: https://www.usefomo.com
Tags: social proof, notifications, orders, woocommerce
Requires at least: 4.4
Tested up to: 4.7.2
Stable tag: 4.7
License: proprietary

Fomo displays recent orders on your WooCommerce storefront.

== Description ==

Fomo displays recent orders on your WooCommerce storefront.

It's the online equivalent of a busy store, showing prospective customers that other people are buying your products.
This has been proven to dramatically increase purchase conversions and create a sense of urgency for your website
visitors.

You know how seeing a packed restaurant makes you want to eat there? Fomo creates the same dynamic on your WooCommerce
store.

There's a reason Fomo is one of the most popular ecommerce apps for increasing sales and conversions. Here's why:

* Highly customizable: you can choose to display every order, some orders, or only orders from a certain time period.
This means that Fomo will work for you, even if you don't have many orders!
* Store owners can position Fomo in any corner (bottom left, bottom right, top left, top right) - whatever works best
for your store!
* FAST. Fomo is lightweight and built to scale, so it won't slow down your site (which can hurt your Google rankings)
* Fully customizable CSS animations and colors
* Works on mobile! Choose how your notifications are displayed on mobile, or turn them off for mobile devices
* Randomize notification delays for a more life-like experience.
* Hide Fomo from specific pages
* Remove individual notifications
* See users interact with your notifications in real time via our live notification list
* Store still young but growing? No worries - you can loop notifications and even disable the "time ago" notification!
* Customize the message, style, position and timing to suit your branding

== Installation ==

Fomo installation is simple, and requires no programming or special skills. Note: the Fomo plugin will only work if the
WooCommerce plugin is already installed on your shop.

Fomo requires a monthly subscription to be active on site. You will need to input a credit card to turn on the 24/7
order syncing. Fomo offers all users a 7 day free trial, and after that costs $19.00 USD per month. You can cancel your
subscription at any time via Fomo settings page -> My Account -> Unsubscribe from monthly charge or by simply
deactivating the plugin.

Installation procedure:

1. Upload the plugin files to the `/wp-content/plugins/woocommerce-plugin-fomo` directory,  or install the plugin
through the WordPress plugins screen directly.
2. Activate the plugin through the 'Plugins' screen in WordPress.
3. Use the Settings->Fomo screen, click button 'Authorize your store with Fomo'.
4. When authorization is completed successfully, you will be redirected back to Settings->Fomo, to administrate Fomo
Settings click Go to Fomo Administration, this will open new browser tab with Fomo Settings for you store.
5. Activate your monthly subscription by inserting credit card information.
6. That's it, recent orders and new orders will import automatically. Visit your storefront and watch the magic happen!.

Notes:

* Fomo plugin calls Fomo backend servers for purpose of exchanging authorization keys for your site access to
Fomo backend services.

* Fomo plugin will make webhook remote calls to Fomo backend server on each new order happening in
site WooCommerce store. Data sent via webhook to Fomo's backend server is limited to the few anonymous pieces of information
required for plugin functionality: order ID, customer first name, customer shipping city, province and country,
timestamp of created order, list of ordered products with following data product ID, product name, product image URL
and product URL.

* Fomo exposes public / private key encryption protected REST endpoint for Fomo backend service access to limited access
to your WooCommerce orders history (same data exposed as on webhook event). This endpoint is used by Fomo backend server
on first access of settings page and on user's request for changed orders threshold settings.

* Fomo will load remote JavaScript code from Fomo backend URL. Script is required to show notifications of recent
purchases on your frontend store.

== Frequently Asked Questions ==

1. How much does Fomo service cost?

    Fomo service offers 7 days free trial, after that 19.00 USD per month.

2. How can I unsubscribe from monthly charge?

    Go to Fomo settings page, My Account menu and click button Unsubscribe from monthly charge or uninstall the plugin.

3. How can I show older orders from my store orders history?

    By default upon installation, we import orders for last 30 days. If you want less or more you can change settings on
    Fomo settings page, just click the Design & Settings tab and change the "Orders threshold" setting. After changing,
    our system will automatically import older / newer orders.

== Screenshots ==

1. Message & Basic settings for Fomo notifications shown on storefront.
2. Design & Settings page for Fomo advanced settings of notification shown on storefront.
3. Fomo Live shows how many notification are currently shown to your customers.
4. Example how Fomo notification looks on storefront.

== Changelog ==

= 1.0.13 =
* Fixed file includes

= 1.0.12 =
* Added support for ignoring products from notifications, PHP7 code compliance

= 1.0.11 =
* Fixed problem with not upgraded servers having problem connecting to our HTTPS endpoints

= 1.0.10 =
* Fixed installation problems affecting some users

= 1.0.9 =
* Added support for Yotpo reviews

= 1.0.8 =
* Fixed error handling on Wordpress error

= 1.0.7 =
* Updated pricing

= 1.0.6 =
* Fixed problem with localized order timestamps

= 1.0.5 =
* Updated styles

= 1.0.4 =
* Added translation strings

= 1.0.3 =
* Updated banners

= 1.0.1 =
* Updated readme.txt to show better description and screenshots

= 1.0 =
* Initial Fomo plugin release

== Upgrade Notice ==

= 1.0 =
* First Build of Fomo for WooCommerce

Fomo will send all users unlimited free upgrades and support previous versions, unless otherwise noted.
