<?php
    include 'dbconnect.php';

    $stmt = $con->prepare("SELECT * FROM url");
    $stmt->execute();
    $url_row = $stmt->fetch();
    $url_count = $stmt->rowCount();

    $old_url = $url_row['url'];
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['url'])) {
        $url = $_POST['url'];
        if ($old_url != $url) {
            
            $stmt = $con->prepare("UPDATE url SET UsersCount = 0");
            $stmt->execute();

            $stmt = $con->prepare("SELECT * FROM url");
            $stmt->execute();
            $url_row = $stmt->fetch();

            $stmt = $con->prepare("TRUNCATE `share`.`users`; INSERT INTO users(UserAgent,MAC,Device) VALUES ('___','___','___')");
            $stmt->execute();

        }
        if ($url_count == 0) {
            $stmt = $con->prepare("INSERT INTO url(url) VALUES (?);");
            $stmt->execute(array($url));
        } else {
            $stmt = $con->prepare("UPDATE url SET url = ?");
            $stmt->execute(array($url));
        }
    }

    $stmt = $con->prepare("SELECT * FROM users");
    $stmt->execute();
    $rows = $stmt->fetchAll();

    if (empty($rows) == TRUE) {
        $stmt = $con->prepare("INSERT INTO users(UserAgent,MAC,Device) VALUES ('___','___','___')");
        $stmt->execute();
    }

    // Upload Files To Folder Uploads/
    if (isset($_POST["submit"])) {
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
        $files_count = (count(glob("$target_dir/*")));
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            $msg = "The file " . basename($_FILES["fileToUpload"]["name"]) . " has been uploaded.";
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Control</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
        <div class="container">
        <h2 class="text-center">WEB REDIRECT</h2>
            <form class="form-group" action="control.php" method="POST">
                <div class="input-flex">
                    <label class="col-form-label col-form-label-lg" for="inputLarge">URL</label>
                    <input class="form-control form-control-lg" type="url" name="url" placeholder="Type URL" id="url" value="<?php if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['url'])) {echo $_POST['url'];} else {echo $url_row['url'];}?>" required>
                </div>
                <div class="users-count" title="Views"><?php if ($url_count == 0) {echo 0;} else {echo $url_row['UsersCount'];} ?></div>
                <input class="btn btn-info btn-lg" type="submit" value="Send" id="url-btn">
            </form>

            <table class="table table-hover main-table text-center table-bordered">
                <tr class="table">
                    <th scope="col">N</th>
                    <th scope="col">User Agent</th>
                    <th scope="col">MAC</th>
                    <th scope="col">Device</th>
                    <th scope="col">Date</th>
                </tr>
                <?php
                $stmt = $con->prepare("SELECT * FROM users");
                $stmt->execute();
                $n_rows = $stmt->fetchAll();
                
                foreach ($n_rows as $tbl_row) {
                    echo '<tr>';
                        if ($tbl_row['UserAgent'] == '___') {
                            echo '<td id="user-id">___</td>';
                        } else {
                            echo '<td id="user-id">' . $tbl_row['UserID'] . '</td>';
                        }
                                   
                        echo '<td id="user-agent">' . $tbl_row['UserAgent'] . '</td>';
                        echo '<td id="mac">' . $tbl_row['MAC'] . '</td>';
                        echo '<td id="device">' . $tbl_row['Device'] . '</td>';

                        if ($tbl_row['UserAgent'] == '___') {
                            echo '<td id="date">___</td>';
                        } else {
                            echo '<td id="date">' . $tbl_row['Date'] . '</td>';
                        }
                    echo '</tr>';
                }
                ?>
            </table> 

            <form class="form-group" action="control.php" method="POST" enctype="multipart/form-data">
                <div class="input-group mb-3">
                    <div class="custom-file">
                        <input type="file" class="custom-file-input" name="fileToUpload" id="fileToUpload" required>
                        <label class="custom-file-label col-form-label-lg" for="fileToUpload">Choose file</label>
                    </div>
                </div>
                <input class="btn btn-info btn-lg file-submit" type="submit" name="submit" value="Upload">
            </form>
            <?php
                if (isset($_POST["submit"]) && $_FILES["fileToUpload"]["name"] !== '') {
                    echo '<iframe src="uploads/"></iframe>';
                } elseif (isset($_POST["submit"]) && $files_count !== 0) {
                    echo '<iframe src="uploads/"></iframe>';
                }      
            ?>
        </div>
        <script src="js/main.js"></script>
    </body>
</html>
