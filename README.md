# wA11y - The Web Accessibility Toolbox

wA11y is a WordPress plugin that provides a toolbox of resources to help you improve the accessibility of your WordPress website.

**If you're simply wanting to use the plugin, I recommend you [download wA11y from the WordPress plugin repo](https://wordpress.org/plugins/wa11y).**

## What Does "wA11y" Mean?

"A11y" is the commonly used abbreviation for accessibility, since there are eleven letters between the "a" and the "y".
 
"w" + "A11y" is my abbreviation for "web accessibility". **[#wa11y](https://twitter.com/search?q=%23wa11y)**

## What Is Web Accessibility?

Web accessibility refers to  the inclusive practice of  removing barriers that prevent interaction with, or access to, websites by people with disabilities.

Data shows 1 in 5 people have a disability. If your site is inaccessible, **you could be excluding 20%  of your potential users,  customers, students,  and more.**

The foundation for good accessibility is great markup which also means that good accessibility can improve your SEO.

## Accessibility Resources and Tools

The WPCampus community has a [great list of accessibility resources and tools](https://wpcampus.org/resources/accessibility/) available on their website.

This resource is open-source and WPCampus would love for you to contribute. [Visit the WPCampus Resources repo](https://github.com/wpcampus/wpcampus-resources) to learn how to contribute.

**WA11Y PRO TIP: Take advantage of in-browser testing tools, like [WAVE](http://wave.webaim.org/) and [HTML_CodeSniffer](http://squizlabs.github.io/HTML_CodeSniffer/), to evaluate WordPress theme demos before you purchase.**

## Tools Included In wA11y

The mission for the wA11y plugin is to provide a plethora of tools to help you evaluate and improve the accessibility of your website.

**If you would like to see any new tools added to the plugin, please create an issue in this repo and label it an "enhancement".**

### Tota11y

[tota11y](http://khan.github.io/tota11y/) is an accessibility visualization toolkit provided by your friends at Khan Academy. It is a single JavaScript file that inserts a small button in the bottom corner of your document and helps visualize how your site performs with assistive technologies.
 
### WAVE

[WAVE](http://wave.webaim.org/) is a free evaluation tool provided by [WebAIM (Web Accessibility In Mind)](http://webaim.org/). It can be used to evaluate a live website for a wide range of accessibility issues.

## WordPress Hooks For wA11y
 
wA11y provides hooks which allow you to "hook into" the plugin and call your own functions or filter data. There are two types of hooks: actions and filters. You can learn more about hooks [in the WordPress Plugin Handbook](https://developer.wordpress.org/plugins/hooks/).

### Filters

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

## wA11y Development

If you would like to contribute to this plugin, it will require a few steps to get the code inititated and compiled on your local or testing environment.

**You will need to have npm installed.**

**If you would like to submit changes to the plugin, please work on a different branch and then create a pull request towards the master branch.**

1. Clone this repo
2. Open the directory in the command line.
3. Run `npm install`
5. Run `gulp` to compile all of the assets
    * You can also run `gulp watch` to watch your assets for changes/updates
