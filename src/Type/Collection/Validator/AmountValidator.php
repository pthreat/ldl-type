<?php declare(strict_types=1);

namespace LDL\Type\Collection\Validator;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Helper\ComparisonOperatorHelper;
use LDL\Validators\Traits\ValidatorValidateTrait;
use LDL\Validators\ValidatorHasConfigInterface;
use LDL\Validators\ValidatorInterface;

class AmountValidator implements ValidatorInterface, ValidatorHasConfigInterface
{
    use ValidatorValidateTrait;

    /**
     * @var int
     */
    private $amount;

    /**
     * @var string
     */
    private $operator;

    /**
     * @var string|null
     */
    private $description;

    public function __construct(int $value, string $operator, string $description=null)
    {
        if($value <= 0){
            $msg = 'Amount of items for validator "%s" must be a positive integer';
            throw new \InvalidArgumentException($msg);
        }

        ComparisonOperatorHelper::validate($operator);

        $this->amount = $value;
        $this->operator = $operator;
        $this->description = $description;
    }

    /**
     * @return int
     */
    public function getAmount(): int
    {
        return $this->amount;
    }

    /**
     * @return string
     */
    public function getOperator(): string
    {
        return $this->operator;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        if(!$this->description){
            return sprintf(
                'The amount of items in the collection can NOT be "%s" than "%s"',
                $this->operator,
                $this->amount
            );
        }

        return $this->description;
    }

    public function assertTrue($value, $key = null, CollectionInterface $collection = null): void
    {
        $compare = $this->compare($collection);

        if(!$compare){
            return;
        }

        $msg = sprintf(
            'The amount of items in the collection can NOT be "%s" than "%s"',
            $this->operator,
            $this->amount
        );

        throw new Exception\AmountValidatorException($msg);
    }

    public function jsonSerialize(): array
    {
        return $this->getConfig();
    }

    /**
     * @param array $data
     * @return ValidatorInterface
     * @throws Exception\InvalidConfigException
     */
    public static function fromConfig(array $data = []): ValidatorInterface
    {
        if(!array_key_exists('amount', $data)){
            $msg = sprintf("Missing property 'amount' in %s", __CLASS__);
            throw new Exception\InvalidConfigException($msg);
        }

        if(!array_key_exists('operator', $data)){
            $msg = sprintf("Missing property 'operator' in %s", __CLASS__);
            throw new Exception\InvalidConfigException($msg);
        }

        if(!is_string($data['operator'])){
            throw new \InvalidArgumentException(
                sprintf('operator must be of type string, "%s" was given',gettype($data['operator']))
            );
        }

        try{
            return new self(
                (int) $data['amount'],
                $data['operator'],
                $data['description'] ?? null
            );
        }catch(\Exception $e){
            throw new Exception\InvalidConfigException($e->getMessage());
        }
    }

    /**
     * @return array
     */
    public function getConfig(): array
    {
        return [
            'amount' => $this->amount,
            'operator' => $this->operator,
            'description' => $this->getDescription()
        ];
    }

    private function compare(CollectionInterface $collection) : bool
    {
        $total = count($collection);

        switch($this->operator){
            case ComparisonOperatorHelper::OPERATOR_SEQ:
                return $total === $this->amount;

            case ComparisonOperatorHelper::OPERATOR_EQ:
                return $total == $this->amount;

            case ComparisonOperatorHelper::OPERATOR_GT:
                return $total + 1 > $this->amount;

            case ComparisonOperatorHelper::OPERATOR_GTE:
                return $total + 1 >= $this->amount;

            case ComparisonOperatorHelper::OPERATOR_LT:
                $total = $total - 1 < 0 ? 0 : $total - 1;
                return $total < $this->amount;

            case ComparisonOperatorHelper::OPERATOR_LTE:
                $total = $total - 1 < 0 ? 0 : $total - 1;
                return $total <= $this->amount;

            default:
                throw new \RuntimeException("Given operator: '{$this->operator}' is invalid");
        }
    }
}
