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
require_once plugin_dir_path( MAGICSUBSCRIPTIONS_FILE ) . "Components/SummaryComponent.php";



use WP_Widget;



class ShopWidget extends WP_Widget
{
	/** @var ShopComponent */
	private static $shopComponent;



	public static function RegisterComponent( ShopComponent $shopComponent )
	{
		function_log();
		ShopWidget::$shopComponent = $shopComponent;
	}



	function __construct()
	{
		function_log();
		parent::__construct(
			'magicsubscriptions-shop',
			'Magic Subs (Shop)',
			array( 'description' => 'Magic Subscriptions products list' )
		);
	}



	public function form( $instance )
	{
		$tag = !empty( $instance[ "tag" ] ) ? $instance[ "tag" ] : "WordPress";
		?>
        <p>
            <label for="<?= esc_attr( $this->get_field_id( "tag" ) ); ?>">Tag:</label>
            <input
                    class="widefat"
                    id="<?= esc_attr( $this->get_field_id( "tag" ) ); ?>"
                    name="<?= esc_attr( $this->get_field_name( "tag" ) ); ?>"
                    type="text"
                    value="<?= esc_attr( $tag ); ?>">
        </p>
		<?php
	}



	public function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance[ "tag" ] = ( !empty( $new_instance[ "tag" ] ) ) ? strip_tags( $new_instance[ "tag" ] ) : "WordPress";
		return $instance;
	}



	public function widget( $args, $instance )
	{
		function_log();
		echo $args[ "before_widget" ];
		echo ShopWidget::$shopComponent->Render( $instance );
		echo $args[ "after_widget" ];
	}
}