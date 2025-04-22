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
			</div><!-- #container -->
		</div><!-- #content -->
</main>
<footer class="footer has-background-primary-20 is-flex-align-items-flex-end">
	<div class="container p-0">
		<div class="has-text-centered p-0">
			<div class="columns">
				<div class="column"> <a href="<?php echo get_site_url(); ?>">Accueil</a></div>
				<div class="column"> <a href="<?php echo get_site_url(); ?>/contact">Contact</a></div>
				<div class="column"> <a href="<?php echo get_site_url(); ?>/mentions-legales">Mentions Legales</a></div>
				<div class="column">
					<a href="https://biscor.nu/">Web Design Biscor.nu</a>
				</div>
			</div>
		</div>
	</div>
</footer>

<div id="backToTop" style="display: none;">
	<button class="button is-primary is-float-right"><i class="fas fa-arrow-up"> </i></button>
</div>

<script>
	
	const backToTopButton = document.getElementById("backToTop");

	window.onscroll = function() {
		if (document.body.scrollTop > 400 || document.documentElement.scrollTop > 400) {
			backToTopButton.style.display = "block";
		} else {
			backToTopButton.style.display = "none";
		}
	};

	// Smooth scroll back to the top when clicked
	backToTopButton.onclick = function() {
		window.scrollTo({
			top: 0,
			behavior: 'smooth'
		});
	};
</script>
<script src="https://unpkg.com/aos@next/dist/aos.js" defer></script>

<script>
  window.addEventListener("load", function () {
    AOS.init();
  });
</script>

<?php wp_footer(); ?>

</body>
</html>
