<?php declare(strict_types=1);

namespace LDL\Type\Collection\Exception;

use LDL\Type\Collection\Types\Object\ObjectCollection;
use LDL\Validators\InterfaceComplianceValidator;

class ExceptionCollection extends ObjectCollection implements \JsonSerializable
{
    public function __construct(iterable $items = null)
    {
        parent::__construct($items);

        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new InterfaceComplianceValidator(\Throwable::class))
            ->lock();
    }

    public function toArray() : array
    {
        $result = [];

        foreach($this as $e){
            $result[] = [
                'class' => get_class($e),
                'message' => $e->getMessage(),
                'code' => $e->getCode()
            ];
        }

        return $result;
    }

    public function jsonSerialize()
    {
        return $this->toArray();
    }

}