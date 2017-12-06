<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\models;

use LimitIterator;
use FilesystemIterator;
use gplcart\core\Config,
    gplcart\core\Hook,
    gplcart\core\Module;
use gplcart\modules\file_manager\helpers\Filter;

/**
 * Manages basic behaviors and data related to File manager module
 */
class Scanner
{

    /**
     * Hook class instance
     * @var \gplcart\core\Hook $hook
     */
    protected $hook;

    /**
     * Config class instance
     * @var \gplcart\core\Config $config
     */
    protected $config;
    
    /**
     * Module class instance
     * @var \gplcart\core\Module $module
     */
    protected $module;
    
    /**
     * @param Hook $hook
     * @param Config $config
     * @param Module $module
     */
    public function __construct(Hook $hook, Config $config, Module $module)
    {
        $this->hook = $hook;
        $this->config = $config;
        $this->module = $module;
    }

    /**
     * Returns an array of scanned files or counts them
     * @param string $directory
     * @param array $options
     * @return array|integer
     */
    public function scan($directory, array $options = array())
    {
        set_time_limit(0);

        $options += array(
            'sort' => null,
            'order' => null,
            'filter_key' => null,
            'filter_value' => null
        );

        $result = null;
        $this->hook->attach('module.file_manager.scan.before', $directory, $options, $result, $this);

        if (isset($result)) {
            return $result;
        }

        $filters = $this->getFilters();
        $iterator = new Filter(new FilesystemIterator($directory), $options, $filters);

        if (!empty($options['count'])) {
            return iterator_count($iterator);
        }

        if (!empty($options['limit'])) {
            list($page, $limit) = $options['limit'];
            $iterator = new LimitIterator($iterator, $page, $limit);
        }

        $result = iterator_to_array($iterator);
        $this->sort($result, $options['sort'], $options['order']);

        $this->hook->attach('module.file_manager.scan.after', $directory, $options, $result, $this);
        return $result;
    }

    /**
     * Sorts an array of files
     * @param array $files
     * @param string $sort
     * @param string $order
     */
    protected function sort(array &$files, $sort, $order)
    {
        $sorters = $this->getSorters();

        if (isset($sorters[$sort]['handlers']['sort'])) {
            $function = $sorters[$sort]['handlers']['sort'];
            // Suppress errors, see https://bugs.php.net/bug.php?id=50688
            @uasort($files, function($a, $b) use ($function, $order) {
                        return $function($a, $b, $order);
                    });
        }
    }

    /**
     * Returns an array of supported filters
     * @return array
     */
    public function getFilters()
    {
        $filters = &gplcart_static('module.file_manager.filter.list');

        if (isset($filters)) {
            return $filters;
        }

        $filters = (array) gplcart_config_get(__DIR__ . '/../config/filters.php');
        $this->hook->attach('module.file_manager.filter.list', $filters, $this);
        return $filters;
    }

    /**
     * Returns an array of file sorters
     * @return array
     */
    public function getSorters()
    {
        $sorters = &gplcart_static('module.file_manager.sorter.list');

        if (isset($sorters)) {
            return $sorters;
        }

        $sorters = (array) gplcart_config_get(__DIR__ . '/../config/sorters.php');
        $this->hook->attach('module.file_manager.sorter.list', $sorters, $this);
        return $sorters;
    }

    /**
     * Returns a string with initial path to scan files
     * @param bool|null|string $absolute
     * @return string
     */
    public function getInitialPath($absolute = false)
    {
        $path = $this->module->getSettings('file_manager', 'initial_path');
        return $absolute ? gplcart_file_absolute($path) : $path;
    }

}
