<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\Attributes\Title;
use Livewire\Attributes\Rule;
use Illuminate\Support\Facades\Log;

#[Title('Contact')]
class Contact extends Component
{
    #[Rule('required|min:3|max:100')]
    public $name = '';

    #[Rule('required|email|max:100')]
    public $email = '';

    #[Rule('required|min:10|max:1000')]
    public $message = '';

    public $successMessage = '';
    public $errorMessage = '';

    public function submitForm()
    {
        $this->validate();

        try {
            $apiKey = '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';

            // Recipient email (you can specify your admin email)
            $adminEmail = env('MAIL_ADMIN_ADDRESS', 'kilwasimo659@gmail.com');

            // Log the attempt
            Log::info('Sending contact email with Brevo API', [
                'to' => $adminEmail,
                'from_name' => $this->name,
                'from_email' => $this->email
            ]);

            // Prepare the email data
            $data = [
                'sender' => [
                    'name' => $this->name,
                    'email' => $this->email
                ],
                'to' => [
                    [
                        'email' => $adminEmail,
                        'name' => 'Admin'
                    ]
                ],
                'subject' => 'New Contact Form Submission from ' . $this->name,
                'htmlContent' => $this->formatEmailContent(),
                'replyTo' => [
                    'name' => $this->name,
                    'email' => $this->email
                ]
            ];

            // Send email using Brevo API
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, 'https://api.sendinblue.com/v3/smtp/email');
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

            $headers = [
                'Accept: application/json',
                'Content-Type: application/json',
                'api-key: ' . $apiKey
            ];

            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);

            $response = curl_exec($ch);
            $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

            if (curl_errno($ch)) {
                throw new \Exception('Curl error: ' . curl_error($ch));
            }

            curl_close($ch);

            // Handle the API response
            $responseData = json_decode($response, true);

            // Log the response
            Log::info('Brevo API response', [
                'http_code' => $httpCode,
                'response' => $responseData
            ]);

            if ($httpCode >= 200 && $httpCode < 300) {
                $this->resetForm();
                $this->successMessage = 'Thank you! Your message has been sent successfully.';
            } else {
                $errorMsg = isset($responseData['message']) ? $responseData['message'] : 'Unknown error';
                throw new \Exception('API Error: ' . $errorMsg);
            }
        } catch (\Exception $e) {
            // Log the detailed error
            Log::error('Contact form email error: ' . $e->getMessage(), [
                'exception' => $e,
                'name' => $this->name,
                'email' => $this->email
            ]);

            $this->errorMessage = 'Sorry, there was a problem sending your message. Please try again later.';
        }
    }

    private function formatEmailContent()
    {
        // Create a simple HTML email
        $html = '
        <!DOCTYPE html>
        <html>
        <head>
            <meta charset="utf-8">
            <style>
                body {
                    font-family: Arial, sans-serif;
                    line-height: 1.6;
                    color: #333;
                    max-width: 600px;
                    margin: 0 auto;
                    padding: 20px;
                }
                .header {
                    background-color: #4338ca;
                    color: white;
                    padding: 15px;
                    border-radius: 5px 5px 0 0;
                }
                .content {
                    background-color: #f9fafb;
                    padding: 20px;
                    border: 1px solid #e5e7eb;
                    border-top: none;
                    border-radius: 0 0 5px 5px;
                }
                .field {
                    margin-bottom: 15px;
                }
                .label {
                    font-weight: bold;
                    color: #4338ca;
                }
                .footer {
                    margin-top: 20px;
                    font-size: 12px;
                    color: #6b7280;
                    text-align: center;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1 style="margin: 0; font-size: 24px;">New Contact Form Submission</h1>
            </div>

            <div class="content">
                <div class="field">
                    <span class="label">Name:</span>
                    <div>' . htmlspecialchars($this->name) . '</div>
                </div>

                <div class="field">
                    <span class="label">Email:</span>
                    <div>' . htmlspecialchars($this->email) . '</div>
                </div>

                <div class="field">
                    <span class="label">Message:</span>
                    <div>' . nl2br(htmlspecialchars($this->message)) . '</div>
                </div>
            </div>

            <div class="footer">
                <p>This message was sent from the contact form on your Teacher Portfolio website.</p>
                <p>Received at: ' . date('Y-m-d H:i:s') . '</p>
            </div>
        </body>
        </html>
        ';

        return $html;
    }

    private function resetForm()
    {
        $this->name = '';
        $this->email = '';
        $this->message = '';
    }

    public function render()
    {
        return view('livewire.contact');
    }
}
