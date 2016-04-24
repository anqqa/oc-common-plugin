<?php namespace Klubitus\Search;

use Backend;
use System\Classes\PluginBase;

/**
 * Search Plugin Information File
 */
class Plugin extends PluginBase {

    /**
     * Returns information about this plugin.
     *
     * @return  array
     */
    public function pluginDetails() {
        return [
            'name'        => 'Klubitus Search',
            'description' => 'Search helpers for Klubitus.',
            'author'      => 'Antti Qvickström',
            'icon'        => 'icon-search',
            'homepage'    => 'https://github.com/anqqa/oc-search-plugin',
        ];
    }


    public function registerMarkupTags() {
        return [
            'filters' => [
                'highlight' => [$this, 'highlight'],
            ],
        ];
    }


    public function highlight($text, $highlight) {
        if (!$highlight) {
            return $text;
        }

        if (!is_array($highlight)) {
            $highlight = [$highlight];
        }

        return ($highlighted = preg_filter('/' . preg_quote(implode(',', $highlight)) . '(?![^<]*>)/i', '<mark>$0</mark>', $text))
            ? $highlighted
            : $text;
    }

}
