=== wA11y - The Web Accessibility Toolbox ===
Contributors: bamadesigner
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ZCAN2UX7QHZPL&lc=US&item_name=Rachel%20Carden%20%28wA11y%29&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: a11y, wa11y, accessibility, tota11y, wave, tools, evaluation, hi roy
Requires at least: 3.0
Tested up to: 5.5.1
Stable tag: 1.0.3
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

wA11y provides a toolbox of resources to help you improve the accessibility of your WordPress website.

== Description ==

wA11y is a WordPress plugin that provides a toolbox of resources to help you improve the accessibility of your WordPress website.

**If you would like to contribute to the plugin, or report issues, please go through [the wA11y Github repo](https://github.com/bamadesigner/wa11y).**

= What Does "wA11y" Mean? =
"A11y" is the commonly used abbreviation for accessibility, since there are eleven letters between the "a" and the "y".

"w" + "A11y" is my abbreviation for "web accessibility". **[#wa11y](https://twitter.com/search?q=%23wa11y)**

== What Is Web Accessibility? ==
Web accessibility refers to the inclusive practice of removing barriers that prevent interaction with, or access to, websites by people with disabilities.

Data shows 1 in 5 people have a disability. If your site is inaccessible, **you could be excluding 20% of your potential users, customers, students, and more.**

The foundation for good accessibility is great markup which also means that good accessibility can improve your SEO.

== Accessibility Resources and Tools ==

The WPCampus community has a [great list of accessibility resources and tools](https://wpcampus.org/resources/accessibility/) available on their website.

This resource is open-source and WPCampus would love for you to contribute. [Visit the WPCampus Resources repo](https://github.com/wpcampus/wpcampus-resources) to learn how to contribute.

**WA11Y PRO TIP: Take advantage of in-browser testing tools, like [WAVE](http://wave.webaim.org/) and [HTML_CodeSniffer](http://squizlabs.github.io/HTML_CodeSniffer/), to evaluate WordPress theme demos before you purchase.**

== Tools Included In wA11y ==

The mission for the wA11y plugin is to provide a plethora of tools to help you evaluate and improve the accessibility of your website.

**If you would like to see any new tools added to the plugin, please [create an issue in the wA11y Github repo](https://github.com/bamadesigner/wa11y) and label it an "enhancement".**

### Tota11y

[tota11y](http://khan.github.io/tota11y/) is an accessibility visualization toolkit provided by your friends at Khan Academy. It is a single JavaScript file that inserts a small button in the bottom corner of your document and helps visualize how your site performs with assistive technologies.

### WAVE

[WAVE](http://wave.webaim.org/) is a free evaluation tool provided by [WebAIM (Web Accessibility In Mind)](http://webaim.org/). It can be used to evaluate a live website for a wide range of accessibility issues.

== Installation ==

1. Upload 'wa11y' to the '/wp-content/plugins/' directory
2. Activate the plugin through the 'Plugins' menu in WordPress
3. Go to Settings > wA11y to setup the plugin.

== Changelog ==

= 1.0.3 =
* Updating totally to v0.2.0.

= 1.0.2 =
* Fixed PHP bug on settings page.

= 1.0.1 =
* Added accessibility resources information to settings page.
* NOTICE: I removed the plugin's PHP constants.

= 1.0.0 =
Plugin launch

== Upgrade Notice ==

= 1.0.3 =
* Updating totally to v0.2.0.

= 1.0.2 =
* Fixed PHP bug on settings page.

= 1.0.1 =
* Added accessibility resources information to settings page.
* NOTICE: I removed the plugin's PHP constants.

= 1.0.0 =
Plugin launch

== Filters ==

Filters provide a way for you to modify a piece of data inside the wA11y plugin. [Learn more about filters](https://developer.wordpress.org/plugins/hooks/filters/).

Here is a list of the filters available for this plugin:

* 'wa11y_settings'
    * Allows you to change the plugin settings
    * Passes 1 argument: the default settings
* 'wa11y_wave_url'
    * Allows you to change the URL passed to WAVE
    * Passes 2 arguments: the default URL and the post object (if defined)
* 'wa11y_load_tota11y'
    * Allows you to pass a boolean to define whether or not the tota11y tool should be loaded
    * Passes 2 arguments: the default setting and the plugin's settings
* 'wa11y_load_wave'
    * Allows you to pass a boolean to define whether or not the WAVE tool should be loaded
    * Passes 2 arguments: the default setting and the plugin's settings
