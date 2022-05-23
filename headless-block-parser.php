<?php

/**
 * Plugin Name: Headless Block Parser
 * Description: Custom Gutenberg block parser that replaces internal link URL domains with that of the decoupled frontend JS app.
 * Version:     0.1.0
 * Author:      Kellen Mace
 * Author URI:  https://kellenmace.com/
 * License:     GPLv2 or later
 * License URI: http://www.gnu.org/licenses/gpl-2.0.html
 */

/**
 * Custom block parser for headless WordPress sites.
 */
class Headless_Block_Parser extends WP_Block_Parser
{
    /**
     * Parses a document and returns a list of block structures
     *
     * When encountering an invalid parse will return a best-effort
     * parse. In contrast to the specification parser this does not
     * return an error on invalid inputs.
     *
     * @param string $document Input document being parsed.
     *
     * @return WP_Block_Parser_Block[]
     */
    public function parse($document): array
    {
        $is_graphql_request = function_exists('is_graphql_request') && is_graphql_request();
        $is_rest_request    = defined('REST_REQUEST');

        // Don't modify the document if this is not a GraphQL or REST API request.
        if (!$is_graphql_request && !$is_rest_request) {
            return parent::parse($document);
        }

        $document_with_replacements = $this->replace_internal_link_url_domains($document);

        return parent::parse($document_with_replacements);
    }

    /**
     * Rewrite internal link URLs to point to the decoupled frontend app.
     *
     * @param string $document Input document being parsed.
     *
     * @return string $document Input document with internal link URL domains replaced.
     */
    private function replace_internal_link_url_domains(string $document): string
    {
        // TODO: Get this value from an environment variable or the database.
        // If you're using Faust.js, you can call WPE\FaustWP\Settings\faustwp_get_setting( 'frontend_uri' )
        $frontend_app_url = 'http://localhost:3000';
        $site_url         = site_url();

        return str_replace('href="' . $site_url, 'data-internal-link="true" href="' . $frontend_app_url, $document);
    }
}

/**
 * Register a custom Gutenberg block parser.
 *
 * @return string Name of block parser class.
 */
add_filter('block_parser_class', fn (): string => 'Headless_Block_Parser');
