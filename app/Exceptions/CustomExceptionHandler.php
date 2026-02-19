<?php

namespace App\Exceptions;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Debug\ExceptionHandlerInterface;
use Throwable;

/**
 * Custom Exception Handler
 * 
 * Note: We cannot extend ExceptionHandler directly as it's final in some CodeIgniter versions.
 * Instead, we implement custom exception handling through the exception config.
 */
class CustomExceptionHandler implements ExceptionHandlerInterface
{
    /**
     * Render the exception in a web format
     */
    public function handle(
        Throwable $exception,
        RequestInterface $request,
        ResponseInterface $response,
        int $statusCode,
        int $exitCode
    ): void
    {
        // Log the exception with full context
        $this->logException($exception, $statusCode, $request);

        // Determine response based on status code
        match ($statusCode) {
            401 => $this->handle401($exception, $request),
            403 => $this->handle403($exception, $request),
            404 => $this->handle404($exception, $request),
            500 => $this->handle500($exception, $request),
            default => $this->handleDefault($exception, $request),
        };
    }

    /**
     * Handle default/unknown status codes
     */
    private function handleDefault(Throwable $exception, ?RequestInterface $request): void
    {
        $statusCode = $this->getCode($exception);
        $this->setStatusCode($statusCode);

        if ($this->isJSON($request)) {
            echo json_encode([
                'success' => false,
                'message' => $exception->getMessage(),
                'code'    => $statusCode,
            ]);
        } else {
            echo view('errors/404', [
                'message' => 'An unexpected error occurred.',
            ]);
        }
    }

    /**
     * Handle 401 Unauthorized
     */
    private function handle401(Throwable $exception, ?RequestInterface $request): void
    {
        $this->setStatusCode(401);

        if ($this->isJSON($request)) {
            echo json_encode([
                'success' => false,
                'message' => 'Authentication required. Please log in.',
                'code'    => 401,
            ]);
        } else {
            echo view('errors/401', [
                'message' => 'You must be logged in to access this page.',
            ]);
        }
    }

    /**
     * Handle 403 Forbidden
     */
    private function handle403(Throwable $exception, ?RequestInterface $request): void
    {
        $this->setStatusCode(403);

        if ($this->isJSON($request)) {
            echo json_encode([
                'success' => false,
                'message' => 'You do not have permission to access this resource.',
                'code'    => 403,
            ]);
        } else {
            echo view('errors/403', [
                'message' => 'You do not have permission to perform this action.',
            ]);
        }
    }

    /**
     * Handle 404 Not Found
     */
    private function handle404(Throwable $exception, ?RequestInterface $request): void
    {
        $this->setStatusCode(404);

        if ($this->isJSON($request)) {
            echo json_encode([
                'success' => false,
                'message' => 'Resource not found.',
                'code'    => 404,
            ]);
        } else {
            echo view('errors/404', [
                'message' => 'The page you are looking for does not exist.',
            ]);
        }
    }

    /**
     * Handle 500 Internal Server Error
     */
    private function handle500(Throwable $exception, ?RequestInterface $request): void
    {
        $this->setStatusCode(500);

        if ($this->isJSON($request)) {
            $response = [
                'success' => false,
                'message' => 'An internal server error occurred.',
                'code'    => 500,
            ];

            // Include error details in development only
            if (ENVIRONMENT === 'development') {
                $response['error'] = $exception->getMessage();
                $response['file']  = $exception->getFile();
                $response['line']  = $exception->getLine();
            }

            echo json_encode($response);
        } else {
            // Don't expose error details in production
            if (ENVIRONMENT === 'production') {
                echo view('errors/500', [
                    'message' => 'Something went wrong. Please try again later.',
                ]);
            } else {
                echo view('errors/500', [
                    'message' => $exception->getMessage(),
                    'file'    => $exception->getFile(),
                    'line'    => $exception->getLine(),
                ]);
            }
        }
    }

    /**
     * Log exception with full context
     */
    private function logException(Throwable $exception, int $statusCode, ?RequestInterface $request): void
    {
        $userId = session()->get('auth_user.id') ?? 'anonymous';
        $ip     = $request?->getIPAddress() ?? 'unknown';
        $method = $request?->getMethod() ?? 'CLI';
        $path   = $request?->getPath() ?? 'N/A';

        $context = [
            'user_id'    => $userId,
            'ip_address' => $ip,
            'method'     => $method,
            'path'       => $path,
            'exception'  => get_class($exception),
        ];

        $level = match ($statusCode) {
            400, 401, 403, 404 => 'warning',
            default            => 'error',
        };

        $message = "{$method} {$path} - [{$statusCode}] {$exception->getMessage()}";
        log_message($level, $message . ' ' . json_encode($context));
    }

    /**
     * Check if request expects JSON response
     */
    private function isJSON(?RequestInterface $request): bool
    {
        if ($request === null) {
            return false;
        }

        $accept = $request->getHeaderLine('Accept');
        return str_contains($accept, 'application/json');
    }

    /**
     * Get HTTP status code from exception
     */
    private function getCode(Throwable $exception): int
    {
        $code = $exception->getCode();

        // Map known exception classes to status codes
        $exceptionMap = [
            'CodeIgniter\Router\Exceptions\RedirectException'              => 302,
            'CodeIgniter\HTTP\Exceptions\HTTPException'                    => 400,
            'CodeIgniter\HTTP\Exceptions\HTTPBadRequest'                   => 400,
            'CodeIgniter\HTTP\Exceptions\HTTPUnauthorized'                 => 401,
            'CodeIgniter\HTTP\Exceptions\HTTPForbidden'                    => 403,
            'CodeIgniter\HTTP\Exceptions\HTTPNotFound'                     => 404,
            'CodeIgniter\HTTP\Exceptions\HTTPInternalServerError'          => 500,
            'CodeIgniter\HTTP\Exceptions\HTTPServiceUnavailable'           => 503,
        ];

        $exceptionClass = get_class($exception);
        if (isset($exceptionMap[$exceptionClass])) {
            return $exceptionMap[$exceptionClass];
        }

        // If code is between 100-599, use it as status code
        if ($code >= 100 && $code < 600) {
            return (int) $code;
        }

        // Default to 500 for unknown exceptions
        return 500;
    }

    /**
     * Set HTTP status code
     */
    private function setStatusCode(int $code): void
    {
        http_response_code($code);
    }
}
