<?php

    require_once "shared/db.php";

    global $idxToEdit;
    $idxToEdit = -1;

    $newEntry = false;


    if (isset($_POST['submit']) ) {
        $suchbegriff = $_POST['suchbegriff'];
    
        $sql = "select PersonID, LastName, FirstName, Address, City from db1.Persons
                WHERE LastName LIKE '%$suchbegriff%' OR Address LIKE '%$suchbegriff%' OR City LIKE '%$suchbegriff%'";
        $result = $conn->query($sql);
    } else {
        $sql = "select PersonID, LastName, FirstName, Address, City from db1.Persons";
        $result = $conn->query($sql);
    }

    if (isset($_POST['newEntry']) ) {
        $newEntry = true;
    } 

    if (isset($_POST['newEntrySave']) ) {
        $personID = $_POST['personID'];
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $address = $_POST['address'];
        $city = $_POST['city'];

        $sql = "insert into db1.persons (PersonID, LastName, FirstName, Address, City) 
        values($personID, '$lastName', '$firstName', '$address', '$city')";

        $conn->query($sql); 

        $sql = "select PersonID, LastName, FirstName, Address, City from db1.Persons";
        $result = $conn->query($sql);
    } 

    if (isset($_POST['updateEntrySave']) ) {
        $personID = $_POST['personID'];
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $address = $_POST['address'];
        $city = $_POST['city'];

        echo $personID;

        $sql = "UPDATE db1.persons
        SET LastName='$lastName', FirstName='$firstName', Address='$address', City='$city'
        WHERE PersonID = $personID";


        // $sql = "insert into db1.persons (PersonID, LastName, FirstName, Address, City) 
        // values($personID, '$lastName', '$firstName', '$address', '$city')";

        $conn->query($sql); 

        $idxToEdit = -1;

        $sql = "select PersonID, LastName, FirstName, Address, City from db1.Persons";
        $result = $conn->query($sql);

        header("Refresh:0, url=index.php");

    } 


    if (isset($_GET['edit'])) {

        $temp = $_GET['edit'];

        if($idxToEdit === -1){
            $idxToEdit = (int)$temp;
        }
        
    }

    if (isset($_GET['delete'])) {
        $personID = (int)$_GET['delete'];

        $sql = "DELETE FROM db1.Persons
        WHERE PersonID = $personID;";

        echo $sql;

        $conn->query($sql); 


        $sql = "select PersonID, LastName, FirstName, Address, City from db1.Persons";
        $result = $conn->query($sql);

        header("Refresh:0, url=index.php");
    }
    

    

?>



<!DOCTYPE html>
<html lang="en" data-bs-theme="light">
    <head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js" integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz" crossorigin="anonymous"></script>
    <title>Document</title>
    </head>

    <!-- <nav class="navbar navbar-expand-lg bg-dark-subtle">
        <div class="container-fluid">
            <a class="navbar-brand" href="#"><span><img style="max-height: 30px; margin-right: 20px;"src="https://img.icons8.com/ios/250/FFFFFF/source-code.png"></img></span>LAP Praktische</a>
            </button>
            <div class="collapse navbar-collapse" id="navbarSupportedContent">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                <a class="nav-link active" aria-current="page" href="..\HTML\index.html">Home</a>
                </li>
                <li class="nav-item">
                <a class="nav-link" href="..\HTML\AddEntryPage.html">Add Entry</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="DeleteAndEdit.php">Delete/Edit</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="GetAllEntries.php">Get All Entries</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" href="SearchEntry.php">Search</a>
                </li>
                
            </ul>
            </div>
        </div>
    </nav> -->

    <body>
        <div class="container text-center">
            <div style="height: 30px;"></div>
            <div class="jumbotron">
                <h2 class="display-3">Top Table</h2>
                <div style="height: 30px;"></div>
                <p class="lead">Alles tippi toppi/Topi</p>
                <hr class="my-2">
            </div>

            <div style="display: flex; align:center; align-items:center; justify-content:center; margin-top:40px;">
                <form method="POST" action="">
                    <input type="text" name="suchbegriff">
                    <input type="submit" name="submit" value="Search">
                </form>

                <form method="POST" action="" style="margin-left: 50px;">
                    <input type="submit" name="newEntry" value="New Entry">
                </form>
            </div>



            <div style="height: 50px;"></div>
            <table class="table">
                <thead>
                    <tr>
                        <th>PersonID</th>
                        <th>LastName</th>
                        <th>FirstName</th>
                        <th>Address</th>
                        <th>City</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $idx = 0;
                    while ($row = mysqli_fetch_assoc($result)) { 
                        if($idx === $idxToEdit){
                            ?>
                                <form method="POST" action="">
                                    <tr>
                                        <!-- because input value from disabled field doesn't work, this hidden field exists -->
                                        <td><input type="text" value="<?php echo $row['PersonID']; ?>"disabled></td>                                      
                                        <input type="hidden" name="personID"value="<?php echo $row['PersonID']; ?>">
                                        <td><input type="text" name="lastName" value=<?php echo $row['LastName']; ?>></td>
                                        <td><input type="text" name="firstName" value=<?php echo $row['FirstName']; ?>></td>
                                        <td><input type="text" name="address" value=<?php echo $row['Address'];?>></td>
                                        <?php echo $row['City'] ?>
                                        <td><input type="text" name="city" value="<?php echo $row['City'];?>"></td>
                                        <td>
                                            <input class="btn btn-primary" type="submit" name="updateEntrySave" value="Save">
                                        </td>
                                    </tr>
                                </form>
                            <?php

                        }
                        else{
                            ?>
                                <tr>
                                    <td><?php echo $row['PersonID']; ?></td>
                                    <td><?php echo $row['LastName']; ?></td>
                                    <td><?php echo $row['FirstName']; ?></td>
                                    <td><?php echo $row['Address']; ?></td>
                                    <td><?php echo $row['City']; ?></td>
                                    <td>                               
                                        <a class='btn btn-secondary m-1' href='?edit=<?php echo $idx ?>'>Edit</a> 
                                        <a class='btn btn-danger' href='?delete=<?php echo $row["PersonID"]?>' >Delete</a>
                                    </td>
                                </tr>
                            <?php
                        }
                            ?>
                        

                    <?php $idx++; } ?>
                    <?php if($newEntry){ ?>
                        <form method="POST" action="">
                            <tr>
                                <td><input type="text" name="personID"></td>
                                <td><input type="text" name="lastName"></td>
                                <td><input type="text" name="firstName"></td>
                                <td><input type="text" name="address"></td>
                                <td><input type="text" name="city"></td>
                                <td>
                                    <input class="btn btn-primary" type="submit" name="newEntrySave" value="Save">
                                </td>
                            </tr>
                        </form>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </body>

</html>