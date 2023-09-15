=== Environment Control Tools ===
Contributors: jesweb.dev
Tags: staging, development, tools, search engine visibility, plugin control
Requires at least: 4.6
Tested up to: 6.3
Requires PHP: 7.4
Stable tag: 1.0.0
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

Environment Control Tools offers environment-specific controls, adjusting plugin behavior and search engine visibility based on your development or production settings. It also visually differentiates environments through favicon changes and admin bar color adjustments.

Have you ever had emails sent from your staging site to many customers because you forgot to deactivate your email plugin after migrating to staging? This is just one of the endless amount of issues that can be solved by this powerful plugin.

Moreover, Environment Control Tools amplifies the effectiveness of other plugins by allowing you to customize their behavior based on the environment. By doing so, it ensures that every plugin operates at its best, whether you're in a live production setting or a controlled development scenario. This not only enhances your workflow but also maximizes the potential of your entire WordPress ecosystem.

== Description ==

Environment Control Tools provide a handy solution for developers and website managers to control plugins and website visibility based on whether the website is in development mode or production. 

**Features**:
1. **Plugin Control**: Choose which plugins to activate or deactivate based on the environment. This plugin will take control of the active state of the plugin and ensure that it is always in the state chosen by you on the settings page.
2. **Search Engine Visibility Control**: Automatically set the website to private or public based on environment.
3. **Admin Bar Color Control**: Set the admin bar color for development mode so that you can easily differentiate between staging and production.
4. **Favicon Control**: Set the favicon for development mode, so that you can easily differentiate between staging and production tabs in your browser.

**Recommended Plugin Configurations**:
1. **Email Control**: Install the [Disable Emails](https://wordpress.org/plugins/disable-emails/) plugin and set it to deactivated in production mode and activated in development mode. Also set your email plugin to deactivated in development mode. This will ensure that your staging site is not sending any emails when it is in development mode. It's also useful to activate a plugin like [WP Mail Logging](https://wordpress.org/plugins/wp-mail-logging/) so that you catch the emails your site is trying to send.
2. **Woocommerce Order Testing**: For easily testing checkout functionality on your Woocommerce staging site, on your production site, install the [WooCommerce Order Test](https://wordpress.org/plugins/woo-order-test/) plugin, activate it, set the "Test Mode" payment method to enabled in the Woocommerce payment settings, and then deactivate the WooCommerce Order Test plugin. Now, in this plugins settings, set the WooCommerce Order Test plugin to deactivated in production mode, and active in development mode. Now you can easily test the checkout when you migrate to staging.
3. **Deactivate Unnecessary Plugins On Staging**: There may be many plugins that you do not need to be running when you migrate to staging. These plugins eat up server resources, making development time slower, and even affecting your live site if your server resources are hitting their limits. Set them to deactivated on development mode and active on production to easily automate this process.
4. **Code Snippets**: Do you have custom code that you only want to run on your staging site or vice versa? Use the [Code Snippets](https://wordpress.org/plugins/code-snippets/) plugin and set it to active on your chosen environment, and deactivated on the other environment.

Please let us know of any useful configurations you have been using so that others can benefit from these ideas! You can get in touch [here](https://jesweb.dev/contact).

== Installation ==

1. **Always start with your live (production) site.** This ensures that the settings you configure will only take effect in development mode once you migrate to staging.
2. In your WordPress dashboard of your **live site**, go to `Plugins` > `Add New`.
3. Click `Upload Plugin`, then `Choose File` and select `environment-control-tools.zip`.
4. Click `Install Now` and then `Activate Plugin`.
5. Activate the plugin through the 'Plugins' menu in WordPress.
6. Navigate to the Environment Control Tools settings page. Here, set up your desired settings for **both** live and development modes.
7. Once you have set up your preferred configurations, you can migrate your site to a staging or development environment. The development mode settings you've specified will automatically take effect in this environment.
8. Should you make changes in staging that you wish to push live, ensure you've properly configured the Environment Control Tools settings again on your live site before migrating. 

== Screenshots ==

1. Environment Control Tools settings page.
2. Notification on search engine visibility being controlled by Environment Control Tools.

== Changelog ==

= 1.0.0 =
* Initial release.

== Other Notes ==

Remember to configure Environment Control Tools after activating to ensure your plugins and search engine visibility settings behave as expected.

