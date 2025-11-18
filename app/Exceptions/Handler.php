<?php

namespace App\Exceptions;

use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Throwable;

class Handler extends ExceptionHandler
{
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            if ($this->shouldReport($e)) {
                \Log::error('Exceção não tratada', [
                    'mensagem' => $e->getMessage(),
                    'arquivo' => $e->getFile(),
                    'linha' => $e->getLine(),
                    'url' => request()->url(),
                    'usuario' => auth()->user()?->id,
                    'ip' => request()->ip(),
                ]);
            }
        });
    }

    public function render($request, Throwable $exception)
    {
        if ($exception instanceof ValidationException) {
            return $this->handleValidationException($request, $exception);
        }

        if ($exception instanceof ModelNotFoundException) {
            return $this->handleModelNotFoundException($request, $exception);
        }

        if ($exception instanceof NotFoundHttpException) {
            return $this->handleNotFoundException($request, $exception);
        }

        if ($exception instanceof AuthenticationException) {
            return $this->handleAuthenticationException($request, $exception);
        }

        if ($exception instanceof AuthorizationException) {
            return $this->handleAuthorizationException($request, $exception);
        }

        if ($exception instanceof QueryException) {
            return $this->handleQueryException($request, $exception);
        }

        return parent::render($request, $exception);
    }

    protected function handleValidationException($request, ValidationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro de validação',
                'erros' => $exception->errors(),
            ], 422);
        }

        return parent::render($request, $exception);
    }

    protected function handleModelNotFoundException($request, ModelNotFoundException $exception)
    {
        $modelo = class_basename($exception->getModel());

        if ($request->expectsJson()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => "Registro de {$modelo} não encontrado.",
            ], 404);
        }

        return redirect()
            ->back()
            ->with('erro', "Registro de {$modelo} não encontrado.")
            ->withInput();
    }

    protected function handleNotFoundException($request, NotFoundHttpException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Página não encontrada.',
            ], 404);
        }

        return response()->view('errors.404', [], 404);
    }

    protected function handleAuthenticationException($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Não autenticado. Por favor, faça login.',
            ], 401);
        }

        return redirect()
            ->route('login')
            ->with('erro', 'Por favor, faça login para acessar esta página.');
    }

    protected function handleAuthorizationException($request, AuthorizationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Acesso não autorizado.',
            ], 403);
        }

        return redirect()
            ->back()
            ->with('erro', 'Você não tem permissão para realizar esta ação.');
    }

    protected function handleQueryException($request, QueryException $exception)
    {
        \Log::error('Erro de banco de dados', [
            'sql' => $exception->getSql(),
            'bindings' => $exception->getBindings(),
            'mensagem' => $exception->getMessage(),
        ]);

        if ($request->expectsJson()) {
            return response()->json([
                'sucesso' => false,
                'mensagem' => 'Erro ao processar dados. Por favor, tente novamente.',
            ], 500);
        }

        return redirect()
            ->back()
            ->with('erro', 'Ocorreu um erro ao processar seus dados. Por favor, tente novamente.')
            ->withInput();
    }
}
