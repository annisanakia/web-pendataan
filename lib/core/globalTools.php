<?php

namespace Lib\core;

class globalTools
{
    public function renderList($data, $name, $selected, $blank = true, $customClass = null)
    {
        $mutiple = is_array($selected) ? 'multiple' : '';

        if ($customClass) {
            $html = '<select class="' . $customClass . '" data-live-search="true" name="' . $name . '" ' . $mutiple . '>';
        } else {
            $html = '<select class="form-control selectpicker" data-live-search="true" name="' . $name . '" ' . $mutiple . '>';
        }

        if ($blank) {
            $html .= '<option value="">-- Pilih --</option>';
        }
        foreach ($data as $key => $row) {
            if (is_array($selected)) {
                if (in_array($key, $selected)) {
                    $select = 'selected';
                } else {
                    $select = '';
                }
            } else {
                if ((string)$selected === (string)$key) {
                    $select = 'selected';
                } else {
                    $select = '';
                }
            }
            $html .= '<option value="' . $key . '" ' . $select . '>' . $row . '</option>';
        }
        $html .= '</select>';
        return $html;
    }
}
