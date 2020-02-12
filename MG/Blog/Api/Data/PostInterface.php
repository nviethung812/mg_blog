<?php

namespace MG\Blog\Api\Data;

interface PostInterface
{
    const POST_ID = "post_id";
    const NAME    = "name";
    const SHORT_DESCRIPTION = "short_description";
    const DESCRIPTION = "description";
    const CONTENT = "content";
    const STATUS  = "status";
    const URL_KEY = "url_key";
    const PUBLISH_DATE_FROM = "publish_date_from";
    const PUBLISH_DATE_TO = "publish_date_to";
    const CREATION_TIME = "creation_time";
    const UPDATE_TIME = "update_time";
    const THUMBNAIL = "thumbnail";

    public function getPostId();
    public function getName();
    public function getShortDescription();
    public function getDescription();
    public function getContent();
    public function getStatus();
    public function getUrlKey();
    public function getPublishDateFrom();
    public function getPublishDateTo();
    public function getCreationTime();
    public function getUpdateTime();
    public function getThumbnail();

    public function setName($name);
    public function setShortDescription($shortDescription);
    public function setDescription($description);
    public function setContent($content);
    public function setStatus($status);
    public function setUrlKey($urlKey);
    public function setPublishDateFrom($publishDateFrom);
    public function setPublishDateTo($publishDateTo);
    public function setCreationTime($creationTime);
    public function setUpdateTime($updateTime);
    public function setThumbnail($thumbnail);

}
