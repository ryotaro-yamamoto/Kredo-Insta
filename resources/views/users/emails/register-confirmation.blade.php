<!DOCTYPE html>
<html lang="ja">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
</head>
<body>
  <div style="padding: 10px">
    <h4 style="text-align: center; font-weight: bold;">Welcome to Insta app!</h4>
    <hr>
    <p style="font-weight: bold; color: #6c757d;">Hello {{$name}},</p>
    <p style="color: #6c757d;">Thank you for signing up to Insta app. We're excited to have you on board!</p>
    <p style="color: #6c757d;">To get started, please access the website <a href="{{$app_url}}">here</a>.</p>
    <p style="color: #6c757d;">Best regards,</p>
    <p style="color: #6c757d;">The {{ config('mail.from.name') }}</p>
    <br>
    <p style="color: gray; font-size: small; font-style: italic;">If you did not sign up for this account, you can ignore this email.</p>
    <hr>
    <p style="color: gray; font-size: small; text-align: center;">&copy; 2024 Kredo Insta App. All rights reserved.</p>
  </div>