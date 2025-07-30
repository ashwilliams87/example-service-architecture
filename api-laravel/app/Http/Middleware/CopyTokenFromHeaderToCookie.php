<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Lan\Security\Security\Jwt;
use Symfony\Component\HttpFoundation\Response;

/**
 * Костыль для работы с токены из заголовка.
 *
 * Приложение не воспринимает токены из заголовка, только из Cookie.
 * Поэтому приходится копировать токен из заголовка в Cookie.
 *
 * Такой же кастыль был предыдущей версии API на phalcon-api в public/index.php
 */
class CopyTokenFromHeaderToCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty(getallheaders()['X-Auth-Token'])) {
            $_COOKIE[Jwt::LAN_ACCESS_TOKEN] = getallheaders()['X-Auth-Token'];
        }

        return $next($request);
    }
}
