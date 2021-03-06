<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\commands;

use gplcart\core\helpers\Session;
use gplcart\core\helpers\Zip;
use gplcart\core\models\File;

/**
 * Contains methods for "download" command
 */
class Download extends Command
{

    /**
     * ZIP class instance
     * @var \gplcart\core\helpers\Zip $zip
     */
    protected $zip;

    /**
     * Session class instance
     * @var \gplcart\core\helpers\Session $session
     */
    protected $session;

    /**
     * File model class instance
     * @var \gplcart\core\models\File $file
     */
    protected $file;

    /**
     * Download constructor.
     * @param File $file
     * @param Zip $zip
     * @param Session $session
     */
    public function __construct(File $file, Zip $zip, Session $session)
    {
        parent::__construct();

        $this->zip = $zip;
        $this->file = $file;
        $this->session = $session;
    }

    /**
     * Whether the command is allowed for the file
     * @param \SplFileInfo $file
     * @return bool
     */
    public function allowed($file)
    {
        return in_array($file->getType(), array('file', 'dir')) && $file->isReadable();
    }

    /**
     * Returns an array of data used to display the command
     * @param \SplFileInfo $file
     * @return array
     */
    public function view($file)
    {
        return array(
            'file_manager|commands/download' => array(
                'path' => gplcart_path_relative($file->getRealPath())
            ));
    }

    /**
     * Download file(s)
     * @param \gplcart\core\Controller $controller
     */
    public function submit($controller)
    {
        set_time_limit(0);

        // Download method calls exit() so clean session here
        $this->session->delete('file_manager_selected');

        $destination = $this->file->getTempFile();
        $files = $controller->getSubmitted('files');

        /* @var $file \SplFileInfo */
        $file = reset($files);

        $path = $file->getRealPath();
        $filename = $file->getBasename();

        if ($file->isFile()) {
            $result = $this->zip->file($path, $destination);
        } else if ($file->isDir()) {
            $result = $this->zip->directory($path, $destination, $filename);
        }

        if (!empty($result)) {
            $controller->download($destination, "$filename.zip");
        }
    }

}
