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

    public function insert_entry(string $name, string $email, string $password)
    {
        $params = ['name' => $name, 'email' => $email, 'password' => password_hash($password, PASSWORD_DEFAULT)];
        $this->db->query("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)", $params);
        return $this->db->lastInsertId();
    }
    
    // ... other methods like update_entry, delete_entry refactored similarly
}