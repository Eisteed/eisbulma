wp.domReady(() => {
    const { createElement } = wp.element;
    const { createHigherOrderComponent } = wp.compose;
    const { addFilter } = wp.hooks;

    const addBulmaClassToButton = createHigherOrderComponent(function(BlockEdit) {
        return function(props) {
            if (props.name === 'core/button') {
                if (!props.attributes.className || !props.attributes.className.includes('button')) {
                    props.setAttributes({
                        ...props.attributes,
                        className: 'button is-link',
                    });
                }
            }
            return createElement(BlockEdit, props);
        };
    }, 'addBulmaClassToButton');

    addFilter(
        'editor.BlockEdit',
        'eisbulma/add-bulma-class',
        addBulmaClassToButton
    );
});
