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



class SummaryComponent
{
	/** @var Client */
	private $client;



	function __construct( Client $client )
	{
		$this->client = $client;

		function_log();



		register_block_type( "magic-subscriptions/summary", array( "render_callback" => array( $this, "Render" ) ) );
		register_block_type( "magic-subscriptions/widesummary", array( "render_callback" => array( $this, "RenderWide" ) ) );

		add_shortcode( "MagicSubscriptions_Summary", array( $this, "Render" ) );
		add_shortcode( "MagicSubscriptions_WideSummary", array( $this, "RenderWide" ) );

		add_action( "admin_post_magicsubscriptions_remove_product", array( $this, "HandleRemoveProductAction" ) );
		add_action( "admin_post_nopriv_magicsubscriptions_remove_product", array( $this, "HandleRemoveProductAction" ) );
	}



	function HandleRemoveProductAction()
	{
		$productId = $_REQUEST[ 'data' ];
		debug_log( "HandleRemoveProductAction : " . $productId );
		$this->client->RemoveProduct( $productId );
		wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
		exit();
	}



	function __destruct()
	{
		function_log();
	}



	function Render()
	{
		return $this->DoRender( false );
	}



	function RenderWide()
	{
		return $this->DoRender( true );
	}



	private function DoRender( $wide )
	{
		if( !$this->client->IsActive() )
		{
			return null;
		}

		if( !$this->client->IsRegistered() )
		{
			return null;
		}

		$subscriptions = $this->client->GetSubscriptions();
		$orders = $this->client->GetOrders();
		$products = $this->client->GetCart();

		ob_start(); ?>
        <div class='magic-subs ms-registered<?= ( $wide ? " ms-wide" : " ms-tall" ) ?>'>
            <div class='ms-card'>
                <div class='ms-content'>
                    <div class='ms-subscriptions'>
                        <label>My Subscriptions</label><?php
						$this->RenderNoneIfEmptyList( $subscriptions ); ?>
                        <ul><?php
							foreach( $subscriptions as $sub )
							{
								?>
                                <li><span class='ms-name'><?= $sub->name ?></span> (<?= $sub->state ?>)</li><?php
							} ?>
                        </ul>
                    </div>

                    <div class='ms-orders'>
                        <label>My Orders</label><?php
						$this->RenderNoneIfEmptyList( $orders ); ?>
                        <ul><?php
							foreach( $orders as $order )
							{
								?>
                                <li><span class='ms-name'><?= $order->name ?></span> (shipping on <?= $order->shipDate ?>)
                                </li><?php
							} ?>
                        </ul>
                    </div>

                    <div class='ms-cart'>
                        <label>My Additions</label><?php
						$this->RenderNoneIfEmptyList( $products ); ?>
                        <ul class='ms-additions-list'><?php
							foreach( $products as $product )
							{
								?>
                                <li>
                                <form method='post' action='<?= esc_url( admin_url( 'admin-post.php' ) ) ?>'>
                                    <input type='hidden' name='action' value='magicsubscriptions_remove_product'>
                                    <input type='hidden' name='data' value='<?= $product->id ?>'>
                                    <button type='submit' class='ms-icon-button ms-colour-primary pmd-ripple-effect'>
                                        <i class='ms-button-wrapper fas fa-trash-alt'></i>
                                    </button>
                                    <span class='ms-name'><?= $product->name ?></span> (£<?= FormatMoney( $product->price ) ?>)
                                </form>
                                </li><?php
							} ?>
                        </ul>
                    </div>

                </div>

                <div class="ms-actions">
                    <a href='<?= $this->client->PageUrl( "/dashboard" ) ?>' class='ms-raised-button ms-button ms-colour-primary pmd-ripple-effect'>
                        <span class='ms-button-wrapper'>Dashboard</span>
                    </a>
                    <br/>
                    <form method='post'>
                        <input type='hidden' name='MagicSubscriptions_ClearToken' value=''>
                        <button type='submit' class='ms-flat-button ms-button pmd-ripple-effect'>
                            <span class='ms-button-wrapper'>Disconnect</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
		<?php
		return ob_get_clean();
	}



	private function RenderNoneIfEmptyList( $list )
	{
		if( count( $list ) < 1 )
		{
			?>
            <div class='ms-name'>- None -</div><?php
		}
	}
}
