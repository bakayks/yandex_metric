<?php
include 'main.php';
?>
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	<title>Парсинг данных</title>
	<link rel="stylesheet" type="text/css" href="static/main.css">
</head>
<body>
<div id="add_modal" class="change_modal">

    <!-- Modal content -->
    <div class="change_modal_content">
        <span class="close">&times;</span>
        <form method="post" action="<?php echo $_SERVER['PHP_SELF'];?>">
            <label for="address">Адрес сайта</label>
            <input type="text" name="siteName" placeholder="https://example.com" required><br><br>
            <label for="kn">Коэффициент накрутки</label>
            <input type="number" name="KN" value="1" required><br><br>
            <label for="days">Число дней выгрузки ключей</label>
            <input type="number" name="VK" value="7" required><br><br>
            <label for="frequency">Минимальная частота</label>
            <input type="number" name="MCH" value="3" required><br><br>
            <label for="active">Активный</label>
            <input type="checkbox" name="isActive">
            <button type="submit"> Добавить </button>
        </form>
    </div>

</div>
<div class="main">
	<div class="panel">
		<div class="button_class">
            <?php
                echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post">
                    <input type="hidden" name="stat" value="stat">
                    <button type="submit" class="button">Собрать статистику</button>
                </form>';
            ?>

		</div>
		<div class="button_class">
			<button class="button add_site" id="add_site">Добавить сайт</button>
		</div>
	</div>
	<div class="content">
        <?php
        $servername = "127.0.0.1:3306";
        $username = "root";
        $password = "mysql";
        $dbname = "yandex_metrika";

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);
            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $sql = "SELECT id, site_name FROM ysite_info";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<form action="'.$_SERVER['PHP_SELF'].'" method="post"><input type="hidden" name="id" value="'.$row['id'].'"><button class="site_class" data-id="'.$row['id'].'" onclick="change_site(this)">'.$row['site_name'].'</button></form>';
                }
            } else {
                echo "0 results";
            }
            $conn->close();
        ?>

	</div>
</div>


<script>
    // Get the modal
    var modal = document.getElementById("add_modal");

    var add_site = document.getElementById("add_site");

    // Get the <span> element that closes the modal
    var span1 = document.getElementsByClassName("close")[0];
    // When the user clicks on <span> (x), close the modal
    span1.onclick = function() {
        modal.style.display = "none";
    }


    add_site.onclick = function() {
        modal.style.display = "block";
    }

</script>
<script
        src="https://code.jquery.com/jquery-3.6.0.js"
        integrity="sha256-H+K7U5CnXl1h5ywQfKtSj8PCmoN9aaq30gDh27Xc0jk="
        crossorigin="anonymous"></script>
</body>
</html>