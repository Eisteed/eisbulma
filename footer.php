<?php

/**
 * The template for displaying the footer
 *
 * Contains the closing of the #content div and all content after.
 *
 * @link https://developer.wordpress.org/themes/basics/template-files/#template-partials
 *
 * @package eisbulma
 */

function eisbulma_get_contact_data()
{
	return array(
		'phone'   => get_theme_mod('eisbulma_contact_phone'),
		'email'   => get_theme_mod('eisbulma_contact_email'),
		'address' => get_theme_mod('eisbulma_contact_address'),
	);
}
$contact = eisbulma_get_contact_data();
?>
</div>
</main>
<footer class="footer has-background-primary-95" style="border-top:1px solid var(--bulma-primary);">
	<div class="container">
		<div class="columns is-multiline" style="border-bottom: 1px solid var(--bulma-light-gray);">
			<div class="column is-3 mb-5">
				<article class="media" style="max-width:10rem;"> <?php the_custom_logo(); ?></article>
				<div>
					<?php
					$facebook  = get_theme_mod('eisbulma_facebook_link');
					$instagram = get_theme_mod('eisbulma_instagram_link');
					$tiktok    = get_theme_mod('eisbulma_tiktok_link');
					$x         = get_theme_mod('eisbulma_x_link');
					?>

					<?php if ($facebook) : ?>
						<a class="is-inline-block" href="<?php echo esc_url($facebook); ?>" target="_blank" rel="noopener">
							<span class="icon has-text-secondary">
								<svg height="200" width="200" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
									<path d="M232 128a104.16 104.16 0 0 1-91.55 103.26a4 4 0 0 1-4.45-4V152h24a8 8 0 0 0 8-8.53a8.17 8.17 0 0 0-8.25-7.47H136v-24a16 16 0 0 1 16-16h16a8 8 0 0 0 8-8.53a8.17 8.17 0 0 0-8.27-7.47H152a32 32 0 0 0-32 32v24H96a8 8 0 0 0-8 8.53a8.17 8.17 0 0 0 8.27 7.47H120v75.28a4 4 0 0 1-4.44 4a104.15 104.15 0 0 1-91.49-107.19c2-54 45.74-97.9 99.78-100A104.12 104.12 0 0 1 232 128Z" fill="currentColor" />
								</svg>
							</span>
						</a>
					<?php endif; ?>
					<?php if ($instagram) : ?>
						<a class="is-inline-block" href="<?php echo esc_url($instagram); ?>" target="_blank" rel="noopener">
							<span class="icon has-text-secondary">
								<svg height="200" width="200" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
									<path d="M176 24H80a56.06 56.06 0 0 0-56 56v96a56.06 56.06 0 0 0 56 56h96a56.06 56.06 0 0 0 56-56V80a56.06 56.06 0 0 0-56-56Zm-48 152a48 48 0 1 1 48-48a48.05 48.05 0 0 1-48 48Zm60-96a12 12 0 1 1 12-12a12 12 0 0 1-12 12Zm-28 48a32 32 0 1 1-32-32a32 32 0 0 1 32 32Z" fill="currentColor" />
								</svg>
							</span>
						</a>
					<?php endif; ?>
				</div>
			</div>
			<div class="column is-9">
				<div class="columns is-multiline has-text-centered-mobile">
					<?php
					wp_nav_menu(array(
						'theme_location' => 'footer_menu',   // un seul menu
						'container'      => false,
						'menu_class'     => '',
						'items_wrap'     => '%3$s',         // important pour laisser le walker gérer le markup
						'depth'          => 2,              // parents + enfants
						'walker'         => new BulmaWP_Footer_Walker(),
					));
					?>
					<div id="contact" class="column">
						<h4 class="is-size-4 has-text-weight-bold mb-4">Contact</h4>
						<ul>
							<?php
							if (! empty($contact['email'])) {
								
								echo '<li class="mb-2"><a href="mailto:' . esc_attr($contact['email']) . '">' . esc_html($contact['email']) . '</a></li>';
							}
							if (! empty($contact['phone'])) {
								echo '<li class="mb-2"><a href="tel:' . esc_html(str_replace([' '], '', $contact['phone'])) . '">' . esc_html(str_replace(['+33'], '', $contact['phone'])) . '</a></li>';
							}
							if (! empty($contact['address'])) {
								$address = trim($contact['address']);
								$map_url = 'https://www.google.com/maps/search/?api=1&query=' . rawurlencode($address);

								echo '<li class="mb-2"><a href="' . esc_url($map_url) . '" target="_blank" rel="noopener">'
									. wp_kses_post(nl2br($address))
									. '</a></li>';
							}
							?>
						</ul>
					</div>
				</div>
			</div>
		</div>
		<div class="pb-3 has-text-centered is-size-6">
			<p class="p-2"><?php echo esc_html(get_bloginfo('name')); ?> © <?php echo date('Y'); ?> - <?php esc_html_e('All rights reserved', 'eisbulma'); ?></p>
			<p> <a href="https://biscor.nu/">Web Design by Biscor.nu</a></p>
		</div>
	</div>
</footer>

</div>

<?php wp_footer(); ?>
</body>

</html>