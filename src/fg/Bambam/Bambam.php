<?php namespace Bambam;

use Bambam\Config\Config;
use SlimController\Slim;
use PetrGrishin\ArrayAccess\Exception\ArrayAccessException;

class Bambam
{
    private $version = '0.0.1-dev';

    protected $app;

    protected $config;

    public function __construct(Slim $app, array $config)
    {
        $this->app = $app;
        $this->config = new Config($config);
        $this->init();
    }

    public function init()
    {
        $this->initServices();
        $this->setApplicationRoute();
    }

    private function initServices()
    {
        try {
            $serviceConfig = $this->config->getValues()->getValue('services');

            foreach ($serviceConfig as $key => $service) {
                if (isset($service['class']) && !empty($service['class']) && class_exists($service['class'])) {
                    $type = (isset($service['type']) && $service['type'] == 'singleton') ? 'singleton' : 'closure';
                    if ($type == 'singleton') {
                        $this->app->container->singleton($key, function() use ($service) {
                            return new $service['class']($this->app->container, $service);
                        });
                    }
                }
            }

        } catch(ArrayAccessException $e) {

        }
    }

    private function setApplicationRoute() {
        try {
            $cleanRoutes = [];
            $routeConfig = $this->config->getValues()->getValue('routes');
            foreach ($routeConfig as $route) {
                $cleanRoutes[$route['route']] = $route['action'];
            }
            $this->app->addRoutes($cleanRoutes);
        } catch (ArrayAccessException $e) {

        } catch (\Exception $e) {

        }
    }

    public function run() {
        $this->app->run();
    }

    /**
     * @return string
     */
    public function getVersion()
    {
        return $this->version;
    }
}