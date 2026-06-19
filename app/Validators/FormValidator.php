<?php

namespace App\Validators;

class FormValidator {
    private array $data;
    private array $errors = [];

    public function __construct(array $data) {
        $this->data = $data;
    }

    /**
     * Validate data against specified rules.
     * 
     * @param array $rules e.g. ['email' => 'required|email', 'password' => 'required|min:8']
     * @return array Errors array, empty if validation passes
     */
    public function validate(array $rules): array {
        $this->errors = [];

        foreach ($rules as $field => $fieldRules) {
            $value = $this->data[$field] ?? null;
            $rulesList = explode('|', $fieldRules);

            foreach ($rulesList as $rule) {
                $ruleName = $rule;
                $ruleParam = null;

                if (str_contains($rule, ':')) {
                    [$ruleName, $ruleParam] = explode(':', $rule, 2);
                }

                $this->applyRule($field, $value, $ruleName, $ruleParam);
            }
        }

        return $this->errors;
    }

    /**
     * Apply a specific rule to a field.
     */
    private function applyRule(string $field, $value, string $ruleName, ?string $ruleParam): void {
        // Humanize field name
        $niceName = ucwords(str_replace('_', ' ', $field));

        switch ($ruleName) {
            case 'required':
                if (is_null($value) || $value === '' || (is_array($value) && empty($value))) {
                    $this->addError($field, "The {$niceName} field is required.");
                }
                break;

            case 'email':
                if (!empty($value) && !filter_var($value, FILTER_VALIDATE_EMAIL)) {
                    $this->addError($field, "The {$niceName} must be a valid email address.");
                }
                break;

            case 'mobile':
                // Check if numeric and fits mobile format (e.g. 10 to 12 digits)
                if (!empty($value) && !preg_match('/^[0-9+]{10,15}$/', str_replace([' ', '-'], '', $value))) {
                    $this->addError($field, "The {$niceName} must be a valid contact number (10-15 digits).");
                }
                break;

            case 'numeric':
                if (!empty($value) && !is_numeric($value)) {
                    $this->addError($field, "The {$niceName} must be a number.");
                }
                break;

            case 'min':
                if (!empty($value)) {
                    if (is_numeric($value)) {
                        if ($value < (float)$ruleParam) {
                            $this->addError($field, "The {$niceName} must be at least {$ruleParam}.");
                        }
                    } else {
                        if (strlen((string)$value) < (int)$ruleParam) {
                            $this->addError($field, "The {$niceName} must be at least {$ruleParam} characters.");
                        }
                    }
                }
                break;

            case 'max':
                if (!empty($value)) {
                    if (is_numeric($value)) {
                        if ($value > (float)$ruleParam) {
                            $this->addError($field, "The {$niceName} may not be greater than {$ruleParam}.");
                        }
                    } else {
                        if (strlen((string)$value) > (int)$ruleParam) {
                            $this->addError($field, "The {$niceName} may not be greater than {$ruleParam} characters.");
                        }
                    }
                }
                break;

            case 'matches':
                $otherValue = $this->data[$ruleParam] ?? null;
                if ($value !== $otherValue) {
                    $otherName = ucwords(str_replace('_', ' ', $ruleParam));
                    $this->addError($field, "The {$niceName} confirmation does not match the {$otherName}.");
                }
                break;

            case 'date':
                if (!empty($value)) {
                    $d = \DateTime::createFromFormat('Y-m-d', $value);
                    $dAlt = \DateTime::createFromFormat('d-m-Y', $value);
                    if (!($d && $d->format('Y-m-d') === $value) && !($dAlt && $dAlt->format('d-m-Y') === $value)) {
                        $this->addError($field, "The {$niceName} must be a valid date.");
                    }
                }
                break;

            case 'in':
                if (!empty($value)) {
                    $allowed = explode(',', $ruleParam);
                    if (!in_array($value, $allowed)) {
                        $this->addError($field, "The selected {$niceName} is invalid.");
                    }
                }
                break;
        }
    }

    /**
     * Add an error message if not already present.
     */
    private function addError(string $field, string $message): void {
        if (!isset($this->errors[$field])) {
            $this->errors[$field] = $message;
        }
    }
}
