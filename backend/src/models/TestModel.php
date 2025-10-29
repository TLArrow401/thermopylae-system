<?php
  namespace Backend\Models;

  use PDO;
  use Backend\Models\Database;

  class TestModel {

    private PDO $db;

    public function __construct() {
      $this->db = Database::getConnection();
    }

    public function getAll(): array {
      $stmt = $this->db->prepare('');
      return $stmt->fetchAll();
    }

    public function find(int $id) {
      $stmt = $this->db->prepare('');
      $stmt->execute(['id' => $id]);
      return $stmt->fetch() ?: null;
    }

    public function create(array $data) {
      $stmt = $this->db->prepare('INSERT INTO users (name, email) VALUES (:name, :email) RETURNING id, name, email');
      $stmt->execute([
          'name' => $data['name'],
          'email' => $data['email']
      ]);
      return $stmt->fetch();
    }

    public function update(int $id, array $data) {
      $stmt = $this->db->prepare('UPDATE users SET name = :name, email = :email WHERE id = :id RETURNING id, name, email');
      $stmt->execute([
          'id' => $id,
          'name' => $data['name'] ?? null,
          'email' => $data['email'] ?? null
      ]);
      return $stmt->fetch() ?: null;
    }

    public function delete(int $id): bool {
      $stmt = $this->db->prepare('');
      return $stmt->execute(['id' => $id]);
    }
  }
?>