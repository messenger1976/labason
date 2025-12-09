<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload CSV</title>
</head>
<body>
    <h2>Upload CSV File</h2>
    <form action="import.php" method="post" enctype="multipart/form-data">
        <input type="file" name="csv_file" accept=".csv" required>
        <button type="submit" name="upload">Upload & Import</button>
    </form>
</body>
</html>
