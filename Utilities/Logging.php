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



function action_log( $actionName )
{
//	error_log( "========== - ACTION - " . $actionName . " - ==========" );
}



function filter_log( $actionName )
{
	//error_log( "========== - FILTER - " . $actionName . " - ==========" );
}



function debug_log( $message )
{
	//error_log( "XXXXXXXXXX - " . $message );
}



function function_log( $message = "" )
{
	//$frame = debug_backtrace( true )[ 1 ];
	//error_log( "========== - FUNCTION - " . $frame[ "class" ] . "::" . $frame[ "function" ] . " " . $message . " - ==========" );
}
