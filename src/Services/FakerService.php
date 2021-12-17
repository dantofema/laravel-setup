<?php

namespace Dantofema\LaravelSetup\Services;

class FakerService
{
    public function toTest ($field): string
    {
        $string = $this->get($field);
        $string = str_replace('first()', 'first()->id', $string);
        $string = str_replace('create()', 'create()->id', $string);

        return str_replace('$this->faker', 'faker()', $string);
    }

    public function get (array $field): string
    {
        if (array_key_exists('relationship', $field))
        {
            return $this->getForeignKeys($field);
        }

        return $this->faker($field);
    }

    private function getForeignKeys (array $field): string
    {
        return $field['relationship']['model'] . "::inRandomOrder()->first() ?? "
            . $field['relationship']['model'] . "::factory()->create()";
    }

    private function faker (array $field): string
    {
        $preFaker = '$this->faker->';
        $preFaker .= in_array('unique', $field) ? 'unique()->' : null;

        return $this->match($field, $preFaker);
    }

    private function match (array $field, string $preFaker): mixed
    {
        return match ($field['name'])
        {
            'name' => $preFaker . 'name()',
            'last_name' => $preFaker . 'lastName()',
            'slug' => "Str::slug($" . $field['source'] . ")",
            'email' => $preFaker . 'safeEmail()',
            'email_verified_at' => $preFaker . 'now()',
            'password' => $preFaker . 'bcrypt("password")',
            'remember_token' => $preFaker . 'Str::random(10)',
            'link' => $preFaker . 'url',
            'image' => $preFaker . 'word().".jpg"',
            'phone' => $preFaker . 'isbn10',
            'title', 'subtitle', 'body', 'lead', 'epigraph' => $preFaker . 'sentence()',
            'publication_time' => $preFaker . 'dateTimeBetween("-90 days", "+7 days", null)',
            'facebook' => $preFaker . 'url()',
            'birthday' => $preFaker . 'date()',
            'date_from' => $preFaker . 'dateTimeBetween("now", "now", null)',
            'date_to' => $preFaker . 'dateTimeInInterval("now", "+5 days", null)',
            default => $this->matchType($field, $preFaker)
        };
    }

    private function matchType (array $field, string $preFaker): string
    {
        return match ($field['type'])
        {
            'string' => $preFaker . 'sentence()',
            'text' => $preFaker . 'text()',
            'boolean' => $preFaker . 'boolean()',
            'integer' => $preFaker . 'randomNumber()',
            'float' => $preFaker . 'randomFloat()',
            'date' => $preFaker . 'date()',
            'dateTime' => $preFaker . 'dateTime()',
            'time' => $preFaker . 'time()',
            default => ''
        };
    }
}
