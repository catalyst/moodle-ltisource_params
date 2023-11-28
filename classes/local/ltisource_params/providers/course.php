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

use core_course_category;
use core_course\customfield\course_handler;


/**
 * Course LTI data provider
 *
 * @package    ltisource_params
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2023 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class course extends base {

    /**
     * Custom field prefix for a placeholder.
     */
    const CUSTOM_FIELD_PREFIX = 'custom_field_';

    /**
     * Category related course fileds mapped with real category fields.
     */
    const CATEGORY_FIELDS = [
        'category_name' => 'name',
        'category_idnumber' => 'idnumber',
    ];

    /**
     * Returns a list of all placeholders.
     *
     * @return array
     */
    public function get_fields(): array {
        $fields = array_merge(
            ['id', 'fullname', 'shortname', 'idnumber', 'category', 'format', 'startdate', 'enddate'],
            array_keys(self::CATEGORY_FIELDS)
        );

        foreach (course_handler::create()->get_fields() as $customfield) {
            $fields[] = self::CUSTOM_FIELD_PREFIX . $customfield->get('shortname');
        }

        return  $fields;
    }

    /**
     * Gets a value for provided placeholder.
     *
     * @param string $field Placeholder name.
     *
     * @return string
     */
    public function get_value(string $field): string {
        global $COURSE;

        $course = clone $COURSE;
        $courses = [$course->id => $course];
        core_course_category::preload_custom_fields($courses);

        $course = reset($courses);

        if (!empty($course->customfields)) {
            $fields = $this->get_fields();
            foreach ($course->customfields as $customfield) {
                $fieldname = self::CUSTOM_FIELD_PREFIX . $customfield->get_field()->get('shortname');
                if (in_array($fieldname, $fields)) {
                    $course->{$fieldname} = $customfield->export_value();
                }
            }
        }

        if (key_exists($field, self::CATEGORY_FIELDS)) {
            $category = core_course_category::get($course->category);
            foreach (self::CATEGORY_FIELDS as $coursefield => $categoryfield) {
                $course->$coursefield = $category->$categoryfield;
            }
        }

        return $course->$field ?? '';
    }
}
