<?php
/**
 * Plugin Name: Magic Subscriptions
 * Description: Integrate MagicSubscriptions into WordPress
 * Version: 0.19.13
 * Author: Ronnie Barker
 * Author URI: https://magicsubscriptions.com/
 * License: GPL3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 *
 * MagicSubscriptions WordPress Plugin
 * Copyright (C) 2019 Ronnie Barker
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 *
 **/

namespace MagicSubscriptions;



defined( 'ABSPATH' ) or die();
define( 'MAGICSUBSCRIPTIONS_FILE', __FILE__ );
define( 'MAGICSUBSCRIPTIONS_VERSION', '0.19.13' );



require_once plugin_dir_path( __FILE__ ) . "Utilities/FontAwesome.php";
require_once plugin_dir_path( __FILE__ ) . "Utilities/Logging.php";
require_once plugin_dir_path( __FILE__ ) . "Utilities/Utilities.php";
require_once plugin_dir_path( __FILE__ ) . "Utilities/ScriptLoader.php";

require_once plugin_dir_path( __FILE__ ) . "Client.php";

require_once plugin_dir_path( __FILE__ ) . "Components/ConnectButtonComponent.php";
require_once plugin_dir_path( __FILE__ ) . "Widgets/ConnectButtonWidget.php";

require_once plugin_dir_path( __FILE__ ) . "Components/SummaryComponent.php";
require_once plugin_dir_path( __FILE__ ) . "Widgets/SummaryWidget.php";
require_once plugin_dir_path( __FILE__ ) . "Widgets/WideSummaryWidget.php";

require_once plugin_dir_path( __FILE__ ) . "Components/PlansComponent.php";
require_once plugin_dir_path( __FILE__ ) . "Widgets/PlansWidget.php";

require_once plugin_dir_path( __FILE__ ) . "Components/ShopComponent.php";
require_once plugin_dir_path( __FILE__ ) . "Widgets/ShopWidget.php";

require_once plugin_dir_path( __FILE__ ) . "Components/AttributesComponent.php";
require_once plugin_dir_path( __FILE__ ) . "Components/ElementorComponent.php";



debug_log( "Loading - " . __FILE__ );



add_action( "init", function()
{
	action_log( "init" );


	wp_enqueue_script( "MagicSubscriptions_Blocks", plugin_dir_url( MAGICSUBSCRIPTIONS_FILE ) . "Admin/Blocks.js", array( "wp-element" ), MAGICSUBSCRIPTIONS_VERSION );


	$client = new Client();
	new ScriptLoader( $client );
	$connectButtonComponent = new ConnectButtonComponent( $client );
	ConnectButtonWidget::RegisterComponent( $connectButtonComponent );

	$summaryComponent = new SummaryComponent( $client );
	SummaryWidget::RegisterComponent( $summaryComponent );
	WideSummaryWidget::RegisterComponent( $summaryComponent );

	$plansComponent = new PlansComponent( $client );
	PlansWidget::RegisterComponent( $plansComponent );

	$shopComponent = new ShopComponent( $client );
	ShopWidget::RegisterComponent( $shopComponent );

	new AttributesComponent( $client );
	new ElementorComponent( $client );
} );



add_action( "widgets_init", function()
{
	action_log( "widgets_init" );
	register_widget( "MagicSubscriptions\\ConnectButtonWidget" );
	register_widget( "MagicSubscriptions\\SummaryWidget" );
	register_widget( "MagicSubscriptions\\WideSummaryWidget" );
	register_widget( "MagicSubscriptions\\PlansWidget" );
	register_widget( "MagicSubscriptions\\ShopWidget" );
} );



add_action( "admin_menu", function()
{
	action_log( "admin_menu" );
	add_options_page( "Magic Subscriptions", "Magic Subscriptions", 1, "Set up Magic Subscriptions integration", function()
	{
		include( "Admin/Options.php" );
	} );
} );



add_filter( "block_categories", function( $categories, $post )
{
	filter_log( "block_categories" );
	return array_merge(
		$categories,
		array(
			array(
				"slug" => "magic-subscriptions",
				"title" => "Magic Subscriptions",
			),
		)
	);
}, 10, 2 );
