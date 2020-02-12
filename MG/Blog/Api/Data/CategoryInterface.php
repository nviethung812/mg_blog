<?php

namespace MG\Blog\Api\Data;

interface CategoryInterface
{
    const ID      = 'id';
    const NAME    = 'name';
    /**#@-*/

    /**
     * Get ID
     *
     * @return int|null
     */
    public function getId();

    /**
     * Get name
     *
     * @return string|null
     */
    public function getName();

    /**
     * Set name
     *
     * @param string $name
     * @return CategoryInterface
     */
    public function setName($name);
}
