# Wa11y - The Web Accessibility Toolbox

Wa11y is a WordPress plugin that provides a toolbox of resources to help you improve the accessibility of your WordPress website.

**If you're simply wanting to use the plugin, I recommend you [download Wa11y from the WordPress plugin repo](https://wordpress.org/plugins/wa11y).**

## What Does "Wa11y" Mean?

"A11y" is the commonly used abbreviation for accessibility, since there are eleven letters between the "a" and the "y".
 
"W" + "a11y" is my abbreviation for "web accessibility". **[#wa11y](https://twitter.com/search?q=%23wa11y)**

## What Is Web Accessibility?

Web accessibility refers to  the inclusive practice of  removing barriers that prevent interaction with, or access to, websites by people with disabilities.

Data shows 1 in 5 people have a disability. If your site is inaccessible, **you could be excluding 20%  of your potential users,  customers, students,  and more.**

The foundation for good accessibility is great markup which also means that good accessibility can improve your SEO.

## A Few Basic Accessibility Principles

* All non-text content needs  a text equivalent to help convey information to those with sensory disabilities.
    * For example, images and videos need text equivalents to convey information and context to those who have trouble with vision and/or hearing.
* Color usage should have  sufficient contrast and should not be used as the sole method for conveying information or direction.
    * For example, if your website has directions in red, and a user cannot see red, then that direction is inaccessible.
    * Links, or actions on your website, should always be signified with something other than simply a different color. For example, an underline is the most common trait.
* Pages should have  proper heading structure  in order to be readable without a stylesheet for those who do not navigate visually.
* All site functionality  should be available  using the keyboard  for persons who do  not use a mouse.
    * Tab order is important.
* Adding ARIA attributes are helpful to provide screen reader users with more context and greater interactivity with content.
* Responsive web design is important to ensure your site can be viewed on assistive devices of various sizes.

For more information about accessibility, including official standards and checklists, [check out our resources](#other-accessibility-resources). 

## Tools Included In Wa11y

The mission for the Wa11y plugin is to provide a plethora of tools to help you evaluate and improve the accessibility of your website.

**If you would like to see any new tools added to the plugin, please create an issue in this repo and label it an "enhancement".**

### Tota11y

[tota11y](http://khan.github.io/tota11y/) is an accessibility visualization toolkit provided by your friends at Khan Academy. It is a single JavaScript file that inserts a small button in the bottom corner of your document and helps visualize how your site performs with assistive technologies.
 
### WAVE

[WAVE](http://wave.webaim.org/) is a free evaluation tool provided by [WebAIM (Web Accessibility In Mind)](http://webaim.org/). It can be used to evaluate a live website for a wide range of accessibility issues.

## WordPress Hooks For Wa11y
 
Wa11y provides hooks which allow you to "hook into" the plugin and call your own functions or filter data. There are two types of hooks: actions and filters. You can learn more about hooks [in the WordPress Plugin Handbook](https://developer.wordpress.org/plugins/hooks/).

### Filters

Filters provide a way for you to modify a piece of data inside the Wa11y plugin. [Learn more about filters](https://developer.wordpress.org/plugins/hooks/filters/).

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

## Other Accessibility Resources

There are a multitude of resources available to help you better understand and evaluate accessibility. Please feel free to recommend more to add to the list.

**WA11Y PRO TIP: Take advantage of in-browser testing tools, like [WAVE](http://wave.webaim.org/) and [HTML_CodeSniffer](http://squizlabs.github.io/HTML_CodeSniffer/), to evaluate WordPress theme demos before you purchase.**

### Web Accessibility Standards

#### WCAG 2.0 - Web Content  Accessibility  Guidelines

[WCAG](https://www.w3.org/WAI/intro/wcag) are part of a series of guidelines published by  the [Web Accessibility Initiative (WAI)](https://www.w3.org/WAI/) of the [World Wide Web Consortium (W3C)](https://www.w3.org/), the main international standards organization for the Internet.

Current version was published in December 2008.

* [WCAG 2.0 Checklist](http://webaim.org/standards/wcag/checklist)

#### Section 508

[Section 508 Standards](https://www.section508.gov/) apply to electronic and  information technology developed, procured, maintained, or used by federal agencies, including computer hardware and software, websites, phone systems, and copiers.

Standards were issued in 2000.

* [Section 508 Checklist](http://webaim.org/standards/508/checklist)

### Other Accessibility Tools

* [Color Contrast Checker](http://webaim.org/resources/contrastchecker)

### Articles About Accessibility

* [Which Tool Is Best?](http://webaim.org/articles/tools)
* [Web Accessibility Evaluation Tools](https://www.w3.org/WAI/ER/tools)
* [Getting Started With ARIA](http://a11yproject.com/posts/getting-started-aria)

## Wa11y Development

If you would like to contribute to this plugin, it will require a few steps to get the code inititated and compiled on your local or testing environment.

**You will need to have npm installed.**

**If you would like to submit changes to the plugin, please work on a different branch and then create a pull request towards the master branch.**

1. Clone this repo
2. Open the directory in the command line.
3. Run `npm install`
5. Run `gulp` to compile all of the assets
    * You can also run `gulp watch` to watch your assets for changes/updates