<?php
/**
 * Created by PhpStorm.
 * User: dima
 * Date: 08.05.14
 * Time: 10:15
 * @var ControllerBase $this
 * @var string $userName
 * @var string $userPassword
 * @var string $userLogin
 */
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
    <meta name="viewport" content="width=device-width, initial scale=1.0"/>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <title>Разместите рекламу на вашем сайте!</title>
    <style>
        /* Client-specific Styles */
        #outlook a {padding:0;} /* Force Outlook to provide a "view in browser" menu link. */
        body{width:100% !important; -webkit-text-size-adjust:100%; -ms-text-size-adjust:100%; margin:0; padding:0; -webkit-font-smoothing: antialiased; font-family: Arial, Helvetica, sans-serif;}
        /* Prevent Webkit and Windows Mobile platforms from changing default font sizes, while not breaking desktop design. */
        .ExternalClass {width:100%;} /* Force Hotmail to display emails at full width */
        .ExternalClass, .ExternalClass p, .ExternalClass span, .ExternalClass font, .ExternalClass td, .ExternalClass div {line-height: 100%;} /* Force Hotmail to display normal line spacing.  More on that: http://www.emailonacid.com/forum/viewthread/43/ */
        img {outline:none; text-decoration:none;border:none; -ms-interpolation-mode: bicubic;}
        a img {border:none;}
        .image_fix {display:block;}
        p {margin: 0px 0px !important;}

        table td {border-collapse: collapse;}
        table { border-collapse:collapse; mso-table-lspace:0pt; mso-table-rspace:0pt; }
        /*a {color: #e95353;text-decoration: none;text-decoration:none!important;}*/

        @media only screen and (max-width:640px) {
            a[class="button"]{ padding: 15px 35px 15px 35px; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; color: #fff!important; background: #f39c12; text-align: center; text-decoration: none!important; font-size: 18px;}
            table[class="hide"], img[class="hide"], td[class="hide"] { display:none!important;}
            .deviceWidth {width:440px!important; padding:0;}
            .buttonWidth {width:420px!important; padding:0;}
            .deviceWidthinner {width:420px!important;text-align:center!important; padding:0;}
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: #666666; /* or whatever your want */
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #666666 !important;
                pointer-events: auto;
                cursor: default;
            }
            .ResponsPaddding {padding: 0;}
        }
        @media only screen and (max-width:480px) {
            a[class="button"]{padding: 15px 35px 15px 35px; -webkit-border-radius: 4px; -moz-border-radius: 4px; border-radius: 4px; color: #fff!important; background: #f39c12; text-align: center; text-decoration: none!important; font-size: 18px;
            }
            table[class="hide"], img[class="hide"], td[class="hide"] {
                display:none!important;
            }
            .deviceWidth {width:280px!important; padding:0;}
            .buttonWidth {width:260px!important; padding:0;}
            .deviceWidthinner {width:260px!important;padding:0;}
            a[href^="tel"], a[href^="sms"] {
                text-decoration: none;
                color: #666666; /* or whatever your want */
                pointer-events: none;
                cursor: default;
            }
            .mobile_link a[href^="tel"], .mobile_link a[href^="sms"] {
                text-decoration: default;
                color: #666666 !important;
                pointer-events: auto;
                cursor: default;
            }
            .ResponsPadding {padding: 0 15px !important;}
        }
    </style>
</head>
<body leftmargin="0" topmargin="0" marginwidth="0" marginheight="0" yahoo="fix" bgcolor="#ffffff" style="color:#34495e; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">
<!-- Start Preheader -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
    <tbody>
    <tr>
        <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="deviceWidth">
                <tbody>
                <!-- Spacing -->
                <tr>
                    <td width="100%" height="30"></td>
                </tr>
                <!-- Spacing -->
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>
<!-- End Preheader -->
<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
    <tbody>
    <tr>
        <td>
            <table width="600" bgcolor="#34495e" cellpadding="0" cellspacing="0" border="0" align="center" class="deviceWidth" height="98px" style="border-radius: 4px 4px 0 0;">
                <tbody>
                <tr>
                    <td style="color:#ffffff; font-size:15px; padding:0 90px; vertical-align:middle;" class="ResponsPadding">
                        <p>Спасибо за регистрацию!</p>
                    </td>
                    <td style="padding: 0 10px 0 0;">
                        <img src="http://gallery.mailchimp.com/93335ed5bc9268f8747e326a9/images/966ee8b3-4106-4b52-9cd8-b7806260b3ca.png">
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" style="border-bottom:1px solid #ffffff;">
    <tbody>
    <tr>
        <td>
            <table width="600" bgcolor="#f1f1f1" cellpadding="0" cellspacing="0" border="0" align="center" class="deviceWidth" height="130px">
                <tbody>
                <tr>
                    <td style="padding:0 90px;" class="ResponsPadding">
                        <p style="color:#727272;">Здравствуйте, <?php echo $userName; ?>! <br>Вы успешно завершили процесс регистрации в тизерной сети AdLoud. Для авторизации в нашей системе используйте логин и пароль, указанные ниже. </p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" style="border-bottom:1px solid #ffffff;">
    <tbody>
    <tr>
        <td>
            <table width="600" bgcolor="#f1f1f1" cellpadding="0" cellspacing="0" border="0" align="center" class="deviceWidth" height="53px">
                <tbody>
                <tr>
                    <td style="padding:0 90px;" class="ResponsPadding">
                        <p style="color:#727272;">Логин: <span style="color:#429ef7;"><?php echo $userLogin; ?></p>
                        <p style="color:#727272;">Пароль: <span style="color:#429ef7;"><?php echo $userPassword; ?></span></p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0" style="margin-bottom:1px;">
    <tbody>
    <tr>
        <td>
            <table width="600" bgcolor="#f1f1f1" cellpadding="0" cellspacing="0" border="0" align="center" class="deviceWidth" height="200px" style="border-radius: 0 0 4px 4px;">
                <tbody>
                <tr>
                    <td style="padding:0 90px; color:#727272;" class="ResponsPadding">
                        <p>Мы очень рады, что вы с нами!</p>
                        <br>
                        <p>Если вы добавили сайт в систему, то в течении нескольких дней вы получите допуск к закрытому бета-тесту.</p>
                        <br>
                        <p>Еще раз благодарим вас за регистрацию!</p>
                        <p>С уважением, служба поддержки компании AdLoud.</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>

<table width="100%" bgcolor="#ffffff" cellpadding="0" cellspacing="0" border="0">
    <tbody>
    <tr>
        <td>
            <table width="600" cellpadding="0" cellspacing="0" border="0" align="center" class="deviceWidth" height="83px" style="border-radius: 0 0 4px 4px;">
                <tbody>
                <tr>
                    <td align="center" style="color:#727272; font-size:11px;" class="ResponsPadding">
                        <p>Большая Житомирская 6, Офис 6, Киев, 01001, Украина., www.adloud.net   support@adloud.net</p>
                    </td>
                </tr>
                </tbody>
            </table>
        </td>
    </tr>
    </tbody>
</table>


</body>
</html>