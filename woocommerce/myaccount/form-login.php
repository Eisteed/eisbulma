<?php
/**
 * Login Form
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/form-login.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 9.9.0
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit; // Exit if accessed directly.
}

do_action( 'woocommerce_before_customer_login_form' );

$registration_enabled = 'yes' === get_option( 'woocommerce_enable_myaccount_registration' );
?>

<section class="section">
	<div class="container">
		<div class="columns is-centered">
			<div class="column <?php echo $registration_enabled ? 'is-10' : 'is-5'; ?>">

				<div class="columns is-multiline" id="customer_login">

					<div class="column <?php echo $registration_enabled ? 'is-6' : 'is-12'; ?>">

						<h2 class="title is-4"><?php esc_html_e( 'Login', 'woocommerce' ); ?></h2>

						<form class="woocommerce-form woocommerce-form-login login box" method="post" novalidate>
							<?php do_action( 'woocommerce_login_form_start' ); ?>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide field">
								<label class="label" for="username">
									<?php esc_html_e( 'Username or email address', 'woocommerce' ); ?>
									<span class="required" aria-hidden="true">*</span>
									<span class="screen-reader-text">
										<?php esc_html_e( 'Required', 'woocommerce' ); ?>
									</span>
								</label>
								<div class="control">
									<input
										type="text"
										class="input woocommerce-Input woocommerce-Input--text input-text"
										name="username"
										id="username"
										autocomplete="username"
										value="<?php echo ( ! empty( $_POST['username'] ) && is_string( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" <?php // @codingStandardsIgnoreLine ?>
										required
										aria-required="true"
									/>
								</div>
							</p>

							<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide field">
								<label class="label" for="password">
									<?php esc_html_e( 'Password', 'woocommerce' ); ?>
									<span class="required" aria-hidden="true">*</span>
									<span class="screen-reader-text">
										<?php esc_html_e( 'Required', 'woocommerce' ); ?>
									</span>
								</label>
								<div class="control">
									<input
										class="input woocommerce-Input woocommerce-Input--text input-text"
										type="password"
										name="password"
										id="password"
										autocomplete="current-password"
										required
										aria-required="true"
									/>
								</div>
							</p>

							<?php do_action( 'woocommerce_login_form' ); ?>

							<div class="field is-flex is-justify-content-space-between is-align-items-center">
								<div class="control">
									<label class="checkbox woocommerce-form__label woocommerce-form__label-for-checkbox woocommerce-form-login__rememberme">
										<input
											class="woocommerce-form__input woocommerce-form__input-checkbox"
											name="rememberme"
											type="checkbox"
											id="rememberme"
											value="forever"
										/>
										<span><?php esc_html_e( 'Remember me', 'woocommerce' ); ?></span>
									</label>
								</div>

								<div class="control">
									<?php wp_nonce_field( 'woocommerce-login', 'woocommerce-login-nonce' ); ?>
									<button
										type="submit"
										class="button is-primary woocommerce-button woocommerce-form-login__submit<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?>"
										name="login"
										value="<?php esc_attr_e( 'Log in', 'woocommerce' ); ?>"
									>
										<?php esc_html_e( 'Log in', 'woocommerce' ); ?>
									</button>
								</div>
							</div>

							<p class="woocommerce-LostPassword lost_password mt-3">
								<a href="<?php echo esc_url( wp_lostpassword_url() ); ?>">
									<?php esc_html_e( 'Lost your password?', 'woocommerce' ); ?>
								</a>
							</p>

							<?php do_action( 'woocommerce_login_form_end' ); ?>
						</form>

					</div><!-- .column (login) -->

					<?php if ( $registration_enabled ) : ?>

						<div class="column is-6">

							<h2 class="title is-4"><?php esc_html_e( 'Register', 'woocommerce' ); ?></h2>

							<form method="post" class="woocommerce-form woocommerce-form-register register box" <?php do_action( 'woocommerce_register_form_tag' ); ?>>

								<?php do_action( 'woocommerce_register_form_start' ); ?>

								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_username' ) ) : ?>
									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide field">
										<label class="label" for="reg_username">
											<?php esc_html_e( 'Username', 'woocommerce' ); ?>
											<span class="required" aria-hidden="true">*</span>
											<span class="screen-reader-text">
												<?php esc_html_e( 'Required', 'woocommerce' ); ?>
											</span>
										</label>
										<div class="control">
											<input
												type="text"
												class="input woocommerce-Input woocommerce-Input--text input-text"
												name="username"
												id="reg_username"
												autocomplete="username"
												value="<?php echo ( ! empty( $_POST['username'] ) ) ? esc_attr( wp_unslash( $_POST['username'] ) ) : ''; ?>" <?php // @codingStandardsIgnoreLine ?>
												required
												aria-required="true"
											/>
										</div>
									</p>
								<?php endif; ?>

								<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide field">
									<label class="label" for="reg_email">
										<?php esc_html_e( 'Email address', 'woocommerce' ); ?>
										<span class="required" aria-hidden="true">*</span>
										<span class="screen-reader-text">
											<?php esc_html_e( 'Required', 'woocommerce' ); ?>
										</span>
									</label>
									<div class="control">
										<input
											type="email"
											class="input woocommerce-Input woocommerce-Input--text input-text"
											name="email"
											id="reg_email"
											autocomplete="email"
											value="<?php echo ( ! empty( $_POST['email'] ) ) ? esc_attr( wp_unslash( $_POST['email'] ) ) : ''; ?>" <?php // @codingStandardsIgnoreLine ?>
											required
											aria-required="true"
										/>
									</div>
								</p>

								<?php if ( 'no' === get_option( 'woocommerce_registration_generate_password' ) ) : ?>

									<p class="woocommerce-form-row woocommerce-form-row--wide form-row form-row-wide field">
										<label class="label" for="reg_password">
											<?php esc_html_e( 'Password', 'woocommerce' ); ?>
											<span class="required" aria-hidden="true">*</span>
											<span class="screen-reader-text">
												<?php esc_html_e( 'Required', 'woocommerce' ); ?>
											</span>
										</label>
										<div class="control">
											<input
												type="password"
												class="input woocommerce-Input woocommerce-Input--text input-text"
												name="password"
												id="reg_password"
												autocomplete="new-password"
												required
												aria-required="true"
											/>
										</div>
									</p>

								<?php else : ?>

									<p>
										<?php esc_html_e( 'A link to set a new password will be sent to your email address.', 'woocommerce' ); ?>
									</p>

								<?php endif; ?>

								<?php do_action( 'woocommerce_register_form' ); ?>

								<p class="woocommerce-form-row form-row field is-grouped is-justify-content-flex-end">
									<?php wp_nonce_field( 'woocommerce-register', 'woocommerce-register-nonce' ); ?>
									<span class="control">
										<button
											type="submit"
											class="button is-primary woocommerce-Button woocommerce-button button<?php echo esc_attr( wc_wp_theme_get_element_class_name( 'button' ) ? ' ' . wc_wp_theme_get_element_class_name( 'button' ) : '' ); ?> woocommerce-form-register__submit"
											name="register"
											value="<?php esc_attr_e( 'Register', 'woocommerce' ); ?>"
										>
											<?php esc_html_e( 'Register', 'woocommerce' ); ?>
										</button>
									</span>
								</p>

								<?php do_action( 'woocommerce_register_form_end' ); ?>

							</form>

						</div><!-- .column (register) -->

					<?php endif; ?>

				</div><!-- .columns#customer_login -->

			</div>
		</div>
	</div>
</section>

<?php
do_action( 'woocommerce_after_customer_login_form' );
