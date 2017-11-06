<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\core\Config;
use gplcart\core\models\Language as LanguageModel;
use gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;
use gplcart\modules\file_manager\handlers\validators\Base as FileManagerBaseValidatorHandler;

/**
 * Provides methods to validate "create" command
 */
class Create extends FileManagerBaseValidatorHandler
{

    /**
     * @param Config $config
     * @param LanguageModel $language
     * @param FileManagerScannerModel $scanner
     */
    public function __construct(Config $config, LanguageModel $language,
            FileManagerScannerModel $scanner)
    {
        parent::__construct($config, $language, $scanner);
    }

    /**
     * Validates an array of submitted data while creating a new file
     * @param array $submitted
     * @param array $options
     * @return boolean|array
     */
    public function validateCreate(&$submitted, $options = array())
    {
        $this->options = $options;
        $this->submitted = &$submitted;

        $this->validateNameCreate();
        $this->validateDestinationCreate();

        return $this->getResult();
    }

    /**
     * Validates a file name
     * @return boolean
     */
    protected function validateNameCreate()
    {
        $name = $this->getSubmitted('name');

        if (strlen($name) == 0) {
            $this->setErrorRequired('name', $this->language->text('Name'));
            return false;
        }

        $pathinfo = pathinfo($name);

        if (!empty($pathinfo['extension'])) {
            // Validate filename. Allow only alphanumeric chars, underscores, dashes and dots
            $name = trim($pathinfo['dirname'], '.');
            if (preg_match('/^[\w-.]+$/', $pathinfo['basename']) !== 1) {
                $this->setErrorInvalid('name', $this->language->text('Name'));
                return false;
            }
        }

        // Validate directory path
        if ($name && preg_match('/^[\w-]+[\w-\/]*[\w-]+$|^[\w-]$/', $name) !== 1) {
            $this->setErrorInvalid('name', $this->language->text('Name'));
            return false;
        }

        return true;
    }

    /**
     * Validates that the destination is unique
     * @return boolean
     */
    protected function validateDestinationCreate()
    {
        if ($this->isError()) {
            return null;
        }

        $name = $this->getSubmitted('name');
        $files = $this->getSubmitted('files');

        /* @var $file \SplFileInfo */
        $file = reset($files);
        $directory = $file->getRealPath();

        if (file_exists("$directory/$name")) {
            $this->setError('name', $this->language->text('Destination already exists'));
            return false;
        }

        $this->setSubmitted('destination', "$directory/$name");
        return true;
    }

}
