<?php
class Router
{
    public function dispatch(): void
    {
        $uri    = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        $controller = new FileController();

        match($uri) {
            '/'         => $controller->index(),
            '/upload'   => $controller->upload(),
            '/download' => $controller->download(),
            '/delete'   => $controller->delete(),
            default     => $this->notFound()
        };
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo '404 — Səhifə tapılmadı';
    }
}