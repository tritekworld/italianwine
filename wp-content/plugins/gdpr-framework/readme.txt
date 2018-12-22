=== The GDPR Framework By Data443 ===
Contributors: data443
Tags: gdpr, compliance, security, privacy, wordpress gdpr, eu privacy directive, eu cookie law, california privacy law, regulations, privacy law, law, data, general data protection regulation, gdpr law
Requires at least: 4.7
Tested up to: 5.0.1
Requires PHP: 5.6.33
Stable tag: trunk
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.en.html

Easy to use tools to help make your website GDPR-compliant. Fully documented, extendable and developer-friendly.  Extensions to enterprise GDPR compliance coming - full active development and QA team.  Free, friendly support!

== Description ==

This plugin is a service of [Data443.com](https://www.data443.com).

Data443 is a Data Security and Compliance company traded on the OTCMarkets as [LDSR](https://www.otcmarkets.com/stock/LDSR/overview).  We have been providing leading GDPR compliance products such as [ClassiDocs](https://www.data443.com/classidocs-home/), Blockchain privacy, and enterprise cloud eDiscovery tools.

The GDPR regulation is a large and complex law.  Each member country is to ratify it into its own legilsation and language.  This makes it cumbersome to manage - but rest asssured - we have a full [Site Owners Guide](https://www.data443.com/wordpress-site-owners-guide-to-gdpr/) to help you learn and understand some of your requirements.

This product gives a simple and elegant interface to handle Data Subject Access Requests (DSARs).  In a few clicks, you can:

### Features
&#9745; Enable DSAR on one page - allow even those without an account to automatically view, export and delete their personal data;
&#9745; Configure the plugin to delete or anonymize personal data automatically or send a notification and allow admins to do it manually;
&#9745; Track, manage and withdraw consent;
&#9745; Generate a GDPR-compatible Privacy Policy template for your site;
&#9745; Use a helpful installation wizard to get you started quickly;
&#9745; Report on related data items within your WordPress installation;
&#9745; Significantly reduce your staff time efforts dealing with DSARs;
&#9745; Enable your larger organization to summarize and consolidate DSAR work;
&#9745; Report to management on DSAR status, volume and data requirements;


&#9745; We provide this fully documented;
&#9745; We are developer-friendly. Everything can be extended, every feature and template can be overridden.
&#9745; Cookie solution
&#9745; Integration with ClassiDocs

## IMPORTANT
Please disable (or otherwise remove) caching capabilities from the plugin pages - as these are very dynamic and based on use interaction.

## Disclaimer
Using The GDPR Framework does NOT guarantee compliance to GDPR. This plugin gives you general information and tools, but is NOT meant to serve as complete compliance package. Compliance to GDPR is risk-based ongoing process that involves your whole business. Codelight is not eligible for any claim or action based on any information or functionality provided by this plugin.

### Documentation
Full documentation: [The WordPress Site Owner's Guide to GDPR](https://www.data443.com/wordpress-site-owners-guide-to-gdpr/)
For developers: [Developer Docs](https://www.data443.com/wordpress-gdpr-framework-developer-docs/)
For users: [Knowledge Base](https://www.data443.com/wordpress-gdpr-framework-knowledge-base/)

### Plugin support:
The GDPR Framework currently works with the following plugins
&#9745; Contact Form 7 & Contact Form Flamingo
&#9745; Gravity Forms - [Download the GDPR add-on](https://wordpress.org/plugins/gdpr-for-gravity-forms/)
&#9745; Formidable Forms - [Download the GDPR add-on](https://wordpress.org/plugins/gdpr-for-formidable-forms/)
&#9745; WPML

== Installation ==

= Download and Activation =

1. Upload the plugin files to the /wp-content/plugins, or install the plugin through the WordPress plugins screen directly.
2. Activate the plugin through the ‘Plugins’ screen in WordPress.
3. ‘The GDPR Framework’ will be managed at Tool>Privacy tab.
4. The page 'Privacy Tools' will be created after Setup Wizard. This page displays the form where visitors can submit their request.

= Setup Guide =

Steps to add consent with contact form 7 are as follow:
1. First, need to create consent "Tool > Consent > Add consent type" .
2. Then note down the slug for example the slug is "contact_acceptance"
3. Then go to the contact form 7 click on Acceptance button a pop-up will get open.
4. Add the name of that acceptance same as that of slug i.e "contact_acceptance".
5. Insert the tag then save the form then it will be embedded with contact form 7.

== Frequently Asked Questions ==
= Help, the identification emails are not sent! =
The GDPR Framework uses the exact same mechanism for sending emails as the rest of your WordPress site. Please test if your site sends out emails at all using the Forgot Password form, for example.
If you get the forgot password email but not the identification email, please make a post in the support forums.

= Help, the link in the identification email doesn't work! =
Are you using SendGrid or another email delivery service? This might corrupt the link in the email.
In case you're using Sendgrid, make sure to turn off "click tracking". Otherwise, please post in the support forum!

= Help, the Privacy Tools page acts weirdly or always displays the "link expired" message! =
Check if you're using any caching plugins. If yes, then make sure the Privacy Tools page is excluded.
Also check if a server side cache is enabled by your web host.

= How is this plugin different from the tools in WordPress v4.9.6? =
WordPress 4.9.6 provides tools to allow administrators to manually handle GDPR requests. However, the GDPR framework allows visitors to automatically download and export data to reduce administrative work load.
In addition to that, we provide tools to manage and track custom consent types and also a privacy policy generator.
We are also planning to add other important privacy-related features missing from WordPress core over time.

== Changelog ==
= 1.0.22 (12/19/2018) =
* SD-172 : Update feature for accept and decline button.
* SD-171 : Custom url for Privacy policy.
* SD-173 : Comment checkbox ignored in WordPress 5.0.

= 1.0.21 (12/05/2018) =
* GDPRF‌-60: Update links in support tag in plugin
* SD‌-138: Resolve header popup issue
* SD‌-144: The pop-up banner is always visible on the bottom
* SD‌-145: There is a bug with your Flamingo integration, here’s the fix
* SD-144: The pop-up banner is always visible on the bottom
* SD‌-146: Compatibility with Gutenberg plugin
* SD‌-150: Compatibility to PHP 7.2 
* SD‌-151: error in download plugin
* SD‌-155: Query Monitor analysis

= 1.0.20 (11/19/2018) =
* SD-133: Cookie Consent first time user popup
* Fixed couple of entries
* Make sperate tab for popup settings
* Inhance popup feature

= 1.0.19 (11/05/2018) =
* GDPRF-44: Change random function to updated library
* GDPRF-48 & GDPRF-55: Updated documentation
* GDPRF-56 & SD-115: Add new Knowledge Base link to Details tab
* SD-121: How to handle the "No" to cookies consent?
* SD-123: informal German translation added
* SD-127: fixed Danish translation issue

= 1.0.18 (10/23/2018) =
* GDPRF‌-47: Classidocs Integration
* GDPRF‌-15: Add compatability with avada Fusion Builder
* GDPRF‌-16: Fix JS conflict when select2 is already loaded
* SD‌-97: Checkbox conditional checked at admin
* SD‌-61 & SD‌-112: Fixed translation problems
* SD‌-104: Consent Tracking and Data Request Help
* SD‌-108 and SD‌-114: Added contact 7 form instructions to Installation tab
* SD‌-114: Add setup guid to read me file

= 1.0.17 (10/1/2018) =
* SD-95: Remove undeclare variable issue
* SD-96: Remove issue with Error with PHP 7.2.1
* SD-98,SD-99 & SD-100: Remove Latest Update Conflict.
* SD-94 & SD-102: Fixed French Translation in .po

= 1.0.16 (9/26/2018) =
* SD-88: Add “Learn More” the all PO And POT langauges Files
* SD-89: Remove Issue of redeclare function with DIVI theme
* GDPRF-1: Make comments privacy policy checkbox optional
* GDPRF-2: Figure out whether or not logs should be exportable / deletable
* GDPRF-5: Add fields for changing email sender name and email
* GDPRF-9: Consider displaying consent log in data export
* GDPRF-23: Altering text for consent types
* GDPRF-29: "gdpr_cookie_consent" consent for cookie popup tracking
* GDPRF-33: Save user logs
* GDPRF-37: Add dutch to supported languages
* Manageable popup from backend

= 1.0.15 (9/11/2018) =
* SD:50: Fix issue with consent 
* SD-80,SD-81 & SD-82: Fix Compatablity issue Contact form 7
* Add languages for text and buttons on "cookie acceptance" popup
* Editable cookie acceptance Popup from general tab
* SD-83: Fix issue with decline button translation
* GDPRF-50: Changes "Taiwan" from "Taiwan, Province of China"
* SD-62: Fix translation text issues.
* GDPRF-43: Made the Privacy Policy checkbox optional
* GDPRF-49: Change Menu Name to "The GDPR Framework By Data443"

= 1.0.14 (8/29/2018) =
* Make Cookie Popup Optional

= 1.0.13 (8/29/2018) =
* Proper update - Upload failure on previous promo

= 1.0.12 (8/27/2018) =
* GDPRF-27: Change comment consent text
* GDPRF-36: Add english (canada) to supported languages
* GDPRF-46: Change checkbox comment
* GDPRF-31: Added "cookie acceptance" pop up
* GDPRF-8: Recaptcha Removed
* GDPRF-24: Make default consent translatable

= 1.0.11 (8/2018) = 
Numerous backlog bug fixes including:
* GDPRF-6: Comments checkbox reported to disappear with WPML active
* GDPRF-22: Can’t save on Consent tab
* GDPRF-12: Treat upper- and lowercase chars in visitor email addresses equally
* GDPRF-25: Captcha on privacy tools page
* GDPRF-26: Privacy Tools Delete text change
* GDPRF-17: Add locations outside of US and EU
* GDPRF-11: Ensure + symbol works in email addresses
* GDPRF-13: Privacy Policy: replace "[TODO]" with something that's not a shortcode format
* GDPRF-39: Confirm "delete my data" when button is pushed
* GDPRF-40: Can't leave any comments with GDPR activated
* GDPRF-4: Add Polylang compatibility
* GDPRF-34: Validate functionality with most current WP version

= 1.0.10 =
* Fix fatal error caused by Flamingo integration

= 1.0.9 =
* Add support for Contact Form 7 Flamingo
* Remove nested the_content filter in the consent area editor to avoid potential conflicts with various plugins (Thanks Gary McPherson!)
* Fix some missing translation strings (Thanks trueqap!)
* Additional minor tweaks
* Update Italian translation (Thanks Rienzi Comunica!)

= 1.0.8 =
* Disable Privacy Tools page if not set via admin (fixes infinite redirect issue)
* Add additional admin notification if Privacy Tools page is not set
* Additional minor tweaks

= 1.0.7 =
* Update translation pot file
* Add partial Greek translations (Thanks @webace-creative-studio)

= 1.0.6 =
* Fix administrative roles not being able to comment via admin interface
* Fix trashed or spam comments not being deleted
* Minor usability tweaks everywhere
* Fix PHP5.6 not properly saving custom consent (Thanks @paulnewson!)
* Fix CF7 always showing as enabled in wizard
* In Tools > Privacy > Data Subjects, add the display of given consents
* Add warning about Sendgrid compatibility in the installer
* Fix issue with installer wizard not properly saving export action
* Add notice in case the settings are not properly configured
* Added Bulgarian translation (thanks Zankov Group!)
* Added partial Italian translation (thanks Matteo Bruno!)

= 1.0.5 =
* Fix installing consent tables and roles properly
* Add Spanish translations (Thanks @elarequi!)
* Add partial German translations (Thanks @knodderdachs!)
* Lower required PHP version to 5.6.0
* Re-add container alias for DataSubjectManager
* Fix for installer giving the option to add links to footer for unsupported themes
* Fix PHP notice in WPML module

= 1.0.4 =
* Fix translations, for real this time
* Add French translations (Thanks @datagitateur!)
* Fix PHP warning if WPML is activated
* Add filter around $headers array for all outgoing emails sent via this plugin

= 1.0.3 =
* Change text domain to 'gdpr-framework' to avoid conflict with other plugins
* Add Portuguese translation (Thanks @kativiti!)
* Add partial Estonian translation

= 1.0.2 =
* Fix T&C and Privacy Policy URLs on registration and comments forms
* Add basic styling and separate stylesheet for Privacy Tools page
* Allow disabling styles for Privacy Tools page via admin
* Add confirmation notice on deleting data via front-end Privacy Tools
* Change strings with 'gdpr-admin' domain back to 'gdpr'. Add context to all admin strings.

= 1.0.1 =
* Fix PHP notice on Privacy Tools frontend page if logged in as admin

= 1.0.0 =
* Initial release
