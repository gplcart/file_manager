<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */

namespace gplcart\modules\file_manager\handlers\validators;

use gplcart\core\Config;
use gplcart\core\models\Language as LanguageModel;
use gplcart\modules\file_manager\models\Scanner as FileManagerScannerModel;
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
     * @param Config $config
     * @param LanguageModel $language
     * @param FileManagerScannerModel $scanner
     */
    public function __construct(Config $config, LanguageModel $language,
            FileManagerScannerModel $scanner)
    {
        parent::__construct($config, $language);

        $this->scanner = $scanner;
    }

}
