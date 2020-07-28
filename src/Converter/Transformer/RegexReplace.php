<?php

declare(strict_types=1);

namespace Smile\GdprDump\Converter\Transformer;

use RuntimeException;
use Smile\GdprDump\Converter\ConverterInterface;
use Smile\GdprDump\Converter\Parameters\Parameter;
use Smile\GdprDump\Converter\Parameters\ParameterProcessor;
use Smile\GdprDump\Converter\Parameters\ValidationException;

class RegexReplace implements ConverterInterface
{
    /**
     * @var string
     */
    private string $pattern;

    /**
     * @var string
     */
    private string $replacement;

    /**
     * @var int
     */
    private int $limit;

    /**
     * @param array $parameters
     * @throws ValidationException
     */
    public function __construct(array $parameters)
    {
        $input = (new ParameterProcessor())
            ->addParameter('pattern', Parameter::TYPE_STRING, true)
            ->addParameter('replacement', Parameter::TYPE_STRING, false, '')
            ->addParameter('limit', Parameter::TYPE_INT, true, -1)
            ->process($parameters);

        $this->pattern = $input->get('pattern');
        $this->replacement = $input->get('replacement');
        $this->limit = $input->get('limit');
    }

    /**
     * @inheritdoc
     */
    public function convert($value, array $context = [])
    {
        $value = (string) $value;

        if ($value !== '') {
            $value = preg_replace($this->pattern, $this->replacement, (string) $value, $this->limit);

            if ($value === null) {
                throw new RuntimeException(
                    sprintf('Failed to perform a regex search and replace with the pattern "%s".', $this->pattern)
                );
            }
        }

        return $value;
    }
}
