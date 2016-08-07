# Wa11y - The Web Accessibility Toolbox

Wa11y is a WordPress plugin/toolbox of resources to help you improve the accessibility of your WordPress website.

## Tools

The mission for the Wa11y plugin is to provide a plethora of tools to help you evaluate and improve the accessibility of your website. If you would like to see any new tools added to the plugin, please create an issue in this repo and label it an "enhancement".

### Tota11y

[tota11y](http://khan.github.io/tota11y/) is an accessibility visualization toolkit provided by your friends at Khan Academy. It is a single JavaScript file that inserts a small button in the bottom corner of your document and helps visualize how your site performs with assistive technologies.
 
### WAVE

[WAVE](http://wave.webaim.org/) is a free evaluation tool provided by [WebAIM (Web Accessibility In Mind)](http://webaim.org/). It can be used to evaluate a live website for a wide range of accessibility issues.

## WordPress Hooks

Hooks are methods provided by WordPress that allow you to "hook into" WordPress and call your own functions or filter data. There are two types of hooks: actions and filters. You can learn more about hooks [in the WordPress Plugin Handbook](https://developer.wordpress.org/plugins/hooks/).

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

## Development

If you would like to contribute to this plugin, it will require a few steps to get the code inititate on your local or testing environment.

**You will need to have bower and npm installed.
If you would like to submit changes to the plugin, please work on a different branch and then create a pull request towards the master branch.**

1. Clone this repo
2. Open the directory in the command line.
3. Run `npm install` 
4. Run `bower install` 
5. Run `gulp` to take care of/compile all of your assets
    * You can also run `gulp watch` to watch your assets for changes/updates