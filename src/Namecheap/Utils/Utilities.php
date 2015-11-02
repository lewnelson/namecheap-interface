<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Namecheap\Utils;

/**
 * Useful functions used throughout Namecheap library
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Utilities
{
    public static function convertCamelCaseToUnderscore($string)
    {
        $new_parts = array();
        $parts = explode('/', $string);
        foreach($parts as $index => $part) {
            $part = substr(strtolower(preg_replace('/([A-Z])/', '_$1', $part)), 1);
            $new_parts[] = $part;
        }

        $string = implode('_', $new_parts);
        return $string;
    }

    public static function getFullNamespace($string)
    {
        // Remove .php extension if exists
        $string = preg_replace('/\.php$/', '', $string);
        $string = str_replace('/', '\\', $string);
        if(substr($string, 0, 1) !== '\\') {
            $string = '\\'.$string;
        }

        return $string;
    }

    public static function getClasses($directory, $base_directory = '', $classes = null)
    {
        if($classes === null) {
            $classes = array();
        }

        $files = array_diff(scandir($directory), array('.', '..'));
        foreach($files as $file) {
            if(is_dir($directory.'/'.$file)) {
                if(strlen($base_directory) === 0) {
                    $new_base_directory = $file;
                } else {
                    $new_base_directory = '/'.$file;
                }
                $classes = self::getClasses($directory.'/'.$file, $new_base_directory, $classes);
            } else {
                $class = preg_replace('/\.php$/', '', $file);
                if(strlen($base_directory) > 0) {
                    $class = $base_directory.'/'.$class;
                }

                $classes[] = $class;
            }
        }

        return $classes;
    }
}

?>