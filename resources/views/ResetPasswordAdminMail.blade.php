<!DOCTYPE html>
<html lang="en">
<head>

    <style>
        body {
            font-family: "Inter", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #e0e0e0;
            width: 100%;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #ffffff;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .img-container {

            display: flex;
            justify-content: center;
            align-items: center;
            margin: auto;
        }
        .contant {
            width: 70%;
            margin: auto;
        }
        .img {
            width: 20%;
            margin: 20px auto;
        }
        h4 {
            font-size: 50px;
            text-align: center;
            font-weight: lighter;
            font-style: normal;
            color: #111827;
            margin-bottom: 8px;
            margin-top: 8px;
            /* background: #111827; */
        }
        .rectangle {
            margin: 8px;
            width: 5%;
            color: #007bff;
            height: 4px;
            border-radius: 50px;
            background-color: #397ff5;
        }
        h5 {
            color: #131313;
            line-height: 1.6;
        }
        h6 {
            color: #131313;
            line-height: 1.6;
        }
        p {
            color: #555;
            line-height: 1.6;
        }
        .footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }

        .social-icons {
            padding-top: 40px;
            text-decoration: none;
            padding-bottom: 10px;
            border-top: #555 solid 1px;
            margin: 0px;
        }
        .social-icons a {
            margin: 0 10px;
            text-decoration: none;
            width: 50%;
        }
        .link {
            color: #397ff5;
            text-decoration: none;
        }

        .footer-text {
            text-decoration: none;
            margin: 2px;
            font-size: 14px;
        }
        .footer-text a {
            text-decoration: none;
            color: #397ff5;
        }
        .web-btn {
            background-color: #111827;

            padding: 8px 28px;
            color: white;
            border-radius: 8px;
            text-decoration: none;
        }
        .bt-verify{
            padding: 8px 20px;
            margin:10px auto;
            width: 40%;
            height: 10%;
            background-color: #007bff;
            border: none;
            border-radius:6px;
            color: #ffffff;
            font-size: 16px;
            text-align: center;

            display: flex;
            justify-content: center;
            align-items: center;


        }
        @media screen and (max-width: 600px) {
            h4 {
                font-size: 30px;
            }
            p {
                font-size: 14px;
            }
            h5 {
                font-size: 14px;
            }
        }

    </style>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Welcome Email</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&family=Noto+Kufi+Arabic:wght@100..900&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap/5.0.1/css/bootstrap.min.css" integrity="sha512-Ez0cGzNzHR1tYAv56860NLspgUGuQw16GiOOp/I2LuTmpSK9xDXlgJz3XN4cnpXWDmkNBKXR/VDMTCnAaEooxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <!-- fontawesome 6 stylesheet -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.3.0/css/all.min.css" integrity="sha512-SzlrxWUlpfuzQ+pcUCosxcglQRNAq/DZjVsC0lE40xsADsfeQoEypE+enwcOiGjk/bSuGGKHEyjSoQ1zVisanQ==" crossorigin="anonymous" referrerpolicy="no-referrer" />
</head>
<body>
<div class="container">
    <div class="img-container">
        <img
            src="http://vegus.blogs.lino5271.odns.fr/logo.png"
            alt="logo"
            class="img"
        />
    </div>

    <div class="contant">

        <p><strong>Dear {{$full_name}}</strong></p>

        <p>
            In response to your request, here is a password reset link for your Vegus Code account
        </p>

        <p>
            To complete the reset process, please follow these steps:
        </p>
        <p>
            Visit the following link.
        </p>
        <p>Choose a strong, new password.</p>
        <p>
            Confirm your new password.
        </p>

        <button class="bt-verify" ><a href="{{$url}}" style="color: #ffffff; text-decoration: none;">
                verify
            </a></button>

        <p>Important Notes:</p>
        <p> To keep your account secure, please choose a unique and complex password that includes a combination of uppercase and lowercase letters, numbers, and symbols.
            Avoid using easily guessable passwords such as your birthdate or pet's name.</p>
        <p>        If you encounter any issues, please do not hesitate to contact our customer support team at email <a style="color:#007bff; text-decoration: none;" href="mailto:support@veguscode.net">support@veguscode.net</a></p>

        <h5>Cheers,<br />vegus code team</h5>
    </div>

    <div class="footer">
        <!-- <a class="web-btn" href="https://veguscode.net/">visit our website</a> -->

        <div class="social-icons">
            <a href="http://veguscode.net">
                <img width="20" height="20" src="http://vegus.blogs.lino5271.odns.fr/icons/instagram.png"/>
            </a>   <a href="#">
                <img width="20" height="20" src="http://vegus.blogs.lino5271.odns.fr/icons/Facebook.png"/>
            </a>   <a href="#">
                <img width="20" height="20" src="http://vegus.blogs.lino5271.odns.fr/icons/whatsapp.png"/>
            </a>   <a href="#">
                <img width="20" height="20" src="http://vegus.blogs.lino5271.odns.fr/icons/linkedin.png"/>
            </a>

        </div>
        <p class="footer-text">

            <span>&copy;</span> <a href="http://veguscode.net">
                veguscode.net
            </a> - All rights reserved.
    </div>
</div>
</div>
</body>
</html>
