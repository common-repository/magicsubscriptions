<?php
/**
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



defined( "ABSPATH" ) or die();



require_once plugin_dir_path( MAGICSUBSCRIPTIONS_FILE ) . "Utilities/Logging.php";
require_once plugin_dir_path( MAGICSUBSCRIPTIONS_FILE ) . "Client.php";



class ElementorComponent
{
	/** @var Client */
	private $client;



	function __construct( Client $client )
	{
		$this->client = $client;

		if( !did_action( "elementor/loaded" ) )
		{
			return;
		}


		add_action( "elementor/element/after_section_start", function( $element, $section_id, $args )
		{
			if( $section_id === "section_advanced" && $element->get_name() === "section" )
			{
				$element->add_control(
					"MagicSubscriptions_VisibleInState",
					[
						"type" => "choose",
						"label" => "Magic Subscriptions - visible when:",
						"default" => "always",
						"toggle" => false,
						"show_label" => true,
						"label_block" => true,
						"options" => [
							"always" => [
								"title" => "Always",
								"icon" => "fas fa-check-double"
							],
							"connected" => [
								"title" => "Connected",
								"icon" => "fas fa-check"
							],
							"not-connected" => [
								"title" => "Not connected",
								"icon" => "fas fa-times"
							]
						]
					]
				);
			}
		}, 10, 3 );



		add_action( "elementor/frontend/section/before_render", array( $this, "BeforeRender" ), 10, 1 );
		add_action( "elementor/frontend/section/after_render", array( $this, "AfterRender" ), 10, 1 );
	}



	function BeforeRender( $element )
	{
		$settings = $element->get_settings();
		if( isset( $settings[ "MagicSubscriptions_VisibleInState" ] ) )
		{
			switch( $settings[ "MagicSubscriptions_VisibleInState" ] )
			{
				default:
					break;

				case "connected":
				case "not-connected":
					ob_start();
					break;
			}
		}
	}



	function AfterRender( $element )
	{
		$settings = $element->get_settings();
		if( isset( $settings[ "MagicSubscriptions_VisibleInState" ] ) )
		{
			$show = true;

			switch( $settings[ "MagicSubscriptions_VisibleInState" ] )
			{
				default:
					break;

				case "connected":
					$this->ShowIf( $this->client->IsRegistered() );
					break;

				case "not-connected":
					$this->HideIf( $this->client->IsRegistered() );
					break;
			}
		}
	}



	private function ShowIf( $condition )
	{
		$this->HideIf( !$condition );
	}



	private function HideIf( $condition )
	{
		if( $condition )
		{
			ob_get_contents();
			ob_get_clean();
		}
	}
}
