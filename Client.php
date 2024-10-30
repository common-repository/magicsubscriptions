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
require_once plugin_dir_path( MAGICSUBSCRIPTIONS_FILE ) . "IConfig.php";



class Client
{
	/** @var string|null */
	private $siteUrl = null;

	/** @var string|null */
	private $token = null;

	/** @var IConfig */
	private $config;

	/** @var bool */
	private $isRegistered;

	/** @var \ArrayObject */
	private $orders;

	/** @var \ArrayObject */
	private $subscriptions;

	/** @var \ArrayObject */
	private $cart;



	function __construct()
	{
		function_log();
		$this->siteUrl = get_option( "MagicSubscriptions_Url" );
		$this->Initialise();
	}



	private function Initialise()
	{
		if( $this->IsActive() )
		{
			$responseObject = $this->RetrieveDetailsJson();

			$this->config = $responseObject->config;

			if( $this->isRegistered = $responseObject->registered )
			{
				$this->orders = $responseObject->orders;
				$this->subscriptions = $responseObject->subscriptions;
				$this->cart = $responseObject->cart;
			}
		}
	}



	/** @return IConfig */
	public function GetConfig()
	{
		return $this->config;
	}



	/** @return bool */
	public function IsRegistered()
	{
		return $this->isRegistered;
	}



	public function GetOrders()
	{
		return $this->orders;
	}



	public function GetSubscriptions()
	{
		return $this->subscriptions;
	}



	public function GetCart()
	{
		return $this->cart;
	}



	public function PageUrl( $page = "" )
	{
		return $this->siteUrl . $page;
	}



	public function ApiUrl( $action = "" )
	{
		// Here for debug and future extension for separation of site and API
		return $this->siteUrl . "/api" . $action;
	}



	public function Token()
	{
		return $this->token;
	}



	public function IsActive()
	{
		return $this->siteUrl != null;
	}



	private function RetrieveDetailsJson()
	{
		function_log();

		if( isset( $_POST[ "MagicSubscriptions_ClearToken" ] ) )
		{
			unset( $_COOKIE[ 'ms_wp_token' ] );
		}
		else if( isset( $_COOKIE[ "ms_wp_token" ] ) )
		{
			$this->token = sanitize_jwt( $_COOKIE[ "ms_wp_token" ] );
		}

        $response = wp_remote_post( $this->ApiUrl() . "/WordPress/Initialise", array( "body" => array( "token" => $this->token ) ) );

		if( is_wp_error( $response ) )
		{
			error_log( "Call to Initialise failed: " . print_r( $response, true ) );
			//TODO: Report error somewhere...
			return null;
		}

		$json = json_decode( wp_remote_retrieve_body( $response ) );
		setcookie( "ms_wp_token", $this->token = $json->token, 0, "/" );

		return $json;
	}



	public function RetrievePlans( $code )
	{
		function_log();

		$response = wp_remote_get( $this->ApiUrl() . "/Plans/Available/" . $code );

		if( is_wp_error( $response ) )
		{
			error_log( "Client : Failed to call Plans/Available" . print_r( $response, true ) );
			//TODO: Report error somewhere...
			return null;
		}

		return json_decode( wp_remote_retrieve_body( $response ) );
	}



	public function RetrieveProducts( $tag )
	{
		function_log();

		$response = wp_remote_get( $this->ApiUrl() . "/Products/Tagged/" . $tag );

		if( is_wp_error( $response ) )
		{
			error_log( "Client : Failed to call Products/Tagged" . print_r( $response, true ) );
			//TODO: Report error somewhere...
			return null;
		}

		return json_decode( wp_remote_retrieve_body( $response ) );
	}



	public function AddProduct( $productId )
	{
		function_log();

		$response = wp_remote_post( $this->ApiUrl() . "/WordPress/AddProduct", array( "body" => array( "token" => $this->token, "productId" => $productId ) ) );

		if( is_wp_error( $response ) )
		{
			error_log( "Client : Failed to call WordPress/AddProduct" . print_r( $response, true ) );
			//TODO: Report error somewhere...
			return null;
		}
	}



	public function RemoveProduct( $productId )
	{
		function_log();

		$response = wp_remote_post( $this->ApiUrl() . "/WordPress/RemoveProduct", array( "body" => array( "token" => $this->token, "productId" => $productId ) ) );

		if( is_wp_error( $response ) )
		{
			error_log( "Client : Failed to call WordPress/RemoveProduct" . print_r( $response, true ) );
			//TODO: Report error somewhere...
			return null;
		}
	}
}