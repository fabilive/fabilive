<?php
$conn = mysqli_connect("127.0.0.1", "fabilive", "fabilive", "fabilive");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}
$result = mysqli_query($conn, "SELECT id, type, image FROM agreements");
while($row = mysqli_fetch_assoc($result)) {
    echo "ID: " . $row['id'] . ", Type: " . $row['type'] . ", Image: " . $row['image'] . "\n";
}
mysqli_close($conn);
