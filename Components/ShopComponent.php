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



class ShopComponent
{
	/** @var Client */
	private $client;



	function __construct( Client $client )
	{
		$this->client = $client;

		function_log();


		register_block_type( "magic-subscriptions/shop", array( "render_callback" => array( $this, "Render" ) ) );

		add_shortcode( "MagicSubscriptions_Shop", array( $this, "Render" ) );

		add_action( "admin_post_magicsubscriptions_add_product", array( $this, "HandleAddProductAction" ) );
		add_action( "admin_post_nopriv_magicsubscriptions_add_product", array( $this, "HandleAddProductAction" ) );
	}



	function HandleAddProductAction()
	{
		$productId = $_REQUEST[ 'data' ];
		debug_log( "HandleAddProductAction : " . $productId );
		$this->client->AddProduct( $productId );
		wp_redirect( $_SERVER[ 'HTTP_REFERER' ] );
		exit();
	}



	function __destruct()
	{
		function_log();
	}



	function Render( $attributes )
	{
		$tag = isset( $attributes[ "tag" ] ) ? $attributes[ "tag" ] : "";

		if( $tag == null || $tag == "" )
		{
			$tag = "WordPress";
		}

		function_log();

		debug_log( "RENDER: " . $tag );

		if( !$this->client->IsActive() )
		{
			return null;
		}

		if( !$this->client->IsRegistered() )
		{
			return null;
		}

		$metal = GetShopMetal( $this->client->GetConfig() );
		$products = $this->client->RetrieveProducts( $tag );

		ob_start();
		if( !empty( $products ) )
		{
			?>
            <div class='magic-subs ms-products ms-metal-<?= $metal ?>'><?php
			foreach( $products as $product )
			{
				switch( $metal )
				{
					default:
						$this->Render_Nickel( $product );
						break;

					case "Platinum":
						$this->Render_Platinum( $product );
						break;
				}
				//$this->{"Render_" . $metal}( $product );
			}
			?></div><?php
		}
		return ob_get_clean();
	}



	private function Render_Nickel( $product )
	{
		?>
        <div class='ms-product'>
	        <?php
	        if( $product->tags )
	        {
		        ?>
		        <div class="ms-tags"><?php
			        foreach( $product->tags as $tag )
			        {
				        ?>
				        <span class="ms-tag"><?= $tag ?></span><?php
			        } ?>
		        </div>
		        <?php
	        } ?>
            <div class="ms-product-content">
                <div class='ms-image'>
                    <img src='<?= $this->client->PageUrl( "/Images/" . $product->imageId ) ?>'>
                </div>
                <span class='ms-title'><?= $product->name ?></span>
                <span class='ms-price'>£ <?= FormatMoney( $product->price ) ?></span>
                <div class="ms-actions">
                    <form method='post' action='<?= esc_url( admin_url( 'admin-post.php' ) ) ?>'>
                        <input type='hidden' name='action' value='magicsubscriptions_add_product'>
                        <input type='hidden' name='data' value='<?= $product->id ?>'>
                        <button type='submit' class='ms-raised-button ms-button ms-colour-primary pmd-ripple-effect'>
                            <span class='ms-button-wrapper'>Add</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
		<?php
		return;
	}



	private function Render_Platinum( $product )
	{
		?>
        <div class='ms-product'>
			<?php
			if( $product->tags )
			{
				?>
                <div class="ms-tags"><?php
					foreach( $product->tags as $tag )
					{
						?>
                        <span class="ms-tag"><?= $tag ?></span><?php
					} ?>
                </div>
			<?php
			} ?>
            <div class="ms-product-content">
                <span class='ms-title-over'><?= $product->name ?></span>
                <div class='ms-image'>
                    <img src='<?= $this->client->PageUrl( "/Images/" . $product->imageId ) ?>'><?php
					if( $product->description )
					{
						?>
                        <div class='ms-description-wrapper'>
                        <div class='ms-description'><?= str_replace( "\n", "<br/>", $product->description ) ?></div>
                        </div><?php
					} ?>
                </div>
                <span class='ms-title-under'><?= $product->name ?></span>
                <div class='ms-footer'>
		<span class='ms-price'>
			<span class='ms-currency'>£</span>
			<span class='ms-amount'><?= FormatMoney( $product->price ) ?></span>
		</span>
                    <form method='post' action='<?= esc_url( admin_url( 'admin-post.php' ) ) ?>'>
                        <input type='hidden' name='action' value='magicsubscriptions_add_product'>
                        <input type='hidden' name='data' value='<?= $product->id ?>'>
                        <button type='submit' class='ms-raised-button ms-button ms-colour-primary pmd-ripple-effect'>
                            <span class='ms-button-wrapper'>Add</span>
                        </button>
                    </form>
                </div>
            </div>
        </div>
		<?php
	}
}
