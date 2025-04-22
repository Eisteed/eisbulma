wp.domReady(() => {
    const { createElement } = wp.element;
    const { createHigherOrderComponent } = wp.compose;
    const { addFilter } = wp.hooks;

    // 🔁 Mapping des blocs WordPress à leurs classes Bulma
    const classMap = {
        'core/button': 'button',
        'core/columns': 'columns',
        'core/column': 'column',
        'core/quote': 'block',
        'core/group': 'box',
    };

    const withBulmaClass = createHigherOrderComponent((BlockEdit) => {
        return (props) => {
            const bulmaClass = classMap[props.name];
            if (bulmaClass) {
                const current = props.attributes.className || '';
                if (!current.includes(bulmaClass)) {
                    props.setAttributes({
                        className: [current, bulmaClass].filter(Boolean).join(' ')
                    });
                }
            }

            return createElement(BlockEdit, props);
        };
    }, 'withBulmaClass');

    addFilter(
        'editor.BlockEdit',
        'eisbulma/with-bulma-class',
        withBulmaClass
    );
});
