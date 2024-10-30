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

	if( isset( $_POST[ "MagicSubscriptions_Url" ] ) )
	{
		$url = esc_url_raw( $_POST[ "MagicSubscriptions_Url" ] );
		update_option( "MagicSubscriptions_Url", $url );

		?>
        <div class="updated"><p><strong><?= translate( "Options saved." ) ?></strong></p></div><?php
	}
	else
	{
		$url = get_option( "MagicSubscriptions_url" );
		if( $url == null )
		{
			$url = "https://magicsubscriptions.gosubs.uk";
		}
	}

?>
<div class="wrap">
    <h2><?= translate( "Magic Subscriptions Options" ) ?></h2>
    <img class="magic-subs-logo" src="<?= plugins_url( "Logo.png", MAGICSUBSCRIPTIONS_FILE ) ?>">
    <hr/>
    <p>Register with <a href="https://www.magicsubscriptions.com">Magic Subscriptions</a> to get a Portal URL</p>
    <hr/>
    <form name="MagicSubscriptions_Options_Form" method="post" action="<?= str_replace( "%7E", "~", $_SERVER[ "REQUEST_URI" ] ) ?>">
        <h4><?= translate( "Integration Settings" ) ?></h4>
        <table class="form-table ms-options">
            <tr>
                <th><?= translate( "Portal URL" ) ?></th>
                <td>
                    <input class="ms-full" type="text" name="MagicSubscriptions_Url" value="<?= $url ?>">
                </td>
            </tr>
        </table>
        <hr/>
        <p class="submit">
            <input type="submit" name="Submit" class="button button-primary pmd-ripple-effect" value="<?= translate( "Save" ) ?>"/>
        </p>
    </form>
</div>