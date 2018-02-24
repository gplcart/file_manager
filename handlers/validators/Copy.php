<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\core\handlers\validator\Element;
use gplcart\modules\file_manager\models\Scanner;

/**
 * Provides methods to validate "copy" command
 */
class Copy extends Element
{

    /**
     * Scanner model class instance
     * @var \gplcart\modules\file_manager\models\Scanner $scanner
     */
    protected $scanner;

    /**
     * Copy constructor.
     * @param Scanner $scanner
     */
    public function __construct(Scanner $scanner)
    {
        parent::__construct();

        $this->scanner = $scanner;
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
     */
    protected function validateDestinationNameCopy()
    {
        $destination = $this->getSubmitted('destination');

        if ($destination !== '' && preg_match('/^[\w-]+[\w-\/]*[\w-]+$|^[\w-]$/', $destination) !== 1) {
            $this->setErrorInvalid('destination', $this->translation->text('Destination'));
        }
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
            $this->setError('destination', $this->translation->text('Destination does not exist'));
            return false;
        }

        if (!is_dir($directory)) {
            $this->setError('destination', $this->translation->text('Destination is not a directory'));
            return false;
        }

        if (!is_writable($directory)) {
            $this->setError('destination', $this->translation->text('Directory is not writable'));
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
                $error = $this->translation->text('Destination already exists');
                continue;
            }

            $destination = "$directory/" . $file->getBasename();

            if (file_exists($destination)) {
                $error = $this->translation->text('Destination already exists');
                continue;
            }

            $destinations[$index] = $destination;
        }

        if (isset($error)) {
            $this->setError('destination', $error);
        } else {
            $this->setSubmitted('destinations', $destinations);
        }

        return true;
    }

}
