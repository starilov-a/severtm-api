<?php

#require(dirname(__FILE__)."/main.php");
#require_once( $_SERVER [ 'DOCUMENT_ROOT' ] . "/init_pdo.php");
#global $d_;

// Задаем ограничение на использование оперативной памяти (Георгий)
ini_set("memory_limit", '64M');

#if (!$acSession->checkAccount())
#{
#    header("Location: index.php");
#    exit;
#}


if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
    $body = file_get_contents('php://input');
    file_put_contents('./data/dashboard.json', $body);
    die;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    header('Content-Type: application/json; charset=utf-8');
    $res = file_get_contents('./data/dashboard.json');
    echo $res;
    die;
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css"
          integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">

</head>

<body class="bg-light">
<div class="modal fade" id="myModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="form-group">
                    <label for="branchId">Номер ветки</label>
                    <input type="email" class="form-control" id="branchId">
                </div>
                <div class="form-group">
                    <label for="userName">Кто тестирует</label>
                    <select class="form-control" id="userName">
                        <option value="Alex" selected>Alex</option>
                        <option value="Anton S.">Anton S.</option>
                        <option value="Viktor">Viktor</option>
                        <option value="Max">Max</option>
                    </select>
                </div>
                <div class="custom-control custom-switch">
                    <input type="checkbox" class="custom-control-input" id="free">
                    <label class="custom-control-label" for="free">Свободен</label>
                </div>

            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-success" onclick="saveChange(targetId)">Save changes</button>
            </div>
        </div>
    </div>
</div>


<div class="container mx-auto p-5 m-5 rounded border">
    <table class="table table-striped table-bordered ">
        <thead class="thead-dark">
        <tr>
            <th scope="col">Cтенд (коротко)</th>
            <th scope="col">Стенд</th>
            <th scope="col">Номер ветки/задачи</th>
            <th scope="col">Пользователь</th>
            <th scope="col">Дата обновления</th>
            <th scope="col">Освободил</th>
            <th scope="col">Действия</th>
        </tr>
        </thead>
        <tbody id="t-body">
        </tbody>
    </table>
</div>


<script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj"
        crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct"
        crossorigin="anonymous"></script>

<script>
    let dashBoard = [];
    let targetId = false;

    // renderDashboard(dashBoard);
    getDashboard();

    function renderDashboard(data) {
        let table = $(`#t-body`);
        data.forEach((row, index) => {
            let free = row.is_free;
            let tr = `
             <tr class=${free ? 'table-success' : 'table-danger'}>
                <td>${row.short_title}</td>
                <th scope="row">${row.title}</th>
                <td>${row.branch}</td>
                <td>${row.user}</td>
                <td>${row.date_change}</td>
                <td>${free ? 'свободен' : "занят"}</td>
                <td>
                    <button type="button" class="btn btn-outline-success" onclick="renderModal(${index})">
                        &#9998
                    </button>
                </td>
             </tr>
            `;
            table.append(tr);
        })
    }

    function saveChange(index) {
        let date = new Date();
        let date_change = `${date.getFullYear()}-${date.getMonth()}-${date.getDate()} ${date.getHours()}:${date.getMinutes().toString().padStart(2, '0')}`;
        dashBoard[index].date_change = date_change;
        dashBoard[index].branch = $(`#branchId`).val();
        dashBoard[index].user = $(`#userName`).val();
        dashBoard[index].is_free = $('#free').prop('checked');
        $('#myModal').modal('toggle');
        updateDashboard(dashBoard);
        clearDashboard();
        renderDashboard(dashBoard);
    }

    function renderModal(index) {
        targetId = index;
        $(`#branchId`).val(dashBoard[index].branch);
        $(`#userName`).val(dashBoard[index].user);
        $('#free').prop('checked', dashBoard[index].is_free);
        $('#myModal').modal('toggle');
    }

    function getDashboard() {
        fetch('/dashboard.php', {
            method: "POST",
            body: JSON.stringify()
        }).then(i => i.json()).then(data => {
            renderDashboard(data);
            dashBoard = data;
        });
    }

    function updateDashboard(data) {
        fetch('/dashboard.php', {
            method: "PUT",
            body: JSON.stringify(data)
        }).then(i => i.json).then(i => console.log(i));
    }

    function clearDashboard() {
        $(`#t-body`).html('');
    }

        console.log(
        '%c🚀 ' + '%cСсылка на список стендов',
        'color: #ff4757; font-size: 16px;',
        'color: #2ed573; font-weight: bold;',
        '\nhttp://<?=$_SERVER['HTTP_HOST'];?>/dashboard.php'
        );
</script>
</body>

</html>
