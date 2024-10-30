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



class PlansWidget extends WP_Widget
{
	/** @var PlansComponent */
	private static $plansComponent;



	public static function RegisterComponent( PlansComponent $plansComponent )
	{
		function_log();
		PlansWidget::$plansComponent = $plansComponent;
	}



	function __construct()
	{
		function_log();
		parent::__construct(
			'magicsubscriptions-plans',
			'Magic Subs (Plans)',
			array( 'description' => 'Magic Subscriptions subscription plans list' )
		);
	}



	public function form( $instance )
	{
		$code = !empty( $instance[ "code" ] ) ? $instance[ "code" ] : "";
		?>
        <p>
            <label for="<?= esc_attr( $this->get_field_id( "code" ) ); ?>">Code:</label>
            <input
                    class="widefat"
                    id="<?= esc_attr( $this->get_field_id( "code" ) ); ?>"
                    name="<?= esc_attr( $this->get_field_name( "code" ) ); ?>"
                    type="text"
                    value="<?= esc_attr( $code ); ?>">
        </p>
		<?php
	}



	public function update( $new_instance, $old_instance )
	{
		$instance = array();
		$instance[ "code" ] = ( !empty( $new_instance[ "code" ] ) ) ? strip_tags( $new_instance[ "code" ] ) : "";

		return $instance;
	}



	public function widget( $args, $instance )
	{
		function_log();
		echo $args[ "before_widget" ];
		echo PlansWidget::$plansComponent->Render( $instance );
		echo $args[ "after_widget" ];
	}
}
