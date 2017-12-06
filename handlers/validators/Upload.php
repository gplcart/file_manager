<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

// Parent
use gplcart\core\Config;
use gplcart\core\models\Language as LanguageModel;
use gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;
// New
use gplcart\core\Module;
use gplcart\core\models\File as FileModel,
    gplcart\core\models\User as UserModel;
use gplcart\core\helpers\Request as RequestHelper;
use gplcart\modules\file_manager\handlers\validators\Base as FileManagerBaseValidatorHandler;

/**
 * Provides methods to validate "upload" command
 */
class Upload extends FileManagerBaseValidatorHandler
{

    /**
     * Request helper class instance
     * @var \gplcart\core\helpers\Request $request
     */
    protected $request;

    /**
     * File model instance
     * @var \gplcart\core\models\File $file
     */
    protected $file;

    /**
     * User model instance
     * @var \gplcart\core\models\User $user
     */
    protected $user;
    
    /**
     * Module class instance
     * @var \gplcart\core\Module $module
     */
    protected $module;
    
    /**
     * @param Config $config
     * @param LanguageModel $language
     * @param FileManagerScannerModel $scanner
     * @param Module $module
     * @param FileModel $file
     * @param UserModel $user
     * @param RequestHelper $request
     */
    public function __construct(Config $config, LanguageModel $language,
            FileManagerScannerModel $scanner, Module $module, FileModel $file, UserModel $user,
            RequestHelper $request)
    {
        parent::__construct($config, $language, $scanner);

        $this->file = $file;
        $this->user = $user;
        $this->module = $module;
        $this->request = $request;
    }

    /**
     * Validates an array of submitted command data
     * @param array $submitted
     * @param array $options
     * @return mixed
     */
    public function validateUpload(array &$submitted, array $options = array())
    {
        $this->options = $options;
        $this->submitted = &$submitted;

        $this->validateLimitsUpload();
        $this->validateFileUpload();

        return $this->getResult();
    }

    /**
     * Validates file limits
     * @return boolean
     */
    protected function validateLimitsUpload()
    {
        $files = $this->request->file('files');

        if (!gplcart_file_multi_upload($files)) {
            return false;
        }

        if ($this->user->isSuperadmin()) {
            return true;
        }

        $role_id = $this->user->getRoleId();
        $settings = $this->module->getSettings('file_manager');

        $maxfilesize = 0;
        $extensions = array();

        if (!empty($settings['filesize_limit'][$role_id])) {
            $maxfilesize = $settings['filesize_limit'][$role_id];
        }

        if (!empty($settings['extension_limit'][$role_id])) {
            $extensions = $settings['extension_limit'][$role_id];
        }

        $errors = array();
        foreach ($files as $file) {

            if ($maxfilesize && filesize($file['tmp_name']) > $maxfilesize) {
                unlink($file['tmp_name']);
                $errors[] = "{$file['name']}: " . $this->language->text('File size exceeds %num bytes', array('%num' => $maxfilesize));
                continue;
            }

            if ($extensions && !in_array(pathinfo($file['name'], PATHINFO_EXTENSION), $extensions)) {
                unlink($file['tmp_name']);
                $errors[] = "{$file['name']}: " . $this->language->text('Unsupported file extension');
            }
        }

        if (empty($errors)) {
            return true;
        }

        $this->setError('files', implode('<br>', $errors));
        return false;
    }

    /**
     * Validates uploaded files
     * @return boolean
     */
    protected function validateFileUpload()
    {
        if ($this->isError()) {
            return null;
        }

        $files = $this->getSubmitted('files');

        /* @var $file \SplFileInfo */
        $file = reset($files);
        $files = $this->request->file('files');

        if (empty($files['name'][0])) {
            $this->setErrorRequired('files', $this->language->text('Files'));
            return false;
        }

        $result = $this->file->uploadMultiple($files, false, $file->getRealPath());
        $this->setSubmitted('uploaded', $result);
        return true;
    }

}
