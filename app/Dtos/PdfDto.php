<?php

namespace App\Dtos;

class PdfDto
{
    public readonly string $link;
    public readonly string $title;
    public readonly string $description;
    public string|null $size;

    public function __construct(array $data)
    {
        $this->link = $data['link'];
        $this->title = $data['title'];
        $this->description = $data['description'];
        $this->size = $data['size'] ?? null;
    }
}
