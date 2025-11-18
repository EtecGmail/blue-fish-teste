<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware para adicionar headers de segurança conforme as melhores práticas
 * e diretrizes do guia Yuri Garcia Pardinho
 */
class SecurityHeaders
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        // Adicionar headers de segurança
        $this->addSecurityHeaders($response);

        return $response;
    }

    /**
     * Adiciona headers de segurança à resposta
     *
     * @param Response $response
     * @return void
     */
    private function addSecurityHeaders(Response $response): void
    {
        // Gerar um nonce único para cada requisição.
        $nonce = base64_encode(random_bytes(16));
        // Disponibilizar o nonce para a aplicação (ex: para ser usado no Blade com Vite).
        app()->instance('csp-nonce', $nonce);

        // Content Security Policy (CSP) com nonce
        $csp = [
            "default-src 'self'",
            "script-src 'self' 'nonce-{$nonce}'", // Permite scripts com o nonce gerado
            "style-src 'self' 'unsafe-inline'", // Manter unsafe-inline para estilos por enquanto é pragmático
            "img-src 'self' data: https:",
            "font-src 'self' data:",
            "connect-src 'self'",
            "media-src 'self'",
            "object-src 'none'",
            "child-src 'none'",
            "form-action 'self'",
            "base-uri 'self'",
            "frame-ancestors 'none'",
        ];
        $response->headers->set('Content-Security-Policy', implode('; ', $csp));

        // Strict Transport Security (HSTS)
        $response->headers->set('Strict-Transport-Security', 'max-age=31536000; includeSubDomains; preload');

        // X-Frame-Options - Previne clickjacking
        $response->headers->set('X-Frame-Options', 'DENY');

        // X-Content-Type-Options - Previne MIME type sniffing
        $response->headers->set('X-Content-Type-Options', 'nosniff');

        // Referrer Policy - Mais restritivo
        $response->headers->set('Referrer-Policy', 'no-referrer');

        // Permissions Policy - Controla recursos do navegador
        $response->headers->set('Permissions-Policy', 'geolocation=(), microphone=(), camera=(), payment=(), usb=(), magnetometer=(), gyroscope=(), accelerometer=()');

        // Cache Control - Previne cache de dados sensíveis
        if ($this->shouldPreventCache($response)) {
            $response->headers->set('Cache-Control', 'no-store, no-cache, must-revalidate, max-age=0');
            $response->headers->set('Pragma', 'no-cache');
            $response->headers->set('Expires', 'Thu, 01 Jan 1970 00:00:00 GMT');
        }

        // Remove headers que revelam informações do servidor
        $response->headers->remove('X-Powered-By');
        $response->headers->remove('Server');
        // Remove header obsoleto de proteção XSS
        $response->headers->remove('X-XSS-Protection');
    }

    /**
     * Determina se deve prevenir cache baseado na resposta
     *
     * @param Response $response
     * @return bool
     */
    private function shouldPreventCache(Response $response): bool
    {
        // Prevenir cache para páginas com conteúdo sensível
        $sensitiveContentTypes = [
            'text/html',
            'application/json',
            'application/xml',
        ];

        $contentType = $response->headers->get('Content-Type', '');
        
        foreach ($sensitiveContentTypes as $type) {
            if (str_contains($contentType, $type)) {
                return true;
            }
        }

        return false;
    }
}