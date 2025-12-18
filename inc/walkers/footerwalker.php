<?php
/**
 * Footer menu walker
 * Un seul menu WP avec items parents = colonnes,
 * items enfants = liens de listes.
 */

if ( ! class_exists( 'BulmaWP_Footer_Walker' ) ) {

	class BulmaWP_Footer_Walker extends Walker_Nav_Menu {

		// On ne génère PAS de <ul>/<li> automatiques via items_wrap
		// => utiliser 'items_wrap' => '%3$s' dans wp_nav_menu()

		public function start_lvl( &$output, $depth = 0, $args = array() ) {
			// Ouverture de la liste des enfants d’une colonne (enfants de depth 0)
			if ( 0 === $depth ) {
				$output .= "\n<ul>\n";
			}
		}

		public function end_lvl( &$output, $depth = 0, $args = array() ) {
			if ( 0 === $depth ) {
				$output .= "</ul>\n";
			}
		}

		public function start_el( &$output, $item, $depth = 0, $args = array(), $id = 0 ) {

			$title = apply_filters( 'the_title', $item->title, $item->ID );

			// Parent = colonne + titre
			if ( 0 === $depth ) {
				$output .= '<div class="column mb-5">';
				$output .= '<h4 class="is-size-4 has-text-weight-bold mb-4">' . esc_html( $title ) . '</h4>';
				return;
			}

			// Enfant = <li><a>
			if ( 1 === $depth ) {

				$atts           = array();
				$atts['title']  = ! empty( $item->attr_title ) ? $item->attr_title : '';
				$atts['target'] = ! empty( $item->target ) ? $item->target : '';
				$atts['rel']    = ! empty( $item->xfn ) ? $item->xfn : '';
				$atts['href']   = ! empty( $item->url ) ? $item->url : '';

				$atts       = apply_filters( 'nav_menu_link_attributes', $atts, $item, $args );
				$attributes = '';

				foreach ( $atts as $attr => $value ) {
					if ( ! empty( $value ) ) {
						$value       = ( 'href' === $attr ) ? esc_url( $value ) : esc_attr( $value );
						$attributes .= ' ' . $attr . '="' . $value . '"';
					}
				}

				$output .= '<li class="mb-2"><a' . $attributes . '>' . esc_html( $title ) . '</a>';
			}
		}

		public function end_el( &$output, $item, $depth = 0, $args = array() ) {

			// Fermeture d’un lien enfant
			if ( 1 === $depth ) {
				$output .= "</li>\n";
				return;
			}

			// Fermeture de la colonne (parent)
			if ( 0 === $depth ) {
				$output .= "</div>\n";
			}
		}

		public function display_element( $element, &$children_elements, $max_depth, $depth, $args, &$output ) {
			if ( ! $element ) {
				return;
			}

			$id_field = $this->db_fields['id'];

			if ( is_object( $args[0] ) ) {
				$args[0]->has_children = ! empty( $children_elements[ $element->$id_field ] );
			}

			parent::display_element( $element, $children_elements, $max_depth, $depth, $args, $output );
		}
	}
}