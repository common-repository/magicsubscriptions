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



class ConnectButtonComponent
{
	/** @var Client */
	private $client;



	function __construct( Client $client )
	{
		$this->client = $client;

		function_log();



		register_block_type( "magic-subscriptions/connect", array( "render_callback" => array( $this, "Render" ) ) );


		add_shortcode( "MagicSubscriptions_Connect", array( $this, "Render" ) );
	}



	function __destruct()
	{
		function_log();
	}



	function Render()
	{
		if( !$this->client->IsActive() )
		{
			return null;
		}

		if( $this->client->IsRegistered() )
		{
			return null;
		}

		ob_start(); ?>
        <div class='magic-subs ms-registered'>
            <div class='ms-card'>
                <form method='post' action='<?= $this->client->ApiUrl( "/WordPress/Connect" ) ?>'>
                    <input type='hidden' name='token' value='<?= $this->client->Token() ?>'>
                    <button type='submit' class='ms-raised-button ms-button ms-colour-primary pmd-ripple-effect'>
                        <span class='ms-button-wrapper'>Connect</span>
                    </button>
                </form>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}
}
