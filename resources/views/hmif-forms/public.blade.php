<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ $form->title }}</title>
</head>
<body style="margin:0">
    <x-hmif-forms.respondent-form :form="$form" :unavailable-message="$unavailableMessage" />
</body>
</html>
