<?php declare(strict_types=1);

namespace LDL\Type\Collection\Types\String;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Helper\ComparisonOperatorHelper;
use LDL\Type\Collection\AbstractCollection;
use LDL\Type\Collection\Interfaces\Validation\HasAppendValueValidatorChainInterface;
use LDL\Type\Collection\Traits\Validator\AppendValueValidatorChainTrait;
use LDL\Validators\StringValidator;

class StringCollection extends AbstractCollection implements HasAppendValueValidatorChainInterface
{
    use AppendValueValidatorChainTrait;

    /**
     * @var ?string
     */
    private $imploded;

    public function __construct(iterable $items = null)
    {
        parent::__construct($items);

        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new StringValidator())
            ->lock();
    }

    public function implode(string $separator=',', bool $considerToStringObjects=true) : string
    {
        if(null !== $this->imploded){
            return $this->imploded;
        }

        $this->imploded = implode($separator, \iterator_to_array($this));
        return $this->imploded;
    }

    public function append($item, $key = null) : CollectionInterface
    {
        $this->imploded = null;
        return parent::append($item, $key);
    }

    public function removeByKey(
        $key,
        string $operator = ComparisonOperatorHelper::OPERATOR_SEQ,
        string $order = ComparisonOperatorHelper::COMPARE_LTR
    ) : int
    {
        $this->imploded = null;
        return parent::removeByKey($key, $operator, $order);
    }

    public function removeByValue(
        $value,
        string $operator = ComparisonOperatorHelper::OPERATOR_SEQ,
        string $order = ComparisonOperatorHelper::COMPARE_LTR
    ) : int
    {
        $this->imploded = null;
        return parent::removeByValue($value, $operator, $order);
    }

    public function removeLast(): CollectionInterface
    {
        $this->imploded = null;
        return parent::removeLast();
    }

    public function toUnique(): UniqueStringCollection
    {
        return new UniqueStringCollection(array_map(
            static function($item) {
                return (string) $item;
            },
            array_keys(array_flip(\iterator_to_array($this)))
        ));
    }
}