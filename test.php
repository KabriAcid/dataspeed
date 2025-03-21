<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bottom Navbar</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
    <style>

    </style>
</head>

<body>

    <form class="container">
        <input type="number" id="number" class="form-control mb-3">
        <button type="submit" class="btn btn-dark" id="submit">Submit</button>
    </form>
    <p id="message"></p>


    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">

    <script>
        document.getElementById('submit').addEventListener('click', function(e) {
            e.preventDefault();
            
            const message = document.getElementById('message');
            const number = document.getElementById('number').value;
            console.log("You entered number" + " " + number);
        })
    </script>
</body>

</html>