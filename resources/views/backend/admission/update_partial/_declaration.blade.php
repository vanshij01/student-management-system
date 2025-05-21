<div id="declaration" class="content form-section">
    @include('frontend.admission._declaration')
    <div class="row mb-3">
        <div class="col declaration_textbox ">
            <label for="additional_notes" class="checkbox_field_label">Additional Notes</label>
            <textarea class="form-control" id="additional_notes" name="note"></textarea>
        </div>
    </div>
    <div class="row g-4">
        <div class="d-flex step-btn-wrapper justify-content-between">
            <button type="button" class="btn btn-prev">Previous</button>
            <button type="button" class="btn btn-reset"><i class="las la-redo-alt"></i> Reset
                Form</button>
            <button type="submit" class="btn btn-next">Next</button>
        </div>
    </div>
</div>
