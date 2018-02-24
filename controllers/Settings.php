<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\controllers;

use gplcart\core\controllers\backend\Controller;

/**
 * Handles incoming requests and outputs data related to File manager module
 */
class Settings extends Controller
{

    /**
     * Route page callback to display the module settings page
     */
    public function editSettings()
    {
        $this->setTitleEditSettings();
        $this->setBreadcrumbEditSettings();

        $this->setData('file_dir', gplcart_path_normalize(GC_DIR_FILE));
        $this->setData('settings', $this->module->getSettings('file_manager'));

        $this->submitSettings();
        $this->setDataAccessSettings();
        $this->setDataMultilineTextSettings('filesize_limit');
        $this->setDataMultilineTextSettings('extension_limit');

        $this->outputEditSettings();
    }

    /**
     * Prepare and set data for "access" field
     */
    protected function setDataAccessSettings()
    {
        $access = $this->getData('settings.access');

        if (is_array($access)) {

            $string = '';

            foreach ($access as $role_id => $patterns) {
                foreach ($patterns as $pattern) {
                    $string .= "$role_id $pattern" . PHP_EOL;
                }
            }

            $this->setData('settings.access', trim($string));
        }
    }

    /**
     * Prepare and set data for textarea fields
     * @param string $setting
     */
    protected function setDataMultilineTextSettings($setting)
    {
        $data = $this->getData("settings.$setting");

        if (is_array($data)) {

            $string = '';

            foreach ($data as $key => $value) {
                $list = implode(',', (array) $value);
                $string .= "$key $list" . PHP_EOL;
            }

            $this->setData("settings.$setting", trim($string));
        }
    }

    /**
     * Set title on the module settings page
     */
    protected function setTitleEditSettings()
    {
        $title = $this->text('Edit %name settings', array('%name' => $this->text('File manager')));
        $this->setTitle($title);
    }

    /**
     * Set breadcrumbs on the module settings page
     */
    protected function setBreadcrumbEditSettings()
    {
        $breadcrumbs = array();

        $breadcrumbs[] = array(
            'text' => $this->text('Dashboard'),
            'url' => $this->url('admin')
        );

        $breadcrumbs[] = array(
            'text' => $this->text('Modules'),
            'url' => $this->url('admin/module/list')
        );

        $this->setBreadcrumbs($breadcrumbs);
    }

    /**
     * Saves the submitted settings
     */
    protected function submitSettings()
    {
        if ($this->isPosted('save') && $this->validateSettings()) {
            $this->updateSettings();
        }
    }

    /**
     * Validate submitted module settings
     * @return bool
     */
    protected function validateSettings()
    {
        $this->setSubmitted('settings', null, false);
        $this->validateComponent('file_manager_settings');

        return !$this->hasErrors();
    }

    /**
     * Update module settings
     */
    protected function updateSettings()
    {
        $this->controlAccess('module_edit');

        $this->module->setSettings('file_manager', $this->getSubmitted());
        $this->redirect('', $this->text('Settings have been updated'), 'success');
    }

    /**
     * Render and output the module settings page
     */
    protected function outputEditSettings()
    {
        $this->output('file_manager|settings');
    }

}
