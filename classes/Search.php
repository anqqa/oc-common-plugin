<?php namespace Klubitus\Search\Classes;


class Search {

    /**
     * Build query string from token array.
     *
     * @param  array  $tokens
     * @param  array  $tokenless
     * @param  array  $exclude
     * @return  string
     */
    public static function buildQuery(array $tokens, array $tokenless = [], array $exclude = []) {
        $query = [];
        $tokenQuery = [];

        // Filter
        foreach ($tokens as $token => $words) {
            if (in_array($token, $exclude)) {
                continue;
            }

            if (in_array($token, $tokenless)) {
                $query += $words;
            }
            else if (isset($tokenQuery[$token])) {
                $tokenQuery[$token] += $words;
            }
            else {
                $tokenQuery[$token] = $words;
            }
        }

        // Build query
        foreach ($tokenQuery as $token => $keywords) {
            $query[] = $token . ':' . implode(',', $keywords);
        }

        return implode(' ', $query);
    }


    /**
     * Parse search query.
     *
     * @param  string  $query
     * @param  array   $tokenless
     * @param  array   $tokens
     * @return  array
     */
    public static function parseQuery($query, array $tokenless = [], array $tokens = []) {
        $parsed = [];
        $keywords = array_keys($tokens);

        // Parsed values
        foreach ($tokenless as $token) {
            $parsed[$token] = [];
        }
        foreach ($keywords as $keyword) {
            $parsed[$keyword] = [];
        }

        $query = trim($query);
        if (strlen($query)) {
            $words = explode(' ', mb_strtolower($query));

            foreach ($words as $word) {
                $_tokens = explode(':', $word, 2);

                // Tokenless words
                if (count($_tokens) == 1 && $tokenless) {
                    foreach ($tokenless as $token) {
                        $parsed[$token][] = $word;
                    }

                    continue;
                }

                // Included tokens
                if (in_array($_tokens[0], $keywords)) {

                    // Known token
                    if (in_array($tokens[$_tokens[0]], $parsed)) {
                        $parsed[$tokens[$_tokens[0]]][] = $_tokens[1];
                    }
                    else {
                        $parsed[$tokens[$_tokens[0]]] = [$_tokens[1]];
                    }

                }
            }
        }

        $parsed = array_map('array_unique', $parsed);

        return array_filter($parsed);
    }

}
