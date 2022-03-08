<?php

namespace Kirby\Data;

use Kirby\Toolkit\A;
use Symfony\Component\Yaml\Yaml as Parser;

/**
 * Simple wrapper around Symfony's YAML class
 *
 * @package   Kirby Data
 * @author    Bastian Allgeier <bastian@getkirby.com>
 * @link      https://getkirby.com
 * @copyright Bastian Allgeier GmbH
 * @license   https://opensource.org/licenses/MIT
 */
class Yaml extends Handler
{
    /**
     * Converts an array to an encoded YAML string
     *
     * @param mixed $data
     * @return string
     */
    public static function encode($data): string
    {
        return Parser::dump($data, 2, 2, Parser::DUMP_MULTI_LINE_LITERAL_BLOCK | Parser::DUMP_EMPTY_ARRAY_AS_SEQUENCE);
    }

    /**
     * Parses an encoded YAML string and returns a multi-dimensional array
     *
     * @param string $string
     * @return array
     */
    public static function decode($string): array
    {
        if ($string === null || $string === '') {
            return [];
        }

        if (is_array($string) === true) {
            return $string;
        }

        // remove BOM
        $string = str_replace("\xEF\xBB\xBF", '', $string);

        $result = Parser::parse($string);
        $result = A::wrap($result);

        return $result;
    }
}
