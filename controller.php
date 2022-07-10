<?php
// PURPOSE: This file contains a bridge between the view and the model and redirects back to the proper page
// with after processing whatever form this code absorbs. This is the C in MVC, the Controller.
//
// Author: Zach Winans


session_start();

require_once './DatabaseAdaptor.php';

$theDBA = new DatabaseAdaptor();

if (isset($_GET['todo']) && $_GET['todo'] === 'getQuotes') {
    $arr = $theDBA->getAllQuotations();
    unset($_GET['todo']);
    echo getQuotesAsHTML($arr);
}

if (isset($_POST['author']) && isset($_POST['quote'])) {
    
    
    
    $author = $_POST['author'];
    if (empty($author)) {
        $author = "Anonymous";
    }
    
    $quote = $_POST['quote'];
    
    if (empty($quote)) {
        header("Location: addQuote.php?failure=true");
        return;
    }

    $theDBA->addQuote($quote, $author);

    header("Location: view.php");
}

if (isset($_POST['user']) && isset($_POST['password'])) {

    $user = htmlspecialchars($_POST['user']);
    $pass = htmlspecialchars($_POST['password']);
    
    if (empty($user) || empty($pass)) {
        header("Location: register.php?failure=blank");
        return;
    }

    if ($theDBA->containsUser($user) == true) {

       header("Location: register.php?failure=true");
       
        
    } else {
        $theDBA->addUser($user, $pass);
        unset($_SESSION['username']);
        unset($_SESSION['password']);

        header("Location: view.php");
    }
}

if (isset($_POST['userLogin']) && isset($_POST['passwordLogin'])) {
    
    if (empty($_POST['userLogin']) || empty($_POST['passwordLogin'])) {
        header("Location: login.php?failure=blank");
        return;
    }
    

    if ($theDBA->verifyCredentials($_POST['userLogin'], $_POST['passwordLogin'])) {

        $_SESSION["username"] = $_POST['userLogin'];
        $_SESSION["password"] = $_POST['passwordLogin'];
        header("Location: view.php");
        
    } else {

        header("Location: login.php?failure=true");
        
    }
}

if (isset($_GET['logout'])) {
    unset($_SESSION['username']);
    unset($_SESSION['password']);
    
    header("Location: view.php");
}

if (isset($_POST['update'])) {

    $direction = $_POST['update'];
    $id = $_POST['ID'];

    if ($direction === 'delete') {
        $theDBA->delete($id);
    }

    if ($direction === 'increase' || $direction === 'decrease') {
        $theDBA->update($id, $direction);
    }

    header("Location: view.php");
}

/*
 * PURPOSE: To create the HTML layout
 */
function getQuotesAsHTML($arr)
{
    // TODO 6: Many things. You should have at least two quotes in
    // table quotes. layout each quote using a combo of PHP and HTML
    // strings that includes HTML for buttons along with the actual
    // quote and the author, ~15 PHP statements. This function will
    // be the most time consuming in Quotes 1. You will
    // need to add css rules to styles.css.
    $result = '<br>';
    if (! isset($_SESSION['username'])) {
        $result .= '<a href="./register.php"><button>Register</button></a><br>';
        $result .= '<a href="./login.php"><button>Login</button></a>';
    }
    
    if (isset($_SESSION['username'])) {
        $result .= '<a href="./addQuote.php"><button>Add Quote</button></a><br>';
        $result .= '<a href="./controller.php?logout=true"><button>Log Out</button></a>';
        $result .= '<br><br>&nbsp;<b>Hello ' . $_SESSION['username'] . '</b>';
    }
    
    
    foreach ($arr as $quote) {

        $result .= '<div class="container">';
        $result .= '"' . $quote['quote'] . '"' . "<br>";
        $result .= '<p class="author">';
        $result .= "--" . $quote['author'] . "<br>";
        $result .= '</p>';
        $result .= '<form action="controller.php" method="post">';
        $result .= '<input type="hidden" name="ID" value="' . $quote['id'] . '">&nbsp;&nbsp;&nbsp;';
        $result .= '<button name="update" value="increase">+</button>';
        $result .= '&nbsp;<span id="rating"> ' . $quote['rating'] . '</span>&nbsp;&nbsp;';
        $result .= '<button name="update" value="decrease">-</button>&nbsp;&nbsp;';
        if (isset($_SESSION['username'])) {
            $result .= '<button name="update" value="delete">Delete</button>';
        }
        $result .= '</form>';
        $result .= '</div>';
    }

    return $result;
}

?>