(function () {
    'use strict';

    // Hook CSS anti-FOUC
    document.documentElement.classList.add('wc-js-ready');

    var classMap = [

        {
            selector: '.woocommerce-notice--success',
            remove: [''],
            add: ['notification', 'is-success']
        },
        {
            selector: '.wc-block-components-checkout-place-order-button',
            remove: [''],
            add: ['button', 'is-secondary']
        },
        {
            selector: '',
            remove: [],
            add: ['button', 'is-secondary']
        },
        {
            selector: '.single_add_to_cart_button',
            remove: [],
            add: ['button', 'is-secondary']
        },

        {
            selector: '.wc-block-components-checkout-return-to-cart-button',
            remove: [],
            add: ['button']
        },
        {
            selector: ['.woocommerce-order-details', '.woocommerce-customer-details'],
            remove: [],
            add: ['box', 'is-flex', 'is-flex-direction-column', 'is-gap-4']
        },
        {
            selector: '.woocommerce-column--1',
            remove: [''],
            add: ['column']
        },
        {
            selector: '.woocommerce-column--2',
            remove: [''],
            add: ['column']
        },
        {
            selector: '.u-columns',
            remove: [''],
            add: ['columns']
        },
        {
            selector: '.u-column1',
            remove: [''],
            add: ['column']
        },
        {
            selector: '.u-column2',
            remove: [''],
            add: ['column']
        },
        {
            selector: '.woocommerce-columns',
            remove: [''],
            add: ['columns']
        },
        {
            selector: '.order-status',
            remove: [''],
            add: ['tag']
        },

        // Login/Register Forms
        {
            selector: '.woocommerce-form-login, .woocommerce-form-register',
            remove: [],
            add: ['box']
        },
        {
            selector: '.woocommerce-form-login label, .woocommerce-form-register label',
            remove: [],
            add: ['label']
        },
        {
            selector: '.woocommerce-form-login input[type="text"], .woocommerce-form-login input[type="password"], .woocommerce-form-login input[type="email"], .woocommerce-form-register input[type="text"], .woocommerce-form-register input[type="password"], .woocommerce-form-register input[type="email"]',
            remove: [],
            add: ['input']
        },
        {
            selector: '.woocommerce-form-login button[type="submit"], .woocommerce-form-login input[type="submit"], .woocommerce-form-register button[type="submit"], .woocommerce-form-register input[type="submit"]',
            remove: [],
            add: ['button', 'is-primary']
        },
        {
            selector: '.woocommerce-form-login__rememberme',
            remove: [],
            add: ['checkbox']
        },
        {
            selector: '.woocommerce-form-login .lost_password',
            remove: [],
            add: ['mt-3']
        },
        {
            selector: '.woocommerce-form-login p.form-row, .woocommerce-form-register p.form-row',
            remove: [],
            add: ['field']
        },
        {
            selector: '.woocommerce-form-login p.form-row, .woocommerce-form-register p.form-row',
            remove: [],
            add: ['field']
        },
        {
            selector: '.woocommerce-form-login p.form-row, .woocommerce-form-register p.form-row',
            remove: [],
            add: ['field']
        },
        {
            selector: '.wc-tabs',
            remove: ['wc-tabs'],
            add: ['is-boxed', 'is-centered']
        },
        {
            selector: 'ul.tabs[role="tablist"] li',
            remove: [],
            add: ['tab']
        },
        {
            selector: 'ul.tabs[role="tablist"] li.active',
            remove: ['active'],
            add: ['is-active', 'tab']
        },
        {
            selector: '.woocommerce-form-login p.form-row, .woocommerce-form-register p.form-row',
            remove: [],
            add: ['field']
        }
        
        
        // New rule example :
        // {
        //   selector: '.autre-bouton',
        //   remove: ['old-class'],
        //   add: ['new-class']
        // }
    ];

    /**
     * Apply custom login/register form structure
     */
    function applyLoginFormStructure() {
        var loginForm = document.querySelector('.woocommerce-form-login');
        var registerForm = document.querySelector('.woocommerce-form-register');

        [loginForm, registerForm].forEach(function (form) {
            if (!form || form.dataset.bulmaProcessed) return;
            form.dataset.bulmaProcessed = 'true';

            // Wrap inputs in .control divs
            form.querySelectorAll('input[type="text"], input[type="password"], input[type="email"]').forEach(function (input) {
                if (!input.closest('.control')) {
                    var control = document.createElement('div');
                    control.className = 'control';
                    input.parentNode.insertBefore(control, input);
                    control.appendChild(input);
                }
            });
        });

        // Login form specific
        if (loginForm && !loginForm.dataset.bulmaLayoutProcessed) {
            loginForm.dataset.bulmaLayoutProcessed = 'true';

            // Style remember me section
            var rememberSection = loginForm.querySelector('.woocommerce-form-login__rememberme');
            if (rememberSection) {
                var parentP = rememberSection.closest('p');
                if (parentP && !parentP.classList.contains('is-flex')) {
                    parentP.className = 'field is-flex is-justify-content-space-between is-align-items-center';

                    // Wrap remember checkbox
                    if (!rememberSection.closest('.control')) {
                        var control1 = document.createElement('div');
                        control1.className = 'control';
                        rememberSection.parentNode.insertBefore(control1, rememberSection);
                        control1.appendChild(rememberSection);
                    }

                    // Wrap submit button
                    var submitBtn = parentP.querySelector('button[type="submit"], input[type="submit"]');
                    if (submitBtn && !submitBtn.closest('.control')) {
                        var control2 = document.createElement('div');
                        control2.className = 'control';
                        submitBtn.parentNode.insertBefore(control2, submitBtn);
                        control2.appendChild(submitBtn);
                    }
                }
            }
        }

        // Register form specific
        if (registerForm && !registerForm.dataset.bulmaLayoutProcessed) {
            registerForm.dataset.bulmaLayoutProcessed = 'true';

            var submitSection = registerForm.querySelector('button[type="submit"], input[type="submit"]');
            if (submitSection) {
                var parentP = submitSection.closest('p');
                if (parentP && !parentP.classList.contains('is-grouped')) {
                    parentP.className = 'field is-grouped is-justify-content-flex-end';

                    if (!submitSection.closest('.control')) {
                        var control = document.createElement('span');
                        control.className = 'control';
                        submitSection.parentNode.insertBefore(control, submitSection);
                        control.appendChild(submitSection);
                    }
                }
            }
        }
    }

    var isApplyingClasses = false; // Flag to prevent observer interference
    var DEBUG = false; // Set to false to disable debug logs

    function applyClassReplacements(root) {
        var context = root || document;
        var handled = new Set();

        isApplyingClasses = true; // Set flag before applying classes

        classMap.forEach(function (rule) {
            if (!rule.selector) return;

            var elements = context.querySelectorAll(rule.selector);
            if (!elements.length) return;

            elements.forEach(function (el) {
                // éviter de traiter 10x le même élément
                if (handled.has(el)) return;
                handled.add(el);

                // Debug: Log tab-related changes
                if (DEBUG && el.matches && el.matches('ul.tabs[role="tablist"] li')) {
                    console.log('[TABS DEBUG] Applying rule:', rule.selector);
                    console.log('[TABS DEBUG] Before - classes:', el.className);
                }

                if (Array.isArray(rule.remove) && rule.remove.length) {
                    var toRemove = rule.remove.filter(Boolean); // vire les ''
                    if (toRemove.length) {
                        el.classList.remove.apply(el.classList, toRemove);
                    }
                }

                if (Array.isArray(rule.add) && rule.add.length) {
                    el.classList.add.apply(el.classList, rule.add);
                }

                // Debug: Log tab-related changes
                if (DEBUG && el.matches && el.matches('ul.tabs[role="tablist"] li')) {
                    console.log('[TABS DEBUG] After - classes:', el.className);
                    console.log('[TABS DEBUG] ---');
                }
            });
        });

        // Apply login/register form structure after class replacements
        applyLoginFormStructure();

        isApplyingClasses = false; // Clear flag after applying classes

        // Debug: Final state of all tabs
        if (DEBUG) {
            setTimeout(function() {
                var allTabs = document.querySelectorAll('ul.tabs[role="tablist"] li');
                console.log('[TABS DEBUG] === FINAL STATE ===');
                allTabs.forEach(function(tab, index) {
                    console.log('[TABS DEBUG] Tab', index, ':', tab.className);
                });
            }, 100);
        }
    }

    function setupTabClickHandler() {
        // Simpler approach: intercept tab clicks and manage classes directly
        var tabsList = document.querySelectorAll('ul.tabs[role="tablist"]');

        if (DEBUG) {
            console.log('[TABS DEBUG] setupTabClickHandler called');
            console.log('[TABS DEBUG] Found', tabsList.length, 'tab containers');
        }

        tabsList.forEach(function(tabsContainer) {
            // Skip if already set up
            if (tabsContainer.dataset.tabHandlerActive) {
                if (DEBUG) console.log('[TABS DEBUG] Already handling this container, skipping');
                return;
            }
            tabsContainer.dataset.tabHandlerActive = 'true';

            if (DEBUG) console.log('[TABS DEBUG] Setting up click handler for container');

            // Add click event to the container (event delegation)
            tabsContainer.addEventListener('click', function(e) {
                var clickedTab = e.target.closest('li');
                if (!clickedTab) return;

                if (DEBUG) console.log('[TABS DEBUG] Tab clicked:', clickedTab.className);

                // Use setTimeout to let WooCommerce do its thing first
                setTimeout(function() {
                    // Remove is-active from all tabs in this container
                    var allTabs = tabsContainer.querySelectorAll('li');
                    allTabs.forEach(function(tab) {
                        tab.classList.remove('active', 'is-active');
                        if (DEBUG && tab === clickedTab) {
                            console.log('[TABS DEBUG] Cleared classes on clicked tab');
                        }
                    });

                    // Add is-active to the clicked tab
                    clickedTab.classList.add('is-active');

                    if (DEBUG) {
                        console.log('[TABS DEBUG] After click handler:');
                        allTabs.forEach(function(tab, index) {
                            console.log('[TABS DEBUG] Tab', index, ':', tab.className);
                        });
                    }
                }, 10);
            });

            // Also set up initial state
            var initialActiveTab = tabsContainer.querySelector('li.active');
            if (initialActiveTab) {
                if (DEBUG) console.log('[TABS DEBUG] Found initial active tab, converting to is-active');
                initialActiveTab.classList.remove('active');
                initialActiveTab.classList.add('is-active');
            }
        });
    }

    function setupMutationObserver() {
        // Observer l'ensemble du body pour les ajouts dynamiques
        var container = document.body;
        if (!container) return;

        var observer = new MutationObserver(function (mutations) {
            mutations.forEach(function (mutation) {
                mutation.addedNodes.forEach(function (node) {
                    if (!(node instanceof HTMLElement)) return;
                    applyClassReplacements(node);

                    // If tabs were added dynamically, set up handler for them
                    if (node.matches && node.matches('ul.tabs[role="tablist"]')) {
                        setupTabClickHandler();
                    } else if (node.querySelector) {
                        var newTabs = node.querySelectorAll('ul.tabs[role="tablist"]');
                        if (newTabs.length) {
                            setupTabClickHandler();
                        }
                    }
                });
            });
        });

        observer.observe(container, {
            childList: true,
            subtree: true
        });
    }

    function init() {
        // Apply class replacements to existing elements
        applyClassReplacements();

        // Use setTimeout to ensure class replacements are fully applied before handling tabs
        setTimeout(function() {
            // Setup tab click handler for dynamic active/is-active conversion
            setupTabClickHandler();
        }, 0);

        // Setup mutation observer
        setupMutationObserver();
    }

    // 1) Apply immediately to any existing elements
    applyClassReplacements();

    // 2) Apply again when DOM is fully loaded and setup observer
    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', init, { once: true });
    } else {
        init();
    }

})();
