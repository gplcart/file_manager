<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\core\handlers\validator\Base as BaseValidator;
use gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;

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
     * @param FileManagerScannerModel $scanner
     */
    public function __construct(FileManagerScannerModel $scanner)
    {
        parent::__construct();

        $this->scanner = $scanner;
    }

}
