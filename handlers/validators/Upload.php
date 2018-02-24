<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\core\handlers\validator\Element;
use gplcart\core\helpers\Request;
use gplcart\core\models\FileTransfer;
use gplcart\core\models\User;
use gplcart\core\Module;

/**
 * Provides methods to validate "upload" command
 */
class Upload extends Element
{

    /**
     * Request helper class instance
     * @var \gplcart\core\helpers\Request $request
     */
    protected $request;

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
     * File transfer model instance
     * @var \gplcart\core\models\FileTransfer $file_transfer
     */
    protected $file_transfer;

    /**
     * Upload constructor.
     * @param Module $module
     * @param FileTransfer $file_transfer
     * @param User $user
     * @param Request $request
     */
    public function __construct(Module $module, FileTransfer $file_transfer, User $user, Request $request)
    {
        parent::__construct();

        $this->user = $user;
        $this->module = $module;
        $this->request = $request;
        $this->file_transfer = $file_transfer;
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
                $errors[] = "{$file['name']}: " . $this->translation->text('File size exceeds %num bytes', array('%num' => $maxfilesize));
                continue;
            }

            if ($extensions && !in_array(pathinfo($file['name'], PATHINFO_EXTENSION), $extensions)) {
                unlink($file['tmp_name']);
                $errors[] = "{$file['name']}: " . $this->translation->text('Unsupported file extension');
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

        $submitted_files = $this->getSubmitted('files');

        /* @var $file \SplFileInfo */
        $file = reset($submitted_files);
        $request_files = $this->request->file('files');

        if (empty($request_files['name'][0])) {
            $this->setErrorRequired('files', $this->translation->text('Files'));
            return false;
        }

        $result = $this->file_transfer->uploadMultiple($request_files, false, $file->getRealPath());
        $this->setSubmitted('uploaded', $result);
        return true;
    }

}
