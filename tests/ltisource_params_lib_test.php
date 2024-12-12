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

/**
 * Tests for lib functions.
 *
 * @package     ltisource_params
 * @copyright   2023 Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @license     https://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class ltisource_params_lib_test extends \advanced_testcase {

    /**
     * Test generating parameters before launching LTI.
     *
     * @covers ::ltisource_params_before_launch
     */
    public function test_ltisource_params_before_launch() {
        global $COURSE;

        $this->resetAfterTest();
        $course = $this->getDataGenerator()->create_course();
        $lti = $this->getDataGenerator()->create_module('lti', ['course' => $course->id]);
        $COURSE = $course;

        $requestparams = [
            'course_id' => 'Params.course.id',
            'course_name' => 'Params.course.fullname',
            'course_shortname' => 'Params.course.shortname',
            'random_name' => 'Some_random_string',
        ];

        $expected = [
            'course_id' => $course->id,
            'course_name' => $course->fullname,
            'course_shortname' => $course->shortname,
        ];

        $this->assertSame($expected, ltisource_params_before_launch($lti, 'dummy.com', $requestparams));
    }
}
