<?php

namespace MG\Blog\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\ScopeInterface;

class ConfigData extends AbstractHelper
{
    // Config section ID
    const XML_PATH_MANAGE_BLOG = 'manage_blog/';

    public function getConfigValue($field)
    {
        return $this->scopeConfig->getValue(
            $field,
            ScopeInterface::SCOPE_STORE,
            null
        );
    }

    public function getPaginationConfig($code)
    {
        return $this->getConfigValue(self::XML_PATH_MANAGE_BLOG . 'pagination/' . $code);
    }

    public function getUrlConfig($code)
    {
        return $this->getConfigValue(self::XML_PATH_MANAGE_BLOG . 'url/' . $code);
    }
}
