<?php

namespace Lucinda\MVC\Response;

final class Headers
{
    /**
     * @var array<string,string|array>
     */
    private array $headers = [];

    public function add(string $key, string|array $value): void
    {
        if (is_array($value) && (empty($value) || in_array("", $value))) {
            throw new Exception("Multi headers cannot be empty!");
        }
        $this->headers[$key] = $value;
    }

    public function send(): void
    {
        foreach ($this->headers as $name=>$value) {
            if (is_array($value)) {
                foreach ($value as $item) {
                    header($name.": ".$item);
                }
            } else {
                header($name.": ".$value);
            }
        }
    }
}

