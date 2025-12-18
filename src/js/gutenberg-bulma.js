( function( hooks, compose, element ) {
    const { addFilter } = hooks;
    const { createHigherOrderComponent } = compose;
    const { createElement: el } = element;

    // Ajoute classes/inline styles au wrapper du bloc core/button dans l'éditeur
    const withButtonWrapperMirroring = createHigherOrderComponent( ( BlockListBlock ) => {
        return ( props ) => {
            if ( props.name !== 'core/button' ) {
                return el( BlockListBlock, props );
            }

            const attrs = props.attributes || {};
            const {
                className: origClass = '',
                textColor,
                backgroundColor,
                style = {},
            } = attrs;

            // Classes utilitaires WP (presets)
            const mirrorClasses = [];
            if ( backgroundColor ) {
                mirrorClasses.push( `has-${ backgroundColor }-background-color`, 'has-background' );
            }
            if ( textColor ) {
                mirrorClasses.push( `has-${ textColor }-color`, 'has-text-color' );
            }

            // Gestion des couleurs personnalisées (non-présélection)
            // -> on pousse en inline style sur le wrapper pour l’aperçu éditeur
            const customBg = style?.color?.background || null;
            const customText = style?.color?.text || null;

            // Fusion des classes sur le wrapper .wp-block-button
            const mergedClass = [ origClass, ...mirrorClasses ]
                .filter(Boolean)
                .join(' ')
                .replace(/\s+/g, ' ')
                .trim();

            // Merge éventuels wrapperProps existants
            const mergedWrapperProps = {
                ...(props.wrapperProps || {}),
                style: {
                    ...(props.wrapperProps?.style || {}),
                    ...(customBg ? { backgroundColor: customBg } : {}),
                    ...(customText ? { color: customText } : {}),
                }
            };

            return el( BlockListBlock, {
                ...props,
                className: mergedClass,
                wrapperProps: mergedWrapperProps
            } );
        };
    }, 'withButtonWrapperMirroring' );

    addFilter(
        'editor.BlockListBlock',
        'your-namespace/mirror-core-button-classes-in-editor',
        withButtonWrapperMirroring
    );

    // (Optionnel) Mirroring pour un wrapper custom ".button" si tu l’utilises dans l’éditeur
    // Ici on ajoute les mêmes classes sur BlockListBlock de core/button ET une classe ".button" si tu veux l’aperçu identique.
    const withOptionalCustomWrapperClass = createHigherOrderComponent( ( BlockListBlock ) => {
        return ( props ) => {
            if ( props.name !== 'core/button' ) return el( BlockListBlock, props );
            const mergedClass = [ props.className, 'button' ]
                .filter(Boolean)
                .join(' ')
                .replace(/\s+/g, ' ')
                .trim();
            return el( BlockListBlock, { ...props, className: mergedClass } );
        };
    }, 'withOptionalCustomWrapperClass' );

    // Décommente si tu veux forcer .button dans l’éditeur
    // addFilter(
    //   'editor.BlockListBlock',
    //   'your-namespace/add-button-class-in-editor',
    //   withOptionalCustomWrapperClass
    // );

} )( window.wp.hooks, window.wp.compose, window.wp.element );
