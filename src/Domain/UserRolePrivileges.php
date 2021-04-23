<?php

namespace Romi\Domain;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Table(name="user_role_privileges")
 * @ORM\Entity(repositoryClass="Romi\Repository\UserRolePrivilegesRepository")
 */

class UserRolePrivileges implements \JsonSerializable
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity="Tenant")
     * @ORM\JoinColumn(name="tenant_id", referencedColumnName="id", nullable=false)
     */
    private $tenantId;

    /**
     * @ORM\ManyToOne(targetEntity="UserRole")
     * @ORM\JoinColumn(name="user_role_id", referencedColumnName="id", nullable=false)
     */
    private $userRoleId;

    /**
     * @ORM\ManyToOne(targetEntity="Privileges")
     * @ORM\JoinColumn(name="privileges_id", referencedColumnName="id", nullable=false)
     */
    private $privilegesId;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="created_at", type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="updated_at", type="datetime", nullable=true)
     */
    private $updatedAt;


    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param int $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get the value of tenantId
     */
    public function getTenantId()
    {
        return $this->tenantId;
    }

    /**
     * Set the value of tenantId
     *
     * @return  self
     */
    public function setTenantId($tenantId)
    {
        $this->tenantId = $tenantId;

        return $this;
    }

    /**
     * Get the value of userRoleId
     */
    public function getUserRoleId()
    {
        return $this->userRoleId;
    }

    /**
     * Set the value of userRoleId
     *
     * @return  self
     */
    public function setUserRoleId($userRoleId)
    {
        $this->userRoleId = $userRoleId;

        return $this;
    }

    /**
     * Get the value of privilegesId
     */
    public function getPrivilegesId()
    {
        return $this->privilegesId;
    }

    /**
     * Set the value of privilegesId
     *
     * @return  self
     */
    public function setPrivilegesId($privilegesId)
    {
        $this->privilegesId = $privilegesId;

        return $this;
    }



    /**
     * @return \DateTime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * @param \DateTime $createdAt
     */
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * @return \DateTime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTime $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }


    /** @ORM\PrePersist
     * @throws \Exception
     */
    public function prePersist()
    {
        $this->createdAt = new \DateTime();
    }

    /** @ORM\PreUpdate
     * @throws \Exception
     */
    public function preUpdate()
    {
        $this->updatedAt = new \DateTime();
    }



    public function jsonSerialize()
    {
        return [
            'id' => $this->id,
            'userRoleId' => $this->userRoleId,
            'privilegesId' => $this->privilegesId
        ];
    }
}
