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

use ltisource_params\provider_factory;
use ltisource_params\placeholder;

/**
 * Plugin hook for loading custom elements into module forms.
 *
 * @param \moodleform_mod $modform moodle course module form to set customfields data in.
 * @param \MoodleQuickForm $form the module settings form itself.
 */
function ltisource_params_coursemodule_standard_elements(moodleform_mod $modform, \MoodleQuickForm $form) {
    global $CFG, $OUTPUT;

    if ($modform instanceof mod_lti_mod_form) {

        // Core static doesn't support set_force_ltr method to hide the field. We have to extend it by a custom one.
        // We need set_force_ltr to be able to hide this element behind "Show more" link in a form.
        \MoodleQuickForm::registerElementType(
            'staticforceltr',
            $CFG->dirroot . '/mod/lti/source/params/classes/static_forceltr.php',
            'ltisource_params\static_forceltr'
        );

        $providershtml = '';
        $providers = provider_factory::get_installed_providers();

        foreach (placeholder::get_all_placeholders() as $providername => $placeholders) {
            if (key_exists($providername, $providers)) {
                $providershtml .= $OUTPUT->render_from_template('ltisource_params/placeholders', [
                    'provider' => $providers[$providername]->get_fullname(),
                    'placeholders' => implode(', ', $placeholders),
                ]);
            }
        }

        $element = $form->createElement(
            'staticforceltr',
            'paramshelp',
            get_string('availableplaceholders', 'ltisource_params'),
            $providershtml
        );

        $form->insertElementBefore($element, 'icon');
        $form->addHelpButton('paramshelp', 'availableplaceholders', 'ltisource_params');
        $form->setAdvanced('paramshelp');
        $form->setForceLtr('paramshelp');
    }
}
