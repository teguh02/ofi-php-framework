<?php

/**
 * OFI PHP Framework
 * Define project name and environment settings.
 */

 // Your Project Name
define('PROJECTNAME', 'OFI Framework');

 // Your Project Environment production or development
define('ENVIRONMENT', 'development');

// Your Project URL
// do not use the '/' at the end of the url http://localhost: 9000
define('PROJECTURL', 'http://localhost:9000');

// Upload file function limiter 
// default 1044070 = 1 mb
define('MAXUPLOAD', 1044070);

// You can overwrite original error page
// with your design when you change overwriteErrorPage 
// to true, so you can use your own 
// error page design
// please see inside resources/views/errorPage
define('overwriteErrorPage', false);

// you can insert your own code 
// to <head> tag by fill codeToHeader
// array
define('codeToHeader', [
    '<link rel="stylesheet" href="//cdn.datatables.net/1.10.23/css/jquery.dataTables.min.css">'
]);

// you can insert your own code
// before </body> tag by fill codeBeforeBody
// array
define('codeBeforeBody', [
    '<script src="//cdn.datatables.net/1.10.23/js/jquery.dataTables.min.js"></script>'
]);

// Database MYSQLI connection configuration
$config = [
    'driver'        => 'mysql',
    'port'          => 3306,
    'host'          => 'localhost',
    'database'        => 'ofi',
    'username'      => 'root',
    'password'      => ''
];

/**
 * Define SEO tag for your website
 * please change according to your needs.
 *
 * What is google-site-verification?
 * For example for the tag :
 * <meta name="google-site-verification" content="ZJTLoB1wuXx1aV_gw0ATcBmS0tk8M3IuUkOMi_Qi6C8" />
 *
 * You can only take the meta tag content, and then the content is
 *          ZJTLoB1wuXx1aV_gw0ATcBmS0tk8M3IuUkOMi_Qi6C8
 * please put thats content in define('GoogleSiteVerification', '');
 */

// Description for your website
define('DESCRIPTION', 'OFI PHP Framework');
define('AUTHOR', 'Teguh Rijanandi');

// Separate with comma
define('KEYWORDS', 'Framework, PHP, Backend');
define('GoogleSiteVerification', '');

/**
 * Define your sites mailer
 * This is default config for gmail
 * Default Mailer is use SMTP.
 *
 * Tips!
 * First you must turn on Access for less secure apps in
 * https://myaccount.google.com/lesssecureapps
 *
 * And then you can change this gmail username and password
 * with your's gmail account
 *
 * Enable SMTP debugging
 * 0 = off (for production use)
 * 1 = client messages
 * 2 = client and server messages
 */
define('SMTPSecure', 'tls');
define('Host', 'smtp.gmail.com');
define('Port', 587);
define('GmailUsername', 'youremail@gmail.com');
define('GmailPassword', 'yourpass');
define('SMTPDebug', 0);
define('senderEmail', 'system@ofiFramework.com');
define('senderName', 'OFI Framework Mailing System');

/**
 * Risk configuration
 * Don't change it when you not understand about
 * our system or you can break our system
 */


// Locate where is upload folder
define('UPLOADPATH', dirname(__FILE__). '/assets/upload');

// define root folder 
define('BASEURL', dirname(__FILE__) . '/');