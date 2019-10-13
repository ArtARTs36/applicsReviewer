<?php

namespace UserBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Security\Core\Role\RoleInterface;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class User
 * @package UserBundle\Entity
 * @ORM\Entity
 * @ORM\Table(name="role")
 */
class UserRole implements RoleInterface
{
    const ROLE_USER = 1;
    const ROLE_ADMIN = 2;
    const ROLE_USER_IDENTITY = 'ROLE_USER';
    const ROLE_ADMIN_IDENTITY = 'ROLE_ADMIN';

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;

    /**
     * @ORM\Column(type="string", unique=true)
     */
    private $role;

    /**
     * @var \Doctrine\Common\Collections\ArrayCollection
     * @ORM\ManyToMany(targetEntity="User", mappedBy="roles")
     *
     */
    private $users;

    /**
     * @ORM\Column(type="integer")
     */
    private $commentAdd;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

    public function getRole()
    {
        return $this->role;
    }

    public function setRole($role)
    {
        $this->role = $role;
    }

    /**
     * Get id
     *
     * @return integer
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set name
     *
     * @param string $name
     *
     * @return UserRole
     */
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get name
     *
     * @return string
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Add user
     *
     * @param User $user
     *
     * @return UserRole
     */
    public function addUser(User $user)
    {
        $this->users[] = $user;

        return $this;
    }

    /**
     * Remove user
     *
     * @param User $user
     */
    public function removeUser(User $user)
    {
        $this->users->removeElement($user);
    }

    /**
     * Get users
     *
     * @return Collection
     */
    public function getUsers()
    {
        return $this->users;
    }

    public function getCommentAdd()
    {
        return $this->commentAdd;
    }

    public function setCommentAdd($commentAdd = 0)
    {
        $this->commentAdd = $commentAdd;
    }
}
