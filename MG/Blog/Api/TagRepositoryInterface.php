<?php

namespace MG\Blog\Api;

interface TagRepositoryInterface
{
    public function save(\MG\Blog\Api\Data\TagInterface $tag);
    public function getById($tagId);
    public function delete(\MG\Blog\Api\Data\TagInterface $tag);
}
