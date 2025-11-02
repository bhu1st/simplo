<!DOCTYPE html>
<html lang="en">
<head>
    <title>Simplo Form</title>
    <style>body { font-family: sans-serif; margin: 2em; } input { padding: 5px; margin-bottom: 10px; width: 200px; }</style>
</head>
<body>
    <h1>My Form</h1>
    <form action="<?php echo base_url('handle-form'); ?>" method="POST">
        <!-- The `old()` helper will repopulate the field on error -->
        <label for="name">Your Name:</label><br>
        <input type="text" id="name" name="name" value="<?php echo htmlspecialchars(old('name')); ?>"><br>

        <button type="submit">Submit</button>
    </form>
</body>
</html>