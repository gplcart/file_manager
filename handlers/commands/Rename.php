<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\commands;

/**
 * Contains methods for "rename" command
 */
class Rename extends Command
{

    /**
     * Whether the command is allowed for the file
     * @param \SplFileInfo $file
     * @return bool
     */
    public function allowed($file)
    {
        return in_array($file->getType(), array('file', 'dir')) && $file->isWritable() && !$this->isInitialPath($file);
    }

    /**
     * Returns an array data used to display "rename" command output
     * @param \SplFileInfo $file
     * @return array
     */
    public function view($file)
    {
        return array(
            'file_manager|commands/rename' => array(
                'name' => $file->getBasename(),
                'path' => dirname($this->getRelativePath($file->getRealPath()))
            ));
    }

    /**
     * Rename a file
     * @param \gplcart\core\Controller $controller
     * @return array
     */
    public function submit($controller)
    {
        set_time_limit(0);

        $submitted = $controller->getSubmitted();
        $file = reset($submitted['files']);

        $path = $file->getRealPath();
        $destination = $controller->getSubmitted('destination');

        $query = array(
            'cmd' => 'list',
            'path' => $this->getRelativeDirectory($path)
        );

        $errors = $success = 0;
        $this->move($path, $destination, $errors, $success);

        $vars = array('@num_success' => $success, '@num_errors' => $errors);

        return array(
            'redirect' => $controller->url('', $query),
            'severity' => empty($errors) ? 'success' : 'warning',
            'message' => $this->translation->text('Renamed @num_success, errors: @num_errors', $vars)
        );
    }

}
