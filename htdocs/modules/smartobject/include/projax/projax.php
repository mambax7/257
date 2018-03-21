<?php
/**
 * Projax
 *
 * An open source set of php helper classes for prototype and script.aculo.us.
 *
 * @package     Projax
 * @author      Vikas Patial
 * @copyright   Copyright (c) 2006, ngcoders.
 * @license     http://www.gnu.org/copyleft/gpl.html
 * @link        http://www.ngcoders.com
 * @since       Version 0.2
 * @filesource
 */

if (!class_exists('Projax')) {
    include __DIR__ . '/classes/JavaScript.php';
    include __DIR__ . '/classes/Prototype.php';
    include __DIR__ . '/classes/Scriptaculous.php';

    // For $projax = new Projax();

    /**
     * Class projax
     */
    class projax extends Scriptaculous
    {
    }
}
