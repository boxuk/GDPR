<?php

/**
 * This file is used to markup the cookie preferences window.
 *
 *
 * @link       https://trewknowledge.com
 * @since      1.0.0
 *
 * @package    GDPR
 * @subpackage public/partials
 */
?>

<div class="gdpr gdpr-privacy-preferences">
	<div class="gdpr-wrapper">
		<form method="post" class="gdpr-privacy-preferences-frm">
			<input type="hidden" name="action" value="gdpr_update_privacy_preferences">
			<?php wp_nonce_field( 'gdpr-update-privacy-preferences', 'update-privacy-preferences-nonce' ); ?>
			<header>
				<div class="gdpr-box-title">
					<h3><?php esc_html_e( 'Privacy Preference Center', 'gdpr' ); ?></h3>
					<span class="gdpr-close"></span>
				</div>
			</header>
			<div class="gdpr-mobile-menu">
				<button type="button"><?php esc_html_e( 'Options', 'gdpr' ); ?></button>
			</div>
			<div class="gdpr-content">
				<div class="gdpr-tabs">
					<ul class="">
						<li><button type="button" class="gdpr-tab-button gdpr-active" data-target="gdpr-consent-management"><?php esc_html_e( 'Consent Management', 'gdpr' ); ?></button></li>
						<?php reset( $args['tabs'] ); ?>
						<?php if ( ! empty( $args['tabs'] ) ) : ?>
							<li><button type="button" class="gdpr-tab-button gdpr-cookie-settings" data-target="<?php echo esc_attr( key( $args['tabs'] ) ); ?>"><?php esc_html_e( 'Cookie Settings', 'gdpr' ); ?></button>
								<ul class="gdpr-subtabs">
									<?php
									foreach ( $args['tabs'] as $key => $modal_tab ) {
										if ( ( isset( $modal_tab['cookies_used'] ) && empty( $modal_tab['cookies_used'] ) ) && ( isset( $modal_tab['hosts'] ) && empty( $modal_tab['hosts'] ) ) ) {
											continue;
										}
										echo '<li><button type="button" data-target="' . esc_attr( $key ) . '" ' . '>' . esc_html( $modal_tab['name'] ) . '</button></li>';
									}
									?>
								</ul>
							</li>
						<?php endif ?>
					</ul>
					<ul class="gdpr-policies">
						<?php if ( ! empty( $args['consent_types'] ) ) : ?>
							<?php foreach ( $args['consent_types'] as $consent_key => $content_type ) : ?>
								<?php
								if ( ! $consent_type['policy-page'] ) {
									continue;
								}
								?>
								<li><a href="<?php echo esc_url( get_permalink( $consent_type['policy-page'] ) ); ?>" target="_blank"><?php echo esc_html( $consent_type['name'] ); ?></a></li>
							<?php endforeach; ?>
						<?php endif; ?>
					</ul>
				</div>
				<div class="gdpr-tab-content">
					<div class="gdpr-consent-management gdpr-active">
						<header>
							<h4><?php esc_html_e( 'Consent Management', 'gdpr' ); ?></h4>
						</header>
						<div class="gdpr-info">
							<p><?php echo nl2br( esc_html( $args['cookie_privacy_excerpt'] ) ); ?></p>
							<?php if ( ! empty( $args['consent_types'] ) ) : ?>
								<?php foreach ( $args['consent_types'] as $consent_key => $consent_type ) : ?>
									<div class="gdpr-cookies-used">
										<div class="gdpr-cookie-title">
											<p><?php echo esc_html( $consent_type['name'] ); ?></p>
											<?php if ( $consent_type['policy-page'] ) : ?>
												<span class="gdpr-always-active"><?php esc_html_e( 'Required', 'gdpr' ); ?></span>
												<input type="hidden" name="user_consents[]" value="<?php echo esc_attr( $consent_key ); ?>" checked style="display:none;">
											<?php else : ?>
												<label class="gdpr-switch">
													<input type="checkbox" name="user_consents[]" value="<?php echo esc_attr( $consent_key ); ?>" <?php echo ! empty( $args['user_consents'] ) ? checked( in_array( $consent_key, $args['user_consents'], true ), 1, false ) : 'checked'; ?>>
													<span class="gdpr-slider round"></span>
													<span class="gdpr-switch-indicator-on"><?php echo esc_html__( 'ON', 'gdpr' ); ?></span>
													<span class="gdpr-switch-indicator-off"><?php echo esc_html__( 'OFF', 'gdpr' ); ?></span>
												</label>
											<?php endif; ?>
										</div>
										<div class="gdpr-cookies">
											<span><?php echo wp_kses( $consent_type['description'], $args['allowed_html'] ); ?></span>
										</div>
									</div>
								<?php endforeach; ?>
							<?php endif ?>
						</div>
					</div>
					<?php $all_cookies = array(); ?>
					<?php foreach ( $args['tabs'] as $key => $modal_tab ) : ?>
						<div class="<?php echo esc_attr( $key ); ?>">
							<header>
								<h4><?php echo esc_html( $modal_tab['name'] ); ?></h4>
							</header><!-- /header -->
							<div class="gdpr-info">
								<p><?php echo nl2br( wp_kses_post( $modal_tab['how_we_use'] ) ); ?></p>
								<?php if ( isset( $modal_tab['cookies_used'] ) && $modal_tab['cookies_used'] ) : ?>
									<div class="gdpr-cookies-used">
										<div class="gdpr-cookie-title">
											<p><?php esc_html_e( 'Cookies Used', 'gdpr' ); ?></p>
											<?php
											$site_cookies             = array();
											$enabled                  = ( 'off' === $modal_tab['status'] ) ? false : true;
											$cookies_used             = explode( ',', $modal_tab['cookies_used'] );
											$args['approved_cookies'] = isset( $gdpr_cookies_array['allowed_cookies'] ) ? json_decode( sanitize_text_field( wp_unslash( $gdpr_cookies_array['allowed_cookies'] ) ) ) : array();
											foreach ( $cookies_used as $cookie ) {
												$site_cookies[] = trim( $cookie );
												$all_cookies[]  = trim( $cookie );
												if ( ! empty( $args['approved_cookies'] ) && isset( $gdpr_cookies_array['gdpr']['privacy_bar'] ) ) {
													if ( in_array( trim( $cookie ), $args['approved_cookies'], true ) ) {
														$enabled = true;
													} else {
														$enabled = false;
													}
												}
											}
											?>
											<?php if ( 'required' === $modal_tab['status'] ) : ?>
												<span class="gdpr-always-active"><?php esc_html_e( 'Required', 'gdpr' ); ?></span>
												<input type="hidden" name="approved_cookies[]" value="<?php echo esc_attr( wp_json_encode( $site_cookies ) ); ?>">
											<?php else : ?>
												<label class="gdpr-switch">
													<input type="checkbox" class="gdpr-cookie-category" data-category="<?php echo esc_attr( $key ); ?>" name="approved_cookies[]" value="<?php echo esc_attr( wp_json_encode( $site_cookies ) ); ?>" <?php checked( $enabled, true ); ?>>
													<span class="gdpr-slider round"></span>
													<span class="gdpr-switch-indicator-on"><?php echo esc_html__( 'ON', 'gdpr' ); ?></span>
													<span class="gdpr-switch-indicator-off"><?php echo esc_html__( 'OFF', 'gdpr' ); ?></span>
												</label>
											<?php endif; ?>
										</div>
										<div class="gdpr-cookies">
											<span><?php echo wp_kses_post( $modal_tab['cookies_used'] ); ?></span>
										</div>
									</div>
								<?php endif ?>
								<?php if ( isset( $modal_tab['hosts'] ) && ! empty( $modal_tab['hosts'] ) ) : ?>
									<?php foreach ( $modal_tab['hosts'] as $host_key => $host ) : ?>
										<div class="gdpr-cookies-used">
											<div class="gdpr-cookie-title">
												<p><?php echo esc_html( $host_key ); ?></p>
												<a href="<?php echo esc_url( $host['optout'] ); ?>" target="_blank" class="gdpr-button"><?php esc_html_e( 'Opt Out', 'gdpr' ); ?></a>
											</div>
											<div class="gdpr-cookies">
												<span><?php echo esc_html( $host['cookies_used'] ); ?></span>
											</div>
										</div>
									<?php endforeach ?>
								<?php endif ?>
							</div>
						</div>
					<?php endforeach; ?>
				</div>
				<input type="hidden" name="all_cookies" value="<?php echo esc_attr( wp_json_encode( $all_cookies ) ); ?>">
			</div>
			<footer>
				<input type="submit" value="<?php esc_attr_e( 'Save Preferences', 'gdpr' ); ?>">
			</footer>
		</form>
	</div>
</div>