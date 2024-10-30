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



class SummaryWidget extends WP_Widget
{
	/** @var SummaryComponent */
	private static $summaryComponent;



	public static function RegisterComponent( SummaryComponent $summaryComponent )
	{
		function_log();
		SummaryWidget::$summaryComponent = $summaryComponent;
	}



	function __construct()
	{
		function_log();
		parent::__construct(
			'magicsubscriptions-summary',
			'Magic Subs (Summary)',
			array( 'description' => 'Magic Subscriptions Summary' )
		);
	}



	// Creating widget front-end

	public function widget( $args, $instance )
	{
		function_log();
		echo $args[ "before_widget" ];
		echo SummaryWidget::$summaryComponent->Render();
		echo $args[ "after_widget" ];
	}

}