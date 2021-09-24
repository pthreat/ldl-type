<?php declare(strict_types=1);

namespace LDL\Type\Collection\Traits\Validator;

use LDL\Type\Collection\AbstractCollection;
use LDL\Type\Collection\Interfaces\Validation\HasAppendValueValidatorChainInterface;
use LDL\Type\Collection\Types\String\StringCollection;
use LDL\Validators\Chain\AndValidatorChain;
use LDL\Validators\Chain\ValidatorChainInterface;

trait AppendKeyValidatorChainTrait
{
    /**
     * @var StringCollection
     */
    private $_tAppendKeyCollection;

    //<editor-fold desc="HasAppendKeyValidatorChainInterface methods">
    public function getAppendKeyValidatorChain(string $class=null, iterable $validators=null): ValidatorChainInterface
    {
        if(null !== $this->_tAppendKeyCollection){
            return $this->_tAppendKeyCollection->getAppendValueValidatorChain();
        }

        if(null === $class){
            $class=AndValidatorChain::class;
        }

        if(!class_exists($class)){
            throw new \InvalidArgumentException("Invalid class \"$class\"");
        }

        if(!is_subclass_of($class, ValidatorChainInterface::class)){
            throw new \InvalidArgumentException(
                sprintf(
                    'Given class must be an instance of "%s"; "%s" was given',
                    ValidatorChainInterface::class,
                    $class
                )
            );
        }

        /**
         * Use an anonymous class here, a proper class is not needed
         */
        $this->_tAppendKeyCollection = new class($class, $validators) extends AbstractCollection implements HasAppendValueValidatorChainInterface
        {
            use AppendValueValidatorChainTrait;

            /**
             * @var ValidatorChainInterface
             */
            private $validatorChain;

            public function __construct(string $class, iterable $validators = null)
            {
                parent::__construct($validators);

                $this->validatorChain = new $class;

                $this->getBeforeResolveKey()->append(function($collection, $item, $key){
                    $this->validatorChain->validate($item, $key, $collection);
                });
            }

            public function getValidatorChain(): ValidatorChainInterface
            {
                return $this->validatorChain;
            }
        };

        $this->getBeforeResolveKey()->append(function($collection, $item, $key){
            $this->_tAppendKeyCollection->append($key);
        });

        return $this->_tAppendKeyCollection->getValidatorChain();
    }
    //</editor-fold>
}