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

namespace ltisource_params\local\ltisource_params\providers;

use advanced_testcase;

/**
 * Test course provider.
 *
 * @package    ltisource_params
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2023 Catalyst IT
 *
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @covers \ltisource_params\local\ltisource_params\providers\course;
 */
class course_test extends advanced_testcase {

    /**
     * Test class constants.
     */
    public function test_constants() {
        $this->assertSame('custom_field_', course::CUSTOM_FIELD_PREFIX);

        $this->assertSame([
            'category_name' => 'name',
            'category_idnumber' => 'idnumber',
        ], course::CATEGORY_FIELDS);
    }

    /**
     * Test getting fields.
     */
    public function test_get_fields() {
        $this->resetAfterTest();

        $fieldcategory = $this->getDataGenerator()->create_custom_field_category([]);
        $datefield = $this->getDataGenerator()->create_custom_field([
            'categoryid' => $fieldcategory->get('id'),
            'shortname' => 'testcustomfield',
            'name' => 'Test custom field',
            'type' => 'text',
        ]);

        $expected = ['id', 'fullname', 'shortname', 'idnumber', 'category', 'format',
            'startdate', 'enddate', 'category_name', 'category_idnumber', 'custom_field_testcustomfield'];

        $courseprovider = course::get_instance();
        $this->assertSame($expected, $courseprovider->get_fields());
    }

    /**
     * Test getting value for a field.
     */
    public function test_get_value() {
        global $COURSE;

        $this->resetAfterTest();

        $fieldcategory = $this->getDataGenerator()->create_custom_field_category([]);
        $datefield = $this->getDataGenerator()->create_custom_field([
            'categoryid' => $fieldcategory->get('id'),
            'shortname' => 'testcustomfield',
            'name' => 'Test custom field',
            'type' => 'text',
        ]);

        $category = $this->getDataGenerator()->create_category(['idnumber' => 'testcategory']);
        $course = $this->getDataGenerator()->create_course([
            'category' => $category->id,
            'customfields' => [
                [
                    'shortname' => $datefield->get('shortname'),
                    'value' => 'Test value of the custom field',
                ],
            ]
        ]);

        $COURSE = $course;

        $courseprovider = course::get_instance();
        foreach ($courseprovider->get_fields() as $field) {
            if (key_exists($field, course::CATEGORY_FIELDS)) {
                $name = course::CATEGORY_FIELDS[$field];
                $this->assertSame($category->$name, $courseprovider->get_value($field), 'Testing on ' . $field);
            } else if ($field == 'custom_field_testcustomfield') {
                $this->assertSame('Test value of the custom field', $courseprovider->get_value($field), 'Testing on ' . $field);
            } else {
                $this->assertSame($course->$field, $courseprovider->get_value($field), 'Testing on ' . $field);
            }
        }

        $this->assertSame('', $courseprovider->get_value('random'));
        $this->assertSame('', $courseprovider->get_value('testcustomfield'));
        $this->assertSame('', $courseprovider->get_value(''));
    }
}
