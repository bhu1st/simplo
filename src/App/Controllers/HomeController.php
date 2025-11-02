<?php
namespace App\Controllers;

use Simplo\Controller;
use App\Models\User;

class HomeController extends Controller
{
    public function index()
    {
        $user = new User($this->db);
        $data['users'] = $user->get_last_ten_entries();
        $this->view('home', ['data' => $data]);
    }

    public function hello()
    {
        $this->view('hello', ['data' => "Hello World!"]);
    }

    public function greet($name = "Guest")
    {
        $data = "Hello, " . htmlspecialchars($name) . "!";
        $this->view('hello', ['data' => $data]);
    }

    public function showForm()
    {
        $this->view('form');
    }

    public function handleForm()
    {
        $name = $_POST['name'] ?? '';

        // Example of using the `dd()` helper for debugging
        // dd($_POST);

        if (empty($name)) {
            // If validation fails, we can just re-render the view.
            // The `old()` helper in the view will repopulate the form.
            // In a real app, you would add an error message here.
            echo "<p style='color:red;'>Name is required!</p>";
            return $this->view('form');
        }

        // On success, use the `redirect()` helper
        echo "Success! Redirecting...";
        // In a real app, you would save the data here.
        
        // Redirect to the homepage
        redirect(base_url());
    }
}