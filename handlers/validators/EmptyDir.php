<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\core\handlers\validator\Base as BaseValidator;

/**
 * Provides methods to validate "emptydir" command
 */
class EmptyDir extends BaseValidator
{

    /**
     * Constructor
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * @return boolean
     */
    public function validateEmptyDir()
    {
        return true;
    }

}
