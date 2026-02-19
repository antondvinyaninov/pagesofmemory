<?php

return [
    'required' => 'Поле :attribute обязательно для заполнения.',
    'string' => 'Поле :attribute должно содержать текст.',
    'max' => [
        'string' => 'Поле :attribute не может быть длиннее :max символов.',
        'file' => 'Размер файла :attribute не может превышать :max килобайт.',
    ],
    'date' => 'Поле :attribute должно быть корректной датой.',
    'after' => 'Поле :attribute должно быть датой после :date.',
    'email' => 'Поле :attribute должно быть корректным email адресом.',
    'unique' => 'Такое значение поля :attribute уже существует.',
    'confirmed' => 'Поле :attribute не совпадает с подтверждением.',
    'min' => [
        'string' => 'Поле :attribute должно содержать минимум :min символов.',
    ],
    'image' => 'Поле :attribute должно быть изображением.',
    'in' => 'Выбранное значение для :attribute некорректно.',
    
    'attributes' => [
        'first_name' => 'имя',
        'last_name' => 'фамилия',
        'middle_name' => 'отчество',
        'birth_date' => 'дата рождения',
        'death_date' => 'дата смерти',
        'birth_place' => 'место рождения',
        'photo' => 'фотография',
        'biography' => 'краткая биография',
        'religion' => 'религия',
        'privacy' => 'приватность',
        'creator_relationship' => 'ваше отношение к усопшему',
        'creator_relationship_custom' => 'уточнение отношения',
        'email' => 'email',
        'password' => 'пароль',
        'name' => 'имя',
    ],
];
