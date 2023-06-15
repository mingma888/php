<?php
        
// $servername = "sql306.epizy.com";
// $username = "epiz_34168782";
// $password = "lE2XvZ3abR6vS5";
// $dbname = "epiz_34168782_weather";
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
        echo "<h1>Past week weather details of $city:</h1>
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
            h1{
                margin-top: 3%;
                text-align: center;
                color: rgb(210, 210, 210);
                text-shadow: 1px 1px 2px black, 0 0 25px blue, 0 0 5px darkblue;
            }
            table {
                
                border-collapse: collapse;
                border: 2px solid White;
                width: 60%;
                padding: 8px;
                margin-left: 25%;
                margin-top: 4%;
                
              }
              th {
                
                border: 2px solid White;
                background-color: Black;
                color: White;
                text-align: left;
                padding: 12px;
                text-align: center;
              }
              td {
                background-color: SkyBlue;
                color: Black;
                border: 1px solid White;
                padding: 8px;
                text-align: center;
              }
                  
              
              
              
        </style>
        ";
    } else {
        echo "<p> Error!  Data not found.</p>";
    }

    
}

if (isset($_POST['search'])){
    $city=$_REQUEST['search'];
    set($conn,$city);
    get($conn,$city);
}

?>
                   
