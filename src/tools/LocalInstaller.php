<?php

namespace Tools;

// todo: https://getcomposer.org/doc/articles/scripts.md

final class LocalInstaller
{
    public static function run(): void
    {
        try {
            $mysqli = new \mysqli('srv-db.backend', 'root', 'passwd');
        } catch (\ErrorException $e) {
            return;
        }
        if ($mysqli->connect_errno) {
            return;
        }
        self::createEnv();
        if ($mysqli->query(
            'CREATE DATABASE `stripe` DEFAULT CHARACTER SET utf8 COLLATE utf8_general_ci;'
        )) {
            // only once!
            self::migrate();
        }
    }

    private static function createEnv(): void
    {
        file_exists(__DIR__ . '/../../.env')
            || copy(__DIR__ . '/../../.env.example', __DIR__ . '/../../.env');
    }

    private static function migrate(): void
    {
        exec('cd ../../ & php artisan migrate');
    }
}
