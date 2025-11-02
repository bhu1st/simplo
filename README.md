# Simplo PHP Framework

![PHP Version](https://img.shields.io/badge/php-%3E=7.4-blue.svg)![License](https://img.shields.io/badge/license-MIT-brightgreen.svg)

Simplo is a minimalist, lightweight PHP MVC framework designed for simplicity and learning. It provides a basic, modern foundation for building small to medium-sized applications, focusing on clean architecture and fundamental concepts like Dependency Injection and a clear Separation of Concerns.

This version is **Composer-free**, making it a completely self-contained project with zero external dependencies.

## Philosophy

The core philosophy of Simplo is to provide a functional MVC structure without the steep learning curve or overhead of larger frameworks. It's an ideal starting point for developers who want to understand the inner workings of a framework or for projects that require a simple, dependency-free foundation.

## Key Features

*   **Zero Dependencies:** The framework is entirely self-contained. No `composer install` step is needed.
*   **PSR-4 Compliant Autoloader:** Includes a simple, built-in autoloader (`autoloader.php`) for all application classes.
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
├── helpers/                # Global helper functions
│   ├── common.php
│   ├── form.php
│   └── url.php
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
├── views/                  # Default presentation/template files
├── themes/                 # Contains theme directories to override default views
└── autoloader.php          # The custom PSR-4 class autoloader
```

## Installation

### Prerequisites

*   PHP 7.4 or higher
*   A database server (e.g., MySQL/MariaDB)

### Steps

1.  **Clone or download the repository:**
    ```bash
    git clone <your-repo-url> simplo
    cd simplo
    ```

2.  **Configure your database:**
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

3.  **Configure your web server:**
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

That's it! Your Simplo application is ready to go.

## How to Use Simplo

### 1. Routing

Routes are defined in `config/routes.php`. The file returns a function that receives the `$router` instance.

*   **Basic Route:**
    `$router->get('/about', [AboutController::class, 'index']);`

*   **Route with a Parameter:** Use curly braces `{}` to define named parameters.
    `$router->get('/user/{id}', [UserController::class, 'show']);`

### 2. Controllers

Controllers handle incoming requests, interact with models, and load views. They must extend `Simplo\Controller`. Use `$this->view('view-name', ['data' => $value])` to render a view.

**Example `src/App/Controllers/HomeController.php`:**
```php
<?php
namespace App\Controllers;

use Simplo\Controller;

class HomeController extends Controller
{
    public function greet($name)
    {
        $data = "Hello, " . htmlspecialchars($name) . "!";
        $this->view('hello', ['data' => $data]);
    }
}
```

### 3. Models

Models are responsible for all data logic and database interaction. They should extend `Simplo\Model`.

**Example `src/App/Models/User.php`:**```php
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

Views are simple PHP files containing HTML and minimal PHP logic for displaying data. They are located in the `views/` directory. Data passed from the controller is available as variables.

**Example `views/hello.php`:**
```php
<h1>Welcome to Simplo!</h1>

<p><?php echo $data; ?></p>
```

## Helper Functions

Simplo supports globally available helper functions for common tasks like redirects and debugging.

### How It Works

Helpers are **manually included** in the main `public/index.php` front controller. This makes them globally available throughout the application.

### Adding New Helpers

1.  Create a new function inside an existing helper file (e.g., `helpers/common.php`) or create a new file (e.g., `helpers/string.php`).
2.  **Important:** Open `public/index.php` and add a `require_once` statement for your new file in the "HELPER FILE LOADING" section.
    ```php
    /*
     * ------------------------------------------------------
     *  MANUAL HELPER FILE LOADING
     * ------------------------------------------------------
     */
    require_once ROOT_PATH . '/helpers/common.php';
    require_once ROOT_PATH . '/helpers/form.php';
    require_once ROOT_PATH . '/helpers/url.php';
    require_once ROOT_PATH . '/helpers/string.php'; // Add your new helper here
    ```

### Available Helpers

*   `dd(...$args)`: Dumps the given variables and ends the script.
*   `redirect(string $path)`: Performs a header redirect to the given path.
*   `old(string $key, $default = '')`: Retrieves an old input value from a POST request to repopulate forms.
*   `base_url(string $path = '')`: Generates the full, dynamic base URL to the application root.
*   `asset(string $path)`: A convenient wrapper around `base_url()` for linking to public assets like CSS, JS, and images.

## Theming System

Simplo's theming system allows you to override default views without modifying core files.

#### How to Use

1.  **Create a Theme Directory:** Create a new folder in the top-level `themes/` directory (e.g., `themes/my-theme/`). Inside it, create a `views` folder.
2.  **Override a View:** Copy any view file you want to change from `views/` into `themes/my-theme/views/` and modify it.
3.  **Activate the Theme:** Open `config/theme.php` and set the `active_theme` key to your theme's directory name. To disable the theme, set the value to `null`.

## License

The Simplo framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).