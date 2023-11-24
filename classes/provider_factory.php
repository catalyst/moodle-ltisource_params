<?php
// This file is part of Moodle - http://moodle.org/
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
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

namespace ltisource_params;

use core_component;
use coding_exception;
use ltisource_params\local\ltisource_params\providers\base;

/**
 * This is a helper class to work with parameter providers.
 *
 * @package    ltisource_params
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2023 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class provider_factory {

    /**
     * Get a list of installed providers.
     *
     * @return base[]
     */
    public static function get_installed_providers(): array {
        $providers = [];
        $classes = core_component::get_component_classes_in_namespace(null, '\\local\\ltisource_params\\providers\\');

        foreach (array_keys($classes) as $class) {
            if (is_subclass_of($class, base::class)) {
                $instance = $class::get_instance();
                if (isset($providers[$instance->get_shortname()])) {
                    throw new coding_exception('Duplicate provider');
                }

                $providers[$instance->get_shortname()] = $instance;
            }
        }

        if (!empty($providers)) {
            // Sort by name.
            uasort($providers, function (base $a, base $b) {
                return ($a->get_fullname() <=> $b->get_fullname());
            });
        }

        return $providers;
    }
}
