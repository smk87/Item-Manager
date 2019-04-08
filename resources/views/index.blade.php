<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Item Manager</title>
    <link rel="stylesheet" href="https://bootswatch.com/4/simplex/bootstrap.min.css">
</head>

<body>
    <nav class="navbar navbar-expand-md navbar-dark bg-dark">
        <a class="navbar-brand" href="#">Item Manager</a>
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarCollapse"
            aria-controls="navbarCollapse" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarCollapse">
            <ul class="navbar-nav mr-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/">Home </a>
                </li>
            </ul>
        </div>
    </nav>

    <div class="container" style="margin-top: 2%">
        <h1>Add Item</h1>
        <form action="" id="itemForm">
            <div class="form-group">
                <label>Text</label>
                <input type="text" id="text" class="form-control">
            </div>
            <div class="form-group">
                <label>Body</label>
                <textarea id="body" class="form-control"></textarea>
            </div>
            <input type="hidden" id="upid">
            <input type="submit" value="Submit" class="btn btn-primary">
            <a href="#" id="update" class="btn btn-info">Update</a>
        </form>
        <br />
        <hr />
        <ul id="items" class="list-group"></ul>
    </div>



    <script src="https://code.jquery.com/jquery-1.12.4.min.js"
        integrity="sha256-ZosEbRLbNQzLpnKIkEdrPv7lOy9C27hHQ+Xp8a4MxAQ=" crossorigin="anonymous"></script>

    <script type="text/javascript">
        $(document).ready(function () {
            getItems();

            // Submit event
            $('#itemForm').on('submit', function (e) {
                e.preventDefault();

                let text = $('#text').val();
                let body = $('#body').val();

                // console.log(text, body);
                addItem(text, body);
            });

            //Delete event
            $('body').on('click', '.btn.btn-danger', function (e) {
                e.preventDefault();

                let id = $(this).data('id');
                deleteItem(id);
            });

            // Delete item through api
            function deleteItem(id) {
                $.ajax({
                    method: 'POST',
                    url: 'http://lva.com/api/items/' + id,
                    data: {
                        _method: 'DELETE'
                    }
                }).done(function (item) {
                    alert(`Item #${id} Deleted.`);
                    location.reload();
                })
            }

            // Insert Items using api
            function addItem(text, body) {
                $.ajax({
                    method: 'POST',
                    url: 'http://lva.com/api/items',
                    data: {
                        text: text,
                        body: body
                    }
                }).done(function (item) {
                    alert(`Item #${item.id} Added.`);
                    location.reload();
                })
            }

            //Update event
            $('body').on('click', '.btn.btn-success', function (e) {
                e.preventDefault();

                let id = $(this).data('id');
                updateItem(id);
            });

            $('#update').on('click', function (e) {
                e.preventDefault();
                var id = $('#upid').val();

                $.ajax({
                    method: 'POST',
                    url: 'http://lva.com/api/items/' + id,
                    data: {
                        text: $('#text').val(),
                        body: $('#body').val(),
                        _method: 'PUT'
                    }
                }).done(function (item) {
                    alert(`Item #${id} Updated.`);
                    location.reload();
                })

            })

            // Update Item using api
            function updateItem(id) {
                $.ajax({
                    url: 'http://lva.com/api/items/' + id
                }).done(function (item) {
                    $('input[id="text"]').val(item.text);
                    $('#body').val(item.body);
                    $('#upid').val(id);
                })
            }

            // Get Items from api
            function getItems() {
                $.ajax({
                    url: 'http://lva.com/api/items'
                }).done(function (items) {
                    let output = '';
                    $.each(items, function (key, item) {
                        output += `
                        <li class="list-group-item">
                        <strong>${item.text}: </strong>${item.body}
                        <a href="#" class="btn btn-danger" data-id="${item.id}">Delete</a>
                        <a href="#" class="btn btn-success" data-id="${item.id}">Edit</a>
                        </li>`
                    });

                    $('#items').append(output);
                })
            }
        })

    </script>
</body>

</html>
