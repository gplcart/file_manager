<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
return array(
    'list' => array(
        'name' => /* @text */'List',
        'tab' => /* @text */'Browse',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Listing', 'view'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Listing', 'allowed')
        )
    ),
    'read' => array(
        'name' => /* @text */'Read',
        'tab' => /* @text */'Read',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Read', 'view'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Read', 'allowed')
        )
    ),
    'create' => array(
        'name' => /* @text */'Create',
        'tab' => /* @text */'Create',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Create', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Create', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Create', 'allowed')
        )
    ),
    'rename' => array(
        'name' => /* @text */'Rename',
        'tab' => /* @text */'Rename',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Rename', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Rename', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Rename', 'allowed')
        )
    ),
    'move' => array(
        'name' => /* @text */'Move',
        'tab' => /* @text */'Move',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Move', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Move', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Move', 'allowed')
        )
    ),
    'emptydir' => array(
        'name' => /* @text */'Empty',
        'tab' => /* @text */'Empty',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\EmptyDir', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\EmptyDir', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\EmptyDir', 'allowed')
        )
    ),
    'delete' => array(
        'name' => /* @text */'Delete',
        'tab' => /* @text */'Delete',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Delete', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Delete', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Delete', 'allowed')
        )
    ),
    'copy' => array(
        'name' => /* @text */'Copy',
        'tab' => /* @text */'Copy',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Copy', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Copy', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Copy', 'allowed')
        )
    ),
    'download' => array(
        'name' => /* @text */'Download',
        'tab' => /* @text */'Download',
        'multiple' => true,
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Download', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Download', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Download', 'allowed')
        )
    ),
    'upload' => array(
        'name' => /* @text */'Upload',
        'tab' => /* @text */'Upload',
        'handlers' => array(
            'view' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Upload', 'view'),
            'submit' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Upload', 'submit'),
            'allowed' => array('gplcart\\modules\\file_manager\\handlers\\commands\\Upload', 'allowed')
        )
    )
);
