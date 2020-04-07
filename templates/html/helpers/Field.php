<?php
/**
 * @copyright 2016-2019 City of Bloomington, Indiana
 * @license http://www.gnu.org/licenses/agpl.txt GNU/AGPL, see LICENSE
 */
namespace Web\Templates\Helpers;

use Web\Helper;
use Web\View;

class Field extends Helper
{
    /**
     * Params keys:
     *
     * label      string
     * name       string
     * id         string
     * value      mixed
     * type       string   HTML5 input tag type (text, email, date, etc.)
     * required   bool
     * attr       array    Additional attributes to include inside the input tag
     *
     * @param array $params
     */
    public function field(array $params)
    {
        $required = '';
        $classes  = '';
        if (!empty($params['required']) && $params['required']) {
            $required = 'required="true"';
            $class[]  = 'required';
        }

        if (isset(  $params['type'])) {
            switch ($params['type']) {
                case 'date':
                    // Until all browsers implement a date picker,
                    // we must continue to use plain text inputs for dates.
                    #unset($params['type']);

                    $params['value'] = !empty($params['value']) ? $params['value']->format('Y-m-d') : '';
                    $params['attr']['placeholder'] = View::translateDateString('Y-m-d');
                    $renderInput = 'input';
                break;

                case 'select':
                case 'textarea':
                case 'radio':
                case 'checkbox':
                case 'person':
                case 'chooser':
                case 'file':
                    $class[]     = $params['type'];
                    $renderInput = $params['type'];
                break;

                default:
                    $renderInput = 'input';
            }
        }
        else {
            $renderInput = 'input';
        }

        if (!empty($class)) { $classes = ' class="'.implode(' ', $class).'"'; }

        $attr  = !empty($params['attr' ]) ? self::attributesToString($params['attr']) : '';
        $for   = !empty($params['id'   ]) ? " for=\"$params[id]\""                    : '';
        $label = !empty($params['label']) ? "<label$for>$params[label]</label>"       : '';
        $help  = !empty($params['help' ]) ? "<div class=\"help\">$params[help]</div>" : '';

        $input = $this->$renderInput($params, $required, $attr);

        return "
        <div$classes>
            $label
            $input$help
        </div>
        ";
    }

    private static function attributesToString(array $attr): string
    {
        $out = '';
        foreach ($attr as $k=>$v) { $out.= "$k=\"$v\""; }
        return $out;
    }

    /**
     * Returns HTML for a generic input element
     *
     * @param array  $params    Raw params array passed to the field
     * @param string $required  The string for the attribute 'required="true"'
     * @param string $attr      The string representation of $params[attr]
     */
    public function input(array $params, $required=null, $attr=null)
    {
        $value = !empty($params['value']) ? $params['value'] : '';

        $id   = '';
        $type = '';
        if (!empty($params['id'  ])) { $id   =   "id=\"$params[id]\""; }
        if (!empty($params['type'])) { $type = "type=\"$params[type]\""; }

        return "<input name=\"$params[name]\" $id $type value=\"$value\" $required  $attr />";
    }

    /**
     * Returns HTML for a select dropdown
     *
     * Additional $params keys:
     *     options  array  The choices to provide the user
     *
     * @param array  $params    Raw params array passed to the field
     * @param string $required  The string for the attribute 'required="true"'
     * @param string $attr      The string representation of $params[attr]
     */
    public function select(array $params, $required=null, $attr=null)
    {
        if ($params['type'] !== 'select') { throw new \Exception('incorrectType'); }

        $value = !empty($params['value']) ? $params['value'] : '';

        $select = "<select name=\"$params[name]\" id=\"$params[id]\" $required $attr>";
        if (!empty(  $params['options'])) {
            foreach ($params['options'] as $o) {
                $attr     = !empty($o['attr' ])   ? self::attributesToString($o['attr']) : '';
                $label    = !empty($o['label'])   ? $o['label']       : $o['value'];
                $selected = $value == $o['value'] ? 'selected="true"' : '';
                $select.= "<option value=\"$o[value]\" $selected $attr>$label</option>";
            }
        }
        $select.= "</select>";
        return $select;
    }

    /**
     * Returns HTML for a set of radio buttons
     *
     * Additional $params keys:
     *     options  array  The choices to provide the user
     *
     * @param array  $params    Raw params array passed to the field
     * @param string $required  The string for the attribute 'required="true"'
     * @param string $attr      The string representation of $params[attr]
     */
    public function radio(array $params, $required=null, $attr=null)
    {
        if ($params['type'] !== 'radio') { throw new \Exception('incorrectType'); }

        $value = !empty($params['value']) ? $params['value'] : '';

        $radioButtons = '<div>';
        if (!empty(  $params['options'])) {
            foreach ($params['options'] as $o) {
                $attr    = !empty($o['attr' ])   ? self::attributesToString($o['attr']) : '';
                $label   = !empty($o['label'])   ? $o['label']      : $o['value'];
                $checked = $value == $o['value'] ? 'checked="true"' : '';

                $radioButtons.= "<label><input name=\"$params[name]\" type=\"radio\" value=\"$o[value]\" $attr $checked /> $label</label>";
            }
        }
        $radioButtons .= '</div>';
        return $radioButtons;
    }

    /**
     * Returns HTML for a set of checkboxes
     *
     * Additional $params keys:
     *     options  array  The choices to provide the user
     *
     * @param array  $params    Raw params array passed to the field
     * @param string $required  The string for the attribute 'required="true"'
     * @param string $attr      The string representation of $params[attr]
     */
    public function checkbox(array $params, $required=null, $attr=null)
    {
        if ($params['type'] !== 'checkbox') { throw new \Exception('incorrectType'); }

        $values = !empty($params['value']) ? $params['value'] : [];

        $inputs = '<div>';
        if (!empty(  $params['options'])) {
            foreach ($params['options'] as $o) {
                $attr    = !empty($o['attr' ]) ? self::attributesToString($o['attr']) : '';
                $label   = !empty($o['label']) ? $o['label'] : $o['value'];
                $checked = in_array($o['value'], $values) ? 'checked="true"' : '';

                $name   = $params['name'].'['.$o['value'].']';
                $inputs.= "<label><input name=\"$name\" type=\"checkbox\" value=\"$o[value]\" $attr $checked /> $label</label>";
            }
        }
        $inputs .= '</div>';
        return $inputs;
    }

    /**
     * Returns HTML for a generic textarea element
     *
     * @param array  $params    Raw params array passed to the field
     * @param string $required  The string for the attribute 'required="true"'
     * @param string $attr      The string representation of $params[attr]
     */
    public function textarea(array $params, $required=null, $attr=null)
    {
        if ($params['type'] !== 'textarea') { throw new \Exception('incorrectType'); }

        $value = !empty($params['value']) ? $params['value'] : '';

        return "<textarea name=\"$params[name]\" id=\"$params[id]\" $required $attr>$value</textarea>";
    }

    /**
     * Returns HTML for a file input
     *
     * @param array  $params    Raw params array passed to the field
     * @param string $required  The string for the attribute 'required="true"'
     * @param string $attr      The string representation of $params[attr]
     */
    public function file(array $params, $required=null, $attr=null)
    {
        return "<input type=\"file\" name=\"$params[name]\" id=\"$params[id]\" $required $attr />";
    }

    /**
     * Returns HTML for a generic chooser
     *
     * A chooser is javascript and HTML for a search-and-choose process.
     * Choosers are usually displayed as a modal dialog.
     *
     * @see Web\Template\Helpers\Chooser
     *
     * Additional $params keys:
     *     display string   The string to display for the currently chosen object
     *     url     string   The URI to the chooser
     *
     * @param array  $params    Raw params array passed to the field
     * @param string $required  The string for the attribute 'required="true"'
     * @param string $attr      The string representation of $params[attr]
     */
    public function chooser(array $params, ?bool $required=false, ?string $attr=null)
    {
        $h = $this->template->getHelper('chooser');
        return $h->chooser($params['name'], $params['id'], $params['chooser'], $params['value'], $params['display']);
    }
}
