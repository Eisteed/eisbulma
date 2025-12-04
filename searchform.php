<?php

/**
 * Search form
 *
 * @package eisbulma
 */

?>


<form role="search" method="get" id="searchform" class="search-form mr-auto ml-auto" action="<?php echo esc_url(home_url('/')); ?>" >
	<?php $form_id = wp_rand(100, 9999); ?>
	<div class="field has-addons" >
		<div class="control has-icons-right has-icons-left">
			<input type="text" class="input is-large" value="<?php the_search_query(); ?>" name="s" id="<?php echo esc_attr('s' . $form_id); ?>" placeholder="<?php echo esc_attr_x('Search &hellip;', 'placeholder', 'eisbulma'); ?>" />
			<button type="submit" class="icon is-small is-left" style="pointer-events:all"><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="#000000" viewBox="0 0 256 256">
					<path d="M229.66,218.34l-50.07-50.06a88.11,88.11,0,1,0-11.31,11.31l50.06,50.07a8,8,0,0,0,11.32-11.32ZM40,112a72,72,0,1,1,72,72A72.08,72.08,0,0,1,40,112Z"></path>
				</svg>
			</button>
		</div>
	</div>
</form>