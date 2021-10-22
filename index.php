<?php
session_start();
// dhpress v0.1
// Created by Dhirodatto Biswas
// change the host_name to your MySQL hostname in the next line
define("host","host_name");
// change user_name to your MySQL username in the next line
define("userdb","user_name");
// change db_pass to your MySQL password in the next line
define("passdb","db_pass");
// change db_name to your MySQL database name in the next line
define("dbname","db_name");
// change admin_here to your prefered site's admin username in the next line
define("admin","admin_here");
// change pass_here to your prefered site's admin username in the next line
define("pass","pass_here");
error_reporting(0);
echo"<center>";
?>
<style>
textarea{
    height:50%;
    width:90%;
    font-size:1.5rem;
}
input{
    height:10%;
    width:30%; 
    font-size:1.5rem;
}
button.add{
    height:10%;
    width:20%;
    font-size:1.3rem;
    background-color:lightblue;
    border:0;
    border-radius: 8px;
    color:slategrey;
}
@media (max-width:700px) {
    input{
    height:10%;
    width:90%; 
    font-size:1.5rem;
}
    button.add{
        height:10%;
        width:40%;
        font-size:1rem;
    }
}
a{
    font-size:1.2rem;
    color:slategrey;
    border-bottom:1px dotted;
    text-decoration:none;
}
.catze{
    font-size:1.5rem;
}
.cat{
    width:90%;
}
</style>
<?php
if(host == "host_name"||userdb == "host_name"||passdb == "db_pass"||dbname == "db_name"||admin == "admin_here"||pass == "pass_here"){
    ?>
    <h1>FOR THE ADMIN:</h1>
    <h2>MAKE THE CHANGES IN THE SOURCE FILE:</h2>
    <ol>
    <li>change the host_name to your MySQL hostname in line no. 6</li>
    <li>change user_name to your MySQL username in line no. 8</li>
    <li>change db_pass to your MySQL password in line no. 10</li>
    <li>change db_name to your MySQL database name in line no. 12</li>
    <li>change admin_here to your prefered site's admin username in line no. 14</li>
    <li>change pass_here to your prefered site's admin username in line no. 16</li>
    </ol>
    <?php
}else{
    $conn = mysqli_connect(host,userdb,passdb,dbname);
    if(!$conn){
        echo"FOR THE ADMIN: Check the information that you gave in the source file about your database";
    }else{
        $s = "CREATE TABLE IF NOT EXISTS post(id int(30) not null auto_increment primary key, title varchar(225), post varchar(10000) not null, clock varchar(100) not null, editclock varchar(100) not null, views int(10) not null)";
        mysqli_query($conn,$s);
        if(isset($_GET['admin'])){
            if($_GET['admin'] == 'login'){
                echo"<form action = '?admin=login' method='post'>\n";
                echo"<h1 style='font-size:3rem;'>Login</h1>";
                echo"<input type='text' name='name' placeholder='Enter your username'><br><br>\n";
                echo"<input type='text' name='password' placeholder='Enter your password'><br><br>\n";
                echo"<button class='add'>Login</button>\n";
                if(isset($_POST['name'])&&isset($_POST['password'])){
                    if($_POST['name']==admin && $_POST['password']==pass){
                        $_SESSION['username']=admin;
                        $_SESSION['password']=pass;
                        echo"<script>location.href='?admin'</script>";
                    }else{
                        echo"<script>alert('Wrong Details Entered')</script>";
                    }
                }
            }else{
                if(!isset($_SESSION['password'])||!isset($_SESSION['username'])){
                echo"<script>location.href='?admin=login'</script>";
                }elseif($_SESSION['password']!=pass||$_SESSION['username']!=admin){
                echo"<script>location.href='?admin=login'</script>";
                }elseif($_GET['admin']=="add"){
                    echo"<form action = '?admin=add' method='post'>\n";
                    echo"<span class='catze'>Heading</span><br><input class='cat' type='text' class='add' name='title' placeholder='Enter your title' required><br><br>\n";
                    echo"<span class='catze'>Matter</span><br><textarea name='post' placeholder='Enter your content in HTML...' required></textarea><br><br>\n";
                    echo"<span class='catze'>Written On</span><br><input class='cat' type='text' name='date' id='dt' required></input><br><br>\n";
                    echo"<script>document.getElementById('dt').value=new Date()</script>\n";
                    echo"<button class='add'>Add Article</button>\n";
                    echo"</form>";
                    echo"<a href='?admin'>Return To Main Admin Page</a>";
                    if(isset($_POST['title'])&&isset($_POST['post'])){
                        $title = $_POST['title'];
                        $post = $_POST['post'];
                        $time = $_POST['date'];
                        $s = "SELECT * FROM post WHERE title = '$title' OR post LIKE '$post'";
                        $q2 = mysqli_query($conn,$s);
                        $num = mysqli_num_rows($q2);
                        if($num==0){
                            $i = "INSERT INTO post (title,post,clock) VALUES ('$title','$post','$time')";
                            $q = mysqli_query($conn,$i);
                            if($q){
                                echo"<script>alert('Article Published Successfully!')</script>";
                            }
                        }else{
                            echo"<script>alert('Article Exists')</script>";
                        }
                    }
                }elseif($_GET['admin']=="edit"){
                    ?>
                    <form action='?admin=edit' method='post'>
                        <select name='article'>
                        <?php 
                        $s = 'SELECT title FROM post';
                        $q = mysqli_query($conn,$s);
                        while($row = mysqli_fetch_array($q)){
                        ?>
                        <option value='<?php echo $row['title'];?>'><?php echo $row['title'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        <button>Edit</button>
                    </form> 
                    <?php
                    if(isset($_POST['article'])){
                         $article = $_POST['article'];
                         echo $article;
                         $s = "SELECT * FROM post WHERE title = '$article'";
                         $q = mysqli_query($conn,$s);
                         while($row = mysqli_fetch_array($q)){
                         echo"<form action = '?admin=edit' method='post'>\n";
                         echo"<span style='font-size:3rem;'>Heading</span><br><input class='cat' type='text' class='add' name='title' placeholder='Enter your title' value='".$row['title']."' required><br><br>\n";
                         echo"<span style='font-size:3rem;'>Matter</span><br><textarea name='post' placeholder='Enter your content in HTML...' required>".$row['post']."</textarea><br><br>\n";
                         echo"<span style='font-size:3rem;'>Last Updated On</span><br><input class='cat' type='text' name='date' id='dt' required></input><br><br>\n";
                         echo"<input type='hidden' name='id' value='".$row['id']."'></input>";
                         echo"<script>document.getElementById('dt').value=new Date()</script>\n";
                         echo"<button class='add'>Update Article</button>\n";
                         echo"<a href='?admin'>Return To Main Admin Page</a>";
                         }
                    }
                        if(isset($_POST['title'])&&isset($_POST['post'])){
                            $id = $_POST['id'];
                            $title = $_POST['title'];
                            $post = $_POST['post'];
                            $time = $_POST['date'];
                            $s = "SELECT * FROM post WHERE title = '$title' OR post LIKE '$post'";
                            $q2 = mysqli_query($conn,$s);
                            $num = mysqli_num_rows($q2);
                            $i = "UPDATE post SET title = '$title' , post = '$post' , editclock = '$time' WHERE id = '$id'";
                            $q = mysqli_query($conn,$i);
                            if($q){
                                echo"<script>alert('Article Updated Successfully!')</script>";
                            }
                    }
                }
                elseif($_GET['admin']=="logout"){
                    $_SESSION['username']='';
                    $_SESSION['password']='';
                    echo"<script>location.href='?admin=login'</script>";
                }elseif($_GET['admin']=="delete"){
                    ?>
                    <form action='?admin=delete' method='post'>
                        <select name='article'>
                        <?php 
                        $s = 'SELECT title FROM post';
                        $q = mysqli_query($conn,$s);
                        while($row = mysqli_fetch_array($q)){
                        ?>
                        <option value='<?php echo $row['title'];?>'><?php echo $row['title'];?></option>
                        <?php 
                        }
                        ?>
                        </select>
                        <button>Delete</button>
                    </form> 
                <?php 
                if(isset($_POST['article'])){
                    $article = $_POST['article'];
                    $d = "DELETE FROM post WHERE title = '$article'";
                    $q = mysqli_query($conn,$d);
                    if($q){
                       echo"<script>alert('Article deleted!')</script>";
                        }
                }
                }elseif($_GET['admin']==""){
                    ?>
                    <h3 style="font-size: 3rem;">Welcome <?php echo admin; ?></h3>
                    <a class='rem' href="?admin=add">Add Article</a><br>
                    <a class='rem'  href="?admin=delete">Delete Article</a><br>
                    <a class='rem' href="?admin=edit">Edit Article</a><br>
                    <a class='rem' href="?admin=logout">Logout</a>
                    <?php
                    echo"</center>";
                }
            }
        }elseif(isset($_GET['article'])){
            echo"<meta name='viewport' content='width=device-width,initial-scale=1.0'>";
            $article = str_replace("_"," ",$_GET['article']); 
            $s = "SELECT * FROM post WHERE title = '$article'";
            $q = mysqli_query($conn,$s);
            $num = mysqli_num_rows($q);
            if($num>0){
                while ($row = mysqli_fetch_array($q)) {
                    if(isset($_SESSION['username'])&&isset($_SESSION['username'])){
                        if($_SESSION['username']==admin && $_SESSION['password']==pass){
                            echo"<form action='?admin=edit' method='post'>";
                            echo"<input type='hidden' name='article' value='".$row['title']."'>";
                            echo"<button>Edit This Article</button></form>";
                            echo"<form action='?admin=delete' method='post'>";
                            echo"<input type='hidden' name='article' value='".$row['title']."'>";
                            echo"<button>Delete This Article</button><br><br></form>";
                            echo"<a href='?admin'>Return To Main Admin Page</a>";
                        }
                    }
                    echo"<h1>".$row['title']."</h1>";
                    echo"<div>Written by ".admin."</div>";
                    echo"<div>On ".$row['clock']."</div>";
                    if($row['editclock']!=''){
                        echo"Edited last on ".$row['editclock'];
                    }
                    echo"</center>";
                    echo"<br><br><p>".nl2br($row['post'])."</p>";
                    echo"<br><a href='?'>Return To HomePage</a>";
                }
            }else{
                echo"<div style='font-size:3rem;'>404 Not Found</div>";
            }
        }else{
            echo"<meta name='viewport' content='width=device-width,initial-scale=1.0'>";
            echo"</center>";    
            $s = "SELECT title,clock FROM post";
            $q = mysqli_query($conn,$s);
            $num = mysqli_num_rows($q);
            if(isset($_SESSION['username'])&&$_SESSION['username']==admin&&isset($_SESSION['password'])&&$_SESSION['password']==pass){
                echo"<a href='?admin=logout'>Logout</a><br>";
            }
            if($num>0){
                while ($row = mysqli_fetch_array($q)) {
                    echo"<a href='?article=".str_replace(" ","_",$row['title'])."'>".$row['title']."</a><br>";
                    echo"-Written by ".admin." ";
                    echo"On ".$row['clock']."<br>";
                    if(isset($_SESSION['username'])&&isset($_SESSION['username'])){
                        if($_SESSION['username']==admin && $_SESSION['password']==pass){
                            echo"<form action='?admin=edit' method='post'>";
                            echo"<input type='hidden' name='article' value='".$row['title']."'>";
                            echo"<button>Edit This Article</button></form>";
                            echo"<form action='?admin=delete' method='post'>";
                            echo"<input type='hidden' name='article' value='".$row['title']."'>";
                            echo"<button>Delete This Article</button><br><br></form>";
                            echo"<a href='?admin'Go To Main Admin Page</a>";
                        }
                    }
                }
            }else{
                echo"<div style='font-size:3rem;'>No Articles Published yet (:</div>";
            }
        }
    }
}
?>
