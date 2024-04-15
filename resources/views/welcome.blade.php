<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Image</title>
</head>
<body>
<form action="/extract-text" method="POST" enctype="multipart/form-data">
    @csrf
    身分證正反面照片:
    <input type="file" name="idFrontImage" required>
    <input type="file" name="idBackImage" required>
    <button type="submit">驗證</button>
</form>
</body>
</html>
