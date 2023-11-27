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

/**
 * Class representing placeholder.
 *
 * @package    ltisource_params
 * @author     Dmitrii Metelkin <dmitriim@catalyst-au.net>
 * @copyright  Catalyst IT
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class placeholder {

    /**
     *Placeholder prefix.
     */
    const PREFIX = 'Params';

    /**
     * A symbol to glue parts of a placeholder.
     */
    const GLUE = '.';

    /**
     * Get a list of all placeholders grouped by provider.
     *
     * @return array
     */
    public static function get_all_placeholders(): array {
        $placeholders = [];

        foreach (provider_factory::get_installed_providers() as $provider) {
            $fields = $provider->get_fields();
            array_walk($fields, function(&$field) use ($provider) {
                $field = placeholder::build_placeholder($provider->get_shortname(), $field);
            });

            $placeholders[$provider->get_shortname()] = $fields;
        }

        return $placeholders;
    }

    /**
     * Get a value of a given placeholder.
     *
     * @param string $placeholder Given placeholder.
     *
     * @return string|null return value or null if nor valid placeholder.
     */
    public static function get_value(string $placeholder): ?string {
        $providers = provider_factory::get_installed_providers();
        $field = self::extract_field($placeholder);
        $provider = self::extract_provider($placeholder);

        if (!empty($field) && !empty($provider) && key_exists($provider, $providers) ) {
            return $providers[$provider]->get_value($field);
        }

        return null;
    }

    /**
     * Build a placeholder based on provider name and field.
     *
     * @param string $provider Provider short name.
     * @param string $field Field name.
     *
     * @return string
     */
    public static function build_placeholder(string $provider, string $field): string {
        return self::PREFIX . self::GLUE . trim($provider) . self::GLUE . trim($field);
    }

    /**
     * Check if a given placeholder is valid.
     *
     * @param string $placeholder Given placeholder.
     *
     * @return bool
     */
    public static function is_valid_placeholder(string $placeholder): bool {
        $parts = explode(self::GLUE, $placeholder);

        if (count($parts) != 3) {
            return false;
        }

        if ($parts[0] != self::PREFIX) {
            return false;
        }

        foreach ($parts as $part) {
            if (empty($part) || trim($part) != $part) {
                return false;
            }
        }

        return true;
    }

    /**
     * Extract provider short name out of placeholder.
     *
     * @param string $placeholder Given placeholder.
     *
     * @return string
     */
    public static function extract_provider(string $placeholder): string {
        if (!self::is_valid_placeholder($placeholder)) {
            return '';
        }

        $parts = explode(self::GLUE, $placeholder);

        return $parts[1];
    }

    /**
     * Extract field name out of placeholder.
     *
     * @param string $placeholder Given placeholder.
     *
     * @return string
     */
    public static function extract_field(string $placeholder): string {
        if (!self::is_valid_placeholder($placeholder)) {
            return '';
        }

        $parts = explode(self::GLUE, $placeholder);

        return $parts[2];
    }
}
