<?php
namespace Simplo;

class Controller
{
    protected $container;
    protected $db;

    public function __construct(Container $container)
    {
        $this->container = $container;
        $this->db = $container->get('db'); // Convenience access to db
    }

    protected function view(string $view, array $data = [])
    {
        // Using `_` prefix to avoid collision with extracted variables
        $_view_path = dirname(__DIR__, 2) . "/views/{$view}.php";

        if (!file_exists($_view_path)) {
            throw new \Exception("View file not found: {$_view_path}");
        }

        extract($data);
        require $_view_path;
    }
}