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

namespace ltisource_params\local\ltisource_params\providers;

/**
 * Base class for params providers.
 *
 * @package    ltisource_params
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  2023 Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
abstract class base {

    /**
     * Protected constructor.
     */
    protected function __construct() {
    }

    /**
     * Get certificate instance.
     *
     * @return base
     */
    public static function get_instance(): base {
        return new static();
    }

    /**
     * Get full human-readable name of the certificate.
     *
     * @return string
     */
    public function get_fullname(): string {
        return get_string($this->get_shortname(), 'ltisource_params');
    }

    /**
     * Returns a short name of the certificate.
     *
     * @return string
     */
    final public function get_shortname(): string {
        return str_replace(__NAMESPACE__ . '\\', '', static::class);
    }

    /**
     * A helper function to generate a placeholder name.
     *
     * @param string $name Placeholder name.
     *
     * @return string
     */
    final protected function generate_placeholder(string $name): string {
        return 'CustomParams.' . $this->get_shortname() . '.' . $name;
    }

    /**
     * Returns a list of all placeholders.
     * @return array
     */
    abstract public function get_placeholders(): array;

    /**
     * Gets a value for provided placeholder.
     *
     * @param string $placeholder Placeholder name.
     * @return string
     */
    abstract public function get_value(string $placeholder): string;

}
