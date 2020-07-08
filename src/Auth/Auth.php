<?php


declare(strict_types=1);

namespace App\Auth;

use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;

class Auth
{

    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;

    }

    public function user()
    {
        if (isset($_SESSION['user'])) {
            return $this->userRepository()->find($_SESSION['user']);
        }

        return null;
    }

    public function check()
    {
        return isset($_SESSION['user']);
    }

    public function attempt(string $username, string $password) : bool {
        $user = $this->userRepository()->findOneBy([
            'username' => $username
        ]);

        if (!$user) {
            return false;
        }

        if (password_verify($password, $user->getPassword())) {
            $_SESSION['user'] = $user->getId();
            return true;
        }

        return false;
    }

    public function loginById(int $id) : bool {
        $user = $this->userRepository()->find($id);

        if ($user) {
            $_SESSION['user'] = $user->getId();
        }

        return false;
    }

    public function userRepository()
    {
        return $this->em->getRepository(User::class);
    }

    public function logout() {
        unset($_SESSION['user']);
    }

}
