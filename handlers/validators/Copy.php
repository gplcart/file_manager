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
 * Provides methods to validate "copy" command
 */
class Copy extends FileManagerBaseValidatorHandler
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
     * Validates an array of submitted command data
     * @param array $submitted
     * @param array $options
     * @return boolean|array
     */
    public function validateCopy(array &$submitted, array $options = array())
    {
        $this->options = $options;
        $this->submitted = &$submitted;

        $this->validateDestinationNameCopy();
        $this->validateDestinationDirectoryCopy();
        $this->validateDestinationExistanceCopy();

        return $this->getResult();
    }

    /**
     * Validates a directory name
     * @return boolean
     */
    protected function validateDestinationNameCopy()
    {
        $destination = $this->getSubmitted('destination');
        if ($destination !== '' && preg_match('/^[\w-]+[\w-\/]*[\w-]+$|^[\w-]$/', $destination) !== 1) {
            $this->setErrorInvalid('destination', $this->language->text('Destination'));
            return false;
        }

        return true;
    }

    /**
     * Validates a destination directory
     * @return bool
     */
    protected function validateDestinationDirectoryCopy()
    {
        if ($this->isError()) {
            return null;
        }

        $initial_path = $this->scanner->getInitialPath(true);
        $directory = gplcart_path_normalize("$initial_path/" . $this->getSubmitted('destination'));

        if (!file_exists($directory) && !mkdir($directory, 0777, true)) {
            $this->setError('destination', $this->language->text('Destination does not exist'));
            return false;
        }

        if (!is_dir($directory)) {
            $this->setError('destination', $this->language->text('Destination is not a directory'));
            return false;
        }

        if (!is_writable($directory)) {
            $this->setError('destination', $this->language->text('Directory is not writable'));
            return false;
        }

        $this->setSubmitted('directory', $directory);
        return true;
    }

    /**
     * Validates file destinations
     * @return boolean
     */
    protected function validateDestinationExistanceCopy()
    {
        if ($this->isError()) {
            return null;
        }

        $destinations = array();
        $directory = $this->getSubmitted('directory');

        $error = null;
        foreach ((array) $this->getSubmitted('files') as $index => $file) {

            /* @var $file \SplFileInfo */
            if ($file->isDir() && gplcart_path_normalize($file->getRealPath()) === $directory) {
                $error = $this->language->text('Destination already exists');
                continue;
            }

            $destination = "$directory/" . $file->getBasename();

            if (file_exists($destination)) {
                $error = $this->language->text('Destination already exists');
                continue;
            }

            $destinations[$index] = $destination;
        }

        if (isset($error)) {
            $this->setError('destination', $error);
            return false;
        }

        $this->setSubmitted('destinations', $destinations);
        return true;
    }

}
