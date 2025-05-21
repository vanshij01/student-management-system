@extends('backend.layouts.app')
@section('title', 'View Admission')
@section('styles')
    <style>
        h6 {
            margin: 10px;
        }

        label {
            margin-left: 8px;
        }

        .card-body hr {
            margin-top: 15px;
            border: 1px solid #D8D8DD
        }

        .border-right {
            border-right: 1px solid #D8D8DD;
        }

        /* .student_img{
                                    width: 50%;
                                    height: 50%;
                                } */

        .image-container {
            position: relative;
            width: 100%;
            height: auto;
            overflow: hidden;
        }

        .view_img {
            width: 100%;
            transition: transform 0.2s ease;
        }

        .magnified {
            transform: scale(2);
            /* Adjust this value to change the zoom level */
        }

        @media screen and (max-width: 426px) {
            .border-right {
                border-right: none;
            }
        }
    </style>
@endsection
@section('content')

    <!-- Send Admin Remark -->
    <div class="modal fade createStatusRemarkModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Send Remarks</h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form class="createStatusRemarkForm" action="{{ route('admission.sendStatusRemark') }}" method="post"
                    class="fees_form" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="student_id" class="student_id">
                    <input type="hidden" name="admission_id" class="admission_id">
                    <div class="modal-body pb-0">
                        <div class="row">
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control student_name" disabled />
                                    <label for="name">Student Name</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <input type="text" class="form-control student_email" disabled />
                                    <label for="name">Student Email</label>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <select name="admission_status" class="select2 form-select admission_status"
                                        data-placeholder="Select Admission Status"
                                        data-parsley-errors-container="#admission_status_errors" required>
                                        <option value="">Select Admission Status</option>
                                        <option @if (old('admission_status') == '0') selected @endif value="0" selected>
                                            Pending
                                        </option>
                                        <option @if (old('admission_status') == '1') selected @endif value="1">
                                            Confirm
                                        </option>
                                        <option @if (old('admission_status') == '2') selected @endif value="2">Reject
                                        </option>
                                        <option @if (old('admission_status') == '3') selected @endif value="3">Cancelled
                                        </option>
                                        <option @if (old('admission_status') == '4') selected @endif value="4">Release
                                        </option>
                                    </select>
                                    <label for="admission_status">Admission Status</label>
                                    <div id="admission_status_errors"></div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-lg-6 mb-4">
                                <div class="form-floating form-floating-outline">
                                    <textarea class="form-control admin_comment" name="admin_comment" cols="30" rows="10"
                                        placeholder="Enter Admin Comment" required>{{ old('admin_comment') }}</textarea>
                                    <label for="admin_comment">Admin Comment</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">
                            Close
                        </button>
                        <button type="submit" class="btn btn-primary">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="card mb-4">
        <div class="card-header d-flex align-items-center justify-content-between py-2">
            <h5 class="card-title m-0 me-2 text-secondary">View Admission</h5>
            <div>
                <a href="{{ route('admission.edit', $admission->admission_id) }}"
                    class="btn btn-primary waves-effect waves-light addButton">Update</a>
                <a href="javascript:void(0)" class="btn btn-primary waves-effect waves-light"
                    onclick="sendStatusRemark({{ $admission->admission_id }})">Admission Status</a>
                <a href="{{ route('admission.index') }}" class="btn btn-primary waves-effect waves-light">Back</a>
            </div>
        </div>
        <div class="card-body">
            <h4>Student Details</h4>
            <div class="d-flex gap-5">
                <div class="row">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Name</h6>
                        <label>{{ $admission->full_name }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Gender</h6>
                        <label>{{ $admission->gender }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>DOB</h6>
                        <label>{{ date('d/m/Y', strtotime($admission->dob)) }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3">
                        <h6>Age (in years)</h6>
                        <label>{{ $admission->age }}</label>
                    </div>
                    <hr class="d-md-none">

                    <hr>
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Contact No</h6>
                        <label>{{ $admission->phone }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Email Id</h6>
                        <label>{{ $admission->email }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Permanent Address</h6>
                        <label>{{ $admission->residence_address }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3">
                        <h6>Village</h6>
                        <label>{{ $admission->village_name }}</label>
                    </div>
                    <hr>
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Country</h6>
                        <label>{{ $admission->country_name }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Nationality</h6>
                        <label>{{ $admission->nationality }}</label>
                    </div>
                    <hr class="d-md-none">
                    @if ($admission->country == 4)
                        <div class="col-md-3 border-right mb-2 mb-md-0">
                            <h6>Aadhar No</h6>
                            <label>{{ $admission->adhaar_number }}</label>
                        </div>
                    @else
                        <div class="col-md-3 border-right mb-2 mb-md-0">
                            <h6>Passport No</h6>
                            <label>{{ $admission->passport_number }}</label>
                        </div>
                    @endif
                    <hr>
                </div>
                <div class="d-flex align-items-center">
                    <img src="{{ 'http://student.sklpsahmedabad.com/public/' . $admission->student_photo_url }}"
                        alt="" class="student_img" width="165px" height="200px">
                </div>
            </div>
        </div>
    </div>
    <div class="card mb-4">
        <div class="card-body">
            <h4>Parent Details</h4>
            <div class="d-flex gap-5">
                <div class="row">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Father Full Name</h6>
                        <label>{{ $admission->father_full_name }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Father Contact No</h6>
                        <label>{{ $admission->father_phone }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Father Occupation</h6>
                        <label>{{ $admission->father_occupation }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3">
                        <h6>Annual Income</h6>
                        <label>{{ $admission->annual_income }}</label>
                    </div>
                    <hr>
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Mother Full Name</h6>
                        <label>{{ $admission->mother_full_name }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Mother Contact No</h6>
                        <label>{{ $admission->mother_phone }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Mother Occupation</h6>
                        <label>{{ $admission->mother_occupation }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3">
                        <h6>Local Guardian Name</h6>
                        <label>{{ $admission->guardian_name ?? '-' }}</label>
                    </div>
                    <hr>
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Local Guardian Relation</h6>
                        <label>{{ $admission->guardian_relation ?? '-' }}</label>
                    </div>
                    <hr class="d-md-none">
                    <div class="col-md-3 border-right mb-2 mb-md-0">
                        <h6>Local Guardian Contact No</h6>
                        <label>{{ $admission->guardian_phone ?? '-' }}</label>
                    </div>
                    <hr>
                </div>
                <div class="d-flex align-items-center">
                    <img src="{{ 'http://student.sklpsahmedabad.com/public/' . $admission->parent_photo_url }}"
                        alt="" class="student_img" width="165px" height="200px">
                </div>
            </div>
        </div>
    </div>
    <div class="card">
        <div class="card-body">
            <h4>Education Details</h4>
            <div class="row">
                <div class="col-md-3 border-right mb-2 mb-md-0">
                    <h6>Course</h6>
                    <label>{{ $admission->course_name }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-3 border-right mb-2 mb-md-0">
                    <h6>Institute /College Name</h6>
                    <label>{{ $admission->institute_name }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-3 border-right mb-2 mb-md-0">
                    <h6>Year of Admission</h6>
                    <label>{{ $admission->year_of_addmission . '-' . $admission->year_of_addmission + 1 }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-3">
                    <h6>Collage Start Time</h6>
                    <label>{{ $admission->college_start_time ?? '-' }}</label>
                </div>
                <hr>
                <div class="col-md-3 border-right mb-2 mb-md-0">
                    <h6>Collage End Time</h6>
                    <label>{{ $admission->college_end_time ?? '-' }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-3 border-right mb-2 mb-md-0">
                    <h6>Collage Fees Receipt No</h6>
                    <label>{{ $admission->college_fees_receipt_no ?? '-' }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-3 border-right mb-2 mb-md-0">
                    <h6>Collage Fees Date</h6>
                    <label>{{ $admission->college_fees_receipt_date ? date('d/m/Y', strtotime($admission->college_fees_receipt_date)) : '-' }}</label>
                </div>
                <hr class="d-md-none">
                <div class="col-md-3 border-right mb-2 mb-md-0">
                    <h6>Admission Date</h6>
                    <label>{{ date('d/m/Y', strtotime($admission->addmission_date)) }}</label>
                </div>
                <hr>
                <div class="col-md-3 border-right mb-2 mb-md-0">
                    <h6>Arriving Date at hostel</h6>
                    <label>{{ date('d/m/Y', strtotime($admission->arriving_date)) }}</label>
                </div>
                <hr>
            </div>
            <div class="row">
                <div class="col-xl-12">
                    <div class="nav-align-top mb-4">
                        <ul class="nav nav-pills" role="tablist">
                            <li class="nav-item">
                                <button type="button" class="nav-link active" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-top-document" aria-controls="navs-pills-top-document"
                                    aria-selected="true">
                                    Documents
                                </button>
                            </li>
                            <li class="nav-item">
                                <button type="button" class="nav-link" role="tab" data-bs-toggle="tab"
                                    data-bs-target="#navs-pills-top-admin-comment"
                                    aria-controls="navs-pills-top-admin-comment" aria-selected="true">
                                    Admin Comments
                                </button>
                            </li>
                        </ul>
                        <hr>
                        <div class="tab-content pt-0">
                            <div class="tab-pane fade show active" id="navs-pills-top-document" role="tabpanel">
                                <div class="table-responsive text-nowrap">
                                    <table class="table" id="studentDocumentTable" style="overflow-x: auto;">
                                        <thead>
                                            <tr>
                                                <th>SR No.</th>
                                                <th>Type</th>
                                                <th>Image</th>
                                                <th>Percentile</th>
                                                <th>Status</th>
                                                <th>Description</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            @foreach ($documents as $key => $document)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $document->doc_type }}</td>
                                                    <td> <a class="img"><img
                                                                src="{{ 'http://student.sklpsahmedabad.com/public/' . $document->doc_url }}"
                                                                alt="{{ $document->doc_type }}" height="50"
                                                                width="50"></a></td>
                                                    {{-- <td>{{ $document->doc_url }}</td> --}}
                                                    <td>{{ $document->percentile }}</td>
                                                    <td>{{ $document->result_status }}</td>
                                                    <td>{{ $document->description }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($document->created_at)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                            <div class="tab-pane fade show" id="navs-pills-top-admin-comment" role="tabpanel">
                                <div class="table-responsive text-nowrap">
                                    <table class="table" id="commentTable" style="overflow-x: auto;">
                                        <thead>
                                            <tr>
                                                <th>SR No.</th>
                                                <th>Admin Comment</th>
                                                <th>Student Comment</th>
                                                <th>Commented By</th>
                                                <th>Date</th>
                                            </tr>
                                        </thead>
                                        <tbody class="table-border-bottom-0">
                                            @foreach ($comments as $key => $comment)
                                                <tr>
                                                    <td>{{ $key + 1 }}</td>
                                                    <td>{{ $comment->admin_comment }}</td>
                                                    <td>{{ $comment->student_comment }}</td>
                                                    <td>{{ $comment->user->name }}</td>
                                                    <td>{{ date('d/m/Y', strtotime($comment->created_at)) }}</td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Send Admin Remark -->
    <div class="modal fade viewDocumentModal" tabindex="-1" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title"></h4>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body text-center">
                    <div class="row">
                        <div class="col-12">
                            <div class="image-container">
                                <img src="" alt="" class="view_img">
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/admission/admission.js') }}"></script>
    {{-- <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script> --}}
    <script>
        $(document).ready(function() {
            $('#studentDocumentTable').DataTable();
            $('#commentTable').DataTable();

            $('.img').on('click', function() {
                $('.viewDocumentModal').modal('show');
                var src = $(this).find('img').prop('src');
                var alt = $(this).find('img').prop('alt');
                $('.view_img').attr('src', src);
                $('.view_img').attr('alt', alt);
                $('.modal-title').text(alt);

            });
            $('.image-container').on('mouseenter', function() {
                $(this).find('.view_img').addClass('magnified');
            });

            $('.image-container').on('mousemove', function(e) {
                let container = $(this);
                let image = container.find('.view_img');

                // Container dimensions
                let containerWidth = container.width();
                let containerHeight = container.height();

                // Mouse position within the container
                let offsetX = e.offsetX;
                let offsetY = e.offsetY;

                // Calculate percentage position within the container
                let xPercent = (offsetX / containerWidth) * 100;
                let yPercent = (offsetY / containerHeight) * 100;

                // Set transform-origin based on mouse position
                image.css('transform-origin', `${xPercent}% ${yPercent}%`);
            });

            $('.image-container').on('mouseleave', function() {
                $(this).find('.view_img').removeClass('magnified');
            });

            // let currentZoom = 1;
            // const minZoom = 1;
            // const maxZoom = 3;
            // const stepSize = 0.1;
            // let isDragging = false;
            // let startX, startY, initialX, initialY;

            // const container = $('.image-container');
            // const $image = container.find('img');

            // // Add an event listener for the 'wheel' event
            // container.on("wheel", function(event) {
            //     const direction = event.originalEvent.deltaY > 0 ? -1 : 1;
            //     zoomImage(direction);
            //     event.preventDefault();
            // });

            //  // Add mousedown, mousemove, and mouseup event listeners for dragging
            // container.on("mousedown", function(event) {
            //     isDragging = true;
            //     startX = event.pageX;
            //     startY = event.pageY;
            //     initialX = $image.position().left;
            //     initialY = $image.position().top;
            //     container.css('cursor', 'grabbing');
            // });

            // $(document).on("mousemove", function(event) {
            //     console.log(event.isDragging);

            //     if (isDragging) {
            //         const dx = event.pageX - startX;
            //         const dy = event.pageY - startY;
            //         $image.css({
            //             'left': initialX + dx,
            //             'top': initialY + dy
            //         });
            //     }
            // });

            // $(document).on("mouseup", function() {
            //     isDragging = false;
            //     container.css('cursor', 'grab');
            // });

            // function zoomImage(direction) {
            //     let newZoom = currentZoom + direction * stepSize;

            //     // Limit the zoom level to the minimum and maximum values
            //     if (newZoom < minZoom || newZoom > maxZoom) {
            //         return;
            //     }

            //     currentZoom = newZoom;

            //     // Update the CSS transform of the image to scale it
            //     let $image = container.find('img');
            //     $image.css('transform', 'scale(' + currentZoom + ')');

            //     // Optionally adjust container size (optional)
            //     /* container.css({
            //         width: $image[0].naturalWidth * currentZoom,
            //         height: $image[0].naturalHeight * currentZoom
            //     }); */
            // }

        });
    </script>
@endsection
