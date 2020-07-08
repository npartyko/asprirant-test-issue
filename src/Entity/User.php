<?php

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 * @ORM\Table(name="users")
 */
final class User
{
    /**
     * @var int|null
     * @ORM\Id()
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string|null
     * @ORM\Column()
     */
    private $username;

    /**
     * @var string|null
     * @ORM\Column()
     */
    private $password;

    /**
     * @ORM\ManyToMany(targetEntity="Movie", inversedBy="users")
     * @ORM\JoinTable(name="users_movies")
     */
    private $movies;

    public function __construct() {
        $this->movies = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUsername(): ?string
    {
        return $this->username;
    }

    /**
     * @param string $username
     *
     * @return User
     */
    public function setUsername(string $username): self
    {
        $this->username = $username;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    /**
     * @param string $password
     *
     * @return User
     */
    public function setPassword(string $password): self
    {
        $this->password = password_hash($password,PASSWORD_DEFAULT);

        return $this;
    }


    public function getMovies()
    {
        return $this->movies;
    }

}
