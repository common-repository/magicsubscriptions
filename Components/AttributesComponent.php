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



require_once plugin_dir_path( MAGICSUBSCRIPTIONS_FILE ) . "Utilities/Logging.php";
require_once plugin_dir_path( MAGICSUBSCRIPTIONS_FILE ) . "Client.php";



class AttributesComponent
{
	/** @var Client */
	private $client;



	function __construct( Client $client )
	{
		$this->client = $client;

		add_action( "admin_init", function()
		{
			if( !is_admin() )
			{
				return;
			}

			wp_enqueue_script( "MagicSubscriptions_AttributesBlock", plugin_dir_url( MAGICSUBSCRIPTIONS_FILE ) . "Admin/AttributeBlocks.js", array( "wp-blocks", "wp-element", "wp-hooks" ), MAGICSUBSCRIPTIONS_VERSION );
		} );

		add_filter( "render_block", array( $this, "Render" ), 200, 2 );
	}



	/**
	 * @param string $block_content
	 * @param array $block
	 */
	function Render( $block_content, $block )
	{
		if( is_admin() )
		{
			return $block_content;
		}

		$attributes = $block[ "attrs" ][ "MagicSubscriptionsAttributes" ];

		if( !$attributes )
		{
			return $block_content;
		}

		switch( $attributes[ "visibleInState" ] )
		{
			default:
				return $block_content;

			case "connected":
				return $this->client->IsRegistered() ? $block_content : null;

			case "not-connected":
				return $this->client->IsRegistered() ? null : $block_content;
		}
	}
}
