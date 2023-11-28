<?php
// This file is part of Moodle - https://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <https://www.gnu.org/licenses/>.

namespace ltisource_params;

use advanced_testcase;

/**
 * Tests for placeholder class.
 *
 * @package     ltisource_params
 * @copyright   2023 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @covers \ltisource_params\placeholder
 */
class placeholder_test extends advanced_testcase {

    /**
     * Data provider for testing test_build_placeholder method.
     *
     * @return array[]
     */
    public function build_placeholder_data_provider(): array {
        return [
            ['test_provider', 'test_field', 'Params.test_provider.test_field'],
            ['test_provider', ' test_field', 'Params.test_provider.test_field'],
            ['test_provider', '', 'Params.test_provider.'],
            ['', 'test_field', 'Params..test_field'],
            ['', '', 'Params..'],
            [' ', '  ', 'Params..'],
        ];
    }

    /**
     * Test checking valid placeholders.
     *
     * @dataProvider build_placeholder_data_provider
     *
     * @param string $provider Provider name for testing.
     * @param string $field Field name for testing.
     * @param string $expected Expected test result.
     */
    public function test_build_placeholder(string $provider, string $field, string $expected) {
        $this->assertSame($expected, placeholder::build_placeholder($provider, $field));
    }

    /**
     * Data provider for testing is_valid_placeholder method.
     *
     * @return array[]
     */
    public function is_valid_placeholder_data_provider(): array {
        return [
            ['Params.test_provider.test_field', true],
            [' Params.test_provider.test_field', false],
            ['Params. test_provider. test_field', false],
            ['Params test_provider test_field', false],
            ['Bla.test_provider.test_field', false],
            ['Params.test_provider.test_field.test', false],
            ['Params.test_provider.', false],
            ['Params..test_field', false],
            ['Params..', false],
            ['Params.test_field', false],
            ['Paramstest_field', false],
            ['Params. .  ', false],
        ];
    }

    /**
     * Test checking valid placeholders.
     *
     * @dataProvider is_valid_placeholder_data_provider
     *
     * @param string $placeholder Placeholder for testing.
     * @param bool $expected Expected test result.
     */
    public function test_is_valid_placeholder(string $placeholder, bool $expected) {
        $this->assertSame($expected, placeholder::is_valid_placeholder($placeholder));
    }

    /**
     * Data provider for testing extract_provider method.
     *
     * @return array[]
     */
    public function extract_provider_data_provider(): array {
        return [
            ['Params.test_provider.test_field', 'test_provider'],
            [' Params.test_provider.test_field', ''],
            ['Params. test_provider. test_field', ''],
            ['Params test_provider test_field', ''],
            ['Bla.test_provider.test_field', ''],
            ['Params.test_provider.test_field.test', ''],
            ['Params.test_provider.', ''],
            ['Params..test_field', ''],
            ['Params..', ''],
            ['Params.test_field', ''],
            ['Paramstest_field', ''],
            ['Params. .  ', ''],
        ];
    }

    /**
     * Test extracting provider.
     *
     * @dataProvider extract_provider_data_provider
     *
     * @param string $placeholder Placeholder for testing.
     * @param string $expected Expected test result.
     */
    public function test_extract_provider(string $placeholder, string $expected) {
        $this->assertSame($expected, placeholder::extract_provider($placeholder));
    }
    /**
     * Data provider for testing extract_field method.
     *
     * @return array[]
     */
    public function extract_field_data_provider(): array {
        return [
            ['Params.test_provider.test_field', 'test_field'],
            [' Params.test_provider.test_field', ''],
            ['Params. test_provider. test_field', ''],
            ['Params test_provider test_field', ''],
            ['Bla.test_provider.test_field', ''],
            ['Params.test_provider.test_field.test', ''],
            ['Params.test_provider.', ''],
            ['Params..test_field', ''],
            ['Params..', ''],
            ['Params.test_field', ''],
            ['Paramstest_field', ''],
            ['Params. .  ', ''],
        ];
    }

    /**
     * Test extracting provider.
     *
     * @dataProvider extract_field_data_provider
     *
     * @param string $placeholder Placeholder for testing.
     * @param string $expected Expected test result.
     */
    public function test_extract_field(string $placeholder, string $expected) {
        $this->assertSame($expected, placeholder::extract_field($placeholder));
    }
}
