<?php

use Kirby\Http\Response;

return [
    'routes' => fn (\Kirby\Cms\App $kirby) => [
        [
            'pattern' => 'deepl/translate',
            'method' => 'POST',
            'action' => function () use (&$kirby) {
                $body = $kirby->request()->body();
                $text = $body->get('text');
                $targetLanguage = $body->get('targetLanguage');

                if (!$text) {
                    return Response::json([
                        'code' => 400,
                        'status' => 'Bad Request'
                    ], 400);
                }

                if (!class_exists(DeepL\Translator::class)) {
                    return Response::json([
                        'code' => 500,
                        'status' => 'Internal Server Error'
                    ], 500);
                }

                $authKey = $kirby->option('johannschopplich.seo-helpers.DeepL.apiKey');
                $translator = new \DeepL\Translator($authKey);
                $result = $translator->translateText($text, null, $targetLanguage);

                return Response::json([
                    'code' => 201,
                    'status' => 'Created',
                    'result' => [
                        'text' => $result->text
                    ]
                ], 201);
            }
        ]
    ]
];
