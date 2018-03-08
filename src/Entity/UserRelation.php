<?php
declare(strict_types=1);

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Knp\DoctrineBehaviors\Model\Timestampable\Timestampable;

/**
 * @ORM\Entity(repositoryClass="App\Repository\UserRelationRepository")
 *
 * @ORM\Table(uniqueConstraints={@ORM\UniqueConstraint(name="unique_follower_followee", columns={"follower_id", "followee_id"})})
 */
class UserRelation
{
    use Timestampable;

    /**
     * @var string
     *
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followeeRelations")
     * @ORM\JoinColumn(name="follower_id", referencedColumnName="id")
     */
    public $follower;

    /**
     * @var User
     *
     * @ORM\ManyToOne(targetEntity="User", inversedBy="followerRelations")
     * @ORM\JoinColumn(name="followee_id", referencedColumnName="id")
     */
    public $followee;

    public function __construct(User $follower, User $followee)
    {
        $this->follower = $follower;
        $this->followee = $followee;
    }

    public function getId(): string
    {
        return $this->id;
    }
}
