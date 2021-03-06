<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\modules\file_manager\models\Scanner;

/**
 * Provides methods to validate "move" command
 */
class Move extends Copy
{

    /**
     * Move constructor.
     * @param Scanner $scanner
     */
    public function __construct(Scanner $scanner)
    {
        parent::__construct($scanner);
    }

    /**
     * Validates an array of submitted data while moving a file to another destination
     * @param array $submitted
     * @param array $options
     * @return boolean|array
     */
    public function validateMove(array &$submitted, array $options = array())
    {
        return $this->validateCopy($submitted, $options);
    }

}
