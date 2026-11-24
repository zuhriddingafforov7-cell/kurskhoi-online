<?php

declare(strict_types=1);

namespace Src\Core;

/**
 * Санҷиши серверии маълумоти шаклҳо бо дастгирии пурраи матни UTF-8 (кириллӣ).
 */
final class Validator
{
    /** @var array<string, list<string>> */
    private array $errors = [];

    public function required(string $field, mixed $value, string $message): self
    {
        $str = is_scalar($value) ? trim((string) $value) : '';
        if ($str === '') {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function minLength(string $field, mixed $value, int $min, string $message): self
    {
        $str = is_scalar($value) ? trim((string) $value) : '';
        if ($str !== '' && mb_strlen($str, 'UTF-8') < $min) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function maxLength(string $field, mixed $value, int $max, string $message): self
    {
        $str = is_scalar($value) ? trim((string) $value) : '';
        if (mb_strlen($str, 'UTF-8') > $max) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function positiveInt(string $field, mixed $value, string $message): self
    {
        $str = is_scalar($value) ? trim((string) $value) : '';
        if ($str === '') {
            return $this;
        }

        if (filter_var($str, FILTER_VALIDATE_INT, ['options' => ['min_range' => 1]]) === false) {
            $this->addError($field, $message);
        }

        return $this;
    }

    /**
     * @param  list<string>  $allowed
     */
    public function in(string $field, mixed $value, array $allowed, string $message): self
    {
        $str = is_scalar($value) ? trim((string) $value) : '';
        if ($str !== '' && !in_array($str, $allowed, true)) {
            $this->addError($field, $message);
        }

        return $this;
    }

    public function addError(string $field, string $message): self
    {
        $this->errors[$field] ??= [];
        if (!in_array($message, $this->errors[$field], true)) {
            $this->errors[$field][] = $message;
        }

        return $this;
    }

    public function fails(): bool
    {
        return $this->errors !== [];
    }

    public function passes(): bool
    {
        return !$this->fails();
    }

    /**
     * @return array<string, list<string>>
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * Аввалин паёми хатогӣ барои ҳар майдон.
     *
     * @return array<string, string>
     */
    public function firstErrors(): array
    {
        $out = [];
        foreach ($this->errors as $field => $messages) {
            if ($messages !== []) {
                $out[$field] = $messages[0];
            }
        }

        return $out;
    }
}
