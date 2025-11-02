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
}