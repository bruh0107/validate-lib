<?php

namespace Validator;

class Validator
{
    //Разрешенные валидаторы
    private array $validators = [];
    //Итоговые ошибки
    private array $errors = [];
    //Проверяемые поля
    private array $fields = [];
    //Массив правил
    private array $rules = [];
    //Кастомные сообщения
    private array $messages = [];

    public function __construct(array $fields, array $rules, array $messages = [])
    {
        $this->validators = [
            'required' => \Validator\Validators\RequireValidator::class,
            'unique' => \Validator\Validators\UniqueValidator::class,
            'min' => \Validator\Validators\MinLengthValidator::class,
            'max' => \Validator\Validators\MaxLengthValidator::class,
            'max_today' => \Validator\Validators\MaxTodayValidator::class,
        ];
        $this->fields = $fields;
        $this->rules = $rules;
        $this->messages = $messages;
        $this->validate();
    }

    //Перебираем список всех валидируемых полей и для
    //каждого поля вызываем метод validateField()
    private function validate(): void
    {
        foreach ($this->rules as $fieldName => $fieldValidators) {
            $this->validateField($fieldName, $fieldValidators);
        }
    }

    //Валидация отдельного поля
    private function validateField(string $fieldName, array $fieldValidators): void
    {
        $value = isset($this->fields[$fieldName]) ? $this->fields[$fieldName] : null;

        foreach ($fieldValidators as $validatorName) {
            //Отделяем от имени валидатора дополнительные аргументы
            $tmp = explode(':', $validatorName);
            [$validatorName, $args] = count($tmp) > 1 ? $tmp : [$validatorName, null];
            $args = isset($args) ? explode(',', $args) : [];

            //Соотносим имя валидатора с классом в массиве разрешенных валидаторов
            $validatorClass = $this->validators[$validatorName];
            if (!class_exists($validatorClass)) {
                continue;
            }
            //Создаем объект валидатора, передаем туда параметры
            $validator = new $validatorClass(
                $fieldName,
                $value,
                $args,
                $this->messages[$validatorName] ?? null
            );

            //Если валидация не прошла, то добавляем ошибку в общий массив ошибок
            if (!$validator->rule()) {
                $this->errors[$fieldName][] = $validator->validate();
            }
        }
    }

    //Возврат массива найденных ошибок
    public function errors(): array
    {
        return $this->errors;
    }

    //Признак успешной валидации
    public function fails(): bool
    {
        return (bool)count($this->errors);
    }
}