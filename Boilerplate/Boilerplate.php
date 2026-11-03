<?php

namespace ModularityBoilerplate;

/**
 * The module class. Modularity requires the file and the class to share the
 * folder name (Boilerplate/Boilerplate.php) and the file to declare a namespace.
 */
class Boilerplate extends \Modularity\Module
{
    /**
     * Becomes the post type "mod-boilerplate".
     */
    public $slug = 'boilerplate';

    /**
     * "editor" gives the module the regular WordPress content editor.
     * Use [] for a module without it. Title and revisions are always added.
     */
    public $supports = ['editor'];

    /**
     * Only offer the module in widget areas, not as a Gutenberg block.
     * Block mode has no post, so post_content would not exist.
     */
    public $isBlockCompatible = false;

    /**
     * Default for the "Hide title" checkbox on the module edit screen.
     * Editors can still change it per module.
     */
    public $hideTitle = false;

    /**
     * Seconds the rendered HTML is kept in the object cache. 0 disables it.
     * Lower it if data() fetches anything that changes without the module
     * being saved. Saving the module always gives a fresh render.
     */
    public $cacheTtl = WEEK_IN_SECONDS;

    public function init() {
        $this->nameSingular = __('Boilerplate', 'modularity-boilerplate');
        $this->namePlural = __('Boilerplates', 'modularity-boilerplate');
        $this->description = __('Minimal example module', 'modularity-boilerplate');

        // Admin menu icon. Any SVG works; it is embedded as a data URI.
        $icon = '<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="#212121"><path d="M4 4h16v16H4V4zm2 2v12h12V6H6zm2 2h8v2H8V8zm0 4h8v2H8v-2z"/></svg>';
        $this->icon = 'data:image/svg+xml;base64,' . base64_encode($icon);
    }

    /**
     * Everything returned here is available as variables in the Blade view.
     * Modularity also passes the WP_Post properties (post_content etc.),
     * $postTitle and $hideTitle automatically.
     */
    public function data(): array {
        $fields = $this->getFields();

        return [
            'content' => apply_filters('the_content', $this->data['post_content']),
            'hasBackground' => !empty($fields['boilerplate_background']),
        ];
    }

    /**
     * View file inside Boilerplate/views/.
     */
    public function template(): string {
        return 'boilerplate.blade.php';
    }

    /**
     * Called by Modularity only on pages where the module is used.
     * The file modification time is the version, so edits bust the browser cache.
     */
    public function style() {
        $file = 'assets/boilerplate.css';
        wp_enqueue_style('modularity-boilerplate', plugins_url($file, __FILE__), [], filemtime(__DIR__ . '/' . $file));
    }

    /**
     * Same as style(), for JavaScript. Delete the method if the module has no JS.
     */
    public function script() {
        $file = 'assets/boilerplate.js';
        wp_enqueue_script('modularity-boilerplate', plugins_url($file, __FILE__), [], filemtime(__DIR__ . '/' . $file), true);
    }
}
