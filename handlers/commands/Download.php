<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\commands;

// Parent
use gplcart\core\Config;
use gplcart\core\models\Language as LanguageModel;
use gplcart\modules\file_manager\models\Command as FileManagerCommandModel;
use gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;
// New
use gplcart\core\helpers\Zip as ZipHelper,
    gplcart\core\helpers\Session as SessionHelper;
use gplcart\modules\file_manager\handlers\commands\Base as FileManagerBaseHandler;

/**
 * Contains methods for "download" command
 */
class Download extends FileManagerBaseHandler
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
     * @param Config $config
     * @param LanguageModel $language
     * @param FileManagerCommandModel $command
     * @param FileManagerScannerModel $scanner
     * @param ZipHelper $zip
     * @param SessionHelper $session
     */
    public function __construct(Config $config, LanguageModel $language,
            FileManagerCommandModel $command, FileManagerScannerModel $scanner, ZipHelper $zip,
            SessionHelper $session)
    {
        parent::__construct($config, $language, $command, $scanner);

        $this->zip = $zip;
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

        $destination = gplcart_file_tempname();
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
