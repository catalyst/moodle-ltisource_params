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
 * Test user provider.
 *
 * @package    ltisource_params
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2024 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 *
 * @covers \ltisource_params\local\ltisource_params\providers\user;
 */
class user_test extends advanced_testcase {

    /**
     * A helper function to create a custom profile field.
     *
     * @param string $shortname Short name of the field.
     * @param string $datatype Type of the field, e.g. text, checkbox, datetime, menu and etc.
     * @param array $params List of params to insert to mdl_user_info_field. E.g. param1 => value, param2 => value
     *
     * @return \stdClass
     */
    protected function add_user_profile_field(string $shortname, string $datatype, array $params = []): \stdClass {
        global $DB;

        // Create a new profile field.
        $data = new \stdClass();
        $data->shortname = $shortname;
        $data->datatype = $datatype;
        $data->name = 'Test ' . $shortname;
        $data->description = 'This is a test field';
        $data->required = false;
        $data->locked = false;
        $data->forceunique = false;
        $data->signup = false;
        $data->visible = '0';
        $data->categoryid = '0';

        foreach ($params as $name => $value) {
            $data->$name = $value;
        }

        $DB->insert_record('user_info_field', $data);

        return $data;
    }

    /**
     * Test class constants.
     */
    public function test_constants() {
        $this->assertSame('profile_field_', user::PROFILE_FIELD_PREFIX);
    }

    /**
     * Test getting fields.
     */
    public function test_get_fields() {
        $this->resetAfterTest();

        // Create profile fields.
        $this->add_user_profile_field('text', 'text');
        $this->add_user_profile_field('checkbox', 'checkbox');
        $this->add_user_profile_field('menu', 'menu', ['param1' => 'menu value']);
        // This field won't be in use as is_user_object_data for this field  type returns false.
        $this->add_user_profile_field('textarea', 'textarea');

        $expected = [
            'id',
            'username',
            'auth',
            'firstname',
            'lastname',
            'email',
            'city',
            'country',
            'lang',
            'description',
            'idnumber',
            'institution',
            'department',
            'phone1',
            'phone2',
            'address',
            'firstnamephonetic',
            'lastnamephonetic',
            'middlename',
            'alternatename',
            'profile_field_text',
            'profile_field_checkbox',
            'profile_field_menu',
        ];

        $userprovider = user::get_instance();
        $this->assertSame($expected, $userprovider->get_fields());
    }

    /**
     * Test getting value for a field.
     */
    public function test_get_value() {
        global $USER;

        $this->resetAfterTest();

        // Create profile fields.
        $this->add_user_profile_field('text', 'text');
        $this->add_user_profile_field('checkbox', 'checkbox');
        $this->add_user_profile_field('menu', 'menu', ['param1' => 'menu value']);
        $this->add_user_profile_field('textarea', 'textarea');

        $user = $this->getDataGenerator()->create_user();

        profile_save_data((object)[
            'id' => $user->id,
            'profile_field_text' => 'Text value',
            'profile_field_checkbox' => true,
            'profile_field_menu' => 'menu value',
            'profile_field_textarea' => 'Text area value',
        ]);

        $this->setUser($user);

        $userprovider = user::get_instance();
        foreach ($userprovider->get_fields() as $field) {
            if (isset($USER->$field)) {
                $this->assertSame($USER->$field, $userprovider->get_value($field), 'Testing on ' . $field);
            } else {
                $this->assertSame('', $userprovider->get_value($field), 'Testing on ' . $field);
            }
        }

        $this->assertSame('', $userprovider->get_value('random'));
        $this->assertSame('', $userprovider->get_value('textarea'));
        $this->assertSame('', $userprovider->get_value(''));
    }
}
