<?php

/**
 * This file is part of the lewnelson/namecheap-interface package.
 *
 * (c) Lewis Nelson <lewis@lewnelson.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace LewNelson\Namecheap\Utils;

/**
 * Useful functions used throughout Namecheap library
 *
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class Utilities
{
    /**
     * Converts a CamelCase string to underscores
     * Used to convert pathnames
     * e.g. CamelCase/Path -> camel_case_path
     *
     * @param string $string
     * @throws \Exception when empty string passed is not a string
     * @throws \Exception when empty string is passed
     *
     * @return string $string
     */
    public static function convertCamelCaseToUnderscore($string)
    {
        $new_parts = array();

        if(!is_string($string)) {
            throw new \Exception('Unexpected value for string in Utilities::convertCamelCaseToUnderscore expecting `string`');
        }

        if(strlen($string) < 1) {
            throw new \Exception('Cannot convert empty string from camelCase to underscore');
        }

        if(substr($string, 0, 1) === '/') {
            $string = preg_replace('/^(\/+)/', '', $string);
        }

        if(substr($string, strlen($string) - 1) === '/') {
            $string = preg_replace('/(\/+)$/', '', $string);
        }

        $parts = explode('/', $string);
        foreach($parts as $index => $part) {
            $replacement = strtolower(preg_replace('/([A-Z])/', '_$1', $part));
            if(ctype_upper(substr($part, 0, 1)) === true) {
                $part = substr($replacement, 1);
            } else {
                $part = $replacement;
            }

            $new_parts[] = $part;
        }

        $string = implode('_', $new_parts);
        return $string;
    }

    /**
     * Converts a given path to PHP file (with or
     * without .php extension) into the namespace
     * e.g. Path/To/Class.php -> \LewNelson\Namecheap\Path\To\Class
     *
     * @param string $string
     * @throws \Exception if $string is not of type string
     * @throws \Exception if $string is empty
     * @throws \Exception if $string is invalid format
     *
     * @return string $string
     */
    public static function getFullNamespace($string)
    {
        if(!is_string($string)) {
            throw new \Exception('Invalid value given for Utilities::getFullNamespace, expecting string');
        }

        if(strlen($string) < 1) {
            throw new \Exception('Value for $string cannot be empty');
        }

        if(strpos($string, '//') !== false) {
            throw new \Exception('Invalid value provided for $string');
        }

        $prefix = '\\LewNelson\\Namecheap';
        $string = preg_replace('/\.php$/', '', $string);
        $string = str_replace('/', '\\', $string);
        if(substr($string, 0, 1) !== '\\') {
            $string = '\\'.$string;
        }

        if(substr($string, 0, strlen($prefix)) !== $prefix) {
            $string = $prefix.$string;
        }

        return $string;
    }

    /**
     * Gets all classes from a given directory.
     * Recursive into all subdirectories, builds array
     * of classes in format
     *
     * Class
     * Directory/Class
     * Directory/SubDirectory/Class
     *
     * @param string $directory
     * @param string $prefix (optional) will set prefix to classes
     * @param array $classes (optional) begin with array of classes
     *
     * @throws \Exception if $directory is not a directory
     *
     * @return array $classes
     */
    public static function getClasses($directory, $prefix = '', $classes = array())
    {
        if(!is_dir($directory)) {
            throw new \Exception('Invalid directory `'.$directory.'` provided for \LewNelson\Namecheap\Utilities::getClasses');
        }

        $files = array_diff(scandir($directory), array('.', '..'));
        foreach($files as $file) {
            if(is_dir($directory.'/'.$file)) {
                if(strlen($prefix) === 0) {
                    $new_base_directory = $file;
                } else {
                    $new_prefix = '/'.$file;
                }
                $classes = self::getClasses($directory.'/'.$file, $new_prefix, $classes);
            } else {
                $class = preg_replace('/\.php$/', '', $file);
                if(strlen($prefix) > 0) {
                    $class = $prefix.'/'.$class;
                }

                $classes[] = $class;
            }
        }

        return $classes;
    }
}

?>