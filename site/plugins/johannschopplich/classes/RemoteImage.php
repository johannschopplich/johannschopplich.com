<?php

namespace JohannSchopplich\Website;

use Kirby\Cache\Cache;
use Kirby\Cms\App;
use Kirby\Filesystem\Asset;
use Kirby\Filesystem\F;
use Kirby\Http\Remote;
use Throwable;

/**
 * Mirrors a remote image into the media folder, so Kirby builds thumbs and a
 * thumbhash for it like for a content file.
 */
class RemoteImage
{
    protected const EXTENSIONS = ['jpeg', 'jpg', 'png', 'webp'];
    protected const REVALIDATE_AFTER = 60 * 60 * 24;
    protected const RETRY_AFTER = 60 * 60;

    public static function asset(string $url): Asset|null
    {
        $extension = strtolower(F::extension(parse_url($url, PHP_URL_PATH) ?: ''));
        if (!in_array($extension, static::EXTENSIONS, true)) {
            return null;
        }

        $kirby = App::instance();
        $cache = $kirby->cache('johannschopplich.website.remote-images');
        $key = sha1($url);
        $path = 'media/remote/' . $key . '.' . $extension;
        $root = $kirby->root('index') . '/' . $path;
        $entry = $cache->get($key, []);

        if (!is_file($root)) {
            // The first download runs inline, so a failing remote waits an hour instead of blocking every render.
            if (($entry['failedAt'] ?? 0) > time() - static::RETRY_AFTER) {
                return null;
            }

            if (static::fetch($url, $root, $cache, $key)) {
                return new Asset($path);
            }

            $cache->set($key, [...$entry, 'failedAt' => time()]);
            return null;
        }

        if (($entry['checkedAt'] ?? 0) < time() - static::REVALIDATE_AFTER) {
            // Stamped before the request, so a failing remote is retried tomorrow, not on every render.
            $cache->set($key, [...$entry, 'checkedAt' => time()]);

            register_shutdown_function(function () use ($url, $root, $cache, $key, $entry): void {
                if (function_exists('fastcgi_finish_request')) {
                    fastcgi_finish_request();
                }
                static::fetch($url, $root, $cache, $key, $entry['etag'] ?? null);
            });
        }

        return new Asset($path);
    }

    protected static function fetch(string $url, string $root, Cache $cache, string $key, string|null $etag = null): bool
    {
        try {
            $response = Remote::get($url, [
                'timeout' => 10,
                'headers' => $etag ? ['If-None-Match' => $etag] : []
            ]);
        } catch (Throwable) {
            return is_file($root);
        }

        $headers = array_change_key_case($response->headers());
        $content = $response->content() ?? '';
        $size = $response->code() === 200 ? @getimagesizefromstring($content) : false;

        if (!$size || $size[0] === 0 || $size[1] === 0) {
            return is_file($root);
        }

        // Rewriting an unchanged image would bump its modified time, and with it every thumb URL.
        if (!is_file($root) || sha1_file($root) !== sha1($content)) {
            // A temporary file plus `rename()` keeps a parallel request from reading a half-written image.
            $temporaryRoot = $root . '.' . bin2hex(random_bytes(4)) . '.tmp';

            try {
                $isWritten = F::write($temporaryRoot, $content) && rename($temporaryRoot, $root);
            } catch (Throwable) {
                $isWritten = false;
            }

            if (!$isWritten) {
                F::remove($temporaryRoot);
                return is_file($root);
            }
        }

        $cache->set($key, [
            'etag' => trim($headers['etag'] ?? '') ?: null,
            'checkedAt' => time()
        ]);

        return true;
    }
}
