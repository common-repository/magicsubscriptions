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



defined( 'ABSPATH' ) or die();



require_once plugin_dir_path( __FILE__ ) . "Utilities.php";
require_once plugin_dir_path( __FILE__ ) . "Logging.php";

require_once plugin_dir_path( MAGICSUBSCRIPTIONS_FILE ) . "Client.php";



debug_log( "Loading - " . __FILE__ );



class ScriptLoader
{
	/** @var Client */
	private $client;



	function __construct( Client $client )
	{
		$this->client = $client;

		function_log();

		add_action( "wp_enqueue_scripts", function()
		{
			debug_log( "wp_enqueue_scripts ver=" . MAGICSUBSCRIPTIONS_VERSION );

			wp_enqueue_script( "jquery" );

			wp_register_script( "MagicSubscriptions_Scripts", plugins_url( "Scripts/Scripts.js", MAGICSUBSCRIPTIONS_FILE ), null, MAGICSUBSCRIPTIONS_VERSION );
			wp_add_inline_script( "MagicSubscriptions_Scripts", "var apiUrl = '" . $this->client->ApiUrl() . "';", "before" );
			wp_add_inline_script( "MagicSubscriptions_Scripts", "var token = '" . $this->client->Token() . "';", "before" );
			wp_enqueue_script( "MagicSubscriptions_Scripts" );

			wp_register_style( "MagicSubscriptions", plugins_url( "Styles/Styles.css", MAGICSUBSCRIPTIONS_FILE ), null, MAGICSUBSCRIPTIONS_VERSION );
			wp_add_inline_style( "MagicSubscriptions", $this->BuildCss() );
			wp_enqueue_style( "MagicSubscriptions" );

			wp_enqueue_style( "MagicSubscriptions_Metal_Nickel", plugins_url( "Styles/Nickel.css", MAGICSUBSCRIPTIONS_FILE ), null, MAGICSUBSCRIPTIONS_VERSION );
			wp_enqueue_style( "MagicSubscriptions_Metal_Platinum", plugins_url( "Styles/Platinum.css", MAGICSUBSCRIPTIONS_FILE ), null, MAGICSUBSCRIPTIONS_VERSION );

			wp_enqueue_style( "MagicSubscriptions_Ripple", plugins_url( "Ripple/Ripple.css", MAGICSUBSCRIPTIONS_FILE ) );
			wp_enqueue_style( "MagicSubscriptions_fonts", "https://use.typekit.net/tiw6esq.css" );
			wp_enqueue_script( "MagicSubscriptions_Ripple", plugins_url( "Ripple/Ripple.js", MAGICSUBSCRIPTIONS_FILE ) );
		} );



		add_action( "admin_enqueue_scripts", function()
		{
			wp_enqueue_style( "MagicSubscriptions_AdminStyles", plugins_url( "Admin/Styles.css", MAGICSUBSCRIPTIONS_FILE ), null, MAGICSUBSCRIPTIONS_VERSION );
		} );
	}



	function BuildCss()
	{
		function_log();

		$themeTokens = explode( " ", $this->client->GetConfig()->theme );

		$font = $themeTokens[ 1 ];

		$defaultFontConfiguration = array( "light" => 200, "normal" => 400, "bold" => 700, "spacing" => "normal" );
		$themeFontConfiguration = null;
		$fonts = $this->client->GetConfig()->fonts;
		if( $fonts )
		{
			foreach( $fonts as $fontConfiguration )
			{
				if( $fontConfiguration->code == null )
				{
					$defaultFontConfiguration = $fontConfiguration;
				}

				if( $fontConfiguration->code == $font )
				{
					$themeFontConfiguration = $fontConfiguration;
				}
			}
		}

		if( $themeFontConfiguration == null )
		{
			$themeFontConfiguration = $defaultFontConfiguration;
		}

		$fontCss = "--ms-font-family: " . $font . "; ";
		$weightCss = "--ms-font-weight-light: " . $themeFontConfiguration->light . "; --ms-font-weight-normal: " . $themeFontConfiguration->normal . "; --ms-font-weight-bold: " . $themeFontConfiguration->bold . "; ";
		$spacingCss = "--ms-font-spacing: " . $themeFontConfiguration->spacing . "; ";


		return ":root { "
			. $fontCss
			. $weightCss
			. $spacingCss
			. "--ms-theme-primary: " . $themeTokens[ 2 ] . "; "
			. "--ms-theme-primary-rgb: " . GetRgb( $themeTokens[ 2 ] ) . "; "
			. "--ms-theme-primary-contrast: " . GetContrastColour( $themeTokens[ 2 ] ) . "; "
			. "--ms-theme-accent: " . $themeTokens[ 3 ] . "; "
			. "--ms-theme-accent-rgb: " . GetRgb( $themeTokens[ 3 ] ) . "; "
			. "--ms-theme-accent-contrast: " . GetContrastColour( $themeTokens[ 3 ] ) . "; "
			. "--ms-theme-warn: " . $themeTokens[ 4 ] . "; "
			. "--ms-theme-warn-contrast: " . GetContrastColour( $themeTokens[ 4 ] ) . "; "
			. "--ms-theme-cta: " . $themeTokens[ 6 ] . "; "
			. "--ms-theme-cta-contrast: " . GetContrastColour( $themeTokens[ 6 ] ) . "; "
			. " }";
	}
}