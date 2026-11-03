# Modularity Boilerplate

Minimal example module for Modularity in Municipio. Shows how a module is registered, gets custom fields, is rendered and loads CSS and JS. Use it as the starting point for new modules.

The module has a title, regular editor content and a field that toggles a background. With the background on, the content is rendered inside a card.

## Requirements

- The Municipio theme, which includes Modularity.
- Advanced Custom Fields (Pro).

## Installation

1. Put the folder in `wp-content/plugins/` and activate the plugin.
2. Enable the module under **Modularity > Options**, in the list of enabled modules.
3. Add the module to a widget area in the Modularity editor.

## Creating a new module from the boilerplate

1. Copy the folder and rename the plugin file.
2. Rename the `Boilerplate` folder, the class file and the class to the same new name. Modularity requires them to match.
3. Change the namespace in the class file.
4. Change `slug` in the class. The post type becomes `mod-<slug>` and is cut off at 20 characters.
5. Search and replace `mod-boilerplate` and `boilerplate` in the plugin file, the view and the assets.
6. Give the ACF field group and its fields new unique keys.

## Architecture

### Files

| File | Contents |
|---|---|
| `modularity-boilerplate.php` | Registration, view path, ACF fields and allowed widget areas |
| `Boilerplate/Boilerplate.php` | Module class: name, icon, settings, view data, CSS and JS |
| `Boilerplate/views/boilerplate.blade.php` | Output using components from the Municipio component library |
| `Boilerplate/assets/` | CSS and JS for the module |

### Registration

Modularity is loaded by the theme. The module is therefore registered on `after_setup_theme` with `modularity_register_module()`. If Modularity is missing, an error notice is shown in wp-admin instead of a fatal error.

Modularity includes the class file itself based on the folder name. The plugin needs no autoloader and no Composer dependencies.

The `/Modularity/externalViewPath` filter tells Modularity where the views are.

### Fields

Fields are defined as an ACF array with `acf_add_local_field_group()` on `acf/init`. They are attached to the `mod-boilerplate` post type. The class reads them with `$this->getFields()`, keyed by each field's `name`.

### Class settings

| Property | Value | Meaning |
|---|---|---|
| `supports` | `['editor']` | Adds the content editor. Set to `[]` for a module without it. |
| `isBlockCompatible` | `false` | The module is only available in widget areas, not as a Gutenberg block. |
| `hideTitle` | `false` | Default for the "Hide title" checkbox. Can be changed per module. |
| `cacheTtl` | `WEEK_IN_SECONDS` | Seconds the rendered HTML is kept in the object cache. `0` disables it. |

The cache is refreshed when the module is saved. Lower `cacheTtl` if `data()` fetches anything that changes without the module being saved.

### Widget areas

The plugin file has a list of allowed widget areas. It starts with every area. Remove the ones the module should not be available in.

Modularity expects a list of blocked areas through the `Modularity/Editor/SidebarIncompability` filter. The filter therefore returns all registered areas minus the allowed list. Areas added later are blocked until they are added to the list.

The restriction only applies in the drag-and-drop editor. Modules already placed in an area stay there.

### CSS and JS

Modularity calls `style()` and `script()` only on pages where the module is used. The files are loaded with `wp_enqueue_style` and `wp_enqueue_script`, using the file modification time as the version. Remove `script()` and the JS file if the module needs no JS.

Every instance is wrapped in an element with the class `modularity-mod-boilerplate`.

### External dependencies

The plugin has no scheduled jobs, no custom endpoints, no environment variables and no secrets.
