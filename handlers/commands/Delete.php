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
 * Contains methods for "delete" command
 */
class Delete extends FileManagerBaseHandler
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
     * @return array
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
