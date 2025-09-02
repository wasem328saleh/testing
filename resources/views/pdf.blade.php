<!DOCTYPE html>
<html lang="en">

<head>
    <meta name="description" content="Webpage description goes here" />
    <meta charset="utf-8">
    <title>Change_me</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="author" content="">
    <link rel="stylesheet" href="css/style.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
</head>

<body>

<div class="container">
    <form action="{{ route('generate-pdf') }}" method="POST">
        @csrf
        <textarea name="text" rows="10" cols="50"></textarea>
        <button type="submit">تحويل إلى PDF</button>
    </form>
</div>

<script>
</script>

</body>
</html>
