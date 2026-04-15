<?php

namespace App\Classes;

use App\Models\Generalsetting;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use PHPMailer\PHPMailer\Exception;
use PHPMailer\PHPMailer\PHPMailer;

class GeniusMailer
{
    public $mail;
    public $gs;
    public $social;

    public function __construct()
    {
        $this->gs = Generalsetting::safeFirst();
        $this->social = \App\Models\Socialsetting::safeFirst();

        $this->mail = new PHPMailer(true);
        $this->mail->Timeout = 10;
        if (isset($this->gs->is_smtp) && $this->gs->is_smtp == 1) {
            $this->mail->isSMTP();
            $this->mail->Host = $this->gs->mail_host;
            $this->mail->SMTPAuth = true;
            $this->mail->Username = $this->gs->mail_user;
            $this->mail->Password = $this->gs->mail_pass;
            $this->mail->SMTPSecure = $this->gs->mail_encryption;
            $this->mail->Port = $this->gs->mail_port;
            $this->mail->Timeout = 10;
            $this->mail->CharSet = 'utf-8';
        }

        $from_email = (!empty($this->gs->from_email) && trim($this->gs->from_email) != '') ? $this->gs->from_email : 'support@fabilive.com';
        $from_name = (!empty($this->gs->from_name) && trim($this->gs->from_name) != '') ? $this->gs->from_name : 'Fabilive';
        $this->mail->setFrom($from_email, $from_name);
    }

    public function sendAutoMail(array $data)
    {
        try {
            $temp = DB::table('email_templates')->where('email_type', '=', $data['type'])->first();
            if (!$temp) {
                Log::warning('Email template missing: ' . $data['type']);
                return false;
            }

            $body = preg_replace('/{customer_name}/', $data['cname'], $temp->email_body);
            if (isset($data['oamount'])) {
                $body = preg_replace('/{order_amount}/', $data['oamount'], $body);
            }
            if (isset($data['aname'])) {
                $body = preg_replace('/{admin_name}/', $data['aname'], $body);
            }
            if (isset($data['aemail'])) {
                $body = preg_replace('/{admin_email}/', $data['aemail'], $body);
            }
            if (isset($data['onumber'])) {
                $body = preg_replace('/{order_number}/', $data['onumber'], $body);
            }
            $body = preg_replace('/{website_title}/', $this->gs->title, $body);

            // Wrap in HTML Template
            $html_body = view('emails.base', [
                'body' => $body,
                'gs' => $this->gs,
                'social' => $this->social
            ])->render();

            $this->addRecipients($data['to']);
            $this->mail->isHTML(true);
            $this->mail->Subject = $temp->email_subject;
            $this->mail->Body = $html_body;
            $this->mail->send();
        } catch (\Exception $e) {
            Log::error('Mailer AutoMail Error: ' . $e->getMessage());
        }

        return true;
    }

    public function sendCustomMail(array $mailData)
    {
        try {
            $this->addRecipients($mailData['to']);

            // Wrap in HTML Template
            $html_body = view('emails.base', [
                'body' => $mailData['body'],
                'gs' => $this->gs,
                'social' => $this->social
            ])->render();

            $this->mail->isHTML(true);
            $this->mail->Subject = $mailData['subject'];
            $this->mail->Body = $html_body;
            
            // Standardizing SMTP for custom mail too if config exists
            if (isset($this->gs->is_smtp) && $this->gs->is_smtp == 1) {
                // Already configured in constructor if we use $this->mail
            } else {
                 $this->mail->SMTPSecure = 'tls';
                 $this->mail->Port = 587;
            }

            $this->mail->send();
        } catch (Exception $e) {
            Log::error('Mailer CustomMail Error: ' . $this->mail->ErrorInfo);
        }

        return true;
    }

    public function sendAutoOrderMail(array $data, $order_id)
    {
        return $this->sendAutoMail($data);
    }

    private function addRecipients($to)
    {
        // Support | or , separated emails
        $emails = preg_split('/[|,]/', $to);

        foreach ($emails as $email) {
            $email = trim($email);
            if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
                $this->mail->addAddress($email);
            }
        }
    }
}
