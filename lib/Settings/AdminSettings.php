<?php

namespace OCA\RocketIntegration\Settings;

use OCA\RocketIntegration\AppInfo\Application;
use OCA\RocketIntegration\Db\Config;
use OCP\AppFramework\Http\TemplateResponse;
use OCP\AppFramework\Controller;
use OCP\Settings\ISettings;
use OCP\IServerContainer;

class AdminSettings implements ISettings {
    protected $appName;
    protected $config;
    protected $server;

    public function __construct(IServerContainer $server)
    {
        $this->appName = Application::APP_ID;
        $this->config = new Config();
        $this->server = $server;
    }

    public function getForm()
    {
        return new TemplateResponse($this->appName, 'admin', [
            'formUrl' => ($this->server->getURLGenerator())->linkToRouteAbsolute($this->appName . '.config.setupUrl'),
            'resetConfig' => ($this->server->getURLGenerator())->linkToRouteAbsolute($this->appName . '.config.resetConfig'),
            'rocketUrl' => $this->config->getUrl(),
        ]);
    }

    /**
     * @return string the section ID, e.g. 'sharing'
     */
    public function getSection()
    {
        return 'additional';
    }

    /**
     * @return int whether the form should be rather on the top or bottom of
     * the admin section. The forms are arranged in ascending order of the
     * priority values. It is required to return a value between 0 and 100.
     */
    public function getPriority()
    {
        return 0;
    }
}
