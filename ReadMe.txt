=== Magic Subscriptions ===
Contributors: ronniebarker
Requires at least: 5.2
Tested up to: 5.4.1
Stable tag: 0.19.13
Requires PHP: 7.1
License: GPLv3
License URI: https://www.gnu.org/licenses/gpl-3.0.html

== Description ==

This WordPress plugin is intended as a connector to [Magic Subscriptions](https://magicsubscriptions.com) subscription box management system. It does require an account with [Magic Subscriptions](https://magicsubscriptions.com) in order to be useful.

The intention is to keep users on the primary WordPress site as much as possible, only redirecting off to MagicSubscriptions portal when necessary; and bringing them back. To facilitate this, there are connectors that pull subscription and order information in to the plugin to display on the primary WordPress site.

For any technical support please contact: [wp-support@magicsubsriptions.com](mailto:wp-support@magicsubsriptions.com)


== Components ==

All blocks are shown under a **Magic Subscriptions** folder in the block editor.


## Plans
**Name**: PlansComponent
**Block name**: Plans
**Block identifier**: magic-subscriptions/plans
**Shortcode**: [ MagicSubscriptions_Plans code='`code`' ]
**Widget**: Magic Subs (Plans)

The **Plans** component displays the subscription plans currently available within the **LandingPage** `code`

The `code` is optional and, if missing, the `Default` *LandingPage* will be used



## ConnectButton
**Name**: ConnectButtonComponent
**Block name**: Connect
**Block identifier**: c-subscriptions/connect
**Shortcode**: [ MagicSubscriptions_Connect ]
**Widget**: Magic Subs (Connect)

The **ConnectButton** component displays a button to link (via a cookie) to a MagicSubscriptions login

If a valid connected cookie exists then this component is empty.



## Summary
**Name**: SummaryComponent
**Block name**: Summary
**Block identifier**: magic-subscriptions/summary
**Shortcode**: [ MagicSubscriptions_Summary ]
**Widget**: Magic Subs (Summary)

The **Summary** component shows a 'shopping cart' style summary of the *connected* customer's active subscriptions, pending orders and 'open-box' added items.

If the system is not connected to a login then this component is empty



## Wide Summary
**Name**: WideSummaryComponent
**Block name**: Wide Summary
**Block identifier**: magic-subscriptions/widesummary
**Shortcode**: [ MagicSubscriptions_WideSummary ]
**Widget**: Magic Subs (Wide Summary)

The **Wide Summary** component is a landscape version of the **Summary** component



## Shop
**Name**: ShopComponent
**Block name**: Shop
**Block identifier**: magic-subscriptions/shop
**Shortcode**: [ MagicSubscriptions_Shop tag='`tag`' ]
**Widget**: Magic Subs (Shop)

The **Shop** component shows a product list that can be **add**ed to the next order that is scheduled.

The `tag` is required (or nothing will display) and relates to the tag set up in the products list in your Magic Subscriptions portal.



## Attributes
**Name**: AttributeComponent

The **Attributes** component adds a section to Gutenberg blocks supporting the following options:

#### Visible when:
* **Always** -> This is just the default (visibility not configured or controlled by the component.
* **When connected** -> Only show this block if the user has connected to their Magic Subscriptions account.
* **When NOT connected** -> Only show this block if the user has NOT connected to their Magic Subscriptions account.



## Elementor
**Name**: ElementorComponent

The **Elementor** component adds a visibility option to the *Advanced* tab on the *Edit Section* -> *Advanced* panel:

#### Magic Subscriptions - visible when:
* **Tick-Tick (Always)** -> This is just the default (visibility not configured or controlled by the component.
* **Tick (Connected)** -> Only show this block if the user has connected to their Magic Subscriptions account.
* **Cross (Not connected)** -> Only show this block if the user has NOT connected to their Magic Subscriptions account.



== Hooks ==

## MagicSubscriptions_OverrideRenderPlan (filter)
### Parameters
**$metal**: The current Metal as configured in **Magic Subscriptions**
**$code$: The **Landing Page* code being rendered
**<Return Value>: New *Metal* Value. Any unknown [non-stndard] *Metal* values will result in the `MagicSubscriptions_RenderPlan` action to be called to render the plan.

###Example:
`
add_filter( "MagicSubscriptions_OverrideRenderPlan", function( $metal, $code ) {
	return "MyNewMetalName";
}, 10, 2 );
`


## MagicSubscriptions_RenderPlan (action)
### Parameters
**$metal**: The **Metal** (theme) that is being rendered (as returned from the `MagicSubscriptions_OverrideRenderPlan` action)
**$code**: The **Landing Page* code being rendered
**$plan**: Object representing the plan (TODO: Document object properties)
**$action**: URL Action for POSTing a form to => hands control over to **Magic Subscriptions** (note the `$code`, `$plan->code` [named `plan`] and `$token` should be included as hidden form post elements)
**$token**: Security token to be POSTed to **Magic Subscriptions**

###Use:
This action is called for every plan being rendered within a section for an unknown [non-standard] *Metal* value.

###Example:
`
add_action( "MagicSubscriptions_RenderPlan", function( $metal, $code, $plan, $action, $token ) {
	?>
    <form class="plan" method='post' action='<?= $action ?>'>
        <input type='hidden' name='token' value='<?= $token ?>'>
        <input type='hidden' name='code' value='"<?= $code ?>'>
        <input type='hidden' name='plan' value='<?= $plan->code ?>'>
        <p><?= $plan->name ? $plan->name : $plan->scheduleName ?></p>
        <p><?= $plan->title ? $plan->title : $plan->scheduleTitle ?></p>
        <p><?= $plan->headline ? $plan->headline : $plan->scheduleHeadline ?></p>
        <p>£ <?= number_format( $plan->dayOnePrice, 2, '.', ',' ) ?></p>
        <p>£ <?= number_format( $plan->recurringPrice, 2, '.', ',' ) ?></p>
        <button type='submit'><?= $plan->actionLabel ?></button>
    </form>
	<?php
}, 10, 5 );
`


== Installation ==

1. Upload the plugin files to the `/wp-content/plugins/plugin-name` directory, or install the plugin through the WordPress plugins screen directly.
1. Activate the plugin through the 'Plugins' screen in WordPress
1. Use the Settings->Plugin Name screen to configure the MagicSubscriptions Portal URL
1. Use the MagicSubscriptions blocks, shortcodes and widgets



== Changelog ==

= 0.19.11 =
* Fixed bug with erroneous speech mark in code


= 0.19.10 =
* Fixed bug with hooks where having no override failed due to over-use of the same value


= 0.19.9 =
* Changed Custom Plan rendering hook to override 'Metal' to allow simpler CSS


= 0.19.8 =
* Added in hooks for Custom Plan rendering


= 0.19.7 =
* Fix ScriptLoader bug when MS->Config fails
* Remove ul padding on Platinum plan


= 0.19.6 =
* Different Metal for Plans and Shop

= 0.19.5 =
* Changed label in cart to "My Additions"

= 0.19.4 =
* Added support for tags that start with an asterisk to be displayed as chips on products (without the asterisk)

= 0.19.1 =
* Set PHP cookie path to '/'

= 0.19 =
* Added Widgets for the PlansComponent and ShopComponent.
* Fixed the registration of the WideSummaryWidget
* Fixed the width of the icon buttons (delete product on Shop)

= 0.18.1 =
* Fixed erroneous '"' in the token field when choosing a plan

= 0.18 =
* Added a *Magic Subscriptions* attribute section to ALL elements to control visibility Always, When connected and When NOT connected.
* Added a *Magic Subscriptions - visible when* option in the *Advanced* section configuration for *Elementor*

= 0.17.6 =
* Extracted FontAwesome SVGs to inline as the <i> elements crash when removed by React from the editor. Need to fix for long-term!!!

= 0.17.5 =
* Fixed missing width on Platinum->Product->Content

= 0.17.4 =
* Checked that the [FontAwesome](https://fontawesome.com/) [Free License](https://fontawesome.com/license/free) is GPL compatible.
* Added a License line to the FontAwesome CDN setup snippet
* Improved ReadMe.txt layout (I hope)


= 0.17.3 =
* Remove title from Connect button to be consistent with new summaries
* Remove transparency from cards by adding a white background behind


= 0.17.2 =
* Wide Summary


= 0.17 =
* Refactored output to use ob_start() ... ob_get_clean() blocks allowing templating within the PHP directly
* Tidied up classes and structure to separate each component and widget
* Separated functionality of "Displayed when not connected" and "Displayed when connected" items so that there is more control over where they can be used in the final page


= 0.16 =
* Added new [trial of] MagicSubscriptions sub-theming [Metal]; Old style = 'Nickel', New style = 'Platinum'
* Reading font configurations from config feed to be consistent with MagicSubscriptions platform (DRY)


= 0.14 =
* Added this ReadMe
