<?php
/**
 * 2019-06-28.
 */

declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Index;

/**
 * @ORM\Entity(repositoryClass="App\Repository\MovieRepository")
 * @ORM\Table(name="movie", indexes={@Index(columns={"title"})})
 */
final class Movie
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
    private $title;

    /**
     * @var string|null
     * @ORM\Column()
     */
    private $link;

    /**
     * @var string|null
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @var \DateTime|null
     * @ORM\Column(type="datetime", name="pub_date")
     */
    private $pubDate;

    /**
     * @var string|null
     * @ORM\Column(nullable=true)
     */
    private $image;

    /**
     * @ORM\ManyToMany(targetEntity="User", mappedBy="movies")
     */
    private $users;

    /**
     * @var integer|null
     */
    private $like;

    public function __construct() {
        $this->users = new \Doctrine\Common\Collections\ArrayCollection();
    }

    /**
     * @return string|null
     */
    public function getImage(): ?string
    {
        return $this->image;
    }

    /**
     * @param string|null $image
     *
     * @return Movie
     */
    public function setImage(?string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string|null
     */
    public function getTitle(): ?string
    {
        return $this->title;
    }

    /**
     * @param string|null $title
     *
     * @return Movie
     */
    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getLink(): ?string
    {
        return $this->link;
    }

    /**
     * @param string|null $link
     *
     * @return Movie
     */
    public function setLink(?string $link): self
    {
        $this->link = $link;

        return $this;
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @param string|null $description
     *
     * @return Movie
     */
    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return \DateTime|null
     */
    public function getPubDate(): ?\DateTime
    {
        return $this->pubDate;
    }

    /**
     * @param \DateTime|null $pubDate
     *
     * @return Movie
     */
    public function setPubDate(?\DateTime $pubDate): self
    {
        $this->pubDate = $pubDate;

        return $this;
    }

    public function getUsers() {
        return $this->users;
    }

    public function getLike() {
        return $this->like;
    }

    public function setLike(?bool $like): self
    {
        $this->like = $like;

        return $this;
    }
}
