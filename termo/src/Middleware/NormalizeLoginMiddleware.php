<?php

declare(strict_types=1);

namespace App\Middleware;

use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\MiddlewareInterface;
use Psr\Http\Server\RequestHandlerInterface;

class NormalizeLoginMiddleware implements MiddlewareInterface
{
    public function process(
        ServerRequestInterface $request,
        RequestHandlerInterface $handler
    ): ResponseInterface {

        // Só age no POST da rota de login
        if (
            $request->getMethod() === 'POST' &&
            str_contains($request->getUri()->getPath(), '/usuarios/login')
        ) {
            $data = $request->getParsedBody();

            \Cake\Log\Log::debug('NormalizeLogin - dados recebidos: ' . print_r($data, true));

            $login = trim($data['username'] ?? '');

            // Se for CPF (só dígitos, 11 chars), limpa a máscara
            $cpfLimpo = preg_replace('/\D/', '', $login);
            if (strlen($cpfLimpo) === 11 && ctype_digit($cpfLimpo)) {
                $login = $cpfLimpo;
            }

            $data['username'] = $login;
            $request = $request->withParsedBody($data);
            
            \Cake\Log\Log::debug('NormalizeLogin - username normalizado: ' . $login);
        }

        return $handler->handle($request);
    }
}