<?php

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if($_POST['deleteId'] != null){
            deleteSite($_POST['deleteId']);
        }
        if($_POST['id'] != null){
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

            $sql = 'SELECT * FROM ysite_info where id = '. $_POST['id'];
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                // output data of each row
                while($row = $result->fetch_assoc()) {
                    echo '<div id="change_modal'.$row['id'].'" class="change_modal" style="display: block">
                            <!-- Modal content -->
                            <div class="change_modal_content">
                                <span style="font-size: 35px; padding-left: 97%" class="close'.$row['id'].'">&times;</span>
                                <form method="post" action="'.$_SERVER['PHP_SELF'].'">
                                    <label for="address">Адрес сайта</label>
                                    <input type="text" name="siteName" value="'.$row['site_name'].'" ><br><br>
                                    <label for="kn">Коэффициент накрутки</label>
                                    <input type="number" name="KN" value="'.$row['nakrutka_coefficient'].'" ><br><br>
                                    <label for="days">Число дней выгрузки ключей</label>
                                    <input type="number" name="VK" value="'.$row['interval_in_days'].'" ><br><br>
                                    <label for="frequency">Минимальная частота</label>
                                    <input type="number" name="MCH" value="'.$row['minimal_frequency'].'" ><br><br>
                                    <label for="active">Активный</label>
                                    <input type="checkbox" name="isActive" '; if ($row['is_active'] == 1) { echo "checked='checked'"; } echo ' >
                                    <input type="hidden" name="isAdd" value="0"><br><br>
                                    <input type="hidden" name="site_id" value="'.$row['id'].'"><br><br>
                                    <button type="submit"> Изменить </button>
                                </form>
                                <form method="post" action="'.$_SERVER['PHP_SELF'].'">
                                            <input type="hidden" name="deleteId" value="'.$row['id'].'" >
                                            <button type="submit"> Удалить </button>
                                    </form>
                            </div>
                        </div>';
                    echo '<script>
                            var modal2 = document.getElementById("change_modal'.$row['id'].'");

                            var span1 = document.getElementsByClassName("close'.$row['id'].'")[0];
                            // When the user clicks on <span> (x), close the modal
                            span1.onclick = function() {
                                modal2.style.display = "none";
                            }
                        </script>';
                }
            } else {
                echo "0 results";
            }
            $conn->close();

        }

        if ($_POST['site_id'] != null){
            $siteName = $_POST['siteName'];
            $siteId = $_POST['site_id'];
            $KN = $_POST['KN'];
            $VK = $_POST['VK'];
            $MCH = $_POST['MCH'];
            $isActive = $_POST['isActive'];
            $isActive = ($isActive == true ? 1 : 0);
            updateSite($siteId, $siteName, $KN, $VK, $MCH, $isActive);
        }

        if($_POST['deleteId'] == null && $_POST['id'] == null && $_POST['site_id'] == null && $_POST['stat'] == null){
            $siteName = $_POST['siteName'];
            $KN = $_POST['KN'];
            $VK = $_POST['VK'];
            $MCH = $_POST['MCH'];
            $isActive = $_POST['isActive'];
            $isActive = ($isActive == true ? 1 : 0);
            addSite($siteName, $KN, $VK, $MCH, $isActive);
        }
    }


    function addSite($siteName, $KN, $VK, $MCH, $isActive){
        $servername = "127.0.0.1:3306";
        $username = "root";
        $password = "mysql";
        $dbname = "yandex_metrika";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "INSERT INTO ysite_info (site_name, interval_in_days, nakrutka_coefficient, minimal_frequency, is_active)
VALUES ('$siteName' , '$VK' , '$KN', '$MCH', '$isActive')";

        if ($conn->query($sql) === TRUE) {
            echo "";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }

    function getActiveSites(){
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

        $sql = 'SELECT * FROM ysite_info where is_active = 1';
        $result = $conn->query($sql);
        $isActiveSites = array();
        if ($result->num_rows > 0) {
            // output data of each row
            while ($row = $result->fetch_assoc()) {
                $isActiveSites[] = $row;
            }
        }
        return $isActiveSites;
    }

    function updateSite($id, $siteName, $KN, $VK, $MCH, $isActive){
        $servername = "127.0.0.1:3306";
        $username = "root";
        $password = "mysql";
        $dbname = "yandex_metrika";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "UPDATE ysite_info SET site_name='$siteName', interval_in_days = '$VK', nakrutka_coefficient = '$KN', minimal_frequency = '$MCH', is_active = '$isActive' WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }

    function deleteSite($id){
        $servername = "127.0.0.1:3306";
        $username = "root";
        $password = "mysql";
        $dbname = "yandex_metrika";

        $conn = new mysqli($servername, $username, $password, $dbname);
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $sql = "DELETE FROM ysite_info WHERE id='$id'";

        if ($conn->query($sql) === TRUE) {
            echo "";
        } else {
            echo "Error: " . $sql . "<br>" . $conn->error;
        }

        $conn->close();
    }
    ?>