<?php
namespace App\Classes;
use App\{
    Models\Order,
    Models\EmailTemplate,
    Models\Generalsetting
};
use Barryvdh\DomPDF\Facade\Pdf;
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\DB;

class GeniusMailer
{
    public $mail;
    public $gs;
    public function __construct()
    {
        $this->gs = Generalsetting::first(); // Safer than findOrFail(1)
        if (!$this->gs) {
            $this->gs = (object)[
                'is_smtp' => 0,
                'from_email' => 'admin@fabilive.com',
                'from_name' => 'Fabilive',
                'title' => 'Fabilive'
            ];
        }
        $this->mail = new PHPMailer(true);
        $this->mail->Timeout = 10;
        if (isset($this->gs->is_smtp) && $this->gs->is_smtp == 1) {
            $this->mail->isSMTP();
            $this->mail->Host       = $this->gs->mail_host;
            $this->mail->SMTPAuth   = true;
            $this->mail->Username   = $this->gs->mail_user;
            $this->mail->Password   = $this->gs->mail_pass;
            $this->mail->SMTPSecure = $this->gs->mail_encryption;
            $this->mail->Port       = $this->gs->mail_port;
        }
    }

    public function sendAutoMail(array $data)
    {
        $temp = DB::table('email_templates')->where('email_type', '=', $data['type'])->first();
        if (!$temp) {
            Log::warning("Email template missing: " . $data['type']);
            return false;
        }

        try {
            $body = preg_replace("/{customer_name}/", $data['cname'], $temp->email_body);
            if (isset($data['oamount'])) $body = preg_replace("/{order_amount}/", $data['oamount'], $body);
            if (isset($data['aname'])) $body = preg_replace("/{admin_name}/", $data['aname'], $body);
            if (isset($data['aemail'])) $body = preg_replace("/{admin_email}/", $data['aemail'], $body);
            if (isset($data['onumber'])) $body = preg_replace("/{order_number}/", $data['onumber'], $body);
            $body = preg_replace("/{website_title}/", $this->gs->title, $body);

            $this->mail->setFrom($this->gs->from_email, $this->gs->from_name);
            $this->addRecipients($data['to']);
            $this->mail->isHTML(true);
            $this->mail->Subject = $temp->email_subject;
            $this->mail->Body = $body;
            $this->mail->send();
        } catch (Exception $e) {
            Log::error("Mailer AutoMail Error: " . $this->mail->ErrorInfo);
        }
        return true;
    }

    public function sendCustomMail(array $mailData)
    {
        try {
            $this->mail->setFrom($this->gs->from_email, $this->gs->from_name);
            $this->addRecipients($mailData['to']);

            $this->mail->isHTML(true);
            $this->mail->Subject = $mailData['subject'];
            $this->mail->Body = $mailData['body'];
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = 587;
            $this->mail->send();
        } catch (Exception $e) {
            Log::error("Mailer CustomMail Error: " . $this->mail->ErrorInfo);
        }
        return true;
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
