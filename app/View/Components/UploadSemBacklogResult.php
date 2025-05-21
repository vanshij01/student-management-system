<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UploadSemBacklogResult extends Component
{
    public $label;
    public $admissionDetail;
    public $admissionDocuments;
    public $formType;

    /**
     * Create a new component instance.
     */
    public function __construct($label, $admissionDetail, $admissionDocuments, $formType)
    {
        $this->label = $label;
        $this->admissionDetail = $admissionDetail;
        $this->admissionDocuments = $admissionDocuments;
        $this->formType = $formType;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.upload-sem-backlog-result');
    }
}
