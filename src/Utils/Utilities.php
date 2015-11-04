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
     * e.g. Path/To/Class.php -> \Path\To\Class
     *
     * @param string $string
     *
     * @return string $string
     */
    public static function getFullNamespace($string)
    {
        $string = preg_replace('/\.php$/', '', $string);
        $string = str_replace('/', '\\', $string);
        if(substr($string, 0, 1) !== '\\') {
            $string = '\\'.$string;
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
     * @param string $base_directory (optional) will set prefix to classes
     * @param array $classes (optional) begin with array of classes
     *
     * @throws \Exception if $directory is not a directory
     *
     * @return array $classes
     */
    public static function getClasses($directory, $base_directory = '', $classes = null)
    {
        if(!is_dir($directory)) {
            throw new \Exception('Invalid directory `'.$directory.'` provided for \LewNelson\Namecheap\Utilities::getClasses');
        }

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