<?php extract($data); ?>

<!DOCTYPE html>
<html>
<head>

    <meta charset="utf-8">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title>Email Template Base</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style type="text/css">
        /**
         * Google webfonts. Recommended to include the .woff version for cross-client compatibility.
         */
        @media screen {
            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 400;
                src: local('Source Sans Pro Regular'), local('SourceSansPro-Regular'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/ODelI1aHBYDBqgeIAH2zlBM0YzuT7MdOe03otPbuUS0.woff) format('woff');
            }

            @font-face {
                font-family: 'Source Sans Pro';
                font-style: normal;
                font-weight: 700;
                src: local('Source Sans Pro Bold'), local('SourceSansPro-Bold'), url(https://fonts.gstatic.com/s/sourcesanspro/v10/toadOcfmlt9b38dHJxOBGFkQc6VGVFSmCnC_l7QZG60.woff) format('woff');
            }
        }

        /**
         * Avoid browser level font resizing.
         * 1. Windows Mobile
         * 2. iOS / OSX
         */
        body,
        table,
        td,
        a {
            -ms-text-size-adjust: 100%; /* 1 */
            -webkit-text-size-adjust: 100%; /* 2 */
        }

        /**
         * Remove extra space added to tables and cells in Outlook.
         */
        table,
        td {
            mso-table-rspace: 0pt;
            mso-table-lspace: 0pt;
        }

        /**
         * Better fluid images in Internet Explorer.
         */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /**
         * Remove blue links for iOS devices.
         */
        a[x-apple-data-detectors] {
            font-family: inherit !important;
            font-size: inherit !important;
            font-weight: inherit !important;
            line-height: inherit !important;
            color: inherit !important;
            text-decoration: none !important;
        }

        /**
         * Fix centering issues in Android 4.4.
         */
        div[style*="margin: 16px 0;"] {
            margin: 0 !important;
        }

        body {
            width: 100% !important;
            height: 100% !important;
            padding: 0 !important;
            margin: 0 !important;
        }

        /**
         * Collapse table borders to avoid space between cells.
         */
        table {
            border-collapse: collapse !important;
        }

        a {
            color: #1a82e2;
        }

        img {
            height: auto;
            line-height: 100%;
            text-decoration: none;
            border: 0;
            outline: none;
        }
    </style>

</head>
<body style="background-color: #e9ecef;">

<!-- start body -->
<table border="0" cellpadding="0" cellspacing="0" width="100%">

    <!-- start logo -->
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                <tr>
                    <td align="center" valign="top" width="600">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="center" valign="top" style="padding: 36px 24px;">
                        <a href="{{url('/')}}" target="_blank" style="display: inline-block;">
                            <img src="{{ asset('img/logo.png') }}" alt="Logo" border="0" width="58" style="display: block; width: 90px;">

                        </a>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <!-- end logo -->

    <!-- start hero -->
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                <tr>
                    <td align="center" valign="top" width="600">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">
                <tr>
                    <td align="left" bgcolor="#ffffff" style="padding: 36px 24px 0; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; border-top: 3px solid #d4dadf;">
                        <h3 style="margin: 0;  font-weight: 700; letter-spacing: -1px; line-height: 48px;">Hi {{ isset($fname) ? " {$fname}" : "User"  }},</h3>
                    </td>
                </tr>
            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <!-- end hero -->

    <!-- start copy block -->
    <tr>
        <td align="center" bgcolor="#e9ecef">
            <!--[if (gte mso 9)|(IE)]>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="600">
                <tr>
                    <td align="center" valign="top" width="600">
            <![endif]-->
            <table border="0" cellpadding="0" cellspacing="0" width="100%" style="max-width: 600px;">

                <!-- start copy -->
                <tr>
                    <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                       <p style="margin: 0;">Welcome and congrats on becoming a member of the Jolli family!" We're so happy you're here. We founded Jolli Portal because we wanted to create a trustworthy and inspiring place for you to find everything you need as a Nigerian, especially Jobs problem to money problem to money.</p>
                                              <br>
                                              <p style="margin: 0;">As a family member you'll have access to:</p>
                                              <ul>
                                                <li><p>Free Job opportunity listing</p></li>
                                                <li><p>Free Daily News Updates in Nigeria</p></li>
                                                <li><p>Collaborating savings with your friends and family (Ajor/Isusu)</p></li>
                                              </ul>

                                              <p>We built Jolli Portal to help you get a Job, this experience is on a whole new level. You might have tried a lot of tools over the years, Jolli Portal is different.
</p><br>
      <p>We recommend you also checking out these resources:</p>
        <ul>
          <li><p>CV Revamp (This helps you to upgrade your CV to a professional Level)</p></li>
          <li><p>Make Money Online Features (You will learn how to make good money sitting at home)</p></li>
        </ul>
        <p>We are here to help make sure you get the results you expect from Jolli Portal so don't hesitate to reach out with questions because we'd love to hear from you. If you need support, you can reply to this email - hello@remedyportal.com or message us on Whatsapp on +2349134445884</p>
      <br>
        <p> We would be happy to walk you through the details and information you need to get started on the right foot.  Feel free to also reach out to us on Instagram @remedyportal </p>
        <p>Thank you and we're excited to have you! Cheers!</p>
                    </td>
                </tr>
                <!-- end copy -->

            @if(isset($link))

                <!-- start button -->
                <tr>
                    <td align="left" bgcolor="#ffffff">
                        <table border="0" cellpadding="0" cellspacing="0" width="100%">
                            <tr>
                                <td align="center" bgcolor="#ffffff" style="padding: 12px;">
                                    <table border="0" cellpadding="0" cellspacing="0">
                                        <tr>
                                            <td align="center" bgcolor="#1a82e2" style="border-radius: 6px;">
                                                <a href="{{ $link['url'] }}" target="_blank" style="display: inline-block; padding: 16px 36px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; color: #ffffff; text-decoration: none; border-radius: 6px;">{{ $link['text'] }}</a>
                                            </td>
                                        </tr>
                                    </table>
                                </td>
                            </tr>
                        </table>
                    </td>
                </tr>
                <!-- end button -->

                    <!-- start copy -->
                    <tr>
                        <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px;">
                            <p style="margin: 0;">If that doesn't work, copy and paste the following link in your browser:</p>
                            <p style="margin: 0;"><a href="{{ $link['url'] }}" target="_blank">{{ $link['url'] }}</a></p>
                        </td>
                    </tr>
                    <!-- end copy -->
            @endif




                <!-- start copy -->
                <tr>
                    <td align="left" bgcolor="#ffffff" style="padding: 24px; font-family: 'Source Sans Pro', Helvetica, Arial, sans-serif; font-size: 16px; line-height: 24px; border-bottom: 3px solid #d4dadf">
                        <p style="margin: 0;">Sincerely,<br> Efungwu Opata <br> CEO, {{ config('app.name') }}</p>
                    </td>
                </tr>
                <!-- end copy -->

            </table>
            <!--[if (gte mso 9)|(IE)]>
            </td>
            </tr>
            </table>
            <![endif]-->
        </td>
    </tr>
    <!-- end copy block -->

</table>
<!-- end body -->

</body>
</html>
