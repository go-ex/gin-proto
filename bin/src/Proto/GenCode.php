<?php


namespace GoProto\Proto;


class GenCode
{
    private static $controller;

    private static $wire;

    private static $action;

    public static function getController(string $package, string $name, string $file): string
    {
        if (!self::$controller) {
            self::$controller = file_get_contents(__DIR__.'/template/controller');
        }

        return str_replace(['{package}', '{name}', '{filename}'], [$package, $name, $file], self::$controller);
    }

    public static function getWire(string $package, string $name): string
    {
        if (!self::$wire) {
            self::$wire = file_get_contents(__DIR__.'/template/wire');
        }

        return str_replace(['{package}', '{name}'], [$package, $name], self::$wire);
    }

    public static function getAction(string $package, string $service, string $name, string $doc): string
    {
        if (!self::$action) {
            self::$action = file_get_contents(__DIR__.'/template/action');
        }
        return str_replace(
            ['{package}', '{service}', '{name}', '{doc}'],
            [$package, $service, $name, $doc],
            self::$action
        );
    }
}