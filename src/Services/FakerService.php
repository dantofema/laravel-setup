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
        if (array_key_exists('relationships', $field))
        {
            return $this->getForeignKeys($field);
        }
        return $this->faker($field);
    }

    private function getForeignKeys (array $field): string
    {
        return $field['relationships']['model'] . "::inRandomOrder()->first() ?? "
            . $field['relationships']['model'] . "::factory()->create()";
    }

    private function faker (array $field): string
    {
        $preFaker = '$this->faker->';
        $preFaker .= in_array('unique', $field) ? 'unique()->' : null;

        return match ($field['name'])
        {
            'name' => $preFaker . 'name()',
            'last_name' => $preFaker . 'lastName()',
            'slug' => "Str::slug('" . $field['source'] . "')",
            'email' => $preFaker . 'safeEmail()',
            'email_verified_at' => $preFaker . 'now()',
            'password' => $preFaker . 'bcrypt("password")',
            'remember_token' => $preFaker . 'Str::random(10)',
            'link' => $preFaker . 'url',
            'image' => $preFaker . 'word().".jpg"',
            'phone' => $preFaker . 'isbn10',
            'title', 'subtitle', 'body', 'lead', 'epigraph' => $preFaker . 'sentence()',
            'publication_time' => $preFaker . 'dateTimeBetween(" - 90 days", " + 7 days", null)->format("d - m - Y H:i:s")',
            'facebook' => $preFaker . 'url()',
            'birthday' => $preFaker . 'date()',
            'date_from' => $preFaker . 'dateTimeBetween("now", "now", null)->format("d - m - Y H:i:s")',
            'date_to' => $preFaker . 'dateTimeInInterval("now", " + 5 days", null)->format("d - m - Y H:i:s")',
            default => 'word()'
        };
    }

}