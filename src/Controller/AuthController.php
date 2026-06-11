<?php
declare(strict_types=1);

namespace PharmaApp\Controller; 

use PharmaApp\Repository\UserRepository;
use PharmaApp\Entity\User; 

class AuthController 
{
    private UserRepository $userRepository;

    public function __construct(UserRepository $userRepository) 
    {
        $this->userRepository = $userRepository;
    }

    public function login(): void 
    {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';

            if (!empty($email) && !empty($password)) {
                $user = $this->userRepository->findByEmail($email);

                if ($user && $user->getPassword() === $password) {
                    $_SESSION['user_id'] = $user->getId();
                    $_SESSION['user_nom'] = $user->getNom();
                    $_SESSION['user_role'] = $user->getRole();

                    header('Location: /dashboard');
                    exit;
                } else {
                    $error = "Identifiants incorrects.";
                }
            } else {
                $error = "Veuillez remplir tous les champs.";
            }
        }

        $title = "Connexion - PharmaFEFO";
        require_once __DIR__ . '/../../templates/auth/login.php';
    }

    public function register(): void 
    {
        $error = null;
        $success = null;

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $nom = trim($_POST['nom'] ?? '');
            $email = trim($_POST['email'] ?? '');
            $password = $_POST['password'] ?? '';
            $role = $_POST['role'] ?? 'preparateur';

            if (!empty($nom) && !empty($email) && !empty($password)) {
                try {
                    $user = new User();
                    $user->setNom($nom)
                         ->setEmail($email)
                         ->setPassword($password)
                         ->setRole($role);

                    $this->userRepository->save($user);
                    $success = "Compte créé avec succès !";
                } catch (\Exception $e) {
                    $error = "Erreur : Cet email est déjà utilisé.";
                }
            } else {
                $error = "Veuillez remplir tous les champs.";
            }
        }

        $title = "Inscription - PharmaFEFO";
        require_once __DIR__ . '/../../templates/auth/register.php';
    }

    public function logout(): void 
    {
        session_destroy();
        header('Location: /');
        exit;
    }
}