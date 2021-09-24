<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Framework\Base\Collection\Contracts\PrioritySortingInterface;
use LDL\Framework\Base\Collection\Traits\PrioritySortingInterfaceTrait;
use LDL\Framework\Base\Contracts\PriorityInterface;
use LDL\Type\Collection\Types\Object\ObjectCollection;
use LDL\Framework\Base\Contracts\NamespaceInterface;
use LDL\Framework\Base\Exception\LockingException;
use LDL\Validators\InterfaceComplianceValidator;
use LDL\Framework\Base\Contracts\NameableInterface;
use LDL\Framework\Base\Collection\Contracts\FilterByNameInterface;
use LDL\Framework\Base\Collection\Traits\FilterByNameInterfaceTrait;

class NSPriority1 implements PriorityInterface, NameableInterface, NamespaceInterface
{
    public function getPriority(): int
    {
        return 1;
    }

    public function getNamespace(): string
    {
        return 'NS 1';
    }

    public function getName() : string
    {
        return 'Name 1';
    }
}

class NSPriority2 implements PriorityInterface, NameableInterface, NamespaceInterface
{
    public function getPriority(): int
    {
        return 2;
    }

    public function getNamespace(): string
    {
        return 'NS 2';
    }

    public function getName() : string
    {
        return 'Name 2';
    }
}

class NSPriorityCollectionExample extends ObjectCollection implements FilterByNameInterface, PrioritySortingInterface
{
    use FilterByNameInterfaceTrait;
    use PrioritySortingInterfaceTrait;

    public function __construct(iterable $items = null)
    {
        parent::__construct($items);

        $this->getAppendValueValidatorChain()
            ->getChainItems()
            ->append(new InterfaceComplianceValidator(NamespaceInterface::class))
            ->append(new InterfaceComplianceValidator(PriorityInterface::class))
            ->lock();
    }

    public function getPriority(): int
    {
        return 2;
    }
}

echo "Create new combined priority and namespace class instance\n";
$collection = new NSPriorityCollectionExample();

echo "Append NSPriority2\n";
$collection->append(new NSPriority2(),'ns_2');

echo "Append NSPriority1\n";
$collection->append(new NSPriority1(),'ns_1');

echo "Try to modify the validation chain (exception must be thrown)\n";

try{

    $collection->getAppendValueValidatorChain()
        ->getChainItems()
        ->append(new InterfaceComplianceValidator(NamespaceInterface::class));

}catch(LockingException $e){

    echo "EXCEPTION: {$e->getMessage()}\n";

}

try {

    echo "Append \stdClass (Must throw exception!)\n";
    $collection->append(new \stdClass());

}catch(\Exception $e){

    echo "EXCEPTION: {$e->getMessage()}\n";

}

echo "Sort by priority ascending:\n";

/**
 * @var PriorityInterface $item
 */
foreach($collection->sortByPriority(PrioritySortingInterface::SORT_ASCENDING) as $item){
    echo $item->getPriority()."\n";
}

echo "\nSort by priority descending:\n";

/**
 * @var PriorityInterface $item
 */
foreach($collection->sortByPriority(PrioritySortingInterface::SORT_DESCENDING) as $item){
    echo $item->getPriority()."\n";
}

echo "Remove item from collection with key: ns_1\n";

$collection->removeByKey('ns_1');

echo "Lock collection\n";
$collection->lock();

echo "Try to remove item with key ns_2 (collection is now locked), exception should be thrown\n";

try{

    $collection->removeByKey('ns_2');

}catch(LockingException $e){

    echo "EXCEPTION: {$e->getMessage()}\n";

}

echo "\nFilter by regex: #Name 2#\n";

/**
 * @var NamespaceInterface $item
 */
foreach($collection->filterByNameRegex('#Name 2#') as $item){
    echo $item->getNamespace(). ' '. $item->getName()."\n";
}
