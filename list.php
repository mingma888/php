<?php
        
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "mingma";
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function set($conn,$city){
    $date=date('Y-m-d');
    
    $city_encoded = urlencode($city); // encode city name
    
    $api="https://api.openweathermap.org/data/2.5/weather?q=".$city_encoded."&units=metric&appid=64193f2f918a240dc55542752d5e3706";
    $json_data = file_get_contents($api);
    $response = json_decode($json_data,true);
    $name=$response["name"];
    $temperature=$response["main"]["temp"];
    $humidity=$response["main"]["humidity"];
    $code="SELECT * FROM weather WHERE city='$name' AND date='$date';";
    $test = $conn->query($code);
    if (mysqli_num_rows($test)==1){
        $code1="UPDATE weather SET `city`='$city',`date`='$date',`temperature`=$temperature,`humidity`=$humidity WHERE city='$city' AND date='$date';";
        $conn->query($code1);
    }else{
        $code2="INSERT INTO `weather`(`city`, `date`, `temperature`, `humidity`) VALUES ('$city','$date',$temperature,$humidity)";
        $conn->query($code2);
    }
}

        
    


function get($conn, $city) {
    $code11 = "SELECT * FROM weather WHERE city='$city' ORDER BY date DESC;";
    $result = $conn->query($code11);

    $len = mysqli_num_rows($result);

    if ($len >= 7) {
        echo "<h1>Past week weather detail of $city:</h1>
            <table>
                <tr>
                    <th>Date</th>
                    <th>Temperature</th>
                    <th>Humidity</th>
                </tr>";
        $count = 0;
        while ($count < 7 && $row = $result->fetch_assoc()) {
            echo "<tr>
                    <td>".$row["date"]."</td>
                    <td>".$row["temperature"]."</td>
                    <td>".$row["humidity"]."</td>
                </tr>";
            $count++;
        }
        echo "</table>
            <style>
            table td,th{
                border: 2px solid purple;
                text-align: center;
                padding: 10px;
            }
            th,td{
                background: rgb(211, 164, 103)
            }
        </style>
        ";
    } else {
        echo "<p>No data found.</p>";
    }

    
}

if (isset($_POST['search'])){
    $city=$_REQUEST['search'];
    set($conn,$city);
    get($conn,$city);
}

?>
                   
