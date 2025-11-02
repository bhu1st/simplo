<?php
namespace App\Controllers\Admin;

use Simplo\Controller;
use App\Models\User;

class AdminController extends Controller
{
    public function index()
    {
        $user = new User($this->db);
		$data['data']['msg'] = "Admin folder";
        $data['data']['users'] = $user->get_last_ten_entries();
        $this->view('admin/index', ['data' => $data]);
    }

}
