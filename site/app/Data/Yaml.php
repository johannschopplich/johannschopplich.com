<?php

namespace Kirby\Data;

use Kirby\Toolkit\A;
use Symfony\Component\Yaml\Yaml as Parser;

/**
 * Simple Wrapper around Symfony's Yaml Component
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
        // $data, when to not inline, indentation
        $yaml = Parser::dump($data, 2, 2, Parser::DUMP_MULTI_LINE_LITERAL_BLOCK);

        return $yaml;
    }

    /**
     * Parses an encoded YAML string and returns a multi-dimensional array
     *
     * @param string $yaml
     * @return array
     */
    public static function decode($yaml): array
    {
        if ($yaml === null) {
            return [];
        }

        if (is_array($yaml) === true) {
            return $yaml;
        }

        // remove BOM
        $yaml   = str_replace("\xEF\xBB\xBF", '', $yaml);
        $result = Parser::parse($yaml);

        // ensure that single string is wrapped in array
        $result = A::wrap($result);

        return $result;
    }
}
