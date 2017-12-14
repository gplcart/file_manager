<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\commands;

use gplcart\modules\file_manager\handlers\commands\Base as FileManagerBaseHandler;

/**
 * Contains methods for "list" command
 */
class Listing extends FileManagerBaseHandler
{

    /**
     * Controller class instance
     * @var \gplcart\core\Controller $controller
     */
    protected $controller;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Whether the command is allowed for the file
     * @param \SplFileInfo $file
     * @return bool
     */
    public function allowed($file)
    {
        return $file->isDir() && $file->isReadable();
    }

    /**
     * Returns an array of data used to display the command
     * @param \SplFileInfo $file
     * @param \gplcart\core\Controller $controller
     * @return array
     */
    public function view($file, $controller)
    {
        $this->controller = $controller;

        $path = $file->getRealPath();
        $params = $controller->getQuery(null, array(), 'array');

        $pager_options = array(
            'max_pages' => 5,
            'query' => $params,
            'total' => $this->getTotal($path, $params),
            'limit' => $this->controller->getModuleSettings('file_manager', 'limit')
        );

        $pager = $this->controller->getPager($pager_options);
        $params['limit'] = $pager['limit'];

        return array(
            'file_manager|commands/list' => array(
                'pager' => $pager['rendered'],
                'filters' => $this->scanner->getFilters(),
                'sorters' => $this->scanner->getSorters(),
                'files' => $this->getFilesListing($path, $params)
        ));
    }

    /**
     * Returns an array of scanned and prepared files
     * @param string $path
     * @param array $params
     * @return array
     */
    protected function getFilesListing($path, array $params)
    {
        $files = $this->getFiles($path, $params);
        return $this->prepareFiles($files);
    }

    /**
     * Prepares an array of scanned files
     * @param array $files
     * @return array
     */
    protected function prepareFiles(array $files)
    {
        $prepared = array();
        foreach ($files as $file) {

            $type = $file->getType();
            $path = $file->getRealPath();
            $relative_path = gplcart_file_relative($path);

            $item = array(
                'info' => $file,
                'type' => $type,
                'path' => $relative_path,
                'owner' => fileowner($path),
                'extension' => $file->getExtension(),
                'size' => gplcart_file_size($file->getSize()),
                'command' => $type === 'dir' ? 'list' : 'read',
                'commands' => $this->command->getAllowed($file),
                'permissions' => gplcart_file_perms($file->getPerms())
            );

            $prepared[$relative_path] = $item;
            $prepared[$relative_path]['icon'] = $this->renderIcon($item);
        }

        return $prepared;
    }

    /**
     * Returns a rendered icon for the given file extension and type
     * @param array $item
     * @return string
     */
    protected function renderIcon(array $item)
    {
        static $rendered = array();

        if (isset($rendered[$item['extension']])) {
            return $rendered[$item['extension']];
        }

        $template = "file_manager|icons/ext/{$item['extension']}";

        if ($item['type'] === 'dir') {
            $template = 'file_manager|icons/dir';
        }

        $data = array('item' => $item);
        $default = $this->controller->render('file_manager|icons/file', $data);
        $rendered[$item['extension']] = $this->controller->render($template, $data, true, $default);

        return $rendered[$item['extension']];
    }

}
