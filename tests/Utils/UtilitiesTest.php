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

use LewNelson\Namecheap\Utils\Utilities;

/**
 * @author Lewis Nelson <lewis@lewnelson.com>
 */
class UtilitiesTest extends \PHPUnit_Framework_TestCase
{
    public function camelCaseToUnderscoreValidProvider()
    {
        return array(
                array('UpperCamelCase', 'upper_camel_case'),
                array('UpperCamelCase/WithPath', 'upper_camel_case_with_path'),
                array('lowerCamelCase', 'lower_camel_case'),
                array('lowerCamelCase/withPath', 'lower_camel_case_with_path'),
                array('/startWithPath/Separator', 'start_with_path_separator'),
                array('endWithPath/Separator/', 'end_with_path_separator')
            );
    }

    public function camelCaseToUnderscoreInvalidProvider()
    {
        return array(
                array(''),
                array(array())
            );
    }

    /**
     * @dataProvider camelCaseToUnderscoreValidProvider
     */
    public function testCamelCaseToUnderscoreValidValues($input, $expected_output)
    {
        $output = Utilities::convertCamelCaseToUnderscore($input);
        $this->assertEquals($expected_output, $output);
    }

    /**
     * @expectedException \Exception
     * @dataProvider camelCaseToUnderscoreInvalidProvider
     */
    public function testCamelCaseToUnderscoreInvalidValues($input)
    {
        $output = Utilities::convertCamelCaseToUnderscore($input);
    }
}

?>