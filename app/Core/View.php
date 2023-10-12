<?php

namespace app\Core;

use app\App;
use Exception;

class View
{
    private array $variables = [];
    private string $viewFolder;

    public function __construct(
        private readonly string $filename
    ){
        $this->viewFolder = App::getConfig()->get('viewsPath');
    }

    /**
     * Add variable to $variables array
     * @throws Exception
     */
    public function setVariable(string $variableName, mixed $variable): void
    {
        if (!preg_match('/^[a-zA-Z0-9_]+$/', $variableName)) {
            throw new Exception('Variable names can contains only letters, digits and underscore');
        }else{
            $this->variables[$variableName] = $variable;
        }
    }

    /**
     * Returns content of template with processed variables
     * @return string
     */
    public function getContent(): string
    {
        try {
            extract($this->variables);

            ob_start();
            include $this->viewFolder.$this->filename;
            $output = ob_get_clean();

            return $output === false ? '' : $output;
        }catch (\Throwable $throwable){
            return false;
        }
    }

    /**
     * Render View file content
     * @return void
     */
    public function render(): void
    {
        echo $this->getContent();
    }
}