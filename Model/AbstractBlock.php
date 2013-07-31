<?php

namespace Symfony\Cmf\Bundle\BlockBundle\Model;

use Sonata\BlockBundle\Model\BlockInterface;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Validator\ExecutionContext;


/**
 * Base class for all blocks - connects to Sonata Blocks
 *
 * Parent handling: The BlockInterface defines a parent to link back to
 * a container block if there is one. PHPCR-ODM blocks always have a parent
 * *document*. If the parent document is a BlockInterface, it is considered
 * a parent in the sonata sense as well.
 *
 * @Assert\Callback(methods={"isSettingsValid"})
 */
abstract class AbstractBlock implements BlockInterface
{
    /**
     * @var string
     */
    protected $id;

    /**
     * @var string
     */
    protected $name;

    /**
     * @var object
     */
    protected $parentDocument;

    /**
     * @var bool
     */
    protected $enabled = true;

    /**
     * @var int
     */
    protected $ttl = 86400;

    /**
     * @var array
     */
    protected $settings = array();

    /**
     * @var \DateTime
     */
    protected $createdAt;

    /**
     * @var \DateTime
     */
    protected $updatedAt;

    /**
     * @param string $src
     *
     * @return string
     */
    protected function dashify($src)
    {
        return preg_replace('/[\/\.]/', '-', $src);
    }

    /**
     * Set id
     *
     * @param string $id
     */
    public function setId($id)
    {
        $this->id = $id;
    }

    /**
     * Get id
     *
     * @return string $id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set type
     *
     * @param string $type
     */
    public function setType($type)
    {
    }

    /**
     * Set enabled
     *
     * @param boolean $enabled
     */
    public function setEnabled($enabled)
    {
        $this->enabled = $enabled;
    }

    /**
     * Get enabled
     *
     * @return boolean $enabled
     */
    public function getEnabled() {
        return $this->enabled;
    }

    /**
     * Set position
     *
     * @param integer $position
     */
    public function setPosition($position)
    {
        // TODO: implement
    }

    /**
     * Get position
     *
     * @return integer $position
     */
    public function getPosition()
    {
        $siblings = $this->getParent()->getChildren();
        return array_search($siblings->indexOf($this), $siblings->getKeys());
    }

    /**
     * Sets the creation date and time
     *
     * @param \Datetime $createdAt
     */
    public function setCreatedAt(\DateTime $createdAt = null)
    {
        $this->createdAt = $createdAt;
    }

    /**
     * Get creation date
     *
     * @return \Datetime
     */
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the last update date and time
     *
     * @param \Datetime $updatedAt
     */
    public function setUpdatedAt(\DateTime $updatedAt = null)
    {
        $this->updatedAt = $updatedAt;
    }

    /**
     * Get update date
     *
     * @return \Datetime
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * Add children
     *
     * @param BlockInterface $children
     */
    public function addChildren(BlockInterface $children)
    {
    }

    /**
     * Get children
     *
     * @return \Doctrine\Common\Collections\Collection $children
     */
    public function getChildren()
    {
        return null;
    }

    /**
     * @abstract
     * @return bool
     */
    public function hasChildren()
    {
        return false;
    }

    public function setName($name)
    {
        $this->name = $name;
    }

    public function getName()
    {
        return $this->name;
    }

    /**
     * set parent document regardless of type. this can be a ContainerBlock
     * but also any PHPCR-ODM document
     *
     * @param object $parent
     */
    public function setParentDocument($parent)
    {
        $this->parentDocument = $parent;
    }

    /**
     * get the parent document
     *
     * @return object|null $document
     */
    public function getParentDocument()
    {
        return $this->parentDocument;
    }

    /**
     * {@inheritDoc}
     *
     * Redirect to setParentDocument
     */
    public function setParent(BlockInterface $parent = null)
    {
        $this->setParentDocument($parent);
    }

    /**
     * {@inheritDoc}
     *
     * Check if parentDocument is instanceof BlockInterface, otherwise return null
     */
    public function getParent()
    {
        if ($this->parentDocument instanceof BlockInterface) {

            return $this->parentDocument;
        }

        return null;
    }

    /**
     * {@inheritDoc}
     */
    public function hasParent()
    {
        return ($this->parentDocument instanceof BlockInterface);
    }

    /**
     * Set ttl
     *
     * @param integer $ttl
     */
    public function setTtl($ttl)
    {
        $this->ttl = $ttl;
    }

    /**
     * Get ttl
     *
     * @return integer
     */
    public function getTtl()
    {
        return $this->ttl;
    }

    /**
     * toString ...
     *
     * @return string
     */
    public function __toString()
    {
        return (string) $this->name;
    }

    /**
     * Validate settings
     *
     * @param \Symfony\Component\Validator\ExecutionContext $context
     */
    public function isSettingsValid(ExecutionContext $context)
    {
        foreach ($this->getSettings() as $value) {
            if (is_array($value)) {
                $context->addViolationAt('settings', 'A multidimensional array is not allowed, only use key-value pairs.');
            }
        }
    }

    /**
     * Set settings
     *
     * @param array $settings
     */
    public function setSettings(array $settings = array())
    {
        $this->settings = $settings;
    }

    /**
     * Get settings
     *
     * @return array $settings
     */
    public function getSettings()
    {
        return $this->settings;
    }

    /**
     * @param $name
     * @param $value
     * @return void
     */
    public function setSetting($name, $value)
    {
        $this->settings[$name] = $value;
    }

    /**
     * @param $name
     * @param null $default
     * @return null
     */
    public function getSetting($name, $default = null)
    {
        return isset($this->settings[$name]) ? $this->settings[$name] : $default;
    }

    /**
     * @return string
     */
    public function getDashifiedId()
    {
        return $this->dashify($this->id);
    }

    /**
     * @return string
     */
    public function getDashifiedType()
    {
        return $this->dashify($this->getType());
    }
}
