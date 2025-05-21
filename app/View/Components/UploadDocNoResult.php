<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UploadDocNoResult extends Component
{
    public $label;
    public $docType;
    public $admissionDocuments;
    public $admissionDetail;
    public $formType;

    public function __construct($label, $docType, $admissionDocuments, $admissionDetail, $formType)
    {
        $this->label = $label;
        $this->docType = $docType;
        $this->admissionDocuments = $admissionDocuments;
        $this->admissionDetail = $admissionDetail;
        $this->formType = $formType;
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.upload-doc-no-result');
    }
}
