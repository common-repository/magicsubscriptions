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



class PlansComponent
{
	/** @var Client */
	private $client;



	function __construct( Client $client )
	{
		$this->client = $client;

		function_log();

		register_block_type( "magic-subscriptions/plans", array(
				"attributes" =>
					[
						"code" =>
							[
								"default" => "",
								"type" => "string"
							]
					],
				"render_callback" => array( $this, "Render" ) )
		);

		add_action( "enqueue_block_editor_assets", function()
		{
			wp_enqueue_script( "MagicSubscriptions_PlansBlock", plugin_dir_url( MAGICSUBSCRIPTIONS_FILE ) . "Admin/PlanBlocks.js", array( "wp-blocks", "wp-editor", "wp-element" ), MAGICSUBSCRIPTIONS_VERSION );
		} );

		add_shortcode( "MagicSubscriptions_Plans", array( $this, "Render" ) );
	}



	function __destruct()
	{
		function_log();
	}



	function Render( $attributes )
	{
		function_log();

		if( $this->client->IsRegistered() )
		{
			return null;
		}

		$code = isset( $attributes[ "code" ] ) ? $attributes[ "code" ] : "";
		$metal = GetPlansMetal( $this->client->GetConfig() );
		$plans = $this->client->RetrievePlans( $code );

		$metal = apply_filters( "MagicSubscriptions_OverrideRenderPlan", $metal, $code );

		if( empty( $plans ) )
		{
			//TODO: Empty plans block
			return null;
		}

		ob_start();
		?>
        <div class='magic-subs ms-plans ms-metal-<?= $metal ?>'><?php
			extract( get_object_vars( $this ) );

			foreach( $plans as $plan )
			{
				switch( $metal )
				{
					default:
						do_action( "MagicSubscriptions_RenderPlan", $metal, $code, $plan, $this->client->ApiUrl( "/WordPress/Register" ), $this->client->Token() );
						break;

					case "Nickel":
						$this->RenderNickel( $code, $plan );
						break;

					case "Platinum":
						$this->RenderPlatinum( $code, $plan );
						break;
				}
			}
			?>
        </div>
		<?php
		return ob_get_clean();
	}



	private function RenderNickel( $code, $plan )
	{
		?>
        <form class='ms-card ms-sub-type' method='post' action='<?= $this->client->ApiUrl( "/WordPress/Register" ) ?>'>
            <input type='hidden' name='token' value='<?= $this->client->Token() ?>'>
            <input type='hidden' name='code' value='<?= $code ?>'>
            <input type='hidden' name='plan' value='<?= $plan->code ?>'>
            <h3><?= $plan->name ?></h3>
            <p><?= $plan->title ?></p>
            <h4><?= $plan->headline ?></h4>
            <button type='submit' class='ms-plan-button ms-raised-button ms-button ms-colour-cta pmd-ripple-effect'>
                <span class='ms-button-wrapper'><?= $plan->actionLabel ?></span>
            </button>
        </form>
		<?php
	}



	private function RenderPlatinum( $code, $plan )
	{
		?>
        <div class="ms-sub-type">
            <form class='ms-card' method='post' action='<?= $this->client->ApiUrl( "/WordPress/Register" ) ?>'>
                <input type='hidden' name='token' value='<?= $this->client->Token() ?>'>
                <input type='hidden' name='code' value='<?= $code ?>'>
                <input type='hidden' name='plan' value='<?= $plan->code ?>'>
                <span class='ms-name'><?= $plan->name ?></span>
                <p class='ms-header'>
                <span class='ms-price'>
                    <span class='ms-currency'>£</span>
                    <span class='ms-amount'> <?= FormatMoney( $plan->dayOnePrice ) ?></span>
                </span>
                    <br>
					<?php
					if( $plan->isGiftPlan )
					{
						?><span class='ms-price-label'>for <?= $plan->giftPlanMonths ?> months</span><?php
					}
					else if( !$plan->isGiftPlan && $plan->dayOnePrice != $plan->recurringPrice )
					{
						?><span class='ms-price-label'>then £<?= FormatMoney( $plan->recurringPrice ) ?> per month</span><?php
					}
					else if( !$plan->isGiftPlan && $plan->dayOnePrice == $plan->recurringPrice )
					{
						?><span class='ms-price-label'>per month</span><?php
					} ?>
                    <br>
                    <br>
                    <span class='ms-title'><?= $plan->title ?></span>
                </p>
                <button type='submit' class='ms-plan-button ms-raised-button ms-button ms-colour-cta pmd-ripple-effect'>
                    <span class='ms-button-wrapper'><?= $plan->actionLabel ?></span>
                </button>
                <ul class='ms-headline-list'>
					<?php
					foreach( explode( ",", $plan->headline ) as $item )
					{ ?>
                        <li>&nbsp;<?= trim( $item ) ?>&nbsp;</li><?php
					} ?>
                </ul>
            </form>
        </div>
		<?php
	}
}
