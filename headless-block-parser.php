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
class Headless_Block_Parser
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
    public function parse(string $document): array
    {
        $parser = new WP_Block_Parser();

        if (!is_graphql_request() && !defined('REST_REQUEST')) {
            return $parser->parse($document);
        }

        $document_with_replacements = $this->replace_headless_content_link_urls($document);

        return $parser->parse($document_with_replacements);
    }

    /**
     * Modify internal link URLs to point to the decoupled frontend app.
     *
     * @param string $document Input document being parsed.
     *
     * @return string $document Input document with internal link URLs replaced.
     */
    private function replace_headless_content_link_urls(string $document): string
    {
        // TODO: Get this value from an environment variable or the database.
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
