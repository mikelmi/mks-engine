<?php

namespace App\Exceptions;

use App\Models\Page;
use App\Repositories\LanguageRepository;
use Artesaos\SEOTools\Contracts\SEOTools;
use Exception;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        \Illuminate\Auth\AuthenticationException::class,
        \Illuminate\Auth\Access\AuthorizationException::class,
        \Symfony\Component\HttpKernel\Exception\HttpException::class,
        \Illuminate\Database\Eloquent\ModelNotFoundException::class,
        \Illuminate\Session\TokenMismatchException::class,
        \Illuminate\Validation\ValidationException::class,
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        $response = parent::render($request, $exception);

        if ($response->getStatusCode() == 403) {
            if ($request->ajax() || $request->wantsJson()) {
                $response->headers->set('X-Flash-Message', rawurlencode(__('admin::auth.Access Denied')));
                $response->headers->set('X-Flash-Message-Type', 'danger');
            }
        }

        return $response;
    }

    /**
     * Convert an authentication exception into an unauthenticated response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Illuminate\Auth\AuthenticationException  $exception
     * @return \Illuminate\Http\Response
     */
    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if ($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        return redirect()->guest('login');
    }

    protected function renderHttpException(HttpException $e)
    {
        $status = $e->getStatusCode();

        $page_id = null;

        $locale = app()->getLocale();

        if ($locale) {
            /** @var LanguageRepository $langs */
            $langs = app(LanguageRepository::class);
            if ($language = $langs->get($locale)) {
                $page_id = $language->get('e'.$status);
            }
        }

        if (!$page_id) {
            $page_id = settings('page.' . $status);
        }

        if ($page_id && ($page = Page::find($page_id))) {
            /** @var SEOTools $seo */
            $seo = app('seotools');

            $title = $page->meta_title ?: $page->title;

            if (!$title) {
                $title = $status . '. ' . $e->getMessage();
            }

            $seo->setTitle($title);

            if ($page->param('template') == '-1') {
                $template = 'page.empty';
            } else {
                $template = 'page.show';
            }

            return response()->view($template, ['exception' => $e, 'page' => $page], $status, $e->getHeaders());
        }

        if (view()->exists("errors.{$status}")) {
            return response()->view("errors.{$status}", ['exception' => $e], $status, $e->getHeaders());
        } else {
            return $this->convertExceptionToResponse($e);
        }
    }
}
