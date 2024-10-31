=== Prisjakt Feed ===
Contributors: prisjakt
Tags: prisjakt, feeds, feed, product-feed
Requires at least: 4.0
Tested up to: 6.3
Stable tag: 0.3.0.1
Requires PHP: 7.1
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

The purpose of the plugin is to help Users to create an XML feed file from their products inventory in WooCommerce e-commerces.

== Description ==

The purpose of the plugin is to help Users to create an XML feed file from their products inventory in WooCommerce e-commerces.

Plugin [Woocommerce](https://wordpress.org/plugins/woocommerce/) installation is required.
*****
## All feeds ##

The page contains a list of all feeds with extra rows actions.

**Important:**

***Feed generation works in the background, if you want to see the progress refresh the page in the feed list view.***

**1. Columns**

**Active**

If this option is checked, the system cron will take the given feed to be generated automatically.
If disabled, cron will have to be generated manually by the user.

**Product feed name**

The field includes a feed title link.

**Refresh interval**

The field includes a refresh interval selected in feed settings.

- 24h
- 12h
- 1h

**Status**

The field includes feed status.

**Products**

Amount of products in this feed.

**Progress**

The field includes the process of feed generation numbers between 0 to 100%.

**Generated At**

The field contains the end date of the feed generation.

**Scheduled At**

The field contains the next date of the feed generation.

**Date**

The field contains the date of feed settings created.
*****
**2. Row Actions**

**Edit**

Action is only available in the feed list with status published.
You will come to edit the feed.

**Trash**

Action is only available in the feed list with status published.
Move feed to trash and disable `Active` cron option.

**Download feed**

Action is only available in the feed list with status published.
Action download feed XML.

**Refresh feed**

Only available in the feed list with status published.
Action trigger manual feed reflash.

**Restore**

Only available in the feed list with status trash.
Action restore feed from Trash to Published feeds with disable cron status. You must enable this option again for cron actions.

**Delete Permanently**

Only available in the feed list with status trash.
Action delete permanently feed settings with existing feed XML file.
*****

## Adding New Feed ##

**1. Generating feed**

**Name**

The name of the feed helps to distinguish the feeds in the list. The XML file is also created on its basis.

**Refresh interval**

Possibility to define a custom schedule interval for feed data generation
Possibility to feed generating every 24, 12 or 1 hour.
*****
**2. Feed Mapping**

The page contains field mapping.
*****
**2.1 Columns**

**Prisjakt shopping attributes**

Lists the attributes Prisjakt.

**Prefix**

The field contains a value that can optionally be added before the value.

**Value**

The field contains the value that is taken from the product data.

**Suffix**

The field contains a value that can optionally be added after the value.

**Delete**

Deletes a selected row.
*****
**2.2 Actions**

**Add Field Mapping**

Adds a row with a selection of fields from select.

**Add Custom Field**

Adds a row with empty text inputs, to be completed by the user.
*****
**3. Feed Filters**

Filters narrow down the products to be included in the product feed.
*****
**3.1 Columns**

**If**

Select with names of available attribute filters.

**Condition**

List of conditions:

- contains
- does not contain
- is equal to
- is not equal to
- is greater than
- is greater or equal to
- is less than
- is less or equal to
- is empty
- is not empty

**Value**

Field value.

**Delete**

Remove the current row.

*****
**3.2 Actions**

**Add Filter**

Added new row filter. All filters have AND operators.
*****

**4. Category mapping**

Category mapping is about better matching the product category to the google category.

*****
## Settings ##

**1. Plugin settings**

The tab contains all global plugin settings.

**2. Extra fields**

This tab contains all the required and optional fields to fill in the user's experiences in the products view. If you want to add the prisjakt_id field in the product edit view, just turn it on and go to product edition and fill in the data. These options work for simple and variable products.

**3. Plugin systems check**

This tab contains all system requirements, if there is anything that needs to be improved on your server, it will definitely be written here.

== Installation ==

This section describes how to install the plugin and get it working.

e.g.

1. Upload the plugin files to the `/wp-content/plugins/prisjakt-feed` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Prisjakt (Admin Menu Tab) to configure the plugin.

== Frequently Asked Questions ==

= What is the purpouse of the plugin? =

The plugin is a tool designed to help generating product feed compatible with Prisjakt feed specification.

= Is the plugin compatible with other channels? =

The plugin generates feed compatible with Google Merchant product feed specication. Therefore it is possible to use generated product feed in all channels supporting Google Merchant specification.

= How does the plugin work? =

The plugin works by creating a feed file containing all neccessary inforomation about your products, such as product name, price, image, link and more. See https://schema.prisjakt.nu to check more information about Prisjakt feed specification.


= How often the plugin updates the product feed? =

The plugin updates the feed in three predefined intervals: 1h, 12h, 24h.

= How much the plugin cost? =

The plugin if available for free.

= Do I need technical skills to use the plugin? =

No, the plugin is designed to be easy to use and no technical skill is needed. However is you encounter any issues please contact us at support@prisjakt.nu. Our customer support team is always available to help.

= Is the plugin customisable? =

Yes, it is customizable within boudries set in the feed settings. You can add or remove attributes, change update intervals and append custom data to the feed.

= Will the plugin slow down my Woocoommerce store? =

No, the plugin is designed to be lightweight and efficient however feed generation my take more time for larger inventory.

== Screenshots ==

1. This screen shot description corresponds to screenshot-1.(png|jpg|jpeg|gif).
2. This is the second screen shot

== Changelog ==


== Upgrade Notice ==

