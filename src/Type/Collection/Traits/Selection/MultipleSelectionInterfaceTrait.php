<?php declare(strict_types=1);

/**
 * This trait applies the MultipleSelectionInterface so you can just easily use it in your class.
 *
 * @see \LDL\Type\Collection\Interfaces\Selection\MultipleSelectionInterface
 */

namespace LDL\Type\Collection\Traits\Selection;

use LDL\Framework\Base\Collection\Contracts\CollectionInterface;
use LDL\Framework\Base\Collection\Contracts\SelectionLockingInterface;
use LDL\Framework\Base\Collection\Traits\CollectionInterfaceTrait;
use LDL\Framework\Base\Exception\LockingException;
use LDL\Type\Collection\Interfaces\Selection\MultipleSelectionInterface;
use LDL\Type\Collection\Types\String\StringCollection;

trait MultipleSelectionInterfaceTrait
{
    /**
     * @var StringCollection
     */
    private $_tMultiSelect;

    //<editor-fold desc="MultipleSelectionInterface methods">
    public function select($key, bool $keyCheck = true) : MultipleSelectionInterface
    {
        $this->_createSelectedValuesInstance();

        $keys = is_iterable($key) ? $key : [ $key ];

        $tag = true === $keyCheck ? MultipleSelectionInterface::SELECT_CLEAN : MultipleSelectionInterface::SELECT_DIRTY;

        /**
         * Check that all passed keys are indeed in the collection
         */
        if(true === $keyCheck){
            array_map(function($key) {$this->get($key);}, $keys);
        }

        $append = [];

        foreach($keys as $value){
            $append[$value] = $tag;
        }

        try {

            $this->_tMultiSelect->appendMany($append, true);

        }catch(LockingException $e){

            throw new LockingException(sprintf('Selection has been locked for class: %s', get_class($this)));

        }

        return $this;
    }

    public function selectAll() : MultipleSelectionInterface
    {
        $this->_createSelectedValuesInstance();
        return $this->select($this->keys());
    }

    public function filterBySelectedItems() : MultipleSelectionInterface
    {
        $this->_createSelectedValuesInstance();

        /**
         * @var MultipleSelectionInterface $collection
         */
        $collection = $this->getEmptyInstance();

        if(null === $this->_tMultiSelect){
            return $collection;
        }

        $selected = [];

        foreach($this as $key => $value){
            if($this->_tMultiSelect->hasValue($key)){
                $selected[$key] = $value;
            }
        }

        $collection->setItems($selected);

        return $collection;
    }

    public function getSelection(): StringCollection
    {
        $this->_createSelectedValuesInstance();

        return $this->_tMultiSelect;
    }

    public function removeSelection() : MultipleSelectionInterface
    {
        $this->_createSelectedValuesInstance();

        return $this->_tMultiSelect->getEmptyInstance();
    }

    public function truncateToSelected() : MultipleSelectionInterface
    {
        $this->_createSelectedValuesInstance();
        $this->setItems($this->filterByKeys($this->_tMultiSelect->keys()));

        return $this;
    }

    public function hasSelection() : bool
    {
        return $this->getSelectionCount() > 0;
    }

    public function getSelectionCount() : int
    {
        $this->_createSelectedValuesInstance();
        return $this->_tMultiSelect->count();
    }
    //</editor-fold>

    //<editor-fold desc="SelectionLockingInterface">
    public function lockSelection(): SelectionLockingInterface
    {
        $this->_createSelectedValuesInstance();
        $this->_tMultiSelect->lock();
        return $this;
    }

    public function isSelectionLocked() : bool
    {
        $this->_createSelectedValuesInstance();
        return $this->_tMultiSelect->isLocked();
    }
    //</editor-fold>

    //<editor-fold desc="Private methods">
    private function _createSelectedValuesInstance() : StringCollection
    {
        $this->requireImplements([CollectionInterface::class, MultipleSelectionInterface::class]);
        $this->requireTraits(CollectionInterfaceTrait::class);

        if(null !== $this->_tMultiSelect) {
            return $this->_tMultiSelect;
        }

        $this->_tMultiSelect = new StringCollection();

        $this->getBeforeRemove()->append(function($collection, $item, $key){
            try{
                $this->_tMultiSelect->removeByKey($key);
            }catch(LockingException $e){
                $this->_tMultiSelect = (new StringCollection())
                    ->appendMany(
                        array_filter(
                            \iterator_to_array($this->_tMultiSelect, true),
                            static function($v, $k) use($key){
                                return $k !== $key;
                            },
                            \ARRAY_FILTER_USE_BOTH
                        ),true
                    );
                $this->_tMultiSelect->lock();
            }
        });

        return $this->_tMultiSelect;
    }
    //</editor-fold>

}