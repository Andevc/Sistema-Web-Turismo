<?php
/**
 * Clase Validator
 * Validación de datos de formularios y entrada del usuario
 */

class Validator {
    
    private $errors = [];
    private $data = [];
    
    /**
     * Constructor
     * @param array $data Datos a validar
     */
    public function __construct($data = []) {
        $this->data = $data;
    }
    
    /**
     * Valida que un campo sea requerido
     * @param string $field
     * @param string $message
     * @return self
     */
    public function required($field, $message = null) {
        if (!isset($this->data[$field]) || empty(trim($this->data[$field]))) {
            $this->errors[$field] = $message ?? "El campo {$field} es requerido";
        }
        return $this;
    }
    
    /**
     * Valida el formato de email
     * @param string $field
     * @param string $message
     * @return self
     */
    public function email($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
                $this->errors[$field] = $message ?? "El email no es válido";
            }
        }
        return $this;
    }
    
    /**
     * Valida la longitud mínima de un campo
     * @param string $field
     * @param int $min
     * @param string $message
     * @return self
     */
    public function minLength($field, $min, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (strlen($this->data[$field]) < $min) {
                $this->errors[$field] = $message ?? "El campo {$field} debe tener al menos {$min} caracteres";
            }
        }
        return $this;
    }
    
    /**
     * Valida la longitud máxima de un campo
     * @param string $field
     * @param int $max
     * @param string $message
     * @return self
     */
    public function maxLength($field, $max, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (strlen($this->data[$field]) > $max) {
                $this->errors[$field] = $message ?? "El campo {$field} no debe exceder {$max} caracteres";
            }
        }
        return $this;
    }
    
    /**
     * Valida que un campo sea numérico
     * @param string $field
     * @param string $message
     * @return self
     */
    public function numeric($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!is_numeric($this->data[$field])) {
                $this->errors[$field] = $message ?? "El campo {$field} debe ser numérico";
            }
        }
        return $this;
    }
    
    /**
     * Valida que un campo sea un número entero
     * @param string $field
     * @param string $message
     * @return self
     */
    public function integer($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_INT)) {
                $this->errors[$field] = $message ?? "El campo {$field} debe ser un número entero";
            }
        }
        return $this;
    }
    
    /**
     * Valida que un campo tenga un valor mínimo
     * @param string $field
     * @param float $min
     * @param string $message
     * @return self
     */
    public function min($field, $min, $message = null) {
        if (isset($this->data[$field]) && is_numeric($this->data[$field])) {
            if ($this->data[$field] < $min) {
                $this->errors[$field] = $message ?? "El campo {$field} debe ser al menos {$min}";
            }
        }
        return $this;
    }
    
    /**
     * Valida que un campo tenga un valor máximo
     * @param string $field
     * @param float $max
     * @param string $message
     * @return self
     */
    public function max($field, $max, $message = null) {
        if (isset($this->data[$field]) && is_numeric($this->data[$field])) {
            if ($this->data[$field] > $max) {
                $this->errors[$field] = $message ?? "El campo {$field} no debe exceder {$max}";
            }
        }
        return $this;
    }
    
    /**
     * Valida que dos campos coincidan
     * @param string $field1
     * @param string $field2
     * @param string $message
     * @return self
     */
    public function match($field1, $field2, $message = null) {
        if (isset($this->data[$field1]) && isset($this->data[$field2])) {
            if ($this->data[$field1] !== $this->data[$field2]) {
                $this->errors[$field2] = $message ?? "Los campos no coinciden";
            }
        }
        return $this;
    }
    
    /**
     * Valida que un campo esté dentro de un conjunto de valores
     * @param string $field
     * @param array $values
     * @param string $message
     * @return self
     */
    public function in($field, $values, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!in_array($this->data[$field], $values)) {
                $this->errors[$field] = $message ?? "El valor del campo {$field} no es válido";
            }
        }
        return $this;
    }
    
    /**
     * Valida formato de fecha
     * @param string $field
     * @param string $format
     * @param string $message
     * @return self
     */
    public function date($field, $format = 'Y-m-d', $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            $date = DateTime::createFromFormat($format, $this->data[$field]);
            if (!$date || $date->format($format) !== $this->data[$field]) {
                $this->errors[$field] = $message ?? "El campo {$field} no tiene un formato de fecha válido";
            }
        }
        return $this;
    }
    
    /**
     * Valida que una fecha sea futura
     * @param string $field
     * @param string $message
     * @return self
     */
    public function futureDate($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            $fecha = strtotime($this->data[$field]);
            $hoy = strtotime('today');
            
            if ($fecha < $hoy) {
                $this->errors[$field] = $message ?? "La fecha debe ser futura";
            }
        }
        return $this;
    }
    
    /**
     * Valida formato de URL
     * @param string $field
     * @param string $message
     * @return self
     */
    public function url($field, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!filter_var($this->data[$field], FILTER_VALIDATE_URL)) {
                $this->errors[$field] = $message ?? "El campo {$field} no es una URL válida";
            }
        }
        return $this;
    }
    
    /**
     * Valida expresión regular personalizada
     * @param string $field
     * @param string $pattern
     * @param string $message
     * @return self
     */
    public function regex($field, $pattern, $message = null) {
        if (isset($this->data[$field]) && !empty($this->data[$field])) {
            if (!preg_match($pattern, $this->data[$field])) {
                $this->errors[$field] = $message ?? "El formato del campo {$field} no es válido";
            }
        }
        return $this;
    }
    
    /**
     * Valida que un archivo sea de imagen
     * @param string $field
     * @param string $message
     * @return self
     */
    public function image($field, $message = null) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/jpg'];
            $fileType = $_FILES[$field]['type'];
            
            if (!in_array($fileType, $allowed)) {
                $this->errors[$field] = $message ?? "El archivo debe ser una imagen (JPG, PNG, GIF)";
            }
        }
        return $this;
    }
    
    /**
     * Valida el tamaño máximo de un archivo
     * @param string $field
     * @param int $maxSize Tamaño en bytes
     * @param string $message
     * @return self
     */
    public function fileSize($field, $maxSize, $message = null) {
        if (isset($_FILES[$field]) && $_FILES[$field]['error'] === UPLOAD_ERR_OK) {
            if ($_FILES[$field]['size'] > $maxSize) {
                $maxMB = round($maxSize / (1024 * 1024), 2);
                $this->errors[$field] = $message ?? "El archivo no debe exceder {$maxMB} MB";
            }
        }
        return $this;
    }
    
    /**
     * Verifica si hay errores
     * @return bool
     */
    public function fails() {
        return !empty($this->errors);
    }
    
    /**
     * Verifica si la validación pasó
     * @return bool
     */
    public function passes() {
        return empty($this->errors);
    }
    
    /**
     * Obtiene todos los errores
     * @return array
     */
    public function getErrors() {
        return $this->errors;
    }
    
    /**
     * Obtiene el primer error
     * @return string|null
     */
    public function getFirstError() {
        return !empty($this->errors) ? reset($this->errors) : null;
    }
    
    /**
     * Obtiene el error de un campo específico
     * @param string $field
     * @return string|null
     */
    public function getError($field) {
        return $this->errors[$field] ?? null;
    }
    
    /**
     * Limpia los errores
     */
    public function clearErrors() {
        $this->errors = [];
    }
    
    /**
     * Método estático para validación rápida
     * @param array $data
     * @param array $rules
     * @return Validator
     */
    public static function make($data, $rules) {
        $validator = new self($data);
        
        foreach ($rules as $field => $ruleSet) {
            $rulesArray = explode('|', $ruleSet);
            
            foreach ($rulesArray as $rule) {
                if (strpos($rule, ':') !== false) {
                    list($ruleName, $param) = explode(':', $rule);
                    $validator->$ruleName($field, $param);
                } else {
                    $validator->$rule($field);
                }
            }
        }
        
        return $validator;
    }
}