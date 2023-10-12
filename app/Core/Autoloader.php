<?php

namespace app\Core;

class Autoloader
{
    public function __construct(private readonly string $basePath){}

    /**
     * Register the loader with the SPL autoloader stack.
     * @return void
     */
    public function register(): void
    {
        spl_autoload_register([$this, 'mainRegister']);
    }

    /**
     * Main register function
     * @param $className
     * @return void
     */
    private function mainRegister($className): void
    {
        $file = $this->basePath . $className . '.php';
        $file = str_replace(array('app\\', '\\'), array('', DIRECTORY_SEPARATOR), $file);
        if (file_exists($file)) {
            include $file;
        }
    }
}