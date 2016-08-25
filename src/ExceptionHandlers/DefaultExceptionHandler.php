<?php

namespace EvgeniyN\LaravelHipChat\ExceptionHandlers;


class DefaultExceptionHandler
{
    protected $view = 'hipchat::exception.default';

    public function convert(\Exception $e)
    {
        $file       = str_replace(base_path(), '', $e->getFile());
        $line       = $e->getLine();

        $method     = request()->getMethod();
        $location   = request()->fullUrl();
        $prev       = request()->header('referer');
        $getRequest = request()->isMethod('get');
        $payload    = false;
        if (!$getRequest) {
            $payload = request()->all();
        }

        $data = compact('e', 'file', 'line', 'method', 'location', 'prev', 'payload', 'getRequest');

        return view($this->view, $data)->render();
    }
}