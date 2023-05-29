=== Facebook Chat Plugin - Live Chat Plugin for WordPress ===
Contributors: facebook
Tags: Facebook, Messenger, Customer Care, Chat, Messaging, Chat Plugin
Requires at least: 3.9
Tested up to: 5.9
Stable tag: 2.5
Requires PHP: 5.2.4
License: GPLv2
License URI: http://www.gnu.org/licenses/gpl-2.0.html

The Facebook Chat Plugin makes it easy for your website visitors to chat with you and ask you questions, even if they don't have Messenger.

== Description ==
Communicate with customers on your website with Messenger-powered chat. Chat Plugin is a chat widget maintained by the Meta Business that enables live chat on your website.

Whether they’re on their computer or their phone, website visitors will be able to message you anytime by clicking on a small Messenger chat bubble in the lower right corner of your site.

Key features:

- Website visitors can message you while browsing your site.
- Set up auto-replies and answers to common questions to serve customers when you’re not available.
- Continue the conversation with customers on Messenger even after they leave your website.
- Visitors without a Facebook Messenger account can ask you questions anonymously in Guest mode.
- [Messenger](https://www.facebook.com/business/messenger/get-started)’s familiar interface builds trust.
- No need to switch between apps to answer questions you get on the website.

== Installation ==

= Pre-requisites =
__Requires WordPress Version:__ 3.9 or higher
__Requires PHP Version:__ 5.2.4 or higher
__Facebook Page__

In order to use the Chat Plugin, you will need to have a published Facebook Page. If you do not have a Facebook Page, you can create one for free [here](https://www.facebook.com/pages/creation/?ref_type=pages_you_admin).

Once you have your Facebook Page ready, you will be asked to login to Facebook. You can then follow the directions below.

= Installing =
To install the Chat Plugin:

1. Log in to your WordPress dashboard, navigate to the Plugins menu and click Add New.
2. In the search field, type 'Facebook Chat Plugin' and click 'Search Plugins'. Select the plugin authored by 'Meta'. You can install it by clicking 'Install Now'.

= Configure plugin for first use =
After navigating to the Chat Plugin settings page, click on 'Setup Customer Chat.' A new window will appear for setting up the plugin and you will be asked to select the Facebook Page you want to use. Next, you will go through the plugin setup with the following steps:

1. After installing the plugin, navigate to **Settings** in your WordPress dashboard menu and click **Facebook Chat**.
2. This will open the Chat Plugin settings page. Click **Setup Chat Plugin**.
3. A new window will appear and you will be asked to **select the Facebook Page you want to use**.
4. You can now **customize** the welcome message, color, and language of the chat, as well as provide answers to frequently asked questions.
5. On the right side of the screen, you can preview what desktop and mobile visitors will see. Once you’re pleased, click **Publish**.

That's it! You're all set. Customers can now message you from your website, and you can access their messages in your Business Page Inbox.

To rerun the setup, go to your WordPress dashboard and click **Facebook Chat**. This will open the Chat Plugin settings page. Click **Edit Chat Plugin Configuration**.

To remove the plugin, you can deactivate the plugin or uninstall it.

= Updating =
Following the [Facebook platform and versioning](https://developers.facebook.com/docs/apps/versions), all versions will be supported for 2 years from launch. Updating should be safe and easy.

= Support =
If you get stuck, or have any questions, you can ask for help in the [Messenger Platform Developer Community](https://www.facebook.com/groups/messengerplatform).

== Screenshots ==
1. Facebook Chat Plugin on your website
2. Facebook Chat Plugin on mobile view
3. Starting a conversation via the Chat Plugin
4. Facebook Chat Plugin settings page on first installation
5. Set your desired language, guest mode and customize the greeting text
6. Facebook Chat Plugin settings page after successful setup

== Frequently Asked Questions ==

= What do I need to set up the Chat Plugin on my website? =
You will need to be an administrator of a Facebook Page and be logged into Facebook.

If you don’t have one, you can create a [business Page in minutes](https://www.facebook.com/business/pages/set-up#).

= Will website visitors need a Facebook account to contact me? =
No, they can message you as a guest user.

If people are logged in to their Facebook accounts, the conversations will be saved in Messenger and they’ll be able to continue the conversation even after leaving your website.

= What will website visitors see when I’m not online? =
Serve customers when you’re not available by setting up auto-replies and answers to common questions.

We recommend setting response times so customers can know when they will receive a response.

For auto-replies, you can thank customers for reaching out, provide average response times, and include relevant links.

Common questions can include inquiries around return policies, ingredients, locations, memberships, etc.

= Can I chat to multiple customers at the same time? =
Yes, all messages will be shown in your Business Page Inbox.

= I don’t want the chat window to show up on every page. Can I choose where it displays? =
1. Once you’ve set up the plugin, click **Facebook Chat** in your WordPress admin dashboard.
2. You should see a tab that says **Setup status**, where you can select to display the chat window on custom pages.
3. Select the pages or posts where you want to display the chat window.

= Can I change the appearance of the chat window? =
Yes! You can customize the color, greeting message, button display, alignment, etc. You can also set your desired language and turn guest mode on or off.

Most customizations (theme color, greeting, language, guest mode, alignment and automatically expand) can be set directly via the plugin setup tool: Facebook page settings --> Messaging --> Add Messenger to your website --> Get Started.

You can customize other features via [Customization API](https://developers.facebook.com/docs/messenger-platform/discovery/facebook-chat-plugin#customization).

= How can I set automatic replies? =
You can set up to 3 automatic replies by customizing the Chat Plugin settings in your Facebook page inbox. Open your Facebook Page -> Open Manage Page panel on the left -> Meta Business Suite -> Inbox -> Chat Plugin -> Start the conversation -> Frequently asked questions
Please note that the “Automated Responses” feature in Inbox only applies to conversations started on your Facebook Page and does not work on the Chat Plugin.

= I have installed the Facebook Chat Plugin on my website but it doesnt show. What can I do? =
If you are having trouble loading Chat Plugin, you can use the [Diagnostic Tool](https://developers.facebook.com/tools/chatplugin/diagnostictool/) to check for errors.

= How can I grow my business through live chat? =
Live chat lets you answer questions about price or delivery in the moment, provide personalized assistance so you can help customers buy with confidence, and build stronger relationships.

By using the Facebook Chat Plugin, ROYBI, the educational robot maker, [improved customer service response times and saw a 50% increase in leads](https://www.facebook.com/business/success/roybi#).

You can also check out our other [case studies](https://www.facebook.com/business/success/categories/messenger#) to see how businesses similar to yours are growing with Facebook marketing.

== Changelog ==
= 2.5 - Jul 5, 2022 =
* Added an admin notice for any service outages or important status updates.
* Added a link to configure availability and automated responses in Meta Business Suite.

= 2.4 - Mar 28, 2022 =
* Changed the prompt for plugin reviews to only appear on fewer screens and only display if the plugin is in active use.

= 2.3 - Mar 2, 2022 =
* Tested up to WordPress 5.9.
* Added a dismissible notice to configure the plugin when activated.
* Added a filter, `fbmcc_options_capability` to support changing the capability required to manage plugin settings.
* Added a filter, `fbmcc_should_display` to support more control over where the chat plugin appears.
* Moved the admin menu item for the plugin's settings into the Settings menu.
* Updated the plugin's PHP code to adhere to the WordPress coding standards.
* Updated the main plugin class to use the Singleton pattern.
* Adjusted some wording in the plugin to improve clarity.
* Fixed an issue where multiple chat windows might appear.

= 2.2 - Jun 30, 2021 =
* Tested up to WordPress 5.7.2
* Product rename! (“Facebook Chat Plugin")
* Fixed PHP8 issues
* Fixed issue with initialisation of ajax_object (Github issue #10)
* Improvements and fixes to the Advanced Configuration page deployment selector
* Added review prompt card to solicit feedback from users

= 2.1 - May 1, 2021 =
* Tested up to WordPress 5.7.1
* Added new configuration options for admin to specify which pages to deploy the plugin on

= 2.0 - Dec 1, 2020 =
* Tested up to WordPress 5.5.3
* Added translations for strings on admin panel settings and css fix

= 1.9 - Nov 3, 2020 =
* Tested up to WordPress 5.5.3
* Added troubleshooting links in plugin settings page and faq's

= 1.8 - Sept 28, 2020 =
* Tested up to WordPress 5.5.1
* Added instruction links in plugin install page

= 1.7 - Aug 5, 2020 =
* Tested up to WordPress 5.4.2
* Added Plugin versioning

= 1.6 - July 27, 2020 =
* Fix security issue

= 1.5 - April 29, 2020 =
* Fix undefined variable error message

= 1.4 - April 13, 2020 =
* Tested up to WordPress 5.3.2
* Simplify installation flow

= 1.3 - June 17, 2019 =
* Tested up to WordPress 5.2.1
* Fixed security issue

= 1.2 - February 28, 2019 =
* Tested up to WordPress 5.1
* Fixed issues with Safari
* Fix: Edge case where multiple SDK loaded on the page resulting with plugin not loading

= 1.1 - October 31, 2018 =
* Updated plugin to use new Facebook JS SDK
* Fix: Error message in code snippet

= 1.0 - September 24, 2018 =
* Plugin released for all users

== Upgrade Notice ==

= 2.2 =
* Various fixes and improvements. Tested up to WordPress 5.7.2, and PHP8.

= 2.1 =
* Added new configuration options for admin to specify which pages to deploy the plugin on. Tested up to WordPress 5.7.1

= 2.0 =
* Added translations for strings on admin panel settings and css fix. Tested up to WordPress 5.5.3

= 1.9 =
* Added troubleshooting links in plugin settings page and faq's. Tested up to WordPress 5.5.3

= 1.8 =
* Added instruction links in plugin install page. Tested up to WordPress 5.5.1

= 1.7 =
* Added Plugin versioning. Tested up to WordPress 5.4.2

= 1.6 =
* Fix security issue

= 1.5 =
* Fix undefined variable error message

= 1.4 =
* Simplify installation flow

= 1.3 =
* Fix security issue

= 1.2 =
* Handle edge case which resulted in plugin being broken for some users.

= 1.1 =
* Upgraded plugin to use new Facebook JS SDK and minor bug fixes.
