<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
return array(
    'date' => array(
        'input' => '10 days ago,yesterday', // @text
        'name' => 'Modified between', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file, $value) {

                $args = array_map('trim', explode(',', $value, 2));
                $args += array(1 => 'now');

                $from = strtotime($args[0]);
                $to = strtotime($args[1]);

                if ($from === false || $to === false) {
                    return false;
                }

                $mtime = $file->getMTime();
                return $mtime > $from && $mtime < $to;
            }
        )
    ),
    'ext' => array(
        'input' => 'jpg,gif,png',
        'name' => 'Has extension', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file, $value) {
                $exts = array_map('trim', explode(',', $value));
                return in_array($file->getExtension(), $exts);
            }
        )
    ),
    'contains' => array(
        'input' => '',
        'name' => 'Filename contains', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file, $value) {
                return stripos($file->getFilename(), $value) !== false;
            }
        )
    ),
    'type_dir' => array(
        'name' => 'Is directory', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file) {
                return $file->isDir();
            }
        )
    ),
    'type_file' => array(
        'name' => 'Is file', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file) {
                return $file->isFile();
            }
        )
    ),
    'type_link' => array(
        'name' => 'Is link', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file) {
                return $file->isLink();
            }
        )
    ),
    'executable' => array(
        'name' => 'Is executable', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file) {
                return $file->isExecutable();
            }
        )
    ),
    'writable' => array(
        'name' => 'Is writable', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file) {
                return $file->isWritable();
            }
        )
    ),
    'readable' => array(
        'name' => 'Is readable', // @text
        'handlers' => array(
            'filter' => function (\SplFileInfo $file) {
                return $file->isReadable();
            }
        )
    )
);
