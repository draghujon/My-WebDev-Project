<!-------f--------

    CRUD project
    Name: Chris Feasby
    Date: March 23, 2020
    Description: ShowServices PHP

----------------->

<?php
    require 'connect.php';
    session_start();

   if(!isset($_SESSION['id']))
   {
        header("Location: login.php");
   }

        $post_name = "";
        $post_desc = "";
        $post_price = "";
        //$service_id = $_SESSION['id'];
        $servId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $query = "SELECT id, name, description, price FROM services WHERE id = :servId";

        $statement = $db->prepare($query); // Returns a PDOStatement object.
        $statement->bindValue(':servId', $servId); 
        $statement->execute(); // The query is now executed.
        
        $row = $statement->fetch();
        
        $serv_id = $row['id'];
        $serv_name = $row['name'];
        $serv_desc = $row['description'];
        $serv_price = $row['price'];

if(isset($_POST['create']))
{

    if($_POST['comments'] === '')
    {
        $isTrue = false;
    }
    else
    {
        $isTrue = true;
    }

    if($_POST['create'] === 'Comment' && $isTrue)
    {
        print_r($_SESSION);
        //exit;
        //Do the insert query
        //$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
        $comments = filter_input(INPUT_POST, 'comments', FILTER_SANITIZE_STRING);
        $created_At = filter_input(INPUT_POST, 'created_At', FILTER_SANITIZE_STRING);

        //$query = "INSERT INTO comments (comments, created_At)
                  //VALUES (:comments, :created_At)";
        $query = "INSERT INTO comments (UserIdFK, ServiceIdFK, comments) VALUES (:userIdFK, :serviceIdFK, :comments)";
        $statement = $db->prepare($query);
        $statement->bindValue(':userIdFK', $_SESSION['id']);
        $statement->bindValue(':serviceIdFK', $serv_id);
        $statement->bindValue(':comments', $comments);
        
        $statement->execute();

        $insert_id = $db->lastInsertId();
        
        //header("Location: index.php");
    }
}

//$servId = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

        $query = "SELECT c.ServiceIdFK, c.comments, c.Created_At, s.id FROM comments c JOIN services s ON s.id = c.ServiceIdFK
            WHERE c.ServiceIdFK = :servId ORDER BY c.created_At DESC ";

        $statement2 = $db->prepare($query); 
        $statement2->bindValue(':servId', $serv_id);

        $statement2->execute(); 





?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>CF Computing</title>
    <link rel="stylesheet" href="cfstyles.css" type="text/css">
</head>
<body>
    <header>
        <?php if(!isset($_SESSION['id'])): ?>
            <h1>Welcome to CF Computing : Update Services</h1>
        <?php else: ?>
            <h1>Welcome to CF Computing : Update Services<br></h1>
            <form action="index.php" method="post">
                <h2>User: <?= $_SESSION['username'] ?></h2>
                <h2><input type="submit" name="act" value="logout"></h2>
            </form>
        <?php endif ?>
    </header>

    <nav id="banner">
        <ul>
            <li><a href = "index.php">Home</a></li>
            <?php if(!isset($_SESSION['id'])): ?>
                <li><a href = "login.php">Login</a></li>
            <?php else: ?>
                <li><a href = "userinfo.php">User Info</a></li>
            <?php endif ?>
            <li><a href = "services.php">Services</a></li>
            <li><a href = "contact.php">Contact Us</a></li>
            <li><a href = "about.php">About Us</a></li>
        </ul>
    </nav>

    
        <div id="services">
          <h4><?= $serv_name ?></h4>
          <p>
            <?= $serv_desc ?>
          </p>
          <p>
            <small>
              <?= $serv_price ?>
            <!-- <a href="ShowServices.php">comment</a>-->

            </small>
          </p>
          <p>
            Comments
          </p>
            <?php while($row2 = $statement2->fetch()): ?>
                <p><?= "Comment : " . $row2['comments'] ?></p>
            <?php endwhile ?>
        </div>
            <div id="comments">
                <form action="#" method="post">
                    <div id="content">
                        <div>
                          <label for="inputComments">Comments</label>
                        </div>
                    </div>

                    <div>
                      <p>
                        <textarea name="comments" class="form-control" id="inputComments"></textarea>
                      </p>
                    </div>
                    <input type="submit" name="create" value="Comment">
                </form>
            </div>

        <footer>
        <nav id = "footerBanner">
            <ul>
            <li><a href = "index.php">Home</a></li>
            <?php if(!isset($_SESSION['id'])): ?>
                <li><a href = "login.php">Login</a></li>
            <?php else: ?>
                <li><a href = "userinfo.php">User Info</a></li>
            <?php endif ?>
            <li><a href = "services.php">Services</a></li>
            <li><a href = "contact.php">Contact Us</a></li>
            <li><a href = "about.php">About Us</a></li>
            </ul>
            <p>CF Computing</p>
            <img src = "keyboard.jpg" alt = "keyboard" id ="keyboard" />
        </nav>
        <!-- image credit(both images) : https://www.google.com/search?q=computer+images&rlz=1C1GCEA_enCA831CA831&tbm=isch&sxsrf=ACYBGNTVhWyi6umZY0WZmyEGXQzpWGoeVg:1573689224689&source=lnt&tbs=sur:fc&sa=X&ved=0ahUKEwiqr-WZsejlAhWV4J4KHa6FDroQpwUIJA&biw=1920&bih=969&dpr=1 -->
        <img src = "footerimage.jpg" alt = "computer" id ="pic" />

    </footer>
    <script src="main.js"></script>
    </body>
</html>

