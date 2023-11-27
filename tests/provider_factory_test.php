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
use core_component;
use ltisource_params\local\ltisource_params\providers\base;

/**
 * Tests for placeholder class.
 *
 * @package     ltisource_params
 * @copyright   2023 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @covers \ltisource_params\provider_factory
 */
class provider_factory_test extends advanced_testcase {

    /**
     * Test list of installed providers.
     */
    public function test_get_installed_providers() {
        $classes = core_component::get_component_classes_in_namespace(null, '\\local\\ltisource_params\\providers\\');

        $expected = [];
        foreach (array_keys($classes) as $class) {
            if (is_subclass_of($class, base::class)) {
                $instance = $class::get_instance();
                $expected[$instance->get_shortname()] = $instance;
            }
        }

        if (!empty($expected)) {
            uasort($expected, function (base $a, base $b) {
                return ($a->get_fullname() <=> $b->get_fullname());
            });
        }

        $this->assertEquals($expected, provider_factory::get_installed_providers());
    }
}
