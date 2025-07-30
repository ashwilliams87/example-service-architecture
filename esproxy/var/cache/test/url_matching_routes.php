<?php

/**
 * This file has been auto-generated
 * by the Symfony Routing Component.
 */

return [
    false, // $matchHost
    [ // $staticRoutes
        '/search/book' => [[['_route' => 'search_book', '_controller' => 'App\\Controller\\BookSearchController::searchBooks'], null, ['POST' => 0], null, false, false, null]],
        '/search/book/content' => [[['_route' => 'search_book_content', '_controller' => 'App\\Controller\\BookSearchController::searchBookContent'], null, ['POST' => 0], null, false, false, null]],
    ],
    [ // $regexpList
    ],
    [ // $dynamicRoutes
    ],
    null, // $checkCondition
];
