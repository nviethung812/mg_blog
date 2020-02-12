<?php

namespace MG\Blog\Api;

interface CategoryRepositoryInterface
{
    public function save(\MG\Blog\Api\Data\CategoryInterface $category);
    public function getById($categoryId);
    public function delete(\MG\Blog\Api\Data\CategoryInterface $category);
}
