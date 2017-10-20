<?php

/**
 * @package File manager
 * @author Iurii Makukh <gplcart.software@gmail.com>
 * @copyright Copyright (c) 2017, Iurii Makukh <gplcart.software@gmail.com>
 * @license https://www.gnu.org/licenses/gpl-3.0.en.html GPL-3.0+
 */
return array(
    'date' => array(
        'input' => /* @text */'10 days ago,yesterday',
        'name' => /* @text */'Modified between',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file, $value) {

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
        'input' => /* @text */'jpg,gif,png',
        'name' => /* @text */'Has extension',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file, $value) {
                $exts = array_map('trim', explode(',', $value));
                return in_array($file->getExtension(), $exts);
            }
        )
    ),
    'contains' => array(
        'input' => '',
        'name' => /* @text */'Filename contains',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file, $value) {
                return stripos($file->getFilename(), $value) !== false;
            }
        )
    ),
    'type_dir' => array(
        'name' => /* @text */'Is directory',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file) {
                return $file->isDir();
            }
        )
    ),
    'type_file' => array(
        'name' => /* @text */'Is file',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file) {
                return $file->isFile();
            }
        )
    ),
    'type_link' => array(
        'name' => /* @text */'Is link',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file) {
                return $file->isLink();
            }
        )
    ),
    'executable' => array(
        'name' => /* @text */'Is executable',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file) {
                return $file->isExecutable();
            }
        )
    ),
    'writable' => array(
        'name' => /* @text */'Is writable',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file) {
                return $file->isWritable();
            }
        )
    ),
    'readable' => array(
        'name' => /* @text */'Is readable',
        'handlers' => array(
            'filter' => function(\SplFileInfo $file) {
                return $file->isReadable();
            }
        )
    )
);
