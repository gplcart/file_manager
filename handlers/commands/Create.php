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
 * Contains methods for "create" command
 */
class Create extends FileManagerBaseHandler
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
        return $file->isDir() && $file->isWritable();
    }

    /**
     * Returns an array of data used to display the command
     * @param \SplFileInfo $file
     * @return array
     */
    public function view($file)
    {
        return array(
            'file_manager|commands/create' => array(
                'path' => $this->getRelativePath($file->getRealPath())
        ));
    }

    /**
     * Creates a new file
     * @param \gplcart\core\Controller $controller
     * @return array
     */
    public function submit($controller)
    {
        $submitted = $controller->getSubmitted();

        /* @var $file \SplFileInfo */
        $file = reset($submitted['files']);

        $directory = $submitted['destination'];
        $pathinfo = pathinfo($submitted['destination']);

        if (!empty($pathinfo['extension'])) {
            $directory = trim($pathinfo['dirname'], '.');
        }

        $result = true;
        if ($directory && !file_exists($directory)) {
            $result = mkdir($directory, 0777, true);
        }

        if ($result && !empty($pathinfo['basename'])) {
            $result = touch($submitted['destination']);
        }

        $query = array(
            'cmd' => 'list',
            'path' => $this->getRelativeFilePath($file->getRealPath())
        );

        if ($result) {
            return array(
                'severity' => 'success',
                'redirect' => $controller->url('', $query),
                'message' => $this->language->text('File has been created')
            );
        }

        return array(
            'severity' => 'warning',
            'redirect' => $controller->url('', $query),
            'message' => $this->language->text('File has not been created')
        );
    }

}
