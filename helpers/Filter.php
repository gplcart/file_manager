<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\helpers;

use FilterIterator;

/**
 * Extends FilterIterator to filter out unwanted files
 */
class Filter extends FilterIterator
{

    /**
     * An array of options
     * @var array
     */
    protected $options = array();

    /**
     * An array of filter handlers
     * @var array
     */
    protected $filters = array();

    /**
     * @param \Iterator $iterator
     * @param array $options
     * @param array $filters
     */
    public function __construct($iterator, array $options, array $filters)
    {
        parent::__construct($iterator);

        $this->options = $options;
        $this->filters = $filters;
    }

    /**
     * Check whether the current element of the iterator is acceptable
     */
    public function accept()
    {
        static $callable = null;

        $file = $this->getInnerIterator()->current();

        if (isset($callable)) {
            return $callable ? $this->filters[$this->options['filter_key']]['handlers']['filter']($file, $this->options['filter_value']) : true;
        }

        if (empty($this->filters[$this->options['filter_key']]['handlers']['filter'])) {
            $callable = false;
            return true;
        }

        $function = $this->filters[$this->options['filter_key']]['handlers']['filter'];

        if (!is_callable($function)) {
            $callable = false;
            return true;
        }

        $callable = true;
        return $function($file, $this->options['filter_value']);
    }

}
