<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Type\Collection\Interfaces\Validation\HasAppendValueValidatorChainInterface;
use LDL\Type\Collection\Traits\Validator\AppendValueValidatorChainTrait;
use LDL\Type\Collection\AbstractCollection;
use LDL\Type\Collection\Types\String\StringCollection;
use LDL\Validators\ClassComplianceValidator;
use LDL\Validators\StringValidator;
use LDL\Validators\Chain\OrValidatorChain;

$str = new StringCollection();

echo "Create String Collection\n";

echo "Append item with value: '123'\n";
$str->append('123');

echo "Append item with value: '456'\n";
$str->append('456');

echo "\nConvert String Collection to Array\n";

var_dump($str->toArray());

class StdStringCollectionExample extends AbstractCollection implements HasAppendValueValidatorChainInterface
{
    use AppendValueValidatorChainTrait;

    public function __construct(iterable $items = null)
    {
        parent::__construct($items);

        $this->getAppendValueValidatorChain(OrValidatorChain::class)
            ->getChainItems()
            ->append(new ClassComplianceValidator(\stdClass::class))
            ->append(new StringValidator());
    }
}

$obj = new \stdClass();
$obj->test = 'Hello World';

echo "Create std object with 'test' property and value 'Hello World'\n";

echo "Create StdStringCollection\n";

$collection = new StdStringCollectionExample();

echo "Append std object and 'string' string\n";

$collection->append($obj, 'stdObjectKey')
    ->append('string');

echo "Lock collection\n";

$collection->lock();

echo "Change 'test' value of the std object to 'Hello Earth'\n";

$collection->toArray()['stdObjectKey']->test='Hello Earth';

echo "Change string value\n";

$collection->toArray()[1] = 'something';

echo "Value of 'test' must be 'Hello World' because the collection is Locked\n";

var_dump($collection->get('stdObjectKey')->test);

echo "Value of string must be 'string' because the collection is Locked\n";
var_dump($collection->keys());
var_dump($collection->get(1));

echo "\nConvert StdCollection to Array\n";

var_dump($collection->toArray());