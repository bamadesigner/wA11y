=== wA11y - The Web Accessibility Toolbox ===
Contributors: bamadesigner
Donate link: https://www.paypal.com/cgi-bin/webscr?cmd=_donations&business=ZCAN2UX7QHZPL&lc=US&item_name=Rachel%20Carden%20%28wA11y%29&currency_code=USD&bn=PP%2dDonationsBF%3abtn_donate_SM%2egif%3aNonHosted
Tags: a11y, wa11y, accessibility, tota11y, wave, tools, evaluation, hi roy
Requires at least: 3.0
Tested up to: 4.5.3
Stable tag: 1.0.0
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html

wA11y provides a toolbox of resources to help you improve the accessibility of your WordPress website.

== Description ==

wA11y is a WordPress plugin that provides a toolbox of resources to help you improve the accessibility of your WordPress website.

**If you would like to contribute to the plugin, or report issues, please go through [the wA11y Github repo](https://github.com/bamadesigner/wa11y).**

= What Does "wA11y" Mean? =
"A11y" is the commonly used abbreviation for accessibility, since there are eleven letters between the "a" and the "y".

"w" + "A11y" is my abbreviation for "web accessibility". **[#wa11y](https://twitter.com/search?q=%23wa11y)**

= What Is Web Accessibility? =
Web accessibility refers to the inclusive practice of removing barriers that prevent interaction with, or access to, websites by people with disabilities.

Data shows 1 in 5 people have a disability. If your site is inaccessible, **you could be excluding 20% of your potential users, customers, students, and more.**

The foundation for good accessibility is great markup which also means that good accessibility can improve your SEO.

= wA11y.org =

[wA11y.org](https://wa11y.org) is a new community initiative to contribute to web accessibility by providing information, education, resources, and tools.

If you're interested in joining the wA11y.org community, and would like to contribute to its growth, please subscribe at [https://wa11y.org](https://wa11y.org).

= Tools Included In wA11y =

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

= 1.0.0 =
Plugin launch

== Upgrade Notice ==

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

== Resources ==

**WA11Y PRO TIP: Take advantage of in-browser testing tools, like [WAVE](http://wave.webaim.org/) and [HTML_CodeSniffer](http://squizlabs.github.io/HTML_CodeSniffer/), to evaluate WordPress theme demos before you purchase.**

= A Few Basic Accessibility Principles =

* All non-text content needs a text equivalent to help convey information to those with sensory disabilities.
    * For example, images and videos need text equivalents to convey information and context to those who have trouble with vision and/or hearing.
* Color usage should have sufficient contrast and should not be used as the sole method for conveying information or direction.
    * For example, if your website has directions in red, and a user cannot see red, then that direction is inaccessible.
    * Links, or actions on your website, should always be signified with something other than simply a different color. For example, an underline is the most common trait.
* Pages should have proper heading structure in order to be readable without a stylesheet for those who do not navigate visually.
* All site functionality should be available using the keyboard for persons who do not use a mouse.
    * Tab order is important.
* Adding ARIA attributes are helpful to provide screen reader users with more context and greater interactivity with content.
* Responsive web design is important to ensure your site can be viewed on assistive devices of various sizes.

= Web Accessibility Standards =

**WCAG 2.0 - Web Content Accessibility Guidelines**

[WCAG](https://www.w3.org/WAI/intro/wcag) are part of a series of guidelines published by the [Web Accessibility Initiative (WAI)](https://www.w3.org/WAI/) of the [World Wide Web Consortium (W3C)](https://www.w3.org/), the main international standards organization for the Internet.

Current version was published in December 2008.

* [WCAG 2.0 Checklist](http://webaim.org/standards/wcag/checklist)

**Section 508**

[Section 508 Standards](https://www.section508.gov/) apply to electronic and information technology developed, procured, maintained, or used by federal agencies, including computer hardware and software, websites, phone systems, and copiers.

Standards were issued in 2000.

* [Section 508 Checklist](http://webaim.org/standards/508/checklist)

= Other Accessibility Tools =

* [Color Contrast Checker](http://webaim.org/resources/contrastchecker)

= Articles About Accessibility =

* [Which Tool Is Best?](http://webaim.org/articles/tools)
* [Web Accessibility Evaluation Tools](https://www.w3.org/WAI/ER/tools)
* [Getting Started With ARIA](http://a11yproject.com/posts/getting-started-aria)