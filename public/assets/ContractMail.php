<?php

namespace App\Mail;

use App\Models\Contract;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Attachment;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Queue\SerializesModels;
use Barryvdh\DomPDF\Facade\Pdf;
use Dompdf\FontMetrics;
use Dompdf\Adapter\CPDF;
use Dompdf\Dompdf;
use Spatie\PdfToImage\Pdf as PdfToImage;
use Imagick;

class ContractMail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     */
    public function __construct(
        public Contract $contract,
        public string $customMessage = '',
        public string $customSubject = ''
    ) {
        //
    }

    /**
     * Get the message envelope.
     */
    public function envelope(): Envelope
    {
        $subject = $this->customSubject ?: 'Contract ' . $this->contract->contract_number . ' from Skylux';
        
        return new Envelope(
            subject: $subject,
        );
    }

    /**
     * Get the message content definition.
     */
    public function content(): Content
    {
        return new Content(
            view: 'emails.contract',
        );
    }

    /**
     * Get the attachments for the message.
     *
     * @return array<int, \Illuminate\Mail\Mailables\Attachment>
     */
    public function attachments(): array
    {
        // Load all necessary relationships for the PDF (same as controller)
        $this->contract->load([
            'quote.customer', 
            'quote.company',
            'quote.branch',
            'quote.aircraft',
            'selectedAircraft', 
            'creator',
            'quote' => function($query) {
                $query->with(['customer', 'company', 'aircraft','branch']);
            }
        ]);

        // Generate PDF using the same template and configuration as controller
        $pdf = Pdf::loadView('admin.contracts.pdf', ['contract' => $this->contract])
            ->setPaper('a4', 'portrait')
            ->setOptions([
                'defaultFont' => 'Helvetica',
                'isHtml5ParserEnabled' => true,
                'isPhpEnabled' => true,
                'isRemoteEnabled' => false,
                'isFontSubsettingEnabled' => false,
                'chroot' => public_path(),
            ]);
        
             // Save to a system temp file
        $tempPdf = tempnam(sys_get_temp_dir(), 'dompdf_') . '.pdf';
        file_put_contents($tempPdf, $pdf->output());

        try {
            // Convert PDF to images using Imagick (copy protection)
            $imagick = new Imagick();
            $imagick->setResolution(150, 150); // adjust DPI (72–300)
            $imagick->readImage($tempPdf);

            // Rebuild as image-only PDF
            $imagick->setImageFormat('pdf');
            $outputPdf = tempnam(sys_get_temp_dir(), 'protected_') . '.pdf';
            $imagick->writeImages($outputPdf, true);

            // Read the protected PDF content
            $protectedPdfContent = file_get_contents($outputPdf);

            // Clean up temporary files
            if (file_exists($tempPdf)) {
                unlink($tempPdf);
            }
            if (file_exists($outputPdf)) {
                unlink($outputPdf);
            }

            return [
                Attachment::fromData(fn () => $protectedPdfContent, "contract-{$this->contract->contract_number}.pdf")
                    ->withMime('application/pdf')
            ];
        } catch (\Exception $e) {
            // Clean up temp files on error and fall back to regular PDF
            if (file_exists($tempPdf)) {
                unlink($tempPdf);
            }

            return [
                Attachment::fromData(fn () => $pdf->output(), 'contract-' . $this->contract->contract_number . '.pdf')
                    ->withMime('application/pdf'),
            ];
        }

        
    }
}