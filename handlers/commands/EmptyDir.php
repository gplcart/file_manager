<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\commands;

use gplcart\core\Config;
use gplcart\core\models\Language as LanguageModel;
use gplcart\modules\file_manager\models\Command as FileManagerCommandModel;
use gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;
use gplcart\modules\file_manager\handlers\commands\Base as FileManagerBaseHandler;

/**
 * Contains methods for "emptydir" command
 */
class EmptyDir extends FileManagerBaseHandler
{

    /**
     * @param Config $config
     * @param LanguageModel $language
     * @param FileManagerCommandModel $command
     * @param FileManagerScannerModel $scanner
     */
    public function __construct(Config $config, LanguageModel $language,
            FileManagerCommandModel $command, FileManagerScannerModel $scanner)
    {
        parent::__construct($config, $language, $command, $scanner);
    }

    /**
     * Whether the command is allowed for the file
     * @param \SplFileInfo $file
     * @return bool
     */
    public function allowed($file)
    {
        return $file->isDir() && !$this->isInitialPath($file);
    }

    /**
     * Returns an array of data used to display the command
     * @param \SplFileInfo $file
     * @return array
     */
    public function view($file)
    {
        $path = $file->getRealPath();

        return array(
            'file_manager|commands/empty' => array(
                'name' => $file->getBasename(),
                'count' => $this->getTotal($path),
                'path' => gplcart_path_relative($path)
        ));
    }

    /**
     * Empty directories
     * @param \gplcart\core\Controller $controller
     * @return array
     */
    public function submit($controller)
    {
        set_time_limit(0);

        $path = null;
        $errors = $success = 0;

        /* @var $file \SplFileInfo */
        foreach ($controller->getSubmitted('files') as $file) {
            $path = $file->getRealPath();
            foreach ($this->getFiles($path) as $f) {
                gplcart_file_delete_recursive($f, $errors, $success);
            }
        }

        $query = array(
            'cmd' => 'list',
            'path' => isset($path) ? $this->getRelativeFilePath($path) : ''
        );

        $vars = array('@num_success' => $success, '@num_errors' => $errors);

        return array(
            'redirect' => $controller->url('', $query),
            'severity' => empty($errors) ? 'success' : 'warning',
            'message' => $this->language->text('Deleted @num_success, errors: @num_errors', $vars)
        );
    }

}
