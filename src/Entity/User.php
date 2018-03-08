<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unique_username", columns={"username"})})
 */
class User
{
    /**
     * @var int
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    public $username;

    public function getId(): int
    {
        return $this->id;
    }

    public function __toString()
    {
        return $this->username;
    }
}
