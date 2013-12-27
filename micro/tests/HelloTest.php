<?php /** HelloTestMicro */

namespace Micro\tests;

/**
 * Class Hello test
 *
 * @author Oleg Lunegov <testuser@mail.linpax.org>
 * @link https://github.com/lugnsk/micro
 * @copyright Copyright &copy; 2013 Oleg Lunegov
 * @license /LICENSE
 * @package micro
 * @subpackage tests
 * @version 1.0
 * @since 1.0
 */
class HelloTest extends TestCase
{
    public function up()
    {
        //
    }

    public function down()
    {
        //
    }

    public function testHello()
    {
        $this->assertEquals('200 Ok', '200 Ok');
    }
}