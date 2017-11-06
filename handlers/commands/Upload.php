<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\commands;

use gplcart\core\Config;
use gplcart\core\models\User as UserModel,
    gplcart\core\models\Language as LanguageModel;
use gplcart\modules\file_manager\models\Command as FileManagerCommandModel;
use gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;
use gplcart\modules\file_manager\handlers\commands\Base as FileManagerBaseHandler;

/**
 * Contains methods for "upload" command
 */
class Upload extends FileManagerBaseHandler
{

    /**
     * User model class instance
     * @var \gplcart\core\models\User $user
     */
    protected $user;

    /**
     * @param Config $config
     * @param LanguageModel $language
     * @param FileManagerCommandModel $command
     * @param FileManagerScannerModel $scanner
     * @param UserModel $user
     */
    public function __construct(Config $config, LanguageModel $language,
            FileManagerCommandModel $command, FileManagerScannerModel $scanner, UserModel $user)
    {
        parent::__construct($config, $language, $command, $scanner);

        $this->user = $user;
    }

    /**
     * Whether the command is allowed for the file
     * @param \SplFileInfo $file
     * @return bool
     */
    public function allowed($file)
    {
        return $file->isDir() && $file->isWritable() && $this->user->access('file_upload');
    }

    /**
     * Returns an array of data used to display the command
     * @return array
     */
    public function view()
    {
        return array('file_manager|commands/upload' => array());
    }

    /**
     * Upload files
     * @param \gplcart\core\Controller $controller
     * @return array
     */
    public function submit($controller)
    {
        $result = $controller->getSubmitted('uploaded', array());

        $vars = array(
            '@num_errors' => count($result['errors']),
            '@num_success' => count($result['transferred'])
        );

        $files = $controller->getSubmitted('files');

        /* @var $file \SplFileInfo */
        $file = reset($files);

        $query = array(
            'cmd' => 'list',
            'path' => $this->getRelativeFilePath($file->getRealPath())
        );

        return array(
            'redirect' => $controller->url('', $query),
            'severity' => empty($result['errors']) ? 'success' : 'warning',
            'message' => $this->language->text('Uploaded @num_success, errors: @num_errors', $vars)
        );
    }

}
