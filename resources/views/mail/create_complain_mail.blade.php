{{-- <!DOCTYPE html>
<html lang="en" dir="ltr">

<head>
    <meta charset="utf-8">
    <title></title>
</head>

<body>
    # Complain is Raised By {{ $studentData->name }}<br />
    # Hostel: {{ $studentData->hostel_name }}<br />
    # Room: {{ $studentData->room_number }}<br />
    # Message: {{ $complain['message'] }}<br />
    @if ($complain['type'] == 1)
        @php($type = 'Technical')
    @elseif($complain['type'] == 2)
        @php($type = 'System')
    @else
        @php($type = 'Management')
    @endif
    # Type: {{ $type }}
</body>

</html> --}}


<!DOCTYPE html>
<html lang="en" style="opacity: 1;" xmlns="http://www.w3.org/1999/xhtml"
    xmlns:o="urn:schemas-microsoft-com:office:office">

<head>
    <meta charset="utf-8" /><!-- utf-8 works for most cases -->
    <meta content="width=device-width" name="viewport" /><!-- Forcing initial-scale shouldn't be necessary -->
    <meta content="IE=edge" http-equiv="X-UA-Compatible" /><!-- Use the latest (edge) version of IE rendering engine -->
    <meta name="x-apple-disable-message-reformatting" /><!-- Disable auto-scale in iOS 10 Mail entirely -->
    <title>Room Allotment</title>
    <!-- The title tag shows in email notifications, like Android 4.4. --><!-- Web Font / @font-face : BEGIN --><!-- NOTE: If web fonts are not required, lines 10 - 27 can be safely removed. --><!-- Desktop Outlook chokes on web font references and defaults to Times New Roman, so we force a safe fallback font. --><!--[if mso]>
<style>
* {
font-family: Helvetica, Arial, sans-serif !important;
}
</style>
<![endif]--><!-- All other clients get the webfont reference; some will render the font and others will silently fail to the fallbacks. More on that here: http://stylecampaign.com/blog/2015/02/webfont-support-in-email/ --><!--[if !mso]><!--><!-- insert web font reference, eg: <link href='https://fonts.googleapis.com/css?family=Roboto:400,700' rel='stylesheet' type='text/css'> --><!--<![endif]--><!-- Web Font / @font-face : END --><!-- CSS Reset -->
    <style type="text/css">
        /* What it does: Remove spaces around the email design added by some email clients. */
        /* Beware: It can remove the padding / margin and add a background color to the compose a reply window. */
        html,
        body {
            margin: 0 auto !important;
            padding: 0 !important;
            height: 100% !important;
            width: 100% !important;
        }

        /* What it does: Stops email clients resizing small text. */
        * {
            -ms-text-size-adjust: 100%;
            -webkit-text-size-adjust: 100%;
        }

        /* What it does: Centers email on Android 4.4 */
        div[style*="margin: 16px 0"] {
            margin: 0 !important;
        }

        /* What it does: Stops Outlook from adding extra spacing to tables. */
        table,
        td {
            mso-table-lspace: 0pt !important;
            mso-table-rspace: 0pt !important;
        }

        /* What it does: Fixes webkit padding issue. Fix for Yahoo mail table alignment bug. Applies table-layout to the first 2 tables then removes for anything nested deeper. */
        table {
            border-spacing: 0 !important;
            border-collapse: collapse !important;
            table-layout: fixed !important;
            margin: 0 auto !important;
        }

        table table table {
            table-layout: auto;
        }

        /* What it does: Uses a better rendering method when resizing images in IE. */
        img {
            -ms-interpolation-mode: bicubic;
        }

        /* What it does: A work-around for iOS meddling in triggered links. */
        *[x-apple-data-detectors] {
            color: inherit !important;
            text-decoration: none !important;
        }

        /* What it does: A work-around for Gmail meddling in triggered links. */
        .x-gmail-data-detectors,
        .x-gmail-data-detectors *,
        .aBn {
            border-bottom: 0 !important;
            cursor: default !important;
        }

        /* What it does: Prevents Gmail from displaying an download button on large, non-linked images. */
        .a6S {
            display: none !important;
            opacity: 0.01 !important;
        }

        /* If the above doesn't work, add a .g-img class to any image in question. */
        img.g-img+div {
            display: none !important;
        }

        /* What it does: Prevents underlining the button text in Windows 10 */
        .button-link {
            text-decoration: none !important;
        }

        /* What it does: Removes right gutter in Gmail iOS app: https://github.com/TedGoas/Cerberus/issues/89  */
        /* Create one of these media queries for each additional viewport size you'd like to fix */
        /* Thanks to Eric Lepetit (@ericlepetitsf) for help troubleshooting */
        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {

            /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }

        @media only screen and (min-device-width: 375px) and (max-device-width: 413px) {

            /* iPhone 6 and 6+ */
            .email-container {
                min-width: 375px !important;
            }
        }
    </style>
    <!-- What it does: Makes background images in 72ppi Outlook render at correct size. --><!--[if gte mso 9]>
<xml>
<o:OfficeDocumentSettings>
<o:AllowPNG/>
<o:PixelsPerInch>96</o:PixelsPerInch>
</o:OfficeDocumentSettings>
</xml>
<![endif]--><!-- Progressive Enhancements -->
    <style type="text/css">
        /* What it does: Hover styles for buttons */
        .button-td,
        .button-a {
            transition: all 100ms ease-in;
        }

        .button-td:hover,
        .button-a:hover {
            background: #D17E30 !important;
            border-color: #D17E30 !important;
        }

        /* Media Queries */
        @media screen and (max-width: 640px) {
            .email-container {
                width: 100% !important;
                margin: auto !important;
            }

            /* What it does: Forces elements to resize to the full width of their container. Useful for resizing images beyond their max-width. */
            .fluid {
                max-width: 100% !important;
                height: auto !important;
                margin-left: auto !important;
                margin-right: auto !important;
            }

            /* What it does: Forces table cells into full-width rows. */
            .stack-column,
            .stack-column-center {
                display: block !important;
                width: 100% !important;
                max-width: 100% !important;
                direction: ltr !important;
            }

            /* And center justify these ones. */
            .stack-column-center {
                text-align: center !important;
            }

            /* What it does: Generic utility class for centering. Useful for images, buttons, and nested tables. */
            .center-on-narrow {
                text-align: center !important;
                display: block !important;
                margin-left: auto !important;
                margin-right: auto !important;
                float: none !important;
            }

            table.center-on-narrow {
                display: inline-block !important;
            }

            /* What it does: Adjust typography on small screens to improve readability */
            .email-container p {
                font-size: 17px !important;
                line-height: 22px !important;
            }

            td.hide-on-small {
                display: none !important;
            }
        }

        @media screen and (min-width: 641px) {
            .height-96 {
                height: 96px;
            }
        }
    </style>
</head>

<body bgcolor="#f2f2f2" style="margin: 0px;" width="100%">
    <center style="background: #f2f2f2; width: 100%; text-align: left;"><!-- Preheader : BEGIN -->
        <!-- <table align="center" aria-hidden="true" border="0" cellpadding="0" cellspacing="0" class="email-container" role="presentation" style="margin: auto; width: 640px;" width="640">
   <tbody>
      <tr>
         <td align="center" dir="ltr" style="padding: 0; vertical-align: middle;" valign="middle" width="100%">
         <table align="center" aria-hidden="true" border="0" cellpadding="0" cellspacing="0" role="presentation" width="100%">
            <tbody>
               <tr>
                  <td align="left" style="padding: 10px 0 10px 10px; margin: 0; font-weight: normal; font-size: 10px ; color: #60605B; font-family: Helvetica, Arial, sans-serif; line-height: 12px; mso-line-height-rule: exactly; text-align: left;" valign="middle"><span>We received a request to reset your password.</span></td>
               </tr>
            </tbody>
         </table>
         </td>
      </tr>
   </tbody>
</table> -->
        <!-- Preheader : END --><!-- Email Body : BEGIN -->

        <table align="center" aria-hidden="true" border="0" cellpadding="0" cellspacing="0" class="email-container"
            role="presentation" style="margin: auto; width: 640px;" width="640">
            <tbody>
                <!-- Header : BEGIN -->
                <tr>
                    <td align="center" bgcolor="#ffffff" dir="ltr" style="padding: 0; vertical-align: middle;"
                        valign="middle" width="100%">
                        <table align="center" aria-hidden="true" border="0" cellpadding="0" cellspacing="0"
                            role="presentation" width="100%">
                            <tbody>
                                <tr><!-- Column : BEGIN -->
                                    <td align="center" dir="ltr"
                                        style="padding: 10px; color: rgb(96, 96, 91); font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 15px;"
                                        valign="top"><a href="https://studentnew.sklpsahmedabad.com/"><img
                                                alt="SKLPS" aria-hidden="true" border="0" class="fluid"
                                                src="{{ asset('assets/images/sklps-logo.png') }}"
                                                style="height: auto; color: rgb(96, 96, 91); font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 15px;"
                                                width="100" /></a></td>
                                    <!-- Column : END -->
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Header : END -->
                <!-- Navigation : BEGIN -->
                <tr>
                    <td align="center" bgcolor="#ffffff" dir="ltr"
                        style="padding: 0 10px; border-bottom: 5px solid #ffb422;" valign="top">
                    </td>
                </tr>
                <!-- Navigation : END -->

                <tr>
                    <td height="0" style="height: 0px; background-color: #f2f2f2;"></td>
                </tr>
                <!-- Heading Text CTA 12 Column : BEGIN -->
                <tr>
                    <td align="center" style="padding: 0" valign="top">
                        <table aria-hidden="true" border="0" cellpadding="0" cellspacing="0" role="presentation"
                            width="100%">
                            <tbody>
                                <tr><!-- Column : BEGIN -->
                                    <td bgcolor="#ffffff" class="stack-column-center" style="vertical-align: top;"
                                        width="65.66%">
                                        <table aria-hidden="true" border="0" cellpadding="0" cellspacing="0"
                                            role="presentation" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td style="vertical-align: top; padding: 30px;">
                                                        <table aria-hidden="true" border="0" cellpadding="0"
                                                            cellspacing="0" role="presentation" width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td align="left"
                                                                        style="vertical-align: top; padding: 0 0 0 0; color: rgb(96, 96, 91); line-height: 24px; font-family: Helvetica, Arial, sans-serif; font-size: 15px;">
                                                                        # Complain is Raised By
                                                                        {{ $studentData->name }}<br />
                                                                        # Hostel: {{ $studentData->hostel_name }}<br />
                                                                        # Room: {{ $studentData->room_number }}<br />
                                                                        # Message: {{ $complain['message'] }}<br />
                                                                        @if ($complain['type'] == 1)
                                                                            @php($type = 'Technical')
                                                                        @elseif($complain['type'] == 2)
                                                                            @php($type = 'System')
                                                                        @else
                                                                            @php($type = 'Management')
                                                                        @endif
                                                                        # Type: {{ $type }}
                                                                    </td>
                                                                </tr>
                                                                {{-- <tr>
                                                                    <td align="left"
                                                                        style="vertical-align: top; padding: 0 0 20px 0; color: rgb(96, 96, 91); line-height: 24px; font-family: Helvetica, Arial, sans-serif; font-size: 15px;">
                                                                        <span>Hi {{ $mailData->name }},<br /><br />
                                                                            <strong>Two Factor Code:</strong>
                                                                            {{ $mailData->two_factor_code }} <br>
                                                                        </span>
                                                                    </td>
                                                                </tr> --}}
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <!-- Column : END -->
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Heading Text CTA 12 Column : END -->
                <tr>
                    <td height="0" style="height: 0px; background-color: #f2f2f2;"></td>
                </tr>
                <!-- Social Icons 1 Column : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff" style="padding: 10px 30px 5px 30px; border-top: 5px solid #ffb422;">
                        <table aria-hidden="true" border="0" cellpadding="0" cellspacing="0"
                            role="presentation" width="100%">
                            <tbody>
                                <tr><!-- Column : BEGIN -->
                                    <td class="stack-column-center hide-on-small" width="33.33%">
                                        <table align="center" aria-hidden="true" border="0" cellpadding="0"
                                            cellspacing="0" role="presentation" style="float: left;" width="100%">
                                            <tbody>
                                                <tr>
                                                    <td class="center-on-narrow" dir="ltr"
                                                        style="text-align: left; font-weight: bold;color: #60605B; line-height: 20px; mso-line-height-rule: exactly; font-family:  Helvetica, Arial, sans-serif; font-size: 12px;"
                                                        valign="top">STAY CONNECTED WITH US:</td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <!-- Column : END -->
                                    <!-- Column : BEGIN -->
                                    <td class="stack-column-center" width="66.66%"><!-- Button : BEGIN -->
                                        <table aria-hidden="true" border="0" cellpadding="0" cellspacing="0"
                                            class="center-on-narrow" role="presentation">
                                            <tbody>
                                                <tr>
                                                    <td style="vertical-align: middle;">
                                                        <table align="center" aria-hidden="true" border="0"
                                                            cellpadding="0" cellspacing="0" role="presentation"
                                                            width="100%">
                                                            <tbody>
                                                                <tr>
                                                                    <td style="width: 10px;">
                                                                        <a href="https://www.facebook.com/">
                                                                            <img alt="Facebook" title="Facebook"
                                                                                aria-hidden="true" border="0"
                                                                                class="fluid"
                                                                                src="{{ asset('assets/images/facebook.png') }}"
                                                                                width="30" height="30">
                                                                        </a>
                                                                    </td>
                                                                    <td style="width: 10px;">
                                                                        <a href="https://www.instagram.com/">
                                                                            <img alt="Instagram" title="Instagram"
                                                                                aria-hidden="true" border="0"
                                                                                class="fluid"
                                                                                src="{{ asset('assets/images/instagram.png') }}"
                                                                                width="30" height="30">
                                                                        </a>
                                                                    </td>
                                                                    <td class="hide-on-small" width="90"
                                                                        height="30"></td>
                                                                </tr>
                                                            </tbody>
                                                        </table>
                                                    </td>
                                                </tr>
                                            </tbody>
                                        </table>
                                    </td>
                                    <!-- Column : END -->
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Social Icons 1 Column : END -->
                <!-- Footer 1 Column : BEGIN -->
                <tr>
                    <td bgcolor="#ffffff" style="padding: 5px 30px 30px 30px;">
                        <table aria-hidden="true" border="0" cellpadding="0" cellspacing="0"
                            role="presentation" width="100%">
                            <tbody>
                                <tr>
                                    <td align="left"
                                        style="padding-top: 10px; font-weight: normal; font-size: 12px ; color:#60605B; font-family:  Helvetica, Arial, sans-serif; line-height: 24px; mso-line-height-rule: exactly;">
                                        <span><strong>Address:</strong> Saint Xavier's School Road, Opp. Kamnath Mahadev
                                            Temple, Naranpura, Ahmedabad - 380013</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left"
                                        style="padding-top: 10px; font-weight: normal; font-size: 12px ; color:#60605B; font-family:  Helvetica, Arial, sans-serif; line-height: 24px; mso-line-height-rule: exactly;">
                                        <span><strong>Contact Info:</strong> M: +91 9099211718 | E-mail:
                                            student@sklpsahmedabad.com</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td align="left"
                                        style="padding-top: 10px; font-weight: normal; font-size: 12px ; color:#60605B; font-family:  Helvetica, Arial, sans-serif; line-height: 24px; mso-line-height-rule: exactly;">
                                        <span> © 2022 Shree Kutchi Leva Patel Samaj - Ahmedabad. All rights
                                            reserved.</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </td>
                </tr>
                <!-- Footer 1 Column : END -->
            </tbody>
        </table>
        <!-- Email Body : END -->
    </center>
</body>

</html>
