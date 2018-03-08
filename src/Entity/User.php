<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRepository")
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unique_username", columns={"username"})})
 */
class User implements UserInterface, \Serializable
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

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=255)
     */
    public $password;

    /**
     * @var string[]
     *
     * @ORM\Column(type="simple_array")
     */
    public $roles;

    /**
     * @var Collection|UserRelation[]
     *
     * @ORM\OneToMany(targetEntity="UserRelation", mappedBy="followee", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    public $followerRelations;

    /**
     * @var Collection|UserRelation[]
     *
     * @ORM\OneToMany(targetEntity="UserRelation", mappedBy="follower", cascade={"persist"}, orphanRemoval=true)
     * @ORM\OrderBy({"createdAt"="DESC"})
     */
    public $followeeRelations;

    public function __construct()
    {
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function eraseCredentials(): void
    {
    }

    public function serialize(): string
    {
        return serialize([
            $this->id,
            $this->username,
            $this->password,
        ]);
    }

    public function unserialize($serialized): void
    {
        list(
            $this->id,
            $this->username,
            $this->password,
        ) = unserialize($serialized);
    }

    public function follow(User $user): self
    {
        if ($user === $this) {
            throw new \LogicException('You cannot follow yourself.');
        }

        $this->followeeRelations->add($relation = new UserRelation($this, $user));
        $user->followerRelations->add($relation);

        return $this;
    }

    public function unfollow(User $user): self
    {
        foreach ($this->followeeRelations as $followeeRelation) {
            if ($followeeRelation->followee === $user) {
                $this->followeeRelations->removeElement($followeeRelation);
                break;
            }
        }

        foreach ($user->followerRelations as $followerRelation) {
            if ($followerRelation->follower === $this) {
                $user->followerRelations->removeElement($followerRelation);
                break;
            }
        }

        return $this;
    }

    /**
     * @return User[]
     */
    public function getFollowers(): array
    {
        $followers = [];

        foreach ($this->followerRelations as $followerRelation) {
            $followers[] = $followerRelation->follower;
        }

        return $followers;
    }

    /**
     * @return User[]
     */
    public function getFollowees(): array
    {
        $followees = [];

        foreach ($this->followeeRelations as $followeeRelation) {
            $followees[] = $followeeRelation->followee;
        }

        return $followees;
    }

    public function __toString(): string
    {
        return $this->username;
    }
}
