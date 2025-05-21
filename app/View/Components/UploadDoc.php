<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class UploadDoc extends Component
{
    public $label;
    public $docType;
    public $percentageFieldName; // Optional
    public $admissionDocuments;
    public $admissionDetail;
    public $formType;

    public function __construct(
        $label,
        $docType,
        $admissionDocuments,
        $admissionDetail,
        $formType = null,
        $percentageFieldName = null // ✅ Optional parameter at the end
    ) {
        $this->label = $label;
        $this->docType = $docType;
        $this->percentageFieldName = $percentageFieldName;
        $this->admissionDocuments = $admissionDocuments;
        $this->admissionDetail = $admissionDetail;
        $this->formType = $formType;
    }

    public function render(): View|Closure|string
    {
        return view('components.upload-doc');
    }
}

