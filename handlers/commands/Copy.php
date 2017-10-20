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
 * Contains methods for "copy" command
 */
class Copy extends FileManagerBaseHandler
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
        return in_array($file->getType(), array('file', 'dir')) && $file->isReadable() && !$this->isInitialPath($file);
    }

    /**
     * Returns an array data used to display the command
     * @return array
     */
    public function view()
    {
        return array('file_manager|commands/copy' => array('path' => $this->getRelativePath()));
    }

    /**
     * Copies files
     * @param \gplcart\core\Controller $controller
     */
    public function submit($controller)
    {
        set_time_limit(0);

        $submitted = $controller->getSubmitted();

        $destination = null;
        $errors = $success = 0;
        foreach ($submitted['files'] as $index => $file) {

            if (empty($submitted['destinations'][$index])) {
                $errors++;
                continue;
            }

            $destination = $submitted['destinations'][$index];
            $this->copy($file->getRealPath(), $destination, $errors, $success);
        }

        $query = array(
            'cmd' => 'list',
            'path' => isset($destination) ? dirname($this->getRelativeFilePath($destination)) : ''
        );

        $vars = array('@num_success' => $success, '@num_errors' => $errors);

        return array(
            'redirect' => $controller->url('', $query),
            'severity' => empty($errors) ? 'success' : 'warning',
            'message' => $this->language->text('Copied @num_success, errors: @num_errors', $vars)
        );
    }

}
