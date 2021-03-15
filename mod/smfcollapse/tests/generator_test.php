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
 * PHPUnit smfcollapse generator tests
 *
 * @package    mod_smfcollapse
 * @category   phpunit
 * @copyright  2013 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();


/**
 * PHPUnit smfcollapse generator testcase
 *
 * @package    mod_smfcollapse
 * @category   phpunit
 * @copyright  2013 Jerome Mouneyrac
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */
class mod_smfcollapse_generator_testcase extends advanced_testcase {
    public function test_generator() {
        global $DB;

        $this->resetAfterTest(true);

        $this->assertEquals(0, $DB->count_records('smfcollapse'));

        $course = $this->getDataGenerator()->create_course();

        /** @var mod_smfcollapse_generator $generator */
        $generator = $this->getDataGenerator()->get_plugin_generator('mod_smfcollapse');
        $this->assertInstanceOf('mod_smfcollapse_generator', $generator);
        $this->assertEquals('smfcollapse', $generator->get_modulename());

        $generator->create_instance(array('course'=>$course->id));
        $generator->create_instance(array('course'=>$course->id));
        $smfcollapse = $generator->create_instance(array('course'=>$course->id));
        $this->assertEquals(3, $DB->count_records('smfcollapse'));

        $cm = get_coursemodule_from_instance('smfcollapse', $smfcollapse->id);
        $this->assertEquals($smfcollapse->id, $cm->instance);
        $this->assertEquals('smfcollapse', $cm->modname);
        $this->assertEquals($course->id, $cm->course);

        $context = context_module::instance($cm->id);
        $this->assertEquals($smfcollapse->cmid, $context->instanceid);
    }
}
