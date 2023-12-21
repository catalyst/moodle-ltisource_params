# Custom params LTI extension #

The plugin provides a way to extend LTI custom parameters.

## How it works ##
This plugin lets any other plugin implement a list of placeholders that can be used to populate custom LTI parameters for LTI tools.

When LTI request is being built those placeholders are replaced by real values.

A list of all available placeholders are displayed on activity editing form. 

For example. Out of the box the plugin supports Course parameter provider. 
That gives a list of placeholders that can be converted to values related to a course that LTI activity is stored at. E.g. placeholder Params.course.fullname will give a course full name. So custom LTI parameters may look like c.name=Params.course.fullname

## In-built parameter providers ##

* Course (including course custom fields)

## Implementing new parameter providers ##

The plugin is written in a way that any other plugins can implement parameters providers so they could be used in LTI activity form as placeholders. 

As an example, plugin itself got some parameter providers; the directory structure is as follows:

```
ltisource_params
└── classes
    └── local
        └── ltisource_params
           └── providers
                 └── course.php
```
Each provider **must extend** the base class.

## Installing via uploaded ZIP file ##

1. Log in to your Moodle site as an admin and go to _Site administration >
   Plugins > Install plugins_.
2. Upload the ZIP file with the plugin code. You should only be prompted to add
   extra details if your plugin type is not automatically detected.
3. Check the plugin validation report and finish the installation.

## Installing manually ##

The plugin can be also installed by putting the contents of this directory to

    {your/moodle/dirroot}/mod/lti/source/params

Afterwards, log in to your Moodle site as an admin and go to _Site administration >
Notifications_ to complete the installation.

Alternatively, you can run

    $ php admin/cli/upgrade.php

to complete the installation from the command line.

## License ##

2023 Dmitrii Metelkin <dmitriim@catalyst-au.net>

This program is free software: you can redistribute it and/or modify it under
the terms of the GNU General Public License as published by the Free Software
Foundation, either version 3 of the License, or (at your option) any later
version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY
WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A
PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with
this program.  If not, see <https://www.gnu.org/licenses/>.
