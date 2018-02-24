<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager;

use gplcart\core\Container;

/**
 * Main class for File manager module
 */
class Main
{

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
            'menu' => array(
                'admin' => 'File manager' // @text
            ),
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
        foreach (array_keys($this->getHandlers()) as $command_id) {
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
        $permissions['module_file_manager'] = gplcart_text('File manager: access');

        foreach ($this->getHandlers() as $command_id => $command) {
            $permissions["module_file_manager_$command_id"] = gplcart_text('File manager: perform command @name', array(
                '@name' => $command['name']));
        }
    }

    /**
     * Returns an array of command handlers
     * @return array
     */
    public function getHandlers()
    {
        return $this->getCommandModel()->getHandlers();
    }

    /**
     * Returns Command model instance
     * @return \gplcart\modules\file_manager\models\Command
     */
    public function getCommandModel()
    {
        /** @var \gplcart\modules\file_manager\models\Command $instance */
        $instance = Container::get('gplcart\\modules\\file_manager\\models\\Command');
        return $instance;
    }

}
