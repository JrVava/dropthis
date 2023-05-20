<?php

defined('USER_ROLE_ADMIN') or define('USER_ROLE_ADMIN', 'admin');
defined('USER_ROLE_USER') or define('USER_ROLE_USER', 'user');

defined('THEME_ONE') or define('THEME_ONE', 1);
defined('THEME_TWO') or define('THEME_TWO', 2);
defined('THEME_THREE') or define('THEME_THREE', 3);

defined('CAMPAIGN_STATUS_READY') or define('CAMPAIGN_STATUS_READY', 'READY TO SEND'); // tick
defined('CAMPAIGN_STATUS_ATTENTION') or define('CAMPAIGN_STATUS_ATTENTION', 'NEEDS ATTENTION'); // cross
defined('CAMPAIGN_STATUS_REVIEW') or define('CAMPAIGN_STATUS_REVIEW', 'REVIEW');
defined('CAMPAIGN_STATUS_SENT') or define('CAMPAIGN_STATUS_SENT', 'SENT'); // envolop
defined('CAMPAIGN_STATUS_VIEW_FEEDBACK') or define('CAMPAIGN_STATUS_VIEW_FEEDBACK', 'VIEW FEEDBACK'); // star

// $domain = request()->getHttpHost();
// $removeHttpFromDomain = substr($domain, 0, strrpos($domain.",", "."));
// $domainName =preg_replace("/-+/", " ", $removeHttpFromDomain);
// $domainFirstAlpha = strtoupper(substr($domainName, 0, 1));
// defined('DOMAIN_NAME_UFIRST') or define('DOMAIN_NAME_UFIRST', ucfirst($domainName)); // uc First DomainName
// defined('DOMAIN_NAME_UPPER_CASE') or define('DOMAIN_NAME_UPPER_CASE', strtoupper($domainName)); // DomainName Upper Case
// defined('DOMAIN_FIRST_ALPHA') or define('DOMAIN_FIRST_ALPHA', strtoupper($domainFirstAlpha)); // DomainName First Alpha
