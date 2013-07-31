<?php

namespace Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr;

use Symfony\Cmf\Bundle\BlockBundle\Doctrine\Phpcr\ContainerBlock;

/**
 * Special container block that renders child items in a way suitable for a
 * slideshow. Note that you need to add some javascript to actually get the
 * blocks to do a slideshow.
 */
class SlideshowBlock extends ContainerBlock
{
    /**
     * @var string
     */
    protected $title;

    /**
     * {@inheritdoc}
     */
    public function getType()
    {
        return 'cmf.block.slideshow';
    }

    /**
     * Get title
     *
     * @return string
     */
    public function getTitle()
    {
        return $this->title;
    }

    /**
     * Set title
     *
     * @param string $title
     */
    public function setTitle($title)
    {
        $this->title = $title;
    }
}
