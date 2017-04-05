<?php

namespace core\harry;

class ConnectDatabase
{
    /**
     * @var object (ConnectDatabase)
     */
    protected static $instance;

    /**
     * @return object
     */
    public static function instance()
    {
        if (is_null(static::$instance)) {
            static::$instance = static::connect();
        }

        return static::$instance;
    }

    /**
     * @return \PDO
     */
    private static function connect(): \PDO
    {
        $dsn = 'mysql:host='.config()->get('app.database.host').';dbname='.config()->get('app.database.db').';charset='
            .config()->get('app.database.charset');

        $opt = [
            \PDO::ATTR_ERRMODE            => \PDO::ERRMODE_EXCEPTION,
            \PDO::ATTR_DEFAULT_FETCH_MODE => \PDO::FETCH_ASSOC,
            \PDO::ATTR_EMULATE_PREPARES   => false,
        ];

        return new \PDO($dsn, config()->get('app.database.user'), config()->get('app.database.password'), $opt);
    }
}