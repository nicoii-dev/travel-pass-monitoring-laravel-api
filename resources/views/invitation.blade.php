<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Invitation</title>
</head>

<body>
    <span style="display: none !important; font-size: 1px;">{{env('APP_ENV')}}</span>
    <center style="width:100%; background: white; color: #555;">
        <div class="email-wrapper" style="max-width:600px; margin:auto">
            <table class="email-header" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="border-spacing:0;border-collapse:collapse;max-width:600px;margin:0 auto">
                <tbody>
                    <tr>
                        <td style="font-family: 'Helvetica Neue',sans-serif;background-color:#2065D1;font-size:13px;line-height:1.6;padding:20px 0" align="center">
                            <a href="#" style="color:#fff;text-decoration:none;font-size:30px" target="_blank" data-saferedirecturl="#">
                                <strong><span>News Network</span></strong>
                            </a>
                        </td>
                    </tr>
                </tbody>
            </table>


            <table class="email-body" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="border-spacing:0;border-collapse:collapse;max-width:600px;margin:0 auto" bgcolor="#ffffff">
                <tbody>
                    <tr>
                        <td>
                            <table border="0" cellpadding="30" cellspacing="0" width="100%" style="border-spacing:0;border-collapse:collapse;margin:0 auto">
                                <tbody>
                                    <tr>
                                        <td valign="top" style="font-family:'Helvetica Neue', sans-serif;color:#444;font-size:14px;line-height:150%">
                                            <p style="font-size:14px;color:#222222;">
                                                Hi {{ $user->first_name }},
                                            </p>
                                            <p style="margin-top:15px;margin-bottom:15px;font-size:14px;color:#222222; line-height: 2;">
                                                You are now a part of News Network team as a {{ $role }}, here's your default credentials:
                                                <br />
                                                email: {{ $user->email }}
                                                <br />
                                                password: {{ $link }}
                                                <br />
                                                <span style="color:#222222;">We hope you enjoy {{env('APP_NAME')}}!</span>
                                            </p>

                                            <p style="margin-top:15px;margin-bottom:15px;font-size:14px;color:#222222">
                                                If you need additional assistance, please visit our&nbsp;
                                                <a href="help_center" style="text-decoration: underline; cursor: pointer; color: #18a749; font-weight: bold;">Help
                                                    Center</a>
                                                <br />
                                                Cheers,
                                                <br />
                                                {{env('APP_NAME')}}
                                            </p>


                                            <hr style="border: none; border-bottom-width:1px;border-bottom-color:#eee;border-bottom-style:solid;" />
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                </tbody>
            </table>
            <table class="email-footer" cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="border-spacing:0;border-collapse:collapse;max-width:600px;margin:0 auto">
                <tbody>
                    <tr>
                        <td style="font-family:'Helvetica Neue',sans-serif;color:#18a749;font-size:12px;line-height:1.6;padding:30px 5%" align="center">
                            <div style="margin-top:10px">
                                <span class="il">© @php echo date("Y"); @endphp</span> &nbsp;•&nbsp; <span class="il">{{env('APP_NAME')}}</span>
                                &nbsp;•&nbsp;
                                All rights reserved
                            </div>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </center>
</body>

</html>