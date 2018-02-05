<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
return array(
    'list' => array(
        'name' => 'List', // @text
        'tab' => 'Browse', // @text
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Listing', 'view'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Listing', 'allowed')
        )
    ),
    'read' => array(
        'name' => 'Read', // @text
        'tab' => 'Read',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Read', 'view'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Read', 'allowed')
        )
    ),
    'create' => array(
        'name' => 'Create', // @text
        'tab' => 'Create',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Create', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Create', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Create', 'allowed')
        )
    ),
    'rename' => array(
        'name' => 'Rename', // @text
        'tab' => 'Rename',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Rename', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Rename', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Rename', 'allowed')
        )
    ),
    'move' => array(
        'name' => 'Move', // @text
        'tab' => 'Move',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Move', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Move', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Move', 'allowed')
        )
    ),
    'emptydir' => array(
        'name' => 'Empty', // @text
        'tab' => 'Empty',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\EmptyDir', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\EmptyDir', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\EmptyDir', 'allowed')
        )
    ),
    'delete' => array(
        'name' => 'Delete', // @text
        'tab' => 'Delete',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Delete', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Delete', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Delete', 'allowed')
        )
    ),
    'copy' => array(
        'name' => 'Copy', // @text
        'tab' => 'Copy',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Copy', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Copy', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Copy', 'allowed')
        )
    ),
    'download' => array(
        'name' => 'Download', // @text
        'tab' => 'Download',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Download', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Download', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Download', 'allowed')
        )
    ),
    'upload' => array(
        'name' => 'Upload', // @text
        'tab' => 'Upload',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Upload', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Upload', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Upload', 'allowed')
        )
    )
);
