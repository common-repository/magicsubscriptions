<?php
/**
 * Font Awesome CDN Setup Webfont
 *
 * This will load Font Awesome from the Font Awesome Free or Pro CDN.
 */

/**
 * Source - https://fontawesome.com/how-to-use/customizing-wordpress/snippets/setup-cdn-svg
 * License - https://fontawesome.com/license/free (Icons: CC BY 4.0, Fonts: SIL OFL 1.1, Code: MIT License)
 */


if( !function_exists( 'fa_custom_setup_cdn_webfont' ) )
{
	function fa_custom_setup_cdn_webfont( $cdn_url = '', $integrity = null )
	{
		$matches = [];
		$match_result = preg_match( '|/([^/]+?)\.css$|', $cdn_url, $matches );
		$resource_handle_uniqueness = ( $match_result === 1 ) ? $matches[ 1 ] : md5( $cdn_url );
		$resource_handle = "font-awesome-cdn-webfont-$resource_handle_uniqueness";

		foreach( [ 'wp_enqueue_scripts', 'admin_enqueue_scripts', 'login_enqueue_scripts' ] as $action )
		{
			add_action(
				$action,
				function() use ( $cdn_url, $resource_handle )
				{
					wp_enqueue_style( $resource_handle, $cdn_url, [], null );
				}
			);
		}

		if( $integrity )
		{
			add_filter(
				'style_loader_tag',
				function( $html, $handle ) use ( $resource_handle, $integrity )
				{
					if( in_array( $handle, [ $resource_handle ], true ) )
					{
						return preg_replace(
							'/\/>$/',
							'integrity="' . $integrity .
							'" crossorigin="anonymous" />',
							$html,
							1
						);
					}
					else
					{
						return $html;
					}
				},
				10,
				2
			);
		}
	}
}



fa_custom_setup_cdn_webfont( 'https://pro.fontawesome.com/releases/v5.10.0/css/all.css', 'sha384-AYmEC3Yw5cVb3ZcuHtOA93w35dYTsvhLPVnYs9eStHfGJvOvKxVfELGroGkvsg+p' );