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



function FormatMoney( $value )
{
	return number_format( $value, 2, '.', ',' );
}



function GetPlansMetal( $config )
{
	$themeTokens = explode( " ", $config->theme );
	if( count( $themeTokens ) > 7 )
	{
		return $themeTokens[ 7 ];
	}

	return "Nickel";
}



function GetShopMetal( $config )
{
	$themeTokens = explode( " ", $config->theme );
	if( count( $themeTokens ) > 8 )
	{
		return $themeTokens[ 8 ];
	}

	if( count( $themeTokens ) > 7 )
	{
		return $themeTokens[ 7 ];
	}

	return "Nickel";
}



function sanitize_jwt( $jwt )
{
	$jwt_tokens = explode( ".", $jwt );
	if( count( $jwt_tokens ) != 3 )
	{
		return null;
	}

	$header = json_decode( base64_decode( $jwt_tokens[ 0 ] ) );
	$content = json_decode( base64_decode( $jwt_tokens[ 1 ] ) );
	$signature = base64_decode( $jwt_tokens[ 2 ] );

	if( !$header || !$content || !$signature )
	{
		return null;
	}

	if( !$content->sub )
	{
		return null;
	}

	if( $content->iss != "MagicSubscriptions" )
	{
		return null;
	}

	if( $content->aud != "MagicSubscriptions.WordPress" )
	{
		return null;
	}

	if( $content->exp < time() )
	{
		return null;
	}

	if( sanitize_key( $content->sub ) != $content->sub )
	{
		return null;
	}

	debug_log( "sanitize_jwt - alg: " . $header->alg . ", typ: " . $header->typ . ", sub: " . $content->sub . ", exp: " . gmstrftime( "%c", $content->exp ) . ", iss: " . $content->iss . ", aud: " . $content->aud );

	return $jwt;
}



function GetContrastColour( $colour )
{
	// TODO: Assuming it's a hex colour for now...
	// Using https://stackoverflow.com/questions/3942878/how-to-decide-font-color-in-white-or-black-depending-on-background-color

	list( $r, $g, $b ) = sscanf( $colour, "#%02x%02x%02x" );
	if( $r * 0.299 + $g * 0.587 + $b * 0.114 > 186 )
	{
		return "rgba(13, 13, 13)";
	}
	else
	{
		return "rgb(248, 248, 248)";
	}
}



function GetRgb( $colour )
{
	// TODO: Assuming it's a hex colour for now...
	list( $r, $g, $b ) = sscanf( $colour, "#%02x%02x%02x" );
	return "$r,$g,$b";
}

