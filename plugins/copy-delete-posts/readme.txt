=== Duplicate Post ===
Contributors: CopyDeletePosts, copydelete
Tags: Duplicate post, Copy posts, Copy pages, Duplicate posts, Duplicate pages, Clone posts, Clone pages, Delete posts, Delete pages, Duplicator, copy post, copy page
Requires at least: 4.6
Tested up to: 6.2
Stable tag: 1.3.9
License: GPLv3
Requires PHP: 5.6

Duplicate post

== Description ==

**Try it out on your free dummy site: Click here => [https://tastewp.com/plugins/copy-delete-posts](https://demo.tastewp.com/cdp).**
(this trick works for all plugins in the WP repo - just replace "wordpress" with "tastewp" in the URL)

Massively increase your WordPress productivity!

Copy Page plugin makes it super-easy to duplicate pages or copy posts - and delete them again!

And why is it handy to duplicate pages? Here are just some of the use cases:

- Duplicate pages to make short work of using again the same elements you repeatedly use (e.g. text paragraphs, images, video, featured image, etc.)
- Create a variation of a page or post fast to modify it and compare side by side (for yourself, your client or company)
- Create one perfect set of page templates and then re-use them for different projects, clients or products
- Apply a facelift to a specific page but keep the older version in case you want to switch back to it anytime
- Make a "holiday special" page template and use it for different holidays with respective adjustments
- Create duplicates for pages used in page builders with their custom settings

You can as well run a load-test on your server by duplicating as much as 1,000 pages, and track how the server behaves. Copy Page plugin also makes it super-easy for you to bulk-delete pages and posts whenever you feel it’s time for a clean-up!

**How to use it**

Two-minute video tutorial and you are ready to use it, that’s how simple Copy Page is!

[youtube https://youtu.be/1KXLuKhNCR4]

After installation you'll see a new copy page button which, on mouse-over, displays a tooltip (see screenshot) which allows you to copy pages or copy posts with various options:

- Copy page or duplicate post instantly with a single click
- Expand to see additional copy page options and specify which elements should be copied for the current copy page or copy post action

The new copy page button will be available on:

- All Pages and All Posts screens
- Edit screens (duplicate page or duplicate post on the respective edit page/post screens)
- Admin bar (at the top)
- Bulk-option to copy pages or copy posts on All Pages and All Posts screens
- Gutenberg editor


You can hide copy page or copy post button on any of these places from the Copy Page plugin menu (Section: Other options). Copy page function on the editor screens allows fast and easy multiplying of pages you are working on at the moment (and open it in the new browser tab immediately, too), so you can quickly make a couple of variations, pick whichever you like better, and afterward use the Delete duplicate posts/pages tool of Copy Page plugin to remove duplicate(s) that you dislike.

Copy Page plugin also provides an intuitive naming tool to define how the cloned pages or posts will be named (e.g. you can add the time and date of copying, or incremental counter, etc.). This way you can multiply page that will have a short name, e.g. “ExamplePage - #4” - where the number at the end will increase with each copy made; or you can duplicate page with much more detailed name of the copy, such as: “ExamplePage copied at 14:22:58 on Sunday, December 1st, 2021” - thankfully to PHP date/time shortcodes being supported in the custom date settings. Long names often make it easy to differentiate the clone post, either to remove duplicate or to e.g. edit it.

Want to duplicate page but also it’s child-page(s)? You can multiply pages altogether with child-pages with all the copy settings currently applied to the parent page copy.

Other options available when you copy pages:

- Specify where you will be navigated to after you copy page or copy post
- Specify which users (WP roles) will be granted to copy pages or copy posts
- Specify which content types will be allowed to be copied - copy pages, copy posts, and copy custom post types
- Enable/disable the display of a reference to the original of copied page or post

Not only can you clone pages or clone posts easily, but the Copy Page plugin also provides a highly developed tool to Delete duplicate posts/pages.

As part of this cleanup process, you can specify:

- Where the plugin will look for duplicates (i.e. delete pages, delete posts or delete custom posts)
- What will be considered as a duplicate page or duplicate post (i.e. will it be the same title, slug, excerpt or word count)
- Which version of duplicates you want to keep: oldest version (original) or newest version (the latest duplicate)

Even if you used this replicator tool to multiply pages or multiply posts in huge numbers, and you want to use this tool to trash duplicates every now and then, but leave out a few specific ones - you will be able to easily find duplicates when you use our duplicates scanner. After setting the parameters to find duplicates, you can use a search box to make sure you don’t delete duplicates you don’t want to, or remove duplicates to the last one (original included!). The duplicate checker tool can help you find duplicate and immediately visually check the clone page by clicking on the URL/slug link, in case you can’t tell by the name or the date clone page was created.

You can select to throttle the delete pages or delete posts process - which you may want to do when you’re on a slow server (note, however: the plugin codes to delete posts or delete pages are already optimized, so usually there shouldn’t be an issue).

Copy Page is a cloner tool with a beautiful, modern design and features going beyond today’s post duplicator tools. We hope that Copy Page will become your favorite posts duplicator tool :)

The free Copy Page plugin offers a lot of options - if you want even more options to copy pages or copy posts, then check out the [premium Copy Page plugin](https://sellcodes.com/CylMIdJD) which offers the following additional features:

- Use several configuration sets to copy pages or delete posts - useful when you want to quickly switch between the copy types, without having to go to the settings page.
- Export and import configuration sets - a handy tool for all of us who manage multiple sites and regularly replicate posts.
- Also include information from third party plugins when you copy pages (e.g. Yoast-information linked to pages/posts)
- Replicate pages across multisite will also prove to be a real time-saver for multisite administrators
- Automatically delete pages or posts - extremely useful for users that multiply posts or pages at high levels.
- Apply automatic redirects for deleted pages or posts

Just try it out! You'll love it :)

This plugin is part of the Inisev product family - [check out our other products](https://inisev.com).


== Installation ==

= Admin Installer via search =
1. Visit the Add New plugin screen and search for "Copy & Delete Posts".
2. Click the "Install Now" button.
3. Activate the plugin.
4. The plugin should be shown below settings menu.

= Admin Installer via zip =
1. Visit the Add New plugin screen and click the "Upload Plugin" button.
2. Click the "Browse..." button and select the zip file of our plugin.
3. Click "Install Now" button.
4. Once uploading is done, activate Copy & Delete Posts.
5. The plugin should be shown below the settings menu.

== Frequently Asked Questions ==

= It seems the post deletion process doesn’t work. Why? =
If you are trying to delete posts and it doesn’t work try to append your wp-config.php with this line of code:
`define('ALTERNATE_WP_CRON', true);`
Does it work if you try to delete posts then? If not, please reach out to us in the support forum.

= When I copy post or copy page, why is title of the duplicate not the same?  =
If you copy posts or copy pages and want the new versions to have exactly the same title as the original post, make sure that prefix and suffix fields are blank in the “What name(s) should the copies have?” section of the Copy Delete Posts plugin area in the WordPress Dashboard.

= If I duplicate posts, how do I know what their original page was?  =
It can be a challenge to keep track of the original content if you duplicate posts. To prevent this we suggest to not leave the prefix and suffix fields empty (which define the name of the new posts) if you duplicate posts. However, even if you want to duplicate posts without any prefix or suffix, you can solve the issue as follows: Go to section “Other options” (on the duplicate post plugin configuration page), and at the bottom of this section you will find the option “Show reference to original item?”. Check this to ensure you can always keep track of original posts when duplicate pages or posts.

= Can I limit who can duplicate posts on my site? =
By default only Administrators can access the plugin and copy posts or copy pages. You can extend these permissions to other user roles by going to the section “Other options”, and then tick boxes next to WP user roles that you want to give permission to. Then also those roles can duplicate posts (or delete posts).

= I want to duplicate posts *only*, i.e. not duplicate pages. Is that possible? =
You can limit the features to duplicate posts only by going to the “Other options” - section and select where it says “Content types which can be copied” to only copy posts, copy pages, copy custom posts or all of these.

= How can I make bulk copies? =
If you want to duplicate posts en masse, select the copy posts option in the “Bulk actions” menu. You’ll see the lightbox asking you to specify your duplicate post options (i.e. which elements to copy).

= I can duplicate posts but it takes a long time. Why? =
If you duplicate posts and it takes long, then you may have selected to include attachments in the duplicate posts configurations. Go to the second section titled “Which elements should be copied?” and de-select the attachments option to exclude those when you duplicate posts.

= Can I bulk delete posts created by this plugin?  =
To easily clean posts or delete duplicate pages that were created by this plugin, go to ”Delete duplicate posts or pages” section, tab “Manual cleanup”. Select Posts, Pages and Custom Posts, and uncheck all other filters, then hit the Scan button. In the empty results list, you will see the message “Click here to show all copies…” - “here” link will show you all posts and pages created by our multiplier plugin.

= Which dupicate post features do you have which the other plugins don’t?  =
Other duplicate post plugins mostly only allow you to duplicate post to the same site. With the Copy Delete Posts plugin (premium version) you can duplicate post to other sites, e.g. duplicate post to a multisite, or duplicate post to a site on a different domain altogether (we’re currently working on this duplicate post functionality). Also, other duplicate post plugins don’t give you the granularity to define how to duplicate post, e.g. which elements specifically should appear on the cloned posts.

= Is this plugin GDPR friendly? =
Copy Delete Posts WordPress plugin doesn’t store any site visitor information so it is completely GDPR friendly.

= ACF compatibility =
ACF is fully supported by Copy Delete Post Premium, as ACF is something more than a simple post. The plugin can only cop native posts and pages ( that are aligned with WordPress standards ). ACF does not stick with these standards as they put multiple posts attached to one post ID, which is visible on the list, while others are hidden.

= Is the plugin also available in my language? =
So far we have translated the plugin into these languages:

Arabic: [انسخ المنشورات وانسخ الصفحات ونسخ المنشورات المخصصة وحذف التكرارات.](https://ar.wordpress.org/plugins/copy-delete-posts/)
Chinese (China): [复制帖子、复制页面、复制自定义帖子和删除重复项。](https://cn.wordpress.org/plugins/copy-delete-posts/)
Croatian: [Kopirajte postove, kopirajte stranice, duplicirajte prilagođene postove i izbrišite duplikate.](https://hr.wordpress.org/plugins/copy-delete-posts/)
Dutch: [Kopieer berichten, kopieer pagina's, dupliceer aangepaste berichten en verwijder duplicaten.](https://nl.wordpress.org/plugins/copy-delete-posts/)
English: [Copy pages, copy posts, and delete the duplicate post again in one go](https://wordpress.org/plugins/copy-delete-posts/)
Finnish: [Kopioi viestejä, kopioi sivuja, monista mukautettuja viestejä ja poista kaksoiskappaleita.](https://fi.wordpress.org/plugins/copy-delete-posts/)
French (France): [Copiez les publications, copiez les pages, dupliquez les publications personnalisées et supprimez les doublons.](https://fr.wordpress.org/plugins/copy-delete-posts/)
German: [Kopieren Sie Beiträge, kopieren Sie Seiten, duplizieren Sie benutzerdefinierte Beiträge und löschen Sie Duplikate.](https://de.wordpress.org/plugins/copy-delete-posts/)
Greek: [Αντιγράψτε αναρτήσεις, αντιγράψτε σελίδες, αντιγράψτε προσαρμοσμένες αναρτήσεις και διαγράψτε διπλότυπα.](https://el.wordpress.org/plugins/copy-delete-posts/)
Hungarian: [Bejegyzések másolása, oldalak másolása, egyéni bejegyzések másolása és ismétlődések törlése.](https://hu.wordpress.org/plugins/copy-delete-posts/)
Indonesian: [Salin posting, salin halaman, duplikat posting kustom, dan hapus duplikat.](https://id.wordpress.org/plugins/copy-delete-posts/)
Italian: [Copia post, copia pagine, duplica post personalizzati ed elimina duplicati.](https://it.wordpress.org/plugins/copy-delete-posts/)
Persian: [پست ها را کپی کنید، صفحات را کپی کنید، پست های سفارشی را تکرار کنید، و موارد تکراری را حذف کنید.](https://fa.wordpress.org/plugins/copy-delete-posts/)
Polish: [Kopiuj posty, kopiuj strony, duplikuj posty niestandardowe i usuwaj duplikaty.](https://pl.wordpress.org/plugins/copy-delete-posts/)
Portuguese (Brazil): [Copie postagens, copie páginas, duplique postagens personalizadas e exclua duplicatas.](https://br.wordpress.org/plugins/copy-delete-posts/)
Russian: [Копируйте сообщения, копируйте страницы, дублируйте пользовательские сообщения и удаляйте дубликаты.](https://ru.wordpress.org/plugins/copy-delete-posts/)
Spanish: [Copie publicaciones, copie páginas, duplique publicaciones personalizadas y elimine duplicados.](https://es.wordpress.org/plugins/copy-delete-posts/)
Turkish: [Gönderi kopyalayın, sayfa kopyalayın, özel tasarlanmış gönderileri çoğaltın, ve kopyaları silin.](https://tr.wordpress.org/plugins/copy-delete-posts/)
Vietnamese: [Sao chép bài đăng, sao chép trang, sao chép bài đăng tùy chỉnh và xóa bản sao.](https://vi.wordpress.org/plugins/copy-delete-posts/)

== Screenshots ==
1. Plugin settings page
2. Copy preset settings
3. Customizable naming system
4. Global settings & permission system
5. Manual clean up
6. Quick-copy tooltip
7. Tooltip individual copy
8. Copy from Gutenberg editor

== Changelog ==

= 1.3.9 =
* Tested up with WordPress 6.2
* Removed two unused modules
* Updated carrousel module
* Fixed old database version compatibility
* Extended TIF module for everyone

= 1.3.8 =
* Tested up WordPress 6.2-Beta1
* [Premium] Added full ACF duplication support
* Adjusted proper duplication of WooCommerce products
* Fixed duplication of WooCommerce variations
* Resolved issues with child-post custom types
* Extended new module
* Minimized styling conflicts in custom post type pages

= 1.3.7 =
* Included new module
* Fixed issues with deactivation feedback
* Corrected display of arrow

= 1.3.6 =
* Adjusted PHP compatibility

= 1.3.5 =
* Added black-friday theme (only for that period)
* Tested up to WordPress 6.1.1

= 1.3.4 =
* Improved premium plugin performance
* Tested with final WordPress 6.1 version

= 1.3.3 =
* Tested with WordPress 6.1-RC5 + Multisite
* Fully tested on PHP 7.4, 8.0, 8.1
* Removed function that could cause conflicts

= 1.3.2 =
* NEW: Added automatic creation of non-existing categories for subsite duplication
* Fixed issues with assigning taxonomy on new posts
* Resolved issues with featured image duplication between sites
* Tested with WordPress 6.0.2 + Multisite (PHP 8)

= 1.3.1 =
* Version fully tested with WordPress 6.0.1 + Multisite
* Removed unnecessary debug code
* Fixed issues with falsive attachment duplication
* Fixed issue inside tooltip while redirect to edit screen option was enabled
* Fixed issue while during duplication notice "Please select settings" appeared
* Fixed issue with multi-post duplication to different subsite
* Fixed post type restrictions, copy option won't appear in dropdown while copying is forbidden
* Fixed corner cases where copy action button was not displayed in subsites
* Fixed issues with backslashes and unicode characters - they should copied correctly now
* Default WordPress Posts and Pages are not longer treated as custom posts
* Resolved issues with scheduled clean-ups - sometimes this option settings were blank
* Resolved all deprecated warnings with PHP 8+
* NEW: Added automatic URL adjustment for multisites (premium)
* NEW: Added possibility to copy into multiple subsites at once - inside bulk modal (premium)

= 1.3.0 =
* Version fully tested with WordPress 6.0
* Removed unnecessary error logging
* Adjusted styles of forms in copy modal
* Fixed conflict issues for our dropdown solution
* Fixed conflict issues with other tooltipster plugins
* Fixed issue when tooltip was displayed without content on SiteGround Hosting
* Fixed automatic profile preselection of options in modal and tooltips
* Adjusted tooltip to not "Flash" on the screen
* Fixed issue when user could not extend duplication options in tooltip
* Modified method of inserting tooltip content to resolve some conflicts
* Fixed error in posts menu for users without access to CDP settings (Thank you @saccones)
* Added "Select all" option to advanced duplication options (tooltip & modal)
* Adjusted plugin performance depending on user's log feedback
* Fixed issues with performance checking on quickest websites
* Added dedicated solution for Elementor posts and added duplication of cached CSS file
* Added support for SeedProd builder, added dedicated duplication for CSS files

= 1.2.9 =
* Improved meta duplication
* Fixed copy issues with Elementor templates and pages
* Improved look of Elementor duplicates (they should be perfect now)
* Updated carrousel
* Fixed conflicts of other plugins that blocks access to our settings

= 1.2.8 =
* Adjusted name of "Hiding Menu" option
* Tested up to WordPress 5.9.1
* Adjusted copy tooltip animation, it should appear and disappear quicker
* Fixed issues with our carrousel display

= 1.2.7 =
* Changed sensitivity of performance check
* Added new feature for copying different kinds of posts
* Updated logic of mirroring metadata posts
* Retested plugin with latest Gutenberg version
* Fully tested with WordPress 5.9

= 1.2.6 =
* Tested with WordPress 5.9-RC1
* Updated tooltips

= 1.2.5 =
* Removed deactivation module

= 1.2.4 =
* Added deactivation module

= 1.2.3 =
* Applied improvements that depends on user performance data

= 1.2.2 =
* Tested up to WordPress 5.8.2
* Tested up to PHP 8.1
* Removed translation notices in the developer console
* Fixed localize script issues on front-end side
* Added new performance window after copy
* Fixed issues with gutenberg copy button

= 1.2.1 =
* Fixed version mishmash

= 1.2.0 =
* Alternate CRON for 5.6.1 re-tested
* Adjusted processing function
* Tested copying with broken post content

= 1.1.9 =
* Plugin translation-ready
* Updated styles
* Added performance checks for individual servers
* Added logs for last 50 copy processes
* Added new notices

= 1.1.8 =
* Performance adjustements for PHP 8
* Plugin prepared for translation
* Fixed activation/deactivation issues for Pro

= 1.1.7 =
* Banner fixes

= 1.1.6 =
* Tested up to WordPress 5.7
* Fixed few notices in PHP 8
* Updated review banner

= 1.1.5 =
* Tested up to WordPress 5.6.2
* Fixed footer issue in Gutenberg editor
* Fixed notice in premium version (regarding new option)
* Added posibility to hide menu under tools

= 1.1.4 =
* Tested up to WordPress 5.6.1
* Added support chat
* Added new premium feature (hide chat)

= 1.1.3 =
* Added support for PHP 8 and WordPress 5.6
* Added Carusel

= 1.1.2 =
* Tested with newest version of WordPress

= 1.1.1 =
* Fixed some warnings and notices in PHP 7
* Added new variable CDP_SHOW_SITE_URLS (for wp-config)

= 1.1.0 =
* Tested stability up to WordPress 5.5.1
* Adjusted icon display / position

= 1.0.9 =
* Tested stability up to WordPress 5.5

= 1.0.8 =
* Added support for WordPress 4.6
* Added support for PHP 5.6

= 1.0.7 =
* [Premium] Automatic Cleanup
* Updated banner tooltips
* Added more info to Admin Bar entries
* Added new type to Admin Bar notifications "Auto Cleanup"
* Changed logic of filter (More title options -> option "Yes")

= 1.0.6 =
* [Premium] WooCommerce integration
* [Premium] Advanced Filters for deletion process
* WooCommerce edit-screen copy fix
* Modal overlay display on front-end fix
* Improved custom posts detection
* Stability improvements

= 1.0.5 =
* Auto-refresh list of notifications
* Fixed issue when notification wasn't updated correctly
* Improvement search engine (Deletion Section)
* Scrollbar fix on plugin configuration page

= 1.0.4 =
* This is Hotfix
* Fixed issue during activation which shows "Header Error"

= 1.0.3 =
* Tested with new version of WordPress
* Fixed visual issue with deletion process

= 1.0.2 =
* Added new cool GIF inside the intro
* Added photos to the screenshots library
* Removed unnecessary stuff in code

= 1.0.1 =
* Improved deletion process above 800 posts
* Fixed sorting issues
* Fixed specified copies options (via tooltip)
* Fixed tooltip (flashing) issues on smaller screens

= 1.0.0 =
* Initial release

== Upgrade Notice ==
= 1.3.9 =
What's new in 1.3.9?
* Tested up with WordPress 6.2
* Removed two unused modules
* Updated carrousel module
* Fixed old database version compatibility
* Extended TIF module for everyone
