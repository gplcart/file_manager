<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager;

use gplcart\core\Config,
    gplcart\core\Container;

/**
 * Main class for File manager module
 */
class Module
{

    /**
     * Config class instance
     * @var \gplcart\core\Config $config
     */
    protected $config;

    /**
     * @param Config $config
     */
    public function __construct(Config $config)
    {
        $this->config = $config;
    }

    /**
     * Implements hook "route.list"
     * @param array $routes
     */
    public function hookRouteList(array &$routes)
    {
        $routes['admin/module/settings/file-manager'] = array(
            'access' => 'module_edit',
            'handlers' => array(
                'controller' => array('gplcart\\modules\\file_manager\\controllers\\Settings', 'editSettings')
            )
        );

        $routes['admin/tool/file-manager'] = array(
            'access' => 'module_file_manager',
            'menu' => array('admin' => /* @text */'File manager'),
            'handlers' => array(
                'controller' => array('gplcart\\modules\\file_manager\\controllers\\FileManager', 'viewFileManager')
            )
        );
    }

    /**
     * Implements hook "validator.handlers"
     * @param array $handlers
     */
    public function hookValidatorHandlers(array &$handlers)
    {
        foreach (array_keys($this->getModel()->getHandlers()) as $command_id) {
            $class = str_replace('_', '', $command_id);
            $handlers["file_manager_$command_id"] = array(
                'handlers' => array(
                    'validate' => array("gplcart\\modules\\file_manager\\handlers\\validators\\$class", "validate$class")
                ),
            );
        }

        $handlers['file_manager_settings'] = array(
            'handlers' => array(
                'validate' => array('gplcart\\modules\\file_manager\\handlers\\validators\\Settings', 'validateSettings')
            ),
        );
    }

    /**
     * Implements hook "user.role.permissions"
     * @param array $permissions
     */
    public function hookUserRolePermissions(array &$permissions)
    {
        $permissions['module_file_manager'] = $this->getLanguage()->text('File manager: access');

        foreach ($this->getModel()->getHandlers() as $command_id => $command) {
            $permissions["module_file_manager_$command_id"] = $this->getLanguage()->text('File manager: perform command @name', array('@name' => $command['name']));
        }
    }

    /**
     * Implements hook "module.install.before"
     * @param null|string $result
     */
    public function hookModuleInstallBefore(&$result)
    {
        if (!class_exists('ZipArchive')) {
            $result = $this->getLanguage()->text('Class ZipArchive does not exist');
        }
    }

    /**
     * Implements hook "module.uninstall.after"
     */
    public function hookModuleUninstallAfter()
    {
        foreach (array_keys($this->config->select()) as $key) {
            if (strpos($key, 'module_file_manager_') === 0) {
                $this->config->reset($key);
            }
        }
    }

    /**
     * Returns Command model instance
     * @return \gplcart\modules\file_manager\models\Command
     */
    protected function getModel()
    {
        return Container::get('gplcart\\modules\\file_manager\\models\\Command');
    }

    /**
     * Language model class instance
     * @return \gplcart\core\modules\Language
     */
    protected function getLanguage()
    {
        return Container::get('gplcart\\core\\modules\\Language');
    }

}