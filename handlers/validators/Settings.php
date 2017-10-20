<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\core\models\UserRole as UserRoleModel;
use gplcart\core\handlers\validator\Base as BaseValidator;

/**
 * Provides methods to validate module settings
 */
class Settings extends BaseValidator
{

    /**
     * User role model instance
     * @var \gplcart\core\models\UserRole $role
     */
    protected $role;

    /**
     * @param UserRoleModel $role
     */
    public function __construct(UserRoleModel $role)
    {
        parent::__construct();

        $this->role = $role;
    }

    /**
     * Validates module settings
     * @param array $submitted
     * @param array $options
     * @return mixed
     */
    public function validateSettings(array &$submitted, array $options = array())
    {
        $this->options = $options;
        $this->submitted = &$submitted;

        $this->validateInitialPathSettings();
        $this->validateLimitSettings();
        $this->validateAccessSettings();
        $this->validateExtensionSettings();
        $this->validateFileSizeSettings();

        return $this->getResult();
    }

    /**
     * Validates the initial path field
     * @return boolean
     */
    protected function validateInitialPathSettings()
    {
        $field = 'initial_path';
        $path = $this->getSubmitted($field);

        if (strlen($path) > 0 && preg_match('/^[\w-]+[\w-\/]*[\w-]+$|^[\w-]$/', $path) !== 1) {
            $this->setErrorInvalid($field, $this->language->text('Initial path'));
            return false;
        }

        $destination = gplcart_file_absolute($path);

        if (!file_exists($destination)) {
            $this->setError($field, $this->language->text('Destination does not exist'));
            return false;
        }

        if (!is_readable($destination)) {
            $this->setError($field, $this->language->text('Destination is not readable'));
            return false;
        }

        return true;
    }

    /**
     * Validates limit field
     * @return boolean
     */
    protected function validateLimitSettings()
    {
        $field = 'limit';
        $limit = $this->getSubmitted($field);

        if (!ctype_digit($limit) || strlen($limit) > 3) {
            $this->setErrorInteger($field, $this->language->text('Limit'));
            return false;
        }

        return true;
    }

    /**
     * Validates the path access field
     * @return boolean
     */
    protected function validateAccessSettings()
    {
        $field = 'access';
        $rules = $this->getSubmitted($field);

        $errors = $converted = array();
        foreach (gplcart_string_explode_multiline($rules) as $line => $rule) {

            $line++;

            $parts = gplcart_string_explode_whitespace($rule, 2);

            if (count($parts) != 2) {
                $errors[] = $line;
                continue;
            }

            list($role_id, $pattern) = $parts;

            if (strlen(trim($pattern, '\\/')) != strlen($pattern)) {
                $errors[] = $line;
            }

            if (!$this->isValidRoleSettings($role_id)) {
                $errors[] = $line;
                continue;
            }

            $normalized_pattern = gplcart_path_normalize($pattern);

            if (!gplcart_string_is_regexp($normalized_pattern) || strlen($normalized_pattern) > 250) {
                $errors[] = $line;
                continue;
            }

            $converted[$role_id][] = $normalized_pattern;
        }

        if (!empty($errors)) {
            $error = $this->language->text('Error on line @num', array('@num' => implode(',', $errors)));
            $this->setError($field, $error);
            return false;
        }

        $this->setSubmitted($field, $converted);
        return true;
    }

    /**
     * Validates the allowed extensions field
     * @return boolean
     */
    protected function validateExtensionSettings()
    {
        $field = 'extension_limit';
        $rules = $this->getSubmitted($field);

        $errors = $converted = array();
        foreach (gplcart_string_explode_multiline($rules) as $line => $rule) {

            $line++;

            $parts = gplcart_string_explode_whitespace($rule, 2);

            if (count($parts) != 2) {
                $errors[] = $line;
                continue;
            }

            list($role_id, $extensions) = $parts;

            if (!$this->isValidRoleSettings($role_id)) {
                $errors[] = $line;
                continue;
            }

            $filtered_extensions = array_filter(array_map('trim', explode(',', $extensions)));

            foreach ($filtered_extensions as $extension) {
                if (!ctype_alnum($extension) || strlen($extension) > 250) {
                    $errors[] = $line;
                    break;
                }
            }

            $converted[$role_id] = $filtered_extensions;
        }

        if (!empty($errors)) {
            $error = $this->language->text('Error on line @num', array('@num' => implode(',', $errors)));
            $this->setError($field, $error);
            return false;
        }

        $this->setSubmitted($field, $converted);
        return true;
    }

    /**
     * Validates the file size limit field
     * @return boolean
     */
    protected function validateFileSizeSettings()
    {
        $field = 'filesize_limit';
        $rules = $this->getSubmitted($field);

        $errors = $converted = array();
        foreach (gplcart_string_explode_multiline($rules) as $line => $rule) {

            $line++;

            $parts = gplcart_string_explode_whitespace($rule, 2);

            if (count($parts) != 2) {
                $errors[] = $line;
                continue;
            }

            list($role_id, $filesize) = $parts;

            if (!$this->isValidRoleSettings($role_id)) {
                $errors[] = $line;
                continue;
            }

            if (!ctype_digit($filesize) || strlen($filesize) > 12) {
                $errors[] = $line;
                break;
            }

            $converted[$role_id] = $filesize;
        }

        if (!empty($errors)) {
            $error = $this->language->text('Error on line @num', array('@num' => implode(',', $errors)));
            $this->setError($field, $error);
            return false;
        }

        $this->setSubmitted($field, $converted);
        return true;
    }

    /**
     * Check whether the value is an existing role
     * @param string $role_id
     * @return bool
     */
    protected function isValidRoleSettings($role_id)
    {
        return ctype_digit($role_id) && $this->role->get($role_id);
    }

}
