<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\modules\file_manager\handlers\validators\Base as FileManagerBaseValidatorHandler;

/**
 * Provides methods to validate "rename" command
 */
class Rename extends FileManagerBaseValidatorHandler
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Validates an array of submitted data while renaming a file
     * @param array $submitted
     * @param array $options
     * @return boolean|array
     */
    public function validateRename(&$submitted, $options = array())
    {
        $this->options = $options;
        $this->submitted = &$submitted;

        $this->validateNameRename();
        $this->validateDestinationRename();

        return $this->getResult();
    }

    /**
     * Validates a file name
     * @return boolean
     */
    protected function validateNameRename()
    {
        $files = $this->getSubmitted('files');

        /* @var $file \SplFileInfo */
        $file = reset($files);
        $name = $this->getSubmitted('name');

        if (strlen($name) == 0) {
            $this->setErrorRequired('name', $this->language->text('Name'));
            return false;
        }

        $pattern = $file->isFile() ? '/^[\w-.]+$/' : '/^[\w-]+$/';

        if (preg_match($pattern, $name) !== 1) {
            $this->setErrorInvalid('name', $this->language->text('Name'));
            return false;
        }

        return true;
    }

    /**
     * Validates that the destination is unique
     * @return boolean
     */
    protected function validateDestinationRename()
    {
        if ($this->isError()) {
            return null;
        }

        $files = $this->getSubmitted('files');

        /* @var $file \SplFileInfo */
        $file = reset($files);
        $name = $this->getSubmitted('name');

        $directory = dirname($file->getRealPath());
        $destination = gplcart_path_normalize("$directory/$name");

        if (file_exists($destination)) {
            $this->setError('name', $this->language->text('Destination already exists'));
            return false;
        }

        $this->setSubmitted('destination', $destination);
        return true;
    }

}