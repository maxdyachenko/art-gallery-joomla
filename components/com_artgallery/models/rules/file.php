<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted access');
class JFormRuleFile extends JFormRule
{
    public function test(SimpleXMLElement $element, $value, $group = null, JRegistry $input = null, JForm $form = null)
    {
        die;
        return true;
    }
}