<?php
declare(strict_types=1);

namespace PharmaFEFO\Repository;

use PDO;
use PharmaApp\Entity\User;

class UserRepository{
    private PDO $pdo;

    public function __construct(PDO $pdo){
        $this->pdo = $pdo;
    }
    public function findByEmail(string $email): ?User{
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE email = :email");
        $stmt->execute([':email' => $email]);
        $row = $stmt->fetch();

        if (!$row){
            return null;
        }
        $user = new User();
        $user->setId((int)$row->id)
             ->setNom($row->nom)
             ->setEmail($row->email)
             ->setPassword($row->password)
             ->setRole($row->role);
        
        return $user;
    }
    public function save(User $user): bool {
        $stmt = $this->pdo->prepare("INSERT INTO users (nom, email, password, role) VALUES (:nom, :email, :password, :role)");
        $stmt->execute([
            ':nom'=> $user->getNom(),
            ':email'=> $user->getEmail(),
            ':password'=> $user->getPassword(),
            ':role'=> $user->getRole()
        ]);

        $userId = (int)$this->pdo->lastInsertId();
        $user->setId($userId);

        if($user->getRole() === 'admin'){
            $stmtFille = $this->pdo->prepare("INSERT INTO administrateurs (id_user) VALUES(:id)");
        } elseif ($user->getRole() === 'titulaire') {
            $stmtFille = $this->pdo->prepare("INSERT INTO titulaires (id_user) VALUES (:id)");
        } else {
            $stmtFille = $this->pdo->prepare("INSERT INTO preparateurs (id_user) VALUES (:id)");
        }
        return $stmtFille->execute([':id' => $userId]);
    }
}