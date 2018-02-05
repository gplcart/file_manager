<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
return array(
    'name' => array(
        'name' => 'Name', // @text
        'handlers' => array(
            'sort' => function (\SplFileInfo $a, \SplFileInfo $b, $order) {
                $result = strcmp($a->getFilename(), $b->getFilename());
                return $order === 'desc' ? -$result : $result;
            }
        )
    ),
    'date' => array(
        'name' => 'Date', // @text
        'handlers' => array(
            'sort' => function (\SplFileInfo $a, \SplFileInfo $b, $order) {
                $result = $a->getMTime() < $b->getMTime() ? -1 : 1;
                return $order === 'desc' ? -$result : $result;
            }
        )
    ),
    'size' => array(
        'name' => 'File size', // @text
        'handlers' => array(
            'sort' => function (\SplFileInfo $a, \SplFileInfo $b, $order) {
                $result = $a->getSize() < $b->getSize() ? -1 : 1;
                return $order === 'desc' ? -$result : $result;
            }
        )
    ),
    'type' => array(
        'name' => 'Type', // @text
        'handlers' => array(
            'sort' => function (\SplFileInfo $a, \SplFileInfo $b, $order) {
                $result = strcmp($a->getType(), $b->getType());
                return $order === 'desc' ? -$result : $result;
            }
        )
    )
);
