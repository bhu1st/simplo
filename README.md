# Simplo PHP Framework

![PHP Version](https://img.shields.io/badge/php-%3E=7.4-blue.svg)![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)

Simplo is a minimalist, lightweight PHP MVC framework designed for simplicity and learning. It provides a basic, modern foundation for building small to medium-sized applications, focusing on clean architecture and fundamental concepts like Dependency Injection and a clear Separation of Concerns.

## Philosophy

The core philosophy of Simplo is to provide a functional MVC structure without the steep learning curve or overhead of larger frameworks. It's an ideal starting point for developers who want to understand the inner workings of a framework or for projects where a full-stack solution like Laravel or Symfony would be overkill.

## Key Features

*   **Modern Architecture:** Built with modern PHP best practices in mind.
*   **PSR-4 Autoloading:** Uses Composer for standard, reliable class loading.
*   **Dependency Injection Container:** A simple service container manages dependencies (like the database), promoting decoupled and testable code.
*   **Clean Routing:** A straightforward router that supports parameters and maps them to controller actions.
*   **MVC Pattern:** A clear separation between Models (data logic), Views (presentation), and Controllers (request handling).
*   **Simple Theming System:** Easily override default views to create custom themes, with a fallback mechanism to the default views.
*   **PDO Database Layer:** Uses PDO for safe and easy database interactions.

## Directory Structure

The project structure is designed for a clear separation of concerns.

```
simplo/
├── config/                 # Configuration files (database, theme, etc.)
│   ├── database.php
│   ├── routes.php
│   └── theme.php
├── public/                 # Web server root - the only publicly accessible directory
│   ├── .htaccess
│   └── index.php           # The single entry point (front controller)
├── src/                    # All PSR-4 autoloaded PHP application code
│   ├── App/                # Your specific application code
│   │   ├── Controllers/
│   │   └── Models/
│   └── Simplo/             # The core framework code
│       ├── Controller.php
│       ├── Container.php
│       ├── Database.php
│       ├── Model.php
│       ├── Router.php
│       └── Application.php
├── themes/                 # Contains theme directories to override default views
│   └── dark-mode/
│       └── views/
├── views/                  # Default presentation/template files
├── vendor/                 # Composer dependencies
└── composer.json           # Composer configuration
```

## Installation

### Prerequisites

*   PHP 7.4 or higher
*   Composer installed globally
*   A database server (e.g., MySQL/MariaDB)

### Steps

1.  **Clone the repository:**
    ```bash
    git clone <your-repo-url> simplo
    cd simplo
    ```

2.  **Install dependencies:**
    This will download the necessary packages and generate the PSR-4 autoloader.
    ```bash
    composer install
    ```

3.  **Configure your database:**
    Open `config/database.php` and enter your database credentials.
    ```php
    <?php
    return [
        'host' => 'localhost',
        'database' => 'your_db_name',
        'username' => 'your_db_user',
        'password' => 'your_db_password',
        // ...
    ];
    ```
    Import your database schema if you have one.

4.  **Configure your web server:**
    Point your web server's document root to the `/public` directory. This is crucial for security as it prevents direct access to your application files.

    **Apache:** The included `.htaccess` file in the `public/` directory should handle this automatically. Ensure `mod_rewrite` is enabled.

    **Nginx:** Add a server block similar to this:
    ```nginx
    server {
        listen 80;
        server_name your-domain.com;
        root /path/to/simplo/public;

        index index.php;

        location / {
            try_files $uri $uri/ /index.php?$query_string;
        }

        location ~ \.php$ {
            include snippets/fastcgi-php.conf;
            fastcgi_pass unix:/var/run/php/php8.1-fpm.sock; # Adjust to your PHP-FPM version
        }
    }
    ```

## How to Use Simplo

### 1. Routing

Routes are defined in `config/routes.php`. The file returns a function that receives the `$router` instance.

*   **Basic Route:**
    `$router->get('/about', [AboutController::class, 'index']);`

*   **Route with a Parameter:** Use curly braces `{}` to define named parameters.
    `$router->get('/user/{id}', [UserController::class, 'show']);`

**Example `config/routes.php`:**
```php
<?php
use App\Controllers\HomeController;
use Simplo\Router;

return function(Router $router) {
    $router->get('/', [HomeController::class, 'index']);
    $router->get('/greet/{name}', [HomeController::class, 'greet']);
};
```

### 2. Controllers

Controllers handle incoming requests, interact with models, and load views. They must extend `Simplo\Controller`.

*   The `__construct()` method receives the Dependency Injection container.
*   The base controller provides a `$this->db` property for database access.
*   Use `$this->view('view-name', ['data' => $value])` to render a view.

**Example `src/App/Controllers/HomeController.php`:**
```php
<?php
namespace App\Controllers;

use Simplo\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $userModel = new User($this->db);
        $users = $userModel->get_last_ten_entries();

        $this->view('home', ['data' => ['users' => $users]]);
    }

    public function greet($name) // The {name} parameter from the route is passed here
    {
        $data = "Hello, " . htmlspecialchars($name) . "!";
        $this->view('hello', ['data' => $data]);
    }
}
```

### 3. Models

Models are responsible for all data logic and database interaction. They should extend `Simplo\Model`.

*   The constructor receives the `Simplo\Database` instance, decoupling it from the controller.
*   Use `$this->db->query()` to execute SQL.

**Example `src/App/Models/User.php`:**
```php
<?php
namespace App\Models;

use Simplo\Model;

class User extends Model
{
    public function get_last_ten_entries()
    {
        $stmt = $this->db->query("SELECT * FROM users ORDER BY id DESC LIMIT 10");
        return $stmt->fetchAll();
    }
}
```

### 4. Views

Views are simple PHP files containing HTML and minimal PHP logic for displaying data. They are located in the `views/` directory.

*   Data passed from the controller via the `$this->view()` method is available as variables. For example, `['user' => $user]` makes a `$user` variable available in the view.

**Example `views/hello.php`:**
```php
<h1>Welcome to Simplo!</h1>

<p><?php echo $data; ?></p>
```

## Theming System

Simplo's theming system allows you to override default views without modifying core files.

#### How It Works

1.  When you call `$this->view('home', $data)`, the framework first checks if an active theme is set in `config/theme.php`.
2.  If a theme is active, it looks for the view file at `themes/your-theme-name/views/home.php`.
3.  If the file exists in the theme, it is rendered.
4.  If the file does not exist in the theme, the framework **falls back** and renders the default view at `views/home.php`.

#### How to Use

1.  **Create a Theme Directory:**
    Create a new folder in the top-level `themes/` directory (e.g., `themes/my-awesome-theme/`). Inside it, create a `views` folder.

2.  **Override a View:**
    Copy any view file you want to change from `views/` into `themes/my-awesome-theme/views/` and modify it.

3.  **Activate the Theme:**
    Open `config/theme.php` and set the `active_theme` key to your theme's directory name.
    ```php
    <?php
    // config/theme.php
    return [
        'active_theme' => 'my-awesome-theme',
    ];
    ```
    To disable the theme, set the value to `null`.

## License

The Simplo framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).