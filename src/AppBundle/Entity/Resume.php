<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * Resume
 *
 * @ORM\Table(name="resume")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\ResumeRepository")
 * @Assert\Callback({"AppBundle\Services\ResumeValidator", "validate"})
 */
class Resume
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var string
     *
     * @ORM\Column(name="firstname", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $firstname;

    /**
     * @var string
     *
     * @ORM\Column(name="lastname", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     */
    private $lastname;

    /**
     * @var int
     *
     * @ORM\Column(name="age", type="integer", nullable=true)
     */
    private $age;

    /**
     * @var string
     *
     * @ORM\Column(name="position", type="string", length=255)
     * @Assert\NotBlank()
     * @Assert\Type("string")
     * @Assert\Choice({
     *     "Backend Developer",
     *     "Frontend Developer",
     *     "Fullstack Developer",
     *     "Symfony Developer",
     *     "Product Owner",
     *     "Roxxor"
     * }, strict=true)
     */
    private $position;

    /**
     * @var bool
     *
     * @ORM\Column(name="symfony", type="boolean")
     */
    private $symfony;

    /**
     * @var string
     *
     * @ORM\Column(name="comment", type="text", nullable=true)
     */
    private $comment;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set firstname
     *
     * @param string $firstname
     *
     * @return Resume
     */
    public function setFirstname($firstname)
    {
        $this->firstname = $firstname;

        return $this;
    }

    /**
     * Get firstname
     *
     * @return string
     */
    public function getFirstname()
    {
        return $this->firstname;
    }

    /**
     * Set lastname
     *
     * @param string $lastname
     *
     * @return Resume
     */
    public function setLastname($lastname)
    {
        $this->lastname = $lastname;

        return $this;
    }

    /**
     * Get lastname
     *
     * @return string
     */
    public function getLastname()
    {
        return $this->lastname;
    }

    /**
     * Set age
     *
     * @param integer $age
     *
     * @return Resume
     */
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get age
     *
     * @return int
     */
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set position
     *
     * @param string $position
     *
     * @return Resume
     */
    public function setPosition($position)
    {
        $this->position = $position;

        return $this;
    }

    /**
     * Get position
     *
     * @return string
     */
    public function getPosition()
    {
        return $this->position;
    }

    /**
     * Set symfony
     *
     * @param boolean $symfony
     *
     * @return Resume
     */
    public function setSymfony($symfony)
    {
        $this->symfony = $symfony;

        return $this;
    }

    /**
     * Get symfony
     *
     * @return bool
     */
    public function getSymfony()
    {
        return $this->symfony;
    }

    /**
     * Set comment
     *
     * @param string $comment
     *
     * @return Resume
     */
    public function setComment($comment)
    {
        $this->comment = $comment;

        return $this;
    }

    /**
     * Get comment
     *
     * @return string
     */
    public function getComment()
    {
        return $this->comment;
    }

    /**
     * @param array $array
     *
     * @return Resume
     */
    public static function fromArray(array $array = [])
    {
        $instance = new self();

        foreach ($array as $key => $val) {
            if (property_exists(self::class, $key)) {
                $method = 'set'.ucfirst($key);
                $instance->$method($val);
            }
        }
        return $instance;
    }
}
