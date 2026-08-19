<?php

namespace Config;

use CodeIgniter\Config\BaseService;
use CodeIgniter\HTTP\IncomingRequest;
use CodeIgniter\HTTP\UserAgent;

/**
 * Services Configuration file.
 *
 * Services are simply other classes/libraries that the system uses
 * to do its job. This is used by CodeIgniter to allow the core of the
 * framework to be swapped out easily without affecting the usage within
 * the rest of your application.
 *
 * This file holds any application-specific services, or service overrides
 * that you might need. An example has been included with the general
 * method format you should use for your service methods. For more examples,
 * see the core Services file at system/Config/Services.php.
 */
class Services extends BaseService
{
    /**
     * Override IncomingRequest untuk deployment Railway.
     *
     * Pada environment hosting saat ini, request body dari CodeIgniter
     * dapat tertinggal sebagai string literal "php://input".
     * Di sini body dibaca langsung sebelum IncomingRequest dibuat,
     * sehingga JSON POST dari ESP32 masuk ke getBody()/getJSON()
     * sebagai isi JSON yang sebenarnya.
     */
    public static function incomingrequest(
        ?\Config\App $config = null,
        bool $getShared = true
    ) {
        if ($getShared) {
            return static::getSharedInstance(
                'request',
                $config
            );
        }

        $config ??= config(\Config\App::class);

        $rawBody = file_get_contents('php://input');

        if ($rawBody === false) {
            $rawBody = null;
        }

        return new IncomingRequest(
            $config,
            static::get('uri'),
            $rawBody,
            new UserAgent()
        );
    }

    /*
     * public static function example($getShared = true)
     * {
     *     if ($getShared) {
     *         return static::getSharedInstance('example');
     *     }
     *
     *     return new \CodeIgniter\Example();
     * }
     */
}
