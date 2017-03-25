<html>
<style>
table, th, td {
  border: 1px solid black;
}
body.one {
    background-color: white;
    padding-top: 100px;
    padding-right: 30px;
    padding-bottom: 50px;
    padding-left: 80px;
  }
  body {
      background-image: url("bgdesert.jpg");
  }
}
</style>
<?php
include 'connection.php';
global $conn;
//Query-1
   if (isset($_POST['query1'])){
   $dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
   $dbh->beginTransaction();
   $date2=$_POST['date2'];
   $stmt=$dbh->prepare('SELECT COMP_NAME, SUM(NUM_SHARES_PUR) AS NUMBER_OF_SHARES_SOLD FROM REGISTERED_COMPANIES A INNER JOIN STOCK_PURCHASE_DETAILS B ON (A.COMP_ID=B.COMP_ID) WHERE REG_DATE >= "'.$date2.'" GROUP BY A.COMP_ID,A.COMP_NAME');

    $stmt->execute();
    echo "<table>
    <tr>
      <th>company name</th>
      <th>Number of shares sold</th>

    </tr>";
    while($row=$stmt->fetch()){

    echo "<tr>
    <td>".$row[0]."</td>
    <td>".$row['NUMBER_OF_SHARES_SOLD']."</td>
    </tr>";

  }

echo "</table>";
$dbh = null;
}
//Query-2
if (isset($_POST['query2'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
//$date=$_POST['date'];
$stmt=$dbh->prepare('SELECT B.F_NAME AS CUSTOMER_NAME,C.COMP_NAME AS COMPANY_NAME,D.F_NAME AS BROKER_NAME,(PRICE_SOLD-PRICE_PURCHASED)*NUM_SHARES_SOLD FROM STOCK_SALE_DETAILS A INNER JOIN CUSTOMER B ON (A.CUST_ID=B.CUST_ID) INNER JOIN REGISTERED_COMPANIES C ON (A.COMP_ID=C.COMP_ID) INNER JOIN BROKER D ON (A.BROKER_ID=D.BROKER_ID) ORDER BY (PRICE_SOLD-PRICE_PURCHASED)*NUM_SHARES_SOLD DESC LIMIT 1');

 $stmt->execute();
 echo "<table>
 <tr>
   <th>Customer Name</th>
   <th>Company Name</th>
   <th>Broker Name</th>
   <th>Profit</th>

 </tr>";
 while($row=$stmt->fetch()){

 echo "<tr>
 <td>".$row[0]."</td>
 <td>".$row[1]."</td>
 <td>".$row[2]."</td>
 <td>".$row[3]."</td>
 </tr>";

}

echo "</table>";
$dbh = null;
}
//Query-3
if (isset($_POST['query3'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
//$date=$_POST['date'];
$stmt=$dbh->prepare('SELECT COMPANY_ID,COMPANY_NAME,ENTRY_DATE,MAX(I.INCREASE_IN_PRICE) AS PRICE_INCREASE_IN_DOLLARS
FROM
( SELECT A.COMP_ID AS COMPANY_ID,B.COMP_NAME AS COMPANY_NAME,A.ENTRY_DATE,(CLOSING_PRICE-OPENING_PRICE) AS INCREASE_IN_PRICE
FROM DAILY_STOCK_DETAILS A INNER JOIN REGISTERED_COMPANIES B ON (A.COMP_ID=B.COMP_ID) ) I
GROUP BY COMPANY_ID,COMPANY_NAME,ENTRY_DATE
HAVING MAX(I.INCREASE_IN_PRICE) >= 1');

 $stmt->execute();
 echo "<table>
 <tr>
   <th>company_id</th>
   <th>company_name</th>
   <th>Entry_date</th>
   <th>Increase_in_price</th>
 </tr>";
 while($row=$stmt->fetch()){

 echo "<tr>
 <td>".$row[0]."</td>
 <td>".$row[1]."</td>
 <td>".$row[2]."</td>
 <td>".$row[3]."</td>
 </tr>";

}

echo "</table>";
$dbh = null;
}

//QUERY-4
if (isset($_POST['query4'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
$date4=$_POST['date4'];
$stmt=$dbh->prepare('SELECT ROLE, COUNT(EMPLOYEE_ID) AS NUMBER_OF_EMPLOYEES
FROM EMPLOYEE
WHERE DATE_OF_JOINING >= "'.$date4.'"
GROUP BY ROLE');

$stmt->execute();
echo "<table>
<tr>
  <th>Role</th>
  <th>Number_of_employees</th>
</tr>";
while($row=$stmt->fetch()){

echo "<tr>
<td>".$row[0]."</td>
<td>".$row[1]."</td>
</tr>";

}

echo "</table>";
$dbh = null;
}

//Query-5
if (isset($_POST['query5'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
//$date=$_POST['date'];
$stmt=$dbh->prepare('SELECT A.F_NAME AS EMPLOYEE_NAME, B.F_NAME AS MANAGER_NAME FROM EMPLOYEE A INNER JOIN EMPLOYEE B ON A.MANAGER_ID = B.EMPLOYEE_ID');

$stmt->execute();
echo "<table>
<tr>
  <th>Employee Name</th>
  <th>Manger Name</th>
</tr>";
while($row=$stmt->fetch()){

echo "<tr>
<td>".$row[0]."</td>
<td>".$row[1]."</td>
</tr>";

}

echo "</table>";
$dbh = null;
}

//Query-6
if (isset($_POST['query6'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
//$date=$_POST['date'];
$stmt=$dbh->prepare('SELECT I.CUSTOMER_ID,I.CUSTOMER_NAME,SUM(I.NUM_SHARES) AS TOTAL_SHARES FROM ( SELECT A.CUST_ID AS CUSTOMER_ID,B.F_NAME AS CUSTOMER_NAME, (TOTAL_PURCHASED - TOTAL_SOLD) AS NUM_SHARES FROM TRANSACTION_MASTER A INNER JOIN CUSTOMER B ON (A.CUST_ID = B.CUST_ID) ) I GROUP BY I.CUSTOMER_ID,I.CUSTOMER_NAME ORDER BY TOTAL_SHARES DESC LIMIT 1');

$stmt->execute();
echo "<table>
<tr>
  <th>Customer_ID</th>
  <th>Customer_Name</th>
  <th>Total_Shares</th>
</tr>";
while($row=$stmt->fetch()){

echo "<tr>
<td>".$row[0]."</td>
<td>".$row[1]."</td>
<td>".$row[2]."</td>
</tr>";

}

echo "</table>";
$dbh = null;
}

//query-7
if (isset($_POST['query7'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
//$date=$_POST['date'];
$stmt=$dbh->prepare('SELECT I.CUSTOMER_ID,C.F_NAME AS CUSTOMER_NAME,MAX(PROFIT)
FROM
( SELECT CUST_ID AS CUSTOMER_ID, SUM((PRICE_SOLD-PRICE_PURCHASED)*NUM_SHARES_SOLD) AS PROFIT
  FROM STOCK_SALE_DETAILS
  GROUP BY CUST_ID ORDER BY PROFIT DESC ) AS I JOIN CUSTOMER C ON (I.CUSTOMER_ID = C.CUST_ID)');

$stmt->execute();
echo "<table>
<tr>
  <th>Customer_ID</th>
  <th>Customer_Name</th>
  <th>Profit</th>
</tr>";
while($row=$stmt->fetch()){

echo "<tr>
<td>".$row[0]."</td>
<td>".$row[1]."</td>
<td>".$row[2]."</td>
</tr>";

}

echo "</table>";
$dbh = null;
}
//Query-8
if (isset($_POST['query8'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
$date8=$_POST['date8'];
$stmt=$dbh->prepare('SELECT R.COMP_NAME,OPENING_PRICE,CLOSING_PRICE
 FROM DAILY_STOCK_DETAILS D INNER JOIN REGISTERED_COMPANIES R ON (D.COMP_ID = R.COMP_ID)
 WHERE ENTRY_DATE = "'.$date8.'"');

$stmt->execute();
echo "<table>
<tr>
  <th>Company_Name</th>
  <th>Opening Price</th>
  <th>Closing Price</th>
</tr>";
while($row=$stmt->fetch()){

echo "<tr>
<td>".$row[0]."</td>
<td>".$row[1]."</td>
<td>".$row[2]."</td>
</tr>";

}

echo "</table>";
$dbh = null;
}

//Query-9
if (isset($_POST['query9'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
$date9=$_POST['date9'];
$stmt=$dbh->prepare('SELECT R.COMP_ID,R.COMP_NAME,SUM(NUM_SHARES_SOLD) AS NUM_OF_SHARES
FROM STOCK_SALE_DETAILS S INNER JOIN REGISTERED_COMPANIES R ON (S.COMP_ID = R.COMP_ID)
WHERE S.DATE_SOLD = "'.$date9.'"
GROUP BY R.COMP_ID,R.COMP_NAME');

$stmt->execute();
echo "<table>
<tr>
  <th>Company_ID</th>
  <th>Company_Name</th>
  <th>Number_of_Shares</th>
</tr>";
while($row=$stmt->fetch()){
echo "<tr>
<td>".$row[0]."</td>
<td>".$row[1]."</td>
<td>".$row[2]."</td>
</tr>";
}
echo "</table>";
$dbh = null;
}

//Query-10
if (isset($_POST['query10'])){
$dbh = new PDO("mysql:hos=localhost:80;dbname=stockexchange","root","",array(PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION));
$dbh->beginTransaction();
//$date=$_POST['date'];
$stmt=$dbh->prepare('SELECT C.F_NAME AS CUSTOMER_NAME,B.F_NAME AS BROKER_NAME, COUNT(B.BROKER_ID) AS NUM_OF_TRANSACTIONS
FROM
STOCK_SALE_DETAILS S INNER JOIN BROKER B ON (S.BROKER_ID = B.BROKER_ID)
INNER JOIN CUSTOMER C ON(C.CUST_ID = S.CUST_ID)
GROUP BY C.F_NAME,B.F_NAME,B.BROKER_ID');

$stmt->execute();
echo "<table>
<tr>
  <th>Customer Name</th>
  <th>Broker Name</th>
  <th>Number of Transactions</th>

</tr>";
while($row=$stmt->fetch()){

echo "<tr>
<td>".$row[0]."</td>
<td>".$row[1]."</td>
<td>".$row[2]."</td>
</tr>";

}

echo "</table>";
$dbh = null;
}

?>
</html>
