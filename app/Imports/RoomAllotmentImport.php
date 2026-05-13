<?php

namespace App\Imports;

use App\Mail\RoomAllotmentMail;
use App\Models\ActivityLog;
use App\Models\StudentAdmissionMap;
use App\Models\Hostel;
use App\Models\Bed;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class RoomAllotmentImport implements ToCollection, WithHeadingRow
{
    public $errors = [];

    public function __construct()
    {
        \Maatwebsite\Excel\Imports\HeadingRowFormatter::default('none');
    }

    public function collection(Collection $rows)
    {
        $rowNumber = 2;
        $validRows = [];

        foreach ($rows as $row) {
            $rawData = $row->toArray();

            // Normalize headers
            $data = [];
            foreach ($rawData as $key => $value) {
                $normalizedKey = strtolower(str_replace(' ', '_', trim($key)));
                $data[$normalizedKey] = trim($value);
            }

            $rowErrors = [];

            $admissionId = $data['admission_id'] ?? null;
            $hostelId = $data['hostel_id'] ?? null;
            $bedNumber = $data['bed_id'] ?? $data['bed_number'] ?? null;
            $status = strtolower($data['status'] ?? '');
            $studentName = $data['student_name'] ?? '';
            $email = $data['email_id'] ?? null;
            $isBedRelease = 0;

            // Validate admission_id
            $map = StudentAdmissionMap::where('admission_id', $admissionId)->first();
            if (!$map) {
                $rowErrors[] = "Admission ID '$admissionId' does not exist.";
            }

            // Validate hostel_id
            $hostel = null;
            if (empty($hostelId)) {
                $rowErrors[] = "Hostel ID is required.";
            } else {
                $hostel = Hostel::find($hostelId);
                if (!$hostel) {
                    $rowErrors[] = "Hostel ID '$hostelId' does not exist.";
                }
            }

            // Validate bed
            $bed = null;
            if (!$bedNumber || !preg_match('/^\d+-[A-Z]$/i', $bedNumber)) {
                $rowErrors[] = "Invalid bed number format (e.g., '403-B').";
            } elseif ($hostel) {
                $bed = Bed::where('bed_number', $bedNumber)
                    ->where('hostel_id', $hostelId)
                    ->first();

                if (!$bed) {
                    $rowErrors[] = "Bed '$bedNumber' does not exist in Hostel ID '$hostelId'.";
                } elseif ($bed) {
                    $currentYear = now()->year;
                    $bedAlreadyAssigned = StudentAdmissionMap::where('bed_id', $bed->id)
                        ->where('admission_year', $currentYear)
                        ->where('admission_id', '!=', $admissionId) 
                        ->exists();

                    if ($bedAlreadyAssigned) {
                        $rowErrors[] = "Bed '$bedNumber' is already allotted to another student for the year '$currentYear'.";
                    }
                }
            }

            if ($status !== 'confirm' && $map && $bed) {
                $activity = activity('Room Allotment')
                    ->causedBy(auth()->user()?->id)
                    ->event('Skipped')
                    ->performedOn($map)
                    ->withProperties([
                        'attributes' => [
                            'admission_id' => $map->admission_id,
                            'student_id' => $map->student_id ?? null,
                            'student_name' => $studentName,
                            'hostel_id' => $hostelId,
                            'bed_number' => $bedNumber,
                            'status' => $status,
                            'reason' => "Skipped due to non-confirm status",
                            'logged_at' => now(),
                        ]
                    ])
                    ->log(" skipped room allotment for student '$studentName' due to status '$status'");

                ActivityLog::where('id', $activity->id)->update([
                    'student_id' => $map->student_id ?? 0,
                    'admission_id' => $map->admission_id ?? 0,
                ]);
            }

            // Validate email format
            if (empty($email)) {
                $rowErrors[] = "Email ID is required.";
            } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $rowErrors[] = "Invalid Email ID format: '$email'.";
            }

            // Collect errors or prepare valid data
            if (!empty($rowErrors)) {
                $this->errors[] = [
                    'row' => $rowNumber,
                    'messages' => $rowErrors,
                ];
            } elseif ($status === 'confirm' && $map && $bed) {
                $validRows[] = [
                    'row' => $rowNumber,
                    'map' => $map,
                    'hostel_id' => $hostelId,
                    'room_id' => $bed->room_id,
                    'bed_id' => $bed->id,
                    'is_bed_release' => $isBedRelease,
                    'email' => $email,
                    'student_name' => $studentName,
                    'hostel_name' => $hostel->hostel_name ?? '',
                    'room_number' => $bed->room->room_number ?? '',
                    'bed_number' => $bed->bed_number ?? '',
                ];
            }

            $rowNumber++;
        }

        // Abort if errors
        if (!empty($this->errors)) {
            return;
        }

        // Check all email addresses are valid
        foreach ($validRows as $row) {
            if (!filter_var($row['email'], FILTER_VALIDATE_EMAIL)) {
                $this->errors[] = [
                    'row' => $row['row'],
                    'messages' => ["Invalid email: {$row['email']}"],
                ];
                return;
            }
        }

        $emailsToSend = []; // collect after DB success

        // Start transaction for DB updates and logs
        DB::beginTransaction();
        try {
            foreach ($validRows as $row) {
                // DB Updates
                $row['map']->update([
                    'hostel_id' => $row['hostel_id'],
                    'room_id' => $row['room_id'],
                    'bed_id' => $row['bed_id'],
                    'is_bed_release' => $row['is_bed_release'],
                ]);


                // Log Room Assignment
                $activity1 = activity('Room Allotment')
                    ->causedBy(auth()->user()->id)
                    ->event('Assignment')
                    ->performedOn($row['map'])
                    ->withProperties([
                        'attributes' => [
                            'admission_id' => $row['map']->admission_id,
                            'student_id' => $row['map']->student_id ?? null,
                            'student_name' => $row['student_name'],
                            'hostel' => $row['hostel_name'],
                            'room' => $row['room_number'],
                            'bed' => $row['bed_number'],
                            'updated_at' => now(),
                        ]
                    ])
                    ->log(auth()->user()->name . ' assigned room ' . $row['room_number'] . ' and bed ' . $row['bed_number'] . ' to ' . $row['student_name']);

                ActivityLog::where('id', $activity1->id)->update([
                    'student_id' => $row['map']->student_id ?? 0,
                    'admission_id' => $row['map']->admission_id ?? 0,
                ]);

                // Prepare Email Sent
                $emailsToSend[] = $row;

                $activity2 = activity('Room Allotment')
                    ->causedBy(auth()->user()->id)
                    ->event('Email Sent')
                    ->performedOn($row['map'])
                    ->withProperties([
                        'attributes' => [
                            'admission_id' => $row['map']->admission_id,
                            'student_id' => $row['map']->student_id ?? null,
                            'email' => $row['email'],
                            'student_name' => $row['student_name'],
                            'sent_at' => now(),
                        ]
                    ])
                    ->log(auth()->user()->name . ' sent room allotment email to ' . $row['email']);

                ActivityLog::where('id', $activity2->id)->update([
                    'student_id' => $row['map']->student_id ?? 0,
                    'admission_id' => $row['map']->admission_id ?? 0,
                ]);
            }

            DB::commit(); // DB work successful

        } catch (\Throwable $e) {
            DB::rollBack(); // Undo all DB updates
            $this->errors[] = ['row' => 'unknown', 'messages' => [$e->getMessage()]];
            return;
        }

        // All DB successful → now send mails
        foreach ($emailsToSend as $row) {
            try {
                Mail::to($row['email'])->queue(new RoomAllotmentMail((object) $row));
            } catch (\Exception $e) {
                $this->errors[] = [
                    'row' => $row['row'],
                    'messages' => ["Email sent failed to {$row['email']}, but DB update already done."],
                ];
            }
        }
    }
}
