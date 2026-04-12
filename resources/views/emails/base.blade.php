<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fabilive Notification</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; background-color: #f4f7f6; margin: 0; padding: 0; -webkit-font-smoothing: antialiased; }
        .wrapper { width: 100%; table-layout: fixed; background-color: #f4f7f6; padding-bottom: 40px; }
        .main { background-color: #ffffff; margin: 0 auto; width: 100%; max-width: 600px; border-spacing: 0; color: #4a4a4a; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 10px rgba(0,0,0,0.05); }
        .header { background-color: #000000; padding: 30px; text-align: center; }
        .logo { width: 120px; height: auto; }
        .content { padding: 40px 30px; line-height: 1.6; font-size: 16px; color: #333333; }
        .footer { text-align: center; padding: 30px; font-size: 12px; color: #999999; line-height: 1.5; }
        .button { display: inline-block; padding: 12px 30px; background-color: #000000; color: #ffffff !important; text-decoration: none; border-radius: 5px; font-weight: bold; margin-top: 20px; }
        .hr { border: none; border-top: 1px solid #eeeeee; margin: 30px 0; }
        @media screen and (max-width: 600px) {
            .main { width: 100% !important; border-radius: 0 !important; }
            .content { padding: 30px 20px !important; }
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <table class="main" align="center">
            <tr>
                <td class="header">
                    <a href="{{ url('/') }}" target="_blank">
                        <img src="{{ url('assets/images/'.$gs->logo) }}" alt="Fabilive Logo" class="logo">
                    </a>
                </td>
            </tr>
            <tr>
                <td class="content">
                    {!! $body !!}
                    
                    <div class="hr"></div>
                    <p style="font-size: 14px; color: #888;">If you have any questions, please contact our support team at <a href="mailto:{{ !empty($gs->from_email) ? $gs->from_email : 'support@fabilive.com' }}" style="color: #000; text-decoration: none;">{{ !empty($gs->from_email) ? $gs->from_email : 'support@fabilive.com' }}</a></p>
                </td>
            </tr>
            <tr>
                <td class="footer">
                    <p>&copy; {{ date('Y') }} Fabilive. All rights reserved.</p>
                    <p>Fabilive — Connecting Hustlers, Buyers & Sellers.<br>Limbe, South West Region, Cameroon</p>
                    <div style="margin-top: 15px;">
                        <a href="{{ $gs->facebook }}" style="margin: 0 10px; text-decoration: none; color: #000;">Facebook</a>
                        <a href="{{ $gs->twitter }}" style="margin: 0 10px; text-decoration: none; color: #000;">Twitter</a>
                        <a href="{{ $gs->instagram }}" style="margin: 0 10px; text-decoration: none; color: #000;">Instagram</a>
                    </div>
                </td>
            </tr>
        </table>
    </div>
</body>
</html>
