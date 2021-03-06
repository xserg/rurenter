<?php

/* vim: set expandtab tabstop=4 shiftwidth=4: */

/**
 * Element for HTML_QuickForm to display a CAPTCHA equation
 *
 * The HTML_QuickForm_CAPTCHA package adds an element to the
 * HTML_QuickForm package to display a CAPTCHA equation.
 *
 * This package requires the use of a PHP session.
 *
 * PHP versions 4 and 5
 *
 * @category   HTML
 * @package    HTML_QuickForm_CAPTCHA
 * @author     Philippe Jausions <Philippe.Jausions@11abacus.com>
 * @copyright  2006 by 11abacus
 * @license    LGPL
 * @version    CVS: $Id: Equation.php 21 2010-10-28 06:25:35Z xxserg $
 * @link       http://pear.php.net/package/HTML_QuickForm_CAPTCHA
 */

/**
 * Required packages
 */
require_once 'HTML/QuickForm/CAPTCHA.php';
require_once 'Text/CAPTCHA/Driver/Equation.php';

/**
 * Element for HTML_QuickForm to display a CAPTCHA equation question
 *
 * The HTML_QuickForm_CAPTCHA package adds an element to the
 * HTML_QuickForm package to display a CAPTCHA equation question.
 *
 * Options for the element
 * <ul>
 *  <li>'min'           (integer)  Minimal number to use in an equation.</li>
 *  <li>'max'           (integer)  Maximal number to use in an equation.</li>
 *  <li>'severity'      (integer)  Complexity of the equation to resolve
 *                                 (1 = easy, 2 = harder)</li>
 *  <li>'numbersToText' (boolean)  Whether to use the Numbers_Words
 *                                 package to convert numbers to text,</li>
 *  <li>'sessionVar'    (string)   name of session variable containing
 *                                 the Text_CAPTCHA instance (defaults to
 *                                 _HTML_QuickForm_CAPTCHA.)</li>
 * </ul>
 *
 * This package requires the use of a PHP session.
 *
 * PHP versions 4 and 5
 *
 * @category   HTML
 * @package    HTML_QuickForm_CAPTCHA
 * @author     Philippe Jausions <Philippe.Jausions@11abacus.com>
 * @copyright  2006 by 11abacus
 * @license    LGPL
 * @version    Release: 0.2.1
 * @link       http://pear.php.net/package/HTML_QuickForm_CAPTCHA
 * @see        Text_CAPTCHA_Driver_Equation
 */
class HTML_QuickForm_CAPTCHA_Equation extends HTML_QuickForm_CAPTCHA
{
    /**
     * Default options
     *
     * @var array
     * @access protected
     */
    var $_options = array(
            'sessionVar'    => '_HTML_QuickForm_CAPTCHA',
            'severity'      => 1,
            'numbersToText' => false,
            'phrase'        => null,
            );

    /**
     * CAPTCHA driver
     *
     * @var string
     * @access protected
     */
    var $_CAPTCHA_driver = 'Equation';
}

/**
 * Register the class with QuickForm
 */
if (class_exists('HTML_QuickForm')) {
    HTML_QuickForm::registerElementType('CAPTCHA_Equation',
            'HTML/QuickForm/CAPTCHA/Equation.php',
            'HTML_QuickForm_CAPTCHA_Equation');
}

?>