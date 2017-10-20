<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\commands;

use DirectoryIterator;
use gplcart\core\Handler,
    gplcart\core\Container;

/**
 * Base handler class
 */
class Base extends Handler
{

    /**
     * Language model class instance
     * @var \gplcart\core\models\Language $language
     */
    protected $language;

    /**
     * Command model class instance
     * @var \gplcart\modules\file_manager\models\Command $command
     */
    protected $command;

    /**
     * Scanner model class instance
     * @var \gplcart\modules\file_manager\models\Scanner $scanner
     */
    protected $scanner;

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();

        $this->language = Container::get('gplcart\\core\\models\\Language');
        $this->command = Container::get('gplcart\\modules\\file_manager\\models\\Command');
        $this->scanner = Container::get('gplcart\\modules\\file_manager\\models\\Scanner');
    }

    /**
     * Returns a total number of scanned files
     * @param string $directory
     * @param array $options
     * @return integer
     */
    protected function getTotal($directory, array $options = array())
    {
        $options['count'] = true;
        return (int) $this->scanner->scan($directory, $options);
    }

    /**
     * Returns an array of scanned files
     * @param string $directory
     * @param array $options
     * @return array
     */
    protected function getFiles($directory, array $options = array())
    {
        return $this->scanner->scan($directory, $options);
    }

    /**
     * Returns a normalized relative path for the file
     * @param string $file
     * @return string
     */
    protected function getRelativeFilePath($file)
    {
        return gplcart_path_normalize(gplcart_file_relative($file));
    }

    /**
     * Returns a relative file path or initial directory
     * @param null|string $path
     * @return string
     */
    protected function getRelativePath($path = null)
    {
        if (!isset($path)) {
            $path = $this->scanner->getInitialPath(true);
        }

        return gplcart_path_normalize(gplcart_path_relative($path));
    }

    /**
     * Returns a relative directory path for the file
     * @param string $file
     * @return string
     */
    protected function getRelativeDirectory($file)
    {
        return trim(dirname($this->getRelativeFilePath($file)), '.');
    }

    /**
     * Moves a file to a new destination
     * @param string $src
     * @param string $dest
     * @param int $errors
     * @param int $success
     * @return bool
     */
    protected function move($src, $dest, &$errors = 0, &$success = 0)
    {
        $this->copy($src, $dest, $errors, $success);

        if (empty($errors)) {
            gplcart_file_delete_recursive($src, $errors);
        }

        return empty($errors);
    }

    /**
     * Copy a file / directory
     * @param string $src
     * @param string $dest
     * @param int $errors
     * @param int $success
     * @return boolean
     */
    protected function copy($src, $dest, &$errors = 0, &$success = 0)
    {
        if (is_file($src)) {
            if (copy($src, $dest)) {
                $success++;
                return true;
            }
            $errors++;
            return false;
        }

        if (!is_dir($dest) && !mkdir($dest)) {
            $errors++;
            return false;
        }

        foreach (new DirectoryIterator($src) as $file) {

            $result = null;
            $copyto = "$dest/" . $file->getBasename();
            if ($file->isFile() || (!$file->isDot() && $file->isDir())) {
                $result = $this->copy($file->getRealPath(), $copyto, $errors, $success);
            }

            if (!isset($result)) {
                continue;
            }

            if ($result) {
                $success++;
            } else {
                $errors++;
            }
        }

        $success++;
        return true;
    }

    /**
     * Whether the current file is the initial file manager path
     * @param \SplFileInfo $file
     * @return bool
     */
    protected function isInitialPath($file)
    {
        $current_path = gplcart_path_normalize($file->getRealPath());
        $initial_path = gplcart_path_normalize($this->scanner->getInitialPath(true));
        return $current_path === $initial_path;
    }

}
