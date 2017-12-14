<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;
use gplcart\modules\file_manager\handlers\validators\Base as FileManagerBaseValidatorHandler;

/**
 * Provides methods to validate "delete" command
 */
class Delete extends FileManagerBaseValidatorHandler
{

    /**
     * @param FileManagerScannerModel $scanner
     */
    public function __construct(FileManagerScannerModel $scanner)
    {
        parent::__construct($scanner);
    }

    /**
     * @return boolean
     */
    public function validateDelete()
    {
        return true;
    }

}
