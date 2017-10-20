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
 * Contains methods for "delete" command
 */
class Delete extends FileManagerBaseHandler
{

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
        return in_array($file->getType(), array('file', 'dir')) && !$this->isInitialPath($file);
    }

    /**
     * Returns an array of data used to display the command
     * @param \SplFileInfo $file
     * @return array
     */
    public function view($file)
    {
        return array(
            'file_manager|commands/delete' => array(
                'path' => $this->getRelativePath($file->getRealPath())
        ));
    }

    /**
     * Delete file(s)
     * @param \gplcart\core\Controller $controller
     */
    public function submit($controller)
    {
        set_time_limit(0);

        $path = null;
        $errors = $success = 0;

        /* @var $file \SplFileInfo */
        foreach ($controller->getSubmitted('files', array()) as $file) {
            $path = $file->getRealPath();
            gplcart_file_delete_recursive($path, $errors, $success);
        }

        $query = array(
            'cmd' => 'list',
            'path' => isset($path) ? $this->getRelativeDirectory($path) : ''
        );

        $vars = array('@num_success' => $success, '@num_errors' => $errors);

        return array(
            'redirect' => $controller->url('', $query),
            'severity' => empty($errors) ? 'success' : 'warning',
            'message' => $this->language->text('Deleted @num_success, errors: @num_errors', $vars)
        );
    }

}
