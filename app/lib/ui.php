<?php
declare(strict_types=1);

/**
 * Vykreslí pole formulára (input / textarea / select) s popiskom a chybou.
 * $o: type, required, placeholder, options (pre select), rows, autocomplete, inputmode, hint, class, min
 */
function field(string $name, string $label, array $form, array $o = []): string
{
    $type = $o['type'] ?? 'text';
    $val = (string) ($form['values'][$name] ?? '');
    $err = $form['errors'][$name] ?? '';
    $id = 'f-' . $name;
    $req = !empty($o['required']);
    $describedBy = trim(($err ? $id . '-err ' : '') . (!empty($o['hint']) ? $id . '-hint' : ''));

    $attrs = ' id="' . e($id) . '" name="' . e($name) . '"';
    if ($req) {
        $attrs .= ' required';
    }
    if ($err) {
        $attrs .= ' aria-invalid="true"';
    }
    if ($describedBy !== '') {
        $attrs .= ' aria-describedby="' . e($describedBy) . '"';
    }
    foreach (['placeholder', 'autocomplete', 'inputmode', 'min', 'maxlength'] as $a) {
        if (isset($o[$a])) {
            $attrs .= ' ' . $a . '="' . e($o[$a]) . '"';
        }
    }

    $html = '<div class="field' . ($err ? ' has-error' : '') . (isset($o['class']) ? ' ' . e($o['class']) : '') . '">';
    $html .= '<label for="' . e($id) . '">' . e($label) . ($req ? ' <span class="req" aria-hidden="true">*</span>' : '') . '</label>';

    if ($type === 'textarea') {
        $html .= '<textarea' . $attrs . ' rows="' . (int) ($o['rows'] ?? 5) . '">' . e($val) . '</textarea>';
    } elseif ($type === 'select') {
        $html .= '<select' . $attrs . '>';
        foreach ($o['options'] as $k => $text) {
            $html .= '<option value="' . e($k) . '"' . ((string) $k === $val ? ' selected' : '') . '>' . e($text) . '</option>';
        }
        $html .= '</select>';
    } else {
        $html .= '<input type="' . e($type) . '"' . $attrs . ' value="' . e($val) . '">';
    }

    if (!empty($o['hint'])) {
        $html .= '<p class="field__hint" id="' . e($id) . '-hint">' . e($o['hint']) . '</p>';
    }
    $html .= '<p class="field__error" id="' . e($id) . '-err" role="alert">' . e($err) . '</p>';
    return $html . '</div>';
}
