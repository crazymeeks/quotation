<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="csrf-token" content="{{ csrf_token() }}">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=0, minimal-ui">
        <title>AURORA PHILS. INC</title>
        <meta content="Admin Dashboard" name="description" />
        <meta content="Mannatthemes" name="author" />
        <meta http-equiv="X-UA-Compatible" content="IE=edge" />

        <link rel="shortcut icon" href="/assets/images/favicon.ico">

        <link href="/assets/css/bootstrap.min.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/icons.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/style.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/toastr.min.css" rel="stylesheet" type="text/css">
        <link href="/assets/css/admin.css?v={{uniqid()}}" rel="stylesheet" type="text/css">
        @yield('css')
        <style>
            .badge-medium {
                font-size: 17px;
            }
            .badge-small {
                font-size: 14px;
            }
        </style>
    </head>