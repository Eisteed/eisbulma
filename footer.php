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

?>
</div>
</main>
<footer class="footer has-background-primary-95" style="border-top:1px solid var(--bulma-primary);">
	<div class="container">
		<div class="columns is-multiline" style="border-bottom: 1px solid #dee2e6;">
			<div class="column is-3 mb-5">
				<article class="media" style="max-width:10rem;"> <?php the_custom_logo(); ?></article>
				<div>
					<a class="mr-3 is-inline-block" href="#">
						<span class="icon has-text-secondary">
							<svg height="200" width="200" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
								<path d="M232 128a104.16 104.16 0 0 1-91.55 103.26a4 4 0 0 1-4.45-4V152h24a8 8 0 0 0 8-8.53a8.17 8.17 0 0 0-8.25-7.47H136v-24a16 16 0 0 1 16-16h16a8 8 0 0 0 8-8.53a8.17 8.17 0 0 0-8.27-7.47H152a32 32 0 0 0-32 32v24H96a8 8 0 0 0-8 8.53a8.17 8.17 0 0 0 8.27 7.47H120v75.28a4 4 0 0 1-4.44 4a104.15 104.15 0 0 1-91.49-107.19c2-54 45.74-97.9 99.78-100A104.12 104.12 0 0 1 232 128Z" fill="currentColor" />
							</svg>
						</span>
					</a>
					<a class="mr-3 is-inline-block" href="#">
						<span class="icon has-text-secondary">
							<svg height="200" width="200" viewBox="0 0 256 256" xmlns="http://www.w3.org/2000/svg">
								<path d="M176 24H80a56.06 56.06 0 0 0-56 56v96a56.06 56.06 0 0 0 56 56h96a56.06 56.06 0 0 0 56-56V80a56.06 56.06 0 0 0-56-56Zm-48 152a48 48 0 1 1 48-48a48.05 48.05 0 0 1-48 48Zm60-96a12 12 0 1 1 12-12a12 12 0 0 1-12 12Zm-28 48a32 32 0 1 1-32-32a32 32 0 0 1 32 32Z" fill="currentColor" />
							</svg>
						</span>
					</a>

				</div>
			</div>
			<div class="column is-9">
				<div class="columns is-multiline">
					<div class="column is-6 is-3-desktop mb-5">
						<h4 class="is-size-4 has-text-weight-bold mb-4">La societé</h4>
						<ul>
							<li class="mb-2"><a href="<?php echo get_site_url(); ?>/lentreprise-dr-wolz/">A propos</a></li>
							<li class="mb-2"><a href="<?php echo get_site_url(); ?>/blog">Blog</a></li>
							<li><a href="<?php echo get_site_url(); ?>/contact">Contact</a></li>
						</ul>
					</div>
					<div class="column is-6 is-3-desktop mb-5">
						<h4 class="is-size-4 has-text-weight-bold mb-4">Boutique</h4>
						<ul>
							<li class="mb-2"><a href="<?php echo get_site_url(); ?>/boutique">Nos produits</a></li>
							<li class="mb-2"><a href="<?php echo get_site_url(); ?>/mon-compte">Mon compte</a></li>
							<li class=""><a href="<?php echo get_site_url(); ?>/mon-compte">S'inscrire</a></li>
						</ul>
					</div>
					<div class="column is-6 is-3-desktop mb-5">
						<h4 class="is-size-4 has-text-weight-bold mb-4">Legal</h4>
						<ul>
							<li class="mb-2"><a href="<?php echo get_site_url(); ?>/cgv">CGV</a></li>
							<li class="mb-2"><a href="<?php echo get_site_url(); ?>/mentions-legales">Mentions Legales</a></li>
							<li><a href="<?php echo get_site_url(); ?>/protection-des-donnees">Protection des données</a></li>

						</ul>
					</div>
					<div class="column is-6 is-3-desktop mb-5">
						<h4 class="is-size-4 has-text-weight-bold mb-4">Contact</h4>
						<ul>
							<li class="mb-2"><a href="mailto:info@wolz.fr">info@wolz.fr</a></li>
							<li class="mb-2"><a href="tel:0033450339642">04 50 33 96 42</a></li>
							<li><a href="https://maps.app.goo.gl/bAuc1TgTDUm32F7g6">19 rue de l'annexion <br>74000 ANNECY<br> FRANCE</a></li>

						</ul>
					</div>
				</div>

			</div>
		</div>
		<div class="pb-3 has-text-centered is-size-6">
			<p class="p-2">Tout droits reservés © Wolz France <?php echo date('Y'); ?></p>
			<p> <a href="https://biscor.nu/">Web Design by Biscor.nu</a></p>
		</div>
	</div>
</footer>

</div>

<?php wp_footer(); ?>
</body>

</html>