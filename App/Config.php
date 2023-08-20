<?php

namespace App;

/**
 * Application configuration
 *
 * PHP version 7.0
 */
class Config
{

    /**
     * Database host (localhost for local development or db for docker)
     * @var string
     */
    const DB_HOST = 'db';

    /**
     * Database name
     * @var string
     */
    const DB_NAME = 'videgrenierenligne';

    /**
     * Database user
     * @var string
     */
    const DB_USER = 'webapplication';

    /**
     * Database password
     * @var string
     */
    const DB_PASSWORD = '653rag9T';

    /**
     * Show or hide error messages on screen
     * @var boolean
     */
    const SHOW_ERRORS = true;
}
