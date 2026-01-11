<?php

// Define Class Language Selector

class CANG_LanguageSelect
{
    protected array $definitions;

    public function __construct(array $definitions)
    {
        $this->definitions = $definitions;
    }

    // ---------------------------------------------------------
    // Selectors
    // ---------------------------------------------------------

    public function byId(int $id): ?CANG_LanguageProfile
    {
        return $this->definitions[$id] ?? null;
    }

    public function byName(string $name): ?CANG_LanguageProfile
    {
        foreach ($this->definitions as $definition) {
            if ($definition->getName() === $name) {
                return $definition;
            }
        }
        return null;
    }

    // ---------------------------------------------------------
    // Output Formatter
    // ---------------------------------------------------------

    public function output(CANG_LanguageProfile $definition): array
    {
        return [
            'id'          => $definition->getId(),
            'name'        => $definition->getName(),
            'type'        => $definition->getType(),
            'description' => $definition->getDescription(),
            'range'       => $definition->getRange(),
            'flattened'   => $definition->getFlattenedRange(),
        ];
    }
}
?>