<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\models;

use gplcart\core\Model,
    gplcart\core\Handler;

/**
 * Manages basic behaviors and data related to File manager module
 */
class Command extends Model
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Returns an array of supported commands
     * @return array
     */
    public function getHandlers()
    {
        $commands = &gplcart_static('module.file_manager.handlers');

        if (isset($commands)) {
            return $commands;
        }

        $commands = (array) gplcart_config_get(__DIR__ . '/../config/commands.php');

        foreach ($commands as $id => &$command) {
            $command['command_id'] = $id;
        }

        $this->hook->attach('module.file_manager.handlers', $commands, $this);
        return $commands;
    }

    /**
     * Returns a single command
     * @param string $command
     * @return array
     */
    public function get($command)
    {
        $commands = $this->getHandlers();
        return empty($commands[$command]) ? array() : $commands[$command];
    }

    /**
     * Returns an array of allowed commands for the given file
     * @param \SplFileInfo|array $file
     * @return array
     */
    public function getAllowed($file)
    {
        $commands = array();
        foreach ($this->getHandlers() as $id => $command) {
            if ($this->isAllowed($command, $file)) {
                $commands[$id] = $command;
            }
        }

        return $commands;
    }

    /**
     * Whether the command is allowed for the file
     * @param array $command
     * @param \SplFileInfo $file
     * @return boolean
     */
    public function isAllowed(array $command, $file)
    {
        if (!$file instanceof \SplFileInfo || empty($command['command_id'])) {
            return false;
        }

        $result = $this->callHandler($command, 'allowed', array($file, $command));
        return is_bool($result) ? $result : false;
    }

    /**
     * Call a command handler
     * @param array $command
     * @param string $method
     * @param array $args
     * @return mixed
     */
    public function callHandler(array $command, $method, $args = array())
    {
        $handlers = $this->getHandlers();

        try {
            return Handler::call($handlers, $command['command_id'], $method, $args);
        } catch (\Exception $ex) {
            return $ex->getMessage();
        }
    }

    /**
     * Submit a command
     * @param array $command
     * @param array $args
     * @return string
     */
    public function submit(array $command, array $args)
    {
        $result = (array) $this->callHandler($command, 'submit', $args);
        $result += array('redirect' => '', 'message' => '', 'severity' => '');
        return $result;
    }

    /**
     * Returns an array of data used to display the command
     * @param array $command
     * @param array $args
     * @return array
     */
    public function getView(array $command, array $args)
    {
        return $this->callHandler($command, 'view', $args);
    }

}
