<?php
/**
 * GDPR User Delete
 *
 * @package Gdpr
 */

declare( strict_types=1 );

/**
 * Class Gdpr_User_Delete
 *
 * @package Gdpr
 */
class Gdpr_User_Delete {

	private const PEAKE_GDPR_ADMIN_MSG = 'peake_gdpr_admin_msg';

	/**
	 * A basic redirect to handle, but completely bypass the GDPR Plugin user delete process.
	 */
	public function delete_user() {
		wp_safe_redirect(
			esc_url_raw(
				add_query_arg(
					[
						self::PEAKE_GDPR_ADMIN_MSG => true,
					],
					wp_get_referer() . '#delete'
				)
			)
		);
		exit;
	}

	/**
	 * A basic error message to inform the Admin that deleting users is not currently possible.
	 */
	public function display_message() {
		$peake_gdpr_admin_msg = filter_input( INPUT_GET, self::PEAKE_GDPR_ADMIN_MSG, FILTER_SANITIZE_STRING );

		if ( null === $peake_gdpr_admin_msg ) {
			return;
		}

		echo '<div class="notice error is-dismissible">
			<p>Deletion of users is not currently implemented.</p>
		</div>';
	}
}
