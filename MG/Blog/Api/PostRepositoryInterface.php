<?php

namespace MG\Blog\Api;

interface PostRepositoryInterface
{
    public function save(\MG\Blog\Api\Data\PostInterface $post);
    public function getById($postId);
    public function delete(\MG\Blog\Api\Data\PostInterface $post);
    public function isNameExists($name);
}
