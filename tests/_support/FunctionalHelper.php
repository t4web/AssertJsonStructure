<?php
namespace Codeception\Module;

// here you can define custom actions
// all public methods declared in helper class will be available in $I

class FunctionalHelper extends \Codeception\Module
{
    public function assertJsonStructure($expectedJson, $object)
    {
        $expectedJson = str_replace(array('<', '>'), '"', $expectedJson);

        $expectedStructure = json_decode($expectedJson, true);

        $this->parseStructure($expectedStructure, $object);
    }

    private function parseStructure($structure, $object)
    {
        foreach ($structure as $fieldName=>$type) {
            \PHPUnit_Framework_Assert::assertArrayHasKey($fieldName, $object);

            if (is_array($type)) {
                $this->parseStructure($type, $object[$fieldName]);
            } else {
                $this->assertType($fieldName, $type, $object[$fieldName]);
            }
        }
    }

    private function assertType($fieldName, $type, $value)
    {
        $ourTypes = array(
            "dateTime" => "assertDataTimeType",
        );

        $internalTypes = array(
            "boolean",
            "integer",
            "float",
            "double",
            "string",
            "array",
            "NULL",
            "null",
        );

        if ($this->isMultiType($type)) {
            $types = explode('|', $type);

            foreach ($types as $partType) {
                if (in_array($partType, $internalTypes)) {
                    if (strtolower(gettype($value)) == $partType) {
                        return true;
                    }
                } elseif (array_key_exists($partType, $ourTypes)) {
                    if ($this->{$ourTypes[$partType]}($fieldName, $value)) {
                        return true;
                    }
                } else {
                    throw new \PHPUnit_Framework_ExpectationFailedException("Unknown parameter type <$partType> for $fieldName (value=$value)");
                }
            }

            throw new \PHPUnit_Framework_ExpectationFailedException("Bad type <$type> for $fieldName (value=$value)");
        } else {
            if (in_array($type, $internalTypes)) {
                \PHPUnit_Framework_Assert::assertInternalType($type, $value, "Bad type <$type> for $fieldName (value=$value)");
            } elseif (array_key_exists($type, $ourTypes)) {
                if ($this->{$ourTypes[$type]}($fieldName, $value)) {
                    return true;
                }
            } else {
                throw new \PHPUnit_Framework_ExpectationFailedException("Unknown parameter type <$type> for $fieldName (value=$value)");
            }
        }
    }

    private function assertDataTimeType($fieldName, $value)
    {
        if (!strtotime($value)) {
            return false;
        }

        return false;
    }

    private function isMultiType($type)
    {
        return strpos($type, '|') !== false;
    }
}
