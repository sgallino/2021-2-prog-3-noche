<?php
namespace App\Validation;

class Validator
{
    /**
     * @var array - La data a validar.
     */
    protected $data = [];

    /**
     * @var array - Las reglas de validación.
     */
    protected $rules = [];

    /**
     * @var array - Los errores de validación.
     */
    protected $errors = [];

    /**
     * @param array $data
     * @param array $rules
     */
    public function __construct(array $data, array $rules)
    {
        $this->data = $data;
        $this->rules = $rules;

        $this->run();
    }

    /**
     * Aplica las reglas de validación contra los datos.
     */
    public function run(): void
    {
        // Recorremos las reglas de validación para aplicarlas a los datos.
        // Cada item de "rules" tiene el formato:
        // 'clave-del-dato' => ['rule1', 'rule2', ....]
        foreach($this->rules as $dataKey => $ruleList) {
            $this->applyRules($dataKey, $ruleList);
        }
    }

    /**
     * Aplica la lista de reglas de validación al dato asociado a la $dataKey.
     *
     * @param string $dataKey - La clave del valor en $data que hay que validar. Por ejemplo: 'nombre'
     * @param array $ruleList - La lista de reglas. Por ejemplo: ['required', 'min:5']
     * @throws \Exception
     */
    public function applyRules(string $dataKey, array $ruleList)
    {
        // Recorremos la lista de reglas, para poder aplicarlas una por una al valor.
        foreach($ruleList as $rule) {
            // Las reglas pueden venir en 2 sabores:
            // Sabor 1:
            //  Ej: $rule = "required".
            // Sabor 2:
            //  Ej: $rule = "min:3";

            // Las reglas, como podemos ver más abajo, coinciden en su nombre con los métodos que las
            // aplican, solo que estos métodos tienen como prefijo un "_".
            // Vamos a ejecutar dinámicamente las reglas buscando el nombre de su método si existe, y
            // aplicarlo.
            // En el caso del "Sabor 1", esto es bien directo, simplemente tenemos que agregar el "_"
            // delante y ya está.
            // En el caso del "Sabor 2", tenemos que separar primero lo que es el nombre (lo que está
            // antes del ":") del parámetro extra (lo que está después).
            // Verificamos en qué caso estamos.
            if(strpos($rule, ':') === false) {
                $methodName = "_" . $rule; // Ej: "_required"

                // Preguntamos si existe esta regla de validación. Es decir, si existe un método que
                // tenga el valor de $methodName.
                if(!method_exists($this, $methodName)) {
                    // Lanzamos una Exception.
                    throw new \Exception("No existe la regla de validación '" . $rule . "'.");
                }

                // Aplicamos la regla.
                // En php podemos usar los valores de variables como reemplazo de nombres de variables,
                // funciones, métodos, clases, etc.
                // Por ejemplo, si tenemos la variable:
                //  $methodName = "_required";
                // Y ejecutamos:
                //  $this->{$methodName}($dataKey);
                // php lo interpreta como:
                //  $this->_required($dataKey);
                $this->{$methodName}($dataKey);
            } else {
                // Separamos el nombre de la regla y su parámetro.
//                $parts = explode(':', $rule);
//                $ruleName = $parts[0];
//                $ruleParameter = $parts[1];
                // Esto es equivalente a lo anterior.
                [$ruleName, $ruleParameter] = explode(':', $rule);

                $methodName = "_" . $ruleName;

                if(!method_exists($this, $methodName)) {
                    throw new \Exception("No existe la regla de validación '" . $rule . "'.");
                }

                $this->{$methodName}($dataKey, $ruleParameter);
            }
        }
    }

    /**
     * @return bool
     */
    public function fails(): bool
    {
        return count($this->errors) > 0;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    /**
     * Agrega un mensaje de error.
     *
     * @param string $key
     * @param string $message
     */
    public function addError(string $key, string $message): void
    {
        // Primero nos aseguramos de que exista un array definido de errores para esta key, y sino,
        // lo creamos.
        if(!isset($this->errors[$key])) {
            $this->errors[$key] = [];
        }

        $this->errors[$key][] = $message;
    }


    /*
     |--------------------------------------------------------------------------
     | Rules
     |--------------------------------------------------------------------------
     | En esta versión de Validator, vamos a hacer que cada regla de validación
     | se corresponda a un método protected de la clase.
     | Los métodos que van a servir como reglas van a llevar todos un prefijo "_".
     | Esto va a permitir diferenciarlos de cualquier otro método que no sea una
     | regla.
     |
     | Todas las reglas deben recibir como parámetro la key del campo a validar.
     | Además, algunas reglas pueden llegar a requerir datos extras, como es el
     | caso de "min", que puede ser "min:3", "min:100", etc.
     | Esas reglas recibirán un segundo parámetro que pida el valor.
     */

    /**
     * Valida que el valor del dato de $key no sea vacío.
     *
     * @param string $key
     */
    public function _required(string $key)
    {
        $value = $this->data[$key];
        if(empty($value)) {
            $this->addError($key, "Tenés que escribir un valor para " . $key);
        }
    }

    /**
     * Valida que el valor del dato de $key tenga al menos $count caracteres.
     *
     * @param string $key
     * @param int $count
     */
    public function _min(string $key, int $count)
    {
        $value = $this->data[$key];
        if(strlen($value) < $count) {
            $this->addError($key, "El valor de " . $key . " debe tener al menos " . $count . " caracteres");
        }
    }

    /**
     * Valida que el valor del dato de $key sea numérico.
     *
     * @param string $key
     */
    public function _numeric(string $key)
    {
        $value = $this->data[$key];
        if(!is_numeric($value)) {
            $this->addError($key, "El valor de " . $key . " debe ser un número");
        }
    }
}
