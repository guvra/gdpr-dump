<?php

declare(strict_types=1);

namespace Smile\GdprDump\Config\Validator;

use JsonSchema\Validator;
use Smile\GdprDump\Config\ConfigInterface;
use Throwable;

final class JsonSchemaValidator implements ValidatorInterface
{
    private ?Validator $schemaValidator = null;

    public function __construct(private string $schemaFile)
    {
        // Prefix the file name by the schema
        if (!str_contains($schemaFile, 'phar://')) {
            $schemaFile = 'file://' . $schemaFile;
        }

        $this->schemaFile = $schemaFile;
    }

    public function validate(ConfigInterface $config): ValidationResultInterface
    {
        $validator = $this->getValidator();

        // Validate the data against the schema file
        try {
            $root = $config->getRoot();
            $validator->validate($root, (object) ['$ref' => $this->schemaFile]);
        } catch (Throwable $e) {
            throw new ValidationException($e->getMessage(), $e);
        }

        // Build the messages array
        $messages = [];
        foreach ($validator->getErrors() as $error) {
            $messages[] = $error['property'] !== ''
                ? sprintf('[%s] %s', $error['property'], $error['message'])
                : $error['message'];
        }

        // Create the validation results object
        $result = new ValidationResult();
        $result->setValid($validator->isValid());
        $result->setMessages($messages);

        return $result;
    }

    /**
     * Get the JSON schema validator.
     */
    private function getValidator(): Validator
    {
        $this->schemaValidator ??= new Validator();

        return $this->schemaValidator;
    }
}
