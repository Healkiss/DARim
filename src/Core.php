<?php
use Symfony\Component\Yaml as YamlParser;
class Core
{
    public $srcPath;
    public $libraryPath;
    public $configPath;
    public $publicPath;
    public $database;
    public $controller;
    public $datas;
    public $view;
    public $parameters;

    public function __construct()
    {
        $this->srcPath = dirname(__FILE__) ."/../src/";
        $this->libraryPath = dirname(__FILE__) . "/";
        $this->configPath = dirname(__FILE__) . "/../config/";
        $this->publicPath = dirname($_SERVER['PHP_SELF']) . "/public/";
        require_once($this->libraryPath . 'Yaml/Yaml.php');
        require_once($this->libraryPath . 'Yaml/Parser.php');
        require_once($this->libraryPath . 'Yaml/Inline.php');
        require_once($this->libraryPath . 'Yaml/Escaper.php');
        require_once($this->libraryPath . 'Yaml/Dumper.php');
        
        $this->readSettings($this->configPath.'parameters.yml');
    }

    public function loadBDD()
    {
        require_once($this->libraryPath . 'Database.php');
        $this->database = new Database($this->parameters);
    }

    public function readSettings($configurationFileName)
    {
        $parametersYml = file_get_contents($configurationFileName, FILE_USE_INCLUDE_PATH);
        $result = YamlParser\Yaml::parse($parametersYml);
        $this->parameters = $result['parameters'];
    }

    public function loadTron()
    {
        require_once($this->libraryPath . 'BaseController.php');
        require_once($this->srcPath . 'TronController.php');

        $this->controller = new TronController($this);
        //to improve (add datas from controller to the core datas instead of affect)
        $this->datas = $this->controller->getDatas();
        //to improve (call view with all datas)
        $this->view = $this->controller->getView($this->datas);
    }

    public function display(){
        echo $this->view;
    }
}
?>
