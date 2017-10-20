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
 * Contains methods for "read" command
 */
class Read extends FileManagerBaseHandler
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
        return $file->isFile() && $file->isReadable();
    }

    /**
     * Returns an array of data used to display the command
     * @param \SplFileInfo $file
     * @param \gplcart\core\Controller $controller
     * @return array
     */
    public function view($file, $controller)
    {
        return array(
            'file_manager|commands/read' => array(
                'content' => $this->callReadMethod($file, $controller)));
    }

    /**
     * Returns an array if reader methods and their supported file extensions
     * @return array
     */
    protected function getMethods()
    {
        return array(
            'viewText' => array('php', 'css', 'js', 'txt'),
            'viewImage' => array('jpg', 'jpeg', 'gif', 'png')
        );
    }

    /**
     * Calls a method to read a file using its extension
     * @param \SplFileInfo $file
     * @param \gplcart\core\Controller $controller
     * @return string
     */
    protected function callReadMethod($file, $controller)
    {
        if (!$file->isFile()) {
            return '';
        }

        $extension = $file->getExtension();
        foreach ($this->getMethods() as $method => $extensions) {
            if (in_array($extension, $extensions)) {
                return $this->$method($file, $controller);
            }
        }

        return $this->viewInfo($file, $controller);
    }

    /**
     * Returns rendered image content
     * @param \SplFileInfo $file
     * @param \gplcart\core\Controller $controller
     * @return string
     */
    protected function viewImage($file, $controller)
    {
        $path = $file->getRealPath();

        $data = array(
            'file' => $file,
            'src' => $controller->image(gplcart_file_relative($path)),
            'exif' => function_exists('exif_read_data') ? exif_read_data($path) : array()
        );

        return $controller->render('file_manager|readers/image', $data);
    }

    /**
     * Returns rendered text content
     * @param \SplFileInfo $file
     * @param \gplcart\core\Controller $controller
     * @return string
     */
    protected function viewText($file, $controller)
    {
        $data = array(
            'file' => $file,
            'content' => file_get_contents($file->getRealPath())
        );

        return $controller->render('file_manager|readers/text', $data);
    }

    /**
     * Returns rendered file info content
     * @param \SplFileInfo $file
     * @param \gplcart\core\Controller $controller
     * @return string
     */
    protected function viewInfo($file, $controller)
    {
        $data = array(
            'file' => $file,
            'perms' => gplcart_file_perms($file->getPerms()),
            'filesize' => gplcart_file_size($file->getSize())
        );

        return $controller->render('file_manager|readers/info', $data);
    }

}
