<?php

class Validator {
    private $errors = [];

    public function validate($data, $rules) {
        $this->errors = []; // Reset errors for each validation

        foreach ($rules as $field => $fieldRules) {
            foreach ($fieldRules as $rule => $value) {
                switch ($rule) {
                    case 'required':
                        if ($value && (!isset($data[$field]) || trim($data[$field]) === '')) {
                            $this->errors[$field][] = "กรุณากรอก {$field}";
                        }
                        break;

                    case 'min':
                        if (isset($data[$field]) && strlen(trim($data[$field])) < $value) {
                            $this->errors[$field][] = "{$field} ต้องมีอักขระอย่างน้อย {$value} ตัว";
                        }
                        break;

                    case 'max':
                        if (isset($data[$field]) && strlen(trim($data[$field])) > $value) {
                            $this->errors[$field][] = "{$field} ต้องมีอักขระไม่เกิน {$value} ตัว";
                        }
                        break;

                    case 'date':
                        if (isset($data[$field]) && !empty($data[$field])) {
                            $date = date_parse($data[$field]);
                            if ($date['error_count'] > 0) {
                                $this->errors[$field][] = "รูปแบบวันที่ไม่ถูกต้อง";
                            }
                        }
                        break;

                    case 'email':
                        if (isset($data[$field]) && !empty($data[$field])) {
                            if (!filter_var($data[$field], FILTER_VALIDATE_EMAIL)) {
                                $this->errors[$field][] = "รูปแบบอีเมลไม่ถูกต้อง";
                            }
                        }
                        break;

                    case 'numeric':
                        if (isset($data[$field]) && !empty($data[$field])) {
                            if (!is_numeric($data[$field])) {
                                $this->errors[$field][] = "{$field} ต้องเป็นตัวเลขเท่านั้น";
                            }
                        }
                        break;

                    case 'enum':
                        if (isset($data[$field]) && !empty($data[$field])) {
                            if (!in_array($data[$field], $value)) {
                                $this->errors[$field][] = "{$field} ต้องเป็นค่าใดค่าหนึ่งใน: " . implode(', ', $value);
                            }
                        }
                        break;
                }
            }
        }

        return empty($this->errors);
    }

    public function getErrors() {
        return $this->errors;
    }

    public function getFirstError() {
        foreach ($this->errors as $field => $errors) {
            if (!empty($errors)) {
                return reset($errors);
            }
        }
        return null;
    }
}