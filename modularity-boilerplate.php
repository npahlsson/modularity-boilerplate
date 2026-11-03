<?php

/**
 * Plugin Name: Modularity Boilerplate
 * Description: Minimal example of a Modularity module: registration, custom fields and rendering.
 * Author:      Niklas Påhlsson
 * Author URI:  https://webbium.se
 * Version:     1.0.0
 * Text Domain: modularity-boilerplate
 */

if (!defined('WPINC')) {
    die;
}

/**
 * 1. Register the module.
 *
 * Modularity ships with the Municipio theme, so its functions exist first on
 * after_setup_theme. Modularity reads the namespace from
 * Boilerplate/Boilerplate.php, includes it and registers the post type
 * "mod-boilerplate". The module then has to be enabled under
 * Modularity > Options in wp-admin.
 */
add_action('after_setup_theme', function () {
    if (!function_exists('modularity_register_module')) {
        add_action('admin_notices', function () {
            echo '<div class="notice notice-error"><p>'
                . esc_html__('Modularity Boilerplate requires Modularity, which ships with the Municipio theme.', 'modularity-boilerplate')
                . '</p></div>';
        });
        return;
    }

    modularity_register_module(__DIR__ . '/Boilerplate', 'Boilerplate');
});

/**
 * 2. Tell Modularity where this module's Blade views live.
 */
add_filter('/Modularity/externalViewPath', function (array $paths) {
    $paths['mod-boilerplate'] = __DIR__ . '/Boilerplate/views';
    return $paths;
});

/**
 * 3. Add custom fields to the module edit screen.
 *
 * Plain ACF field group array. Fields are read in the module class with
 * $this->getFields(), keyed by their "name".
 */
add_action('acf/init', function () {
    acf_add_local_field_group([
        'key' => 'group_modularity_boilerplate',
        'title' => __('Boilerplate settings', 'modularity-boilerplate'),
        'fields' => [
            [
                'key' => 'field_modularity_boilerplate_background',
                'name' => 'boilerplate_background',
                'label' => __('Background', 'modularity-boilerplate'),
                'instructions' => __('Render the module inside a card with a background color.', 'modularity-boilerplate'),
                'type' => 'true_false',
                'ui' => 1,
                'default_value' => 0,
            ],
        ],
        'location' => [
            [
                [
                    'param' => 'post_type',
                    'operator' => '==',
                    'value' => 'mod-boilerplate',
                ],
            ],
        ],
    ]);
});

/**
 * 4. Limit which widget areas the module can be dragged into.
 *
 * Remove the areas the module should not be available in. Areas not listed
 * here, including areas a theme update adds later, are blocked.
 *
 * Modularity only enforces this in the drag-and-drop editor. Modules already
 * placed in an area stay there.
 */
add_filter('Modularity/Editor/SidebarIncompability', function (array $module, string $postType) {
    if ($postType !== 'mod-boilerplate') {
        return $module;
    }

    $allowedSidebars = [
        'header-area-site-banner', // Webbplatsbanner
        'slider-area',             // Hero
        'top-sidebar',             // Under sidhuvud (hela bredden)
        'above-columns-sidebar',   // Ovanför innehållskolumner
        'content-area-top',        // Innehållsarea (ovanför artikel)
        'content-area',            // Innehållsarea (nedanför artikel)
        'content-area-bottom',     // Under artikel
        'left-sidebar',            // Vänster sidofält (övre)
        'left-sidebar-bottom',     // Vänster sidofält (undre)
        'right-sidebar',           // Höger sidofält
        'bottom-sidebar',          // Ovanför sidfot (hela bredden)
        // 'footer-area-top',         // Sidfot topp
        // 'footer-area',             // Sidfot (1)
        // 'footer-area-column-1',    // Sidfot (2)
        // 'footer-area-column-2',    // Sidfot (3)
        // 'footer-area-column-3',    // Sidfot (4)
        // 'footer-area-column-4',    // Sidfot (5)
        // 'footer-area-column-5',    // Sidfot (6)
        // 'footer-area-bottom',      // Botten av sidfot
    ];

    // Modularity expects a list of blocked areas, so invert the allowed list.
    $allSidebars = array_keys($GLOBALS['wp_registered_sidebars']);
    $module['sidebar_incompability'] = array_values(array_diff($allSidebars, $allowedSidebars));

    return $module;
}, 20, 2); // After Municipio's own rules on priority 10.
