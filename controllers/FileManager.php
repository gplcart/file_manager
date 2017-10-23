<?php

/**
 * @package File manager 
 * @author Iurii Makukh <gplcart.software@gmail.com> 
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com> 
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+ 
 */

namespace gplcart\modules\file_manager\controllers;

use SplFileInfo;
use gplcart\modules\file_manager\models\Command as FileManagerCommandModel,
    gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;
use gplcart\core\controllers\backend\Controller as BackendController;

/**
 * Handles incoming requests and outputs data related to File manager module
 */
class FileManager extends BackendController
{

    /**
     * The current file manager command
     * @var array
     */
    protected $data_command;

    /**
     * The current absolute path
     * @var string
     */
    protected $data_absolute_path;

    /**
     * The current relative path
     * @var string
     */
    protected $data_path;

    /**
     * The current SplFileInfo file object
     * @var \SplFileInfo $data_file
     */
    protected $data_file;

    /**
     * An array of SplFileInfo objects of selected files
     * @var array
     */
    protected $data_selected = array();

    /**
     * The current access
     * @var bool
     */
    protected $data_access = true;

    /**
     * Command model class instance
     * @var \gplcart\modules\file_manager\models\Command $command
     */
    protected $command;

    /**
     * Scanner model class instance
     * @var \gplcart\modules\file_manager\models\Scanner $scanner
     */
    protected $scanner;

    /**
     * @param FileManagerCommandModel $command
     * @param FileManagerScannerModel $scanner
     */
    public function __construct(FileManagerCommandModel $command,
            FileManagerScannerModel $scanner)
    {
        parent::__construct();

        $this->command = $command;
        $this->scanner = $scanner;
    }

    /**
     * Sets the current working file path
     */
    protected function setFileFileManager()
    {
        $initial_path = $this->scanner->getInitialPath();
        $initial_absolute_path = $this->scanner->getInitialPath(true);
        $this->data_path = $this->getQuery('path', $initial_path);

        if (empty($this->data_path)) {
            $this->data_path = $initial_path;
        }

        $this->data_absolute_path = gplcart_file_absolute($this->data_path);
        if (gplcart_path_starts($this->data_absolute_path, $initial_absolute_path) && file_exists($this->data_absolute_path)) {
            $this->data_access = true;
            $this->data_file = new SplFileInfo($this->data_absolute_path);
        } else {
            $this->data_access = false;
        }

        $this->setSelectedFileManager();
    }

    /**
     * Prepare and set selected files
     */
    protected function setSelectedFileManager()
    {
        foreach ($this->session->get('file_manager_selected', array()) as $path) {
            $file = new SplFileInfo(gplcart_file_absolute($path));
            if (is_object($file)) {
                $this->data_selected[$path] = $file;
            }
        }
    }

    /**
     * Displays the file manager page
     */
    public function viewFileManager()
    {
        $this->setFileFileManager();
        $this->setAccessFileManager();
        $this->setDataMessagesFileManager();

        $this->setData('access', $this->data_access);
        $this->setData('command', $this->data_command);
        $this->setData('selected', $this->data_selected);
        $this->setData('tabs', $this->getTabsFileManager());
        $this->setData('actions', $this->getActionsFileManager());
        $this->setData('process_selected', $this->isQuery('selected'));

        $this->submitFileManager();
        $this->setDataContendFileManager();

        $rendered = $this->render('file_manager|filemanager', $this->data);

        if ($this->isQuery('output')) {
            $this->response->html($rendered);
        }

        $this->setJsFileManager();
        $this->setCssFileManager();

        $this->setTitleFileManager();
        $this->setBreadcrumbFileManager();

        $this->setData('rendered_filemanager', $rendered);
        $this->outputViewFileManager();
    }

    /**
     * Sets rendered HTML for the current command
     */
    protected function setDataContendFileManager()
    {
        if (!$this->data_access) {
            return null;
        }

        $data = $this->command->getView($this->data_command, array($this->data_file, $this));

        if (is_string($data)) {
            $this->setMessageFileManager($data, 'warning');
            return null;
        }

        settype($data, 'array');

        $template_data = reset($data);
        $template = key($data);
        $template_data['file'] = $this->data_file;
        $template_data['breadcrumbs'] = $this->getPathBreadcrumbsFileManager();
        $rendered = $this->render($template, array_merge($template_data, $this->data));

        $this->setData('content', $rendered);
    }

    /**
     * Sets messages
     */
    protected function setDataMessagesFileManager()
    {
        $existing_messages = $this->getData('messages', array());
        $session_messages = (array) $this->session->getMessage(null, 'file_manager_messages');
        $messages = array_merge_recursive($session_messages, $existing_messages);
        $this->setData('messages', $messages);
    }

    /**
     * Sets CSS files
     */
    protected function setCssFileManager()
    {
        $this->setCss('system/modules/file_manager/css/common.css');
    }

    /**
     * Sets JS files
     */
    protected function setJsFileManager()
    {
        $this->setJsSettings('file_manager', array('upload_limit' => ini_get('max_file_uploads')));
        $this->setJs('system/modules/file_manager/js/common.js');
    }

    /**
     * Sets access to the current path
     * @return bool
     */
    protected function setAccessFileManager()
    {
        $path_from_query = $this->getQuery('cmd', 'list');
        $this->data_command = $this->command->get($path_from_query);

        if (empty($this->data_command['command_id'])) {
            return $this->data_access = false;
        }

        if (!$this->access("module_file_manager_{$this->data_command['command_id']}")) {
            return $this->data_access = false;
        }

        if (!empty($this->data_selected) && $this->isQuery('selected')) {
            return $this->data_access = true;
        }

        if (!$this->command->isAllowed($this->data_command, $this->data_file)) {
            return $this->data_access = false;
        }

        if ($this->accessPathAccessFileManager($this->data_path)) {
            return $this->data_access = true;
        }

        return $this->data_access = $this->access('module_file_manager');
    }

    /**
     * Whether the current user has access to the file
     * @param string $path
     * @return boolean
     */
    protected function accessPathAccessFileManager($path)
    {
        $role_id = $this->getUser('role_id');
        $settings = $this->config->module('file_manager');

        if (empty($settings['access'][$role_id])) {
            return true;
        }

        foreach ($settings['access'][$role_id] as $pattern) {
            if (gplcart_path_match($path, $pattern)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Returns an array of path breadcrumbs
     * @return array
     */
    protected function getPathBreadcrumbsFileManager()
    {
        $initial_path = $this->scanner->getInitialPath();
        $breadcrumbs = array(array('text' => $this->text('Home'), 'path' => $initial_path));

        $path = '';
        foreach (explode('/', $this->data_path) as $folder) {
            $path .= "$folder/";
            $trimmed_path = trim($path, '/');
            if ($trimmed_path !== $initial_path && $this->accessPathAccessFileManager($trimmed_path)) {
                $breadcrumbs[] = array('text' => $folder, 'path' => $trimmed_path);
            }
        }

        $breadcrumbs[count($breadcrumbs) - 1]['path'] = null;
        return $breadcrumbs;
    }

    /**
     * Sets file manager messages
     * @param string $message
     * @param string $severity
     * @param bool $once
     */
    protected function setMessageFileManager($message, $severity, $once = false)
    {
        if ($once) {
            $this->session->setMessage($message, $severity, 'file_manager_messages');
        } else {
            $messages = $this->getData("messages.$severity", array());
            $messages[] = $message;
            $this->setData("messages.$severity", $messages);
        }
    }

    /**
     * Handles a submitted data
     */
    protected function submitFileManager()
    {
        if ($this->data_access) {
            if ($this->isPosted('process_selected')) {
                $this->submitSelectedFileManager();
            } else if ($this->isPosted('submit') && $this->validateFileManager()) {
                $this->submitCommandFileManager();
            }
        }
    }

    /**
     * Handles submitted selected items
     */
    protected function submitSelectedFileManager()
    {
        $command_id = $this->getPosted('command_id');
        $selected = $this->getPosted('selected', array(), false, 'array');

        if ($command_id && $selected) {
            $limit = 100; // Max number of items to keep in the session
            $save = array_slice(array_unique($selected), 0, $limit);
            $this->session->set('file_manager_selected', $save);
            $query = array('cmd' => $command_id, 'path' => $this->data_path, 'selected' => true);
            $this->url->redirect('', $query);
        }

        $this->redirect();
    }

    /**
     * Process the current command
     */
    protected function submitCommandFileManager()
    {
        $result = $this->command->submit($this->data_command, array($this));
        $this->setMessageFileManager($result['message'], $result['severity'], true);
        $this->session->delete('file_manager_selected');
        $this->redirect($result['redirect']);
    }

    /**
     * Validates a submitted data
     * @return bool
     */
    protected function validateFileManager()
    {
        $selected = $this->isQuery('selected');
        $files = $selected ? $this->data_selected : array($this->data_file);

        $this->setSubmitted('filemanager');
        $this->setSubmitted('files', $files);
        $this->setSubmitted('command', $this->data_command);

        $this->validateComponent("file_manager_{$this->data_command['command_id']}");
        return !$this->hasErrors(false);
    }

    /**
     * Returns an array of allowed tabs for the current command
     * @return array
     */
    protected function getTabsFileManager()
    {
        $commands = $this->command->getAllowed($this->data_file);

        if (!empty($this->data_selected) && $this->isQuery('selected')) {
            $commands[$this->data_command['command_id']] = $this->data_command;
        }

        $tabs = array();
        foreach ($commands as $command_id => $command) {

            if (!isset($command['tab']) || !$this->access("module_file_manager_$command_id")) {
                continue;
            }

            $tabs[$command['tab']] = array(
                'text' => $this->text($command['tab']),
                'url' => $this->url('', array('path' => $this->data_path, 'cmd' => $command_id)),
            );
        }

        return $tabs;
    }

    /**
     * Returns an array of commands that support multiple selection
     * @return array
     */
    protected function getActionsFileManager()
    {
        $commands = $this->command->getHandlers();
        foreach ($commands as $command_id => $command) {
            if (empty($command['multiple'])) {
                unset($commands[$command_id]);
            }
        }

        return $commands;
    }

    /**
     * Sets titles on the file manager page
     */
    protected function setTitleFileManager()
    {
        $this->setTitle($this->text('File manager'));
    }

    /**
     * Sets breadcrumbs on the file manager page
     */
    protected function setBreadcrumbFileManager()
    {
        $this->setBreadcrumbHome();

        $breadcrumb = array(
            'url' => $this->url('admin/tool'),
            'text' => $this->text('Tools')
        );

        $this->setBreadcrumb($breadcrumb);
    }

    /**
     * Render and display the file manager page
     */
    protected function outputViewFileManager()
    {
        $this->output('file_manager|layout');
    }

}
