<?php

/**
 * Custom template tags for this theme
 *
 * @package eisbulma
 */

if (! function_exists('eisbulma_posted_on')) :
	/**
	 * Prints HTML with meta information for the current post-date/time.
	 */
	function eisbulma_posted_on()
	{
		$time_string = '<time class="entry-date published updated" datetime="%1$s">%2$s</time>';
		if (get_the_time('U') !== get_the_modified_time('U')) {
			$time_string = '<time class="updated" datetime="%3$s">%4$s</time>';
		}

		$time_string = sprintf(
			$time_string,
			esc_attr(get_the_date(DATE_W3C)),
			esc_html(get_the_date()),
			esc_attr(get_the_modified_date(DATE_W3C)),
			esc_html(get_the_modified_date())
		);

		$posted_on = sprintf(
			'%s',
			'<a href="' . esc_url(get_permalink()) . '" rel="bookmark">' . $time_string . '</a>'
		);

	echo '<span class="has-icons-left is-small">
				<span class="icon"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" viewBox="0 0 256 256"><path d="M128,24A104,104,0,1,0,232,128,104.11,104.11,0,0,0,128,24Zm0,192a88,88,0,1,1,88-88A88.1,88.1,0,0,1,128,216Zm64-88a8,8,0,0,1-8,8H128a8,8,0,0,1-8-8V72a8,8,0,0,1,16,0v48h48A8,8,0,0,1,192,128Z"></path></svg></span>
				</span>' . $posted_on . '</span>
		  </span>';
	}
endif;

if (! function_exists('eisbulma_posted_by')) :
	/**
	 * Prints HTML with meta information for the current author.
	 */
	function eisbulma_posted_by()
	{
		$byline = sprintf(
			'%s',
			'<span class="author vcard"><a class="url fn n" href="' . esc_url(get_author_posts_url(get_the_author_meta('ID'))) . '">' . esc_html(get_the_author()) . '</a></span>'
		);

		echo '<span class="byline"> ' . $byline . '</span>'; // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
	}
endif;


if (! function_exists('eisbulma_categories')) :

	function eisbulma_categories()
	{
		// Hide category and tag text for pages.
		if ('post' === get_post_type()) {
			/* translators: used between list items, there is a space after the comma */
			$categories_list = get_the_category_list(esc_html_x(', ', 'list item separator', 'eisbulma'));
			if ($categories_list) {
				printf('<span class="tag cat-links">%1$s</span>', $categories_list); // phpcs:ignore WordPress.Security.EscapeOutput
			}

			/* translators: used between list items, there is a space after the comma */
			$tags_list = get_the_tag_list('', esc_html_x(', ', 'list item separator', 'eisbulma'));
			if ($tags_list) {
				printf('<span class="tags-links">%1$s</span>', $tags_list); // phpcs:ignore WordPress.Security.EscapeOutput
			}
		}
	}
endif;

if (! function_exists('eisbulma_entry_footer')) :
	/**
	 * Prints HTML with meta information for the categories, tags and comments.
	 */
	function eisbulma_entry_footer()
	{

		edit_post_link(
			sprintf(
				wp_kses(
					/* translators: %s: Name of current post. Only visible to screen readers */
					__('Edit <span class="screen-reader-text">%s</span>', 'eisbulma'),
					array(
						'span' => array(
							'class' => array(),
						),
					)
				),
				get_the_title()
			),
			'<span class="edit-link">',
			'</span>'
		);
	}
endif;

if (! function_exists('eisbulma_post_thumbnail')) :
	/**
	 * Displays an optional post thumbnail.
	 *
	 * Wraps the post thumbnail in an anchor element on index views, or a div
	 * element when on single views.
	 */
	function eisbulma_post_thumbnail()
	{
		if (post_password_required() || is_attachment() || ! has_post_thumbnail()) {
			return;
		}

		if (is_singular()) :
?>

			<div class="post-thumbnail">
				<?php the_post_thumbnail('large', array('class' => 'aligncenter')); ?>
			</div><!-- .post-thumbnail -->

		<?php else : ?>

			<a class="post-thumbnail" href="<?php the_permalink(); ?>" aria-hidden="true" tabindex="-1">
				<?php
				the_post_thumbnail('large', array('class' => 'aligncenter'));
				?>
			</a>

<?php
		endif; // End is_singular().
	}
endif;
