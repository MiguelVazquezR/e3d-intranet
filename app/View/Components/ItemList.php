<?php

namespace App\View\Components;

use Illuminate\View\Component;

class ItemList extends Component
{
    public $index,
        $object_id,
        $inactive_message,
        $can_undo,
        $edit,
        $delete,
        $edit_method_name,
        $delete_method_name,
        $undo_method_name,
        $active;

    public function __construct(
        $index,
        $objectId = null,
        $active,
        $canUndo = true,
        $edit = true,
        $delete = true,
        $editMethodName = 'editItem',
        $deleteMethodName = 'deleteItem',
        $undoMethodName = 'removeFromTemporaryDeletedList',
        $inactiveMessage = ''
        
    ) {
        $this->index = $index;
        $this->object_id = $objectId;
        $this->active = $active;
        $this->can_undo = $canUndo;
        $this->edit = $edit;
        $this->delete = $delete;
        $this->edit_method_name = $editMethodName;
        $this->delete_method_name = $deleteMethodName;
        $this->undo_method_name = $undoMethodName;
        $this->inactive_message = $inactiveMessage;
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|\Closure|string
     */
    public function render()
    {
        return view('components.item-list');
    }
}
