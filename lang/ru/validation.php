<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'string' => 'Поле :attribute должно быть строкой.',
    'max' => [
        'string' => 'Поле :attribute не может быть длиннее :max символов.',
    ],
    'date' => 'Поле :attribute должно быть корректной датой.',
    'email' => 'Поле :attribute должно быть корректным email адресом.',
    'unique' => 'Такое значение поля :attribute уже существует.',
    'confirmed' => 'Поле :attribute не совпадает с подтверждением.',
    'min' => [
        'string' => 'Поле :attribute должно содержать минимум :min символов.',
    ],
    
    'attributes' => [
        'first_name' => 'имя',
        'last_name' => 'фамилия',
        'middle_name' => 'отчество',
        'birth_date' => 'дата рождения',
        'death_date' => 'дата смерти',
        'email' => 'email',
        'password' => 'пароль',
        'name' => 'имя',
    ],
];
