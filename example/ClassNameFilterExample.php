<?php declare(strict_types=1);

require __DIR__.'/../vendor/autoload.php';

use LDL\Type\Collection\Types\Object\ObjectCollection;

class ClassNameFilteringExample extends ObjectCollection
{

}

class ClassNameFilterTest1 {
    public function methodToDelete(): void
    {

    }
}

class ClassNameFilterTest2 {

}

class ClassNameFilterTest3 {

}

echo "Create collection instance\n";

$collection = new ClassNameFilteringExample();

$collection->append(new ClassNameFilterTest1);
$collection->append(new ClassNameFilterTest2);
$collection->append(new ClassNameFilterTest3);

echo "Iterate through the collection\n\n";

foreach($collection as $item){
    echo get_class($item)."\n";
}

echo "\nFilter collection by class Test2, ONLY ClassNameFilterTest2 must show up\n\n";

$c = $collection->filterByClasses([ClassNameFilterTest2::class]);

/*var_dump(count($c));

var_dump(count($collection->filterByClass(ClassNameFilterTest2::class) ));
die();*/

foreach($collection->filterByClass(ClassNameFilterTest2::class) as $key => $item){
    echo get_class($item)."\n";
}

echo "Filter collection by classes Test1, Test3\n";

/**
 * @var ObjectCollection $filtered
 */
$filtered = $collection->filterByClasses([ClassNameFilterTest1::class, ClassNameFilterTest3::class]);

foreach($filtered as $key => $item){
    echo "$key => ".\get_class($item)."\n";
}

echo "Check filtered collection\n";

foreach($filtered as $key => $item){
    echo "$key => ".\get_class($item)."\n";
}

echo "Try to filter by class again with class Test3, class Test3 *must show up*\n";

foreach($filtered->filterByClass(ClassNameFilterTest3::class) as $key => $item){
    echo "$key => ".\get_class($item)."\n";
}