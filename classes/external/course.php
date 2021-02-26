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

/**
 * External functions
 *
 * @package   local_report_completion
 * @copyright 2018 Osvaldo Arriola  <osvaldo@e-abclearning.com>
 * @license   http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

namespace local_smf\external;

use dml_exception;
use external_api;
use external_function_parameters;
use external_multiple_structure;
use external_single_structure;
use external_value;
use invalid_parameter_exception;
use moodle_exception;
use Exception;

defined('MOODLE_INTERNAL') || die();

class course extends \external_api
{
    /**
     * @return external_function_parameters
     */
    public static function get_template_parameters()
    {
        /**
         * parametros que acepta el ws
         */
        return new external_function_parameters(
            []
        );
    }

    /**
     * @param int $courseid
     * @return array
     * @throws dml_exception
     * @throws invalid_parameter_exception
     */
    public static function get_template()
    {
        global $DB, $CFG, $USER, $OUTPUT;
        error_log("llego al ws");
        
        try {
            $params = self::validate_parameters(
                self::get_template_parameters(),
                array()
            );
            require_login();
            $data = array();
            //$bu = $OUTPUT->render_from_template('local_smf/template_course_iframe', []);
            $html = 'jjjjjjjj';
        } catch (\Exception $e) {
            error_log(print_r($e, true));
            throw new moodle_exception('errormsg', 'local_smf', '', $e->getMessage());
        }

        
        return [
            'html' => $html
        ];
    }

    /**
     * @return external_multiple_structure
     */
    public static function get_template_returns()
    {
        return new external_single_structure(
            [
                'html' => new external_value(PARAM_RAW, 'elearningbool', VALUE_OPTIONAL, ''),
            ]
        );
    }
}
