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

use core_user;

defined('MOODLE_INTERNAL') || die();

require_once($CFG->dirroot . '/user/profile/lib.php');

/**
 * User fields LTI parameters provider
 *
 * @package    ltisource_params
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2024 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class user extends base {

    /**
     * Custom profile field prefix for a placeholder.
     */
    const PROFILE_FIELD_PREFIX = 'profile_field_';

    /**
     * Returns a list of all placeholders.
     *
     * @return array
     */
    public function get_fields(): array {
        $fields = core_user::AUTHSYNCFIELDS;

        array_unshift($fields, 'auth');
        array_unshift($fields, 'username');
        array_unshift($fields, 'id');

        $customfields = array_column(profile_get_custom_fields(true), 'shortname', 'shortname');
        if (!empty($customfields)) {
            // Prefix custom profile fields to be able to distinguish.
            array_walk($customfields, function(&$value) {
                $value = self::PROFILE_FIELD_PREFIX . $value;
            });

            $fields = array_merge($fields, $customfields);
            $fields = array_values($fields);
        }

        return $fields;
    }

    /**
     * Gets a value for provided placeholder.
     *
     * @param string $field Placeholder name.
     * @return string
     */
    public function get_value(string $field): string {
        global $USER;

        profile_load_data($USER);

        return $USER->$field ?? '';
    }
}
