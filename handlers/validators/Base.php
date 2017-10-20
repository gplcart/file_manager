<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\core\Container;
use gplcart\core\handlers\validator\Base as BaseValidator;

/**
 * Base validator class
 */
class Base extends BaseValidator
{

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

        $this->scanner = Container::get('gplcart\\modules\\file_manager\\models\\Scanner');
    }

}
